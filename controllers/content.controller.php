<?php
class content extends appController
{
	### DISPLAY ######################
	function index()
	{
		$this->_smarty->display("index.tpl");
	}
	function siteinfo()
	{
		$this->site_info();
	}
	function view($aUrlParam = null, $aParams)
	{
		if(!empty($aUrlParam["page"]))
			$sPage = $aUrlParam["page"];
		elseif(!empty($aParams["page"]))
			$sPage = $aParams["page"];
		else
			$this->error("404");
		
		if(preg_match("/[a-z0-9_-]+/i", $sPage) > 0)
		{
			if($this->template_exists("content/".$sPage.".tpl"))
				$this->_smarty->display("content/".$sPage.".tpl");
			else
			{
				$rContent = $this->_db->query("SELECT * FROM `content`"
					." WHERE `tag` = ".$this->_db->quote($sPage, "text")
					." AND `active` = 1"
					." LIMIT 1"
				);
			
				if(PEAR::isError($rContent))
					$this->send_error("content->view->tag", "dberror", $rContent);
			
				$aContent = $rContent->fetchRow();
			
				if(!empty($aContent))
				{
					$this->_smarty->assign("aContent", $aContent);
					$this->_smarty->display("content.tpl");
				}
				else
					$this->error("404");
			}
		}
		else
			$this->error("404");
	}
	function contact()
	{
		$aForm["to"] = $this->encrypt("defvayne23@gmail.com");
		$aForm["from"] = $this->encrypt("defvayne23@gmail.com");
		$aForm["subject"] = $this->encrypt("Test email!");
		$aForm["forward"] = $this->encrypt("/");
		
		$this->_smarty->assign("aForm", $aForm);
		$this->_smarty->display("contact.tpl");
	}
	function form_submit()
	{
		// name="{ order | linetype | text }"
		$aItems = array();//Email components
		$aInfo = array();//Form values
		
		// Build array from from, only those made for the email will do
		foreach($_POST as $x => $input)
		{
			$info = explode("|",str_replace("_"," ",$x));
			
			// Check if made for email
			if(count($info) > 1)
			{
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
		foreach($aItems as $input)
		{
			// Only padding below
			if($input["linetype"] == "s")
				$sBody .= $input["name"]." ".stripslashes($input["value"])."\n";
			// Padding on top and bottom
			elseif($input["linetype"] == "n")
				$sBody .= "\n".$input["name"]."\n".stripslashes($input["value"])."\n";
			else
				$this->send_error("content->form_submit", "Invalid line type. (".$input["linetype"].")");
		}
		
		// Email to
		$aRecipients = array(
			$this->decrypt($_POST["to"])
		);
		
		$aHeaders["From"] = $this->decrypt($_POST["from"]);
		$aHeaders["To"] = $this->decrypt($_POST["to"]);
		$aHeaders["Subject"] = $this->decrypt($_POST["subject"]);
		
		$this->mail($aRecipients, $aHeaders, $sBody);
		
		$this->forward($this->decrypt($_POST["forward"]));
	}
	##################################
	
	### Functions ####################
	##################################
}