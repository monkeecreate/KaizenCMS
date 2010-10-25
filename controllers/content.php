<?php
class content extends appController
{
	### DISPLAY ######################
	function index() {
		$this->tplDisplay("index.tpl");
	}
	function search() {
		if(!empty($_GET["query"])) {
			$sSearch = $_GET["query"];
			$sSearchInclude = $_GET["query_include"];
			$sSearchExclude = $_GET["query_exclude"];
			
			if((!$sSearchInclude) || ($sSearchInclude == "")) { $sSearchInclude = ""; } else { $sSearchInclude = "+(".$sSearchInclude.")"; } 
			if((!$sSearch) || ($sSearch == "")) { $sSearch = ""; }  
			if((!$sSearchExclude) || ($sSearchExclude == "")) { $sSearchExclude = ""; } else { $sSearchExclude = "-(".$sSearchExclude.")"; }
			
			$sQuery = $this->dbQuote($sSearchInclude." ".$sSearchExclude." ".$sSearch, "text");
			
			$aTables = $this->dbQuery("SELECT * FROM `{dbPrefix}search`", "all");
			$aSearchTables = array();
			
			foreach($aTables as $aSearch) {
				$sFilter = "";
				$aSearch["rows"] = json_decode($aSearch["rows"], true);
				$aRows = array();
				foreach($aSearch["rows"] as $sRow) {
					$aRows[] = "`table`.`".$sRow."`";
				}
				$sRows = implode(",", $aRows);
				
				if(!empty($aSearch["filter"])) {
					$aSearch["filter"] = str_replace("{time}", time(), $aSearch["filter"]);
					$sFilter = " AND ".$aSearch["filter"];
				}
				
				$aSearchTables[] = "SELECT"
					." `id`"
					.", `".$aSearch["column_title"]."` AS `title`".
					((!empty($aSearch["column_content"]))?", `".$aSearch["column_content"]."` AS `content`":", '' AS `content`")
					.", '".$aSearch["plugin"]."' as `plugin`"
					.", MATCH(".$sRows.") AGAINST (".$sQuery." IN BOOLEAN MODE) AS `score`"
					." FROM `{dbPrefix}".$aSearch["table"]."` AS `table`"
					." WHERE MATCH(".$sRows.") AGAINST (".$sQuery." IN BOOLEAN MODE)"
					.$sFilter
				;
			}
			
			$sSQL = implode(" UNION ", $aSearchTables)/*." ORDER BY `score` DESC"*/;
			
			$aSearch = $this->dbQuery($sSQL, "all");
			
			foreach($aSearch as &$aItem) {
				// Content
				$aItem["content"] = implode(". ", array_slice(explode(". ", strip_tags(stripslashes($aItem["content"]))), 0, 2)).".";
				if(strlen($aItem["content"]) > 150)
					$aItem["content"] = substr($aItem["content"], 0, 150)."...";
				
				// Score
				$aItem["score"] = round($aItem["score"], 3);
				
				// Link
				if($aItem["plugin"] == "content") {
					$sTag = $this->dbQuery("SELECT `tag` FROM `{dbPrefix}content` WHERE `id` = ".$this->dbQuote($aItem["id"], "integer"), "one");
					$aItem["link"] = "/".$sTag."/";
				} else {
					$oModel = $this->loadModel($aItem["plugin"]);
					$aItem["link"] = $oModel->getURL($aItem["id"]);
				}
			}
			
			$this->tplAssign("sSearched", 1);
			$this->tplAssign("aSearch", $aSearch);
			$this->tplAssign("sQuery", $_GET["query"]);
			$this->tplAssign("sQueryInclude", $_GET["query_include"]);
			$this->tplAssign("sQueryExclude", $_GET["query_exclude"]);
		}
		
		$this->tplDisplay("search.tpl");
	}
	function view() {
		if(!empty($this->urlVars->dynamic["page"]))
			$sPage = $this->urlVars->dynamic["page"];
		elseif(!empty($this->urlVars->manual["page"]))
			$sPage = $this->urlVars->manual["page"];
		else
			$this->error("404");
		
		if(preg_match("/[a-z0-9_-]+/i", $sPage) > 0) {
			$aContent = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content`"
					." WHERE `tag` = ".$this->dbQuote($sPage, "text")
					." LIMIT 1"
				,"row"
			);
			
			if(!empty($aContent)) {
				$aContent["title"] = htmlspecialchars(stripslashes($aContent["title"]));
				$aContent["content"] = stripslashes($aContent["content"]);
			}
			
			$this->tplAssign("aContent", $aContent);
			
			if($this->tplExists("content/".$sPage.".tpl"))
				$this->tplDisplay("content/".$sPage.".tpl");
			else {
				if(!empty($aContent)) {
					if(empty($aContent["template"]))
						$this->tplDisplay("content.tpl");
					else
						$this->tplDisplay("content/".$aContent["template"]);
				} else
					$this->error("404");
			}
		} else
			$this->error("404");
	}
	function form_submit() {
		require_once($this->settings->root.'helpers/recaptchalib.php');
		$privatekey = "6LfXQwkAAAAAAJ2WgHyDtraMxy639SPAln9f0uFj";
		$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		
		if (!$resp->is_valid) {
			$_SESSION["post_data"] = $_POST;
			$this->forward($this->decrypt($_POST["return"]));
		}
		
		// name="{ order | linetype | text }"
		$aItems = array();//Email components
		$aInfo = array();//Form values
		
		// Build array from from, only those made for the email will do
		foreach($_POST as $x => $input) {
			$info = explode("|",str_replace("_"," ",$x));
			
			// Check if made for email
			if(count($info) > 1) {
				$aItems[$info[0]] = Array(
					"linetype" => $info[1],
					"name" => $info[2],
					"value" => $input
					);
				$aInfo[$info[2]] = $input;
			}
		}
		// Sort based on data in form
		ksort($aItems);
		
		//Build email
		$sBody = "";
		foreach($aItems as $input) {
			// Only padding below
			if($input["linetype"] == "s")
				$sBody .= $input["name"]." ".stripslashes($input["value"])."\n";
			// Padding on top and bottom
			elseif($input["linetype"] == "n")
				$sBody .= "\n".$input["name"]."\n".stripslashes($input["value"])."\n";
			else
				$this->sendError("content->form_submit", "Invalid line type. (".$input["linetype"].")");
		}
		
		$aHeaders["From"] = $this->formSubmitValues($this->decrypt($_POST["from"]), $aItems);
		$aHeaders["To"] = $this->formSubmitValues($this->decrypt($_POST["to"]), $aItems);
		$aHeaders["Subject"] = $this->formSubmitValues($this->decrypt($_POST["subject"]), $aItems);
		
		$this->mail($aHeaders, $sBody);
		
		$this->forward($this->decrypt($_POST["forward"]));
	}
	function formSubmitValues($sString, $aValues) {
		foreach($aValues as $key => $item)
			$sString = str_replace("[$".$key."]", $item["value"], $sString);
		
		return $sString;
	}
	##################################
	
	### Functions ####################
	##################################
}