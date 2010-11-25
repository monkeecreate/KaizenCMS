<?php
class admin_content extends adminController
{
	function __construct() {
		parent::__construct();
		
		$this->menuPermission("content");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;
		
		$aPages = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." ORDER BY `title`"
			,"all"
		);
		
		$this->tplAssign("aPages", $aPages);
		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplDisplay("content/index.tpl");
	}
	function add() {
		$this->tplAssign("aTemplates", $this->getTemplates());
		$this->tplAssign("aPage", $_SESSION["admin"]["admin_content"]);
		$this->tplDisplay("content/add.tpl");
	}
	function add_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if($this->superAdmin && !empty($_POST["tag"]))
			$sTag = $_POST["tag"];
		else
			$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
		
		$aPages = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}content`"
				." ORDER BY `tag`"
			,"all"
		);

		if (in_array(array('tag' => $sTag), $aPages)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aPages);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sID = $this->dbInsert(
			"content",
			array(
				"tag" => $sTag
				,"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if($this->superAdmin) {
			$this->dbUpdate(
				"content",
				array(
					"tag" => $sTag
					,"perminate" => $this->boolCheck($_POST["perminate"])
					,"module" => $this->boolCheck($_POST["module"])
					,"template" => $_POST["template"]
				),
				$sID
			);
		}
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_content"])) {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPage = $_SESSION["admin"]["admin_content"];
			
			$aPage["updated_datetime"] = $aPageRow["updated_datetime"];
			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPageRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPage", $aPage);
		} else {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPage["updated_by"]
				,"row"
			);
		
			$this->tplAssign("aPage", $aPage);
		}
		
		$this->tplAssign("aTemplates", $this->getTemplates());
		$this->tplDisplay("content/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"content",
			array(
				"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"updated_datetime" =>time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		if($this->superAdmin) {
			if(!empty($_POST["tag"]))
				$sTag = $_POST["tag"];
			else
				$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
			
			$aPages = $this->dbQuery(
				"SELECT `tag` FROM `{dbPrefix}content`"
					." ORDER BY `tag`"
				,"all"
			);

			if (in_array(array('tag' => $sTag), $aPages)) {
				$i = 1;
				do {
					$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
					$i++;
					$checkDuplicate = in_array(array('tag' => $sTempTag), $aPages);
				} while ($checkDuplicate);
				$sTag = $sTempTag;
			}
			
			$this->dbUpdate(
				"content",
				array(
					"tag" => $sTag
					,"perminate" => $this->boolCheck($_POST["perminate"])
					,"module" => $this->boolCheck($_POST["module"])
					,"template" => $_POST["template"]
				),
				$_POST["id"]
			);
		}
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("content", $this->urlVars->dynamic["id"]);
		
		$this->forward("/admin/content/?notice=".urlencode("Page removed successfully!"));
	}
	##################################
	
	### Functions ####################
	function getTemplates() {
		$aTemplates = array();
		$aFiles = scandir($this->settings->root."views/content/");
		foreach($aFiles as $sFile) {
			if($sFile != "." && $sFile != "..")
				$aTemplates[] = $sFile;
		}
		
		return $aTemplates;
	}
	##################################
}