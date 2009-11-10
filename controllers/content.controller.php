<?php
class content extends appController
{
	### DISPLAY ######################
	function index()
	{
		$this->tpl_display("index.tpl");
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
				$this->tpl_display("content/".$sPage.".tpl");
			else
			{
				$aContent = $this->db_results(
					"SELECT * FROM `content`"
						." WHERE `tag` = ".$this->db_quote($sPage, "text")
						." LIMIT 1"
					,"content->view"
					,"row"
				);
			
				if(!empty($aContent))
				{
					$this->tpl_assign("aContent", $aContent);
					
					if(empty($aContent["template"]))
						$this->tpl_display("content.tpl");
					else
						$this->tpl_display("content/".$aContent["template"]);
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
		
		$this->tpl_assign("aForm", $aForm);
		$this->tpl_display("contact.tpl");
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