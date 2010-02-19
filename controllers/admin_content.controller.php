<?php
class admin_content extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;
		
		$aPages = $this->dbResults(
			"SELECT * FROM `content`"
				." ORDER BY `title`"
			,"all"
		);
		
		$this->tplAssign("aPages", $aPages);
		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplDisplay("content/index.tpl");
	}
	function add()
	{
		$this->tplAssign("aPage", $_SESSION["admin"]["admin_content"]);
		$this->tplDisplay("content/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,30);
		
		$aPages = $this->dbResults(
			"SELECT `tag` FROM `content`"
				." ORDER BY `tag`"
			,"all"
		);

		if (in_array(array('tag' => $sTag), $aPages))
		{
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 30-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aPages);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sID = $this->dbResults(
			"INSERT INTO `content`"
				." (`tag`, `title`, `content`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($sTag, "text")
					.", ".$this->dbQuote($_POST["title"], "text")
					.", ".$this->dbQuote($_POST["content"], "text")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit()
	{
		if(!empty($_SESSION["admin"]["admin_content"]))
		{
			$aPage = $this->dbResults(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPage = $_SESSION["admin"]["admin_content"];
			
			$aPage["updated_datetime"] = $aPageRow["updated_datetime"];
			$aPage["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPageRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPage", $aPage);
		}
		else
		{
			$aPage = $this->dbResults(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPage["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPage["updated_by"]
				,"row"
			);
		
			$this->tplAssign("aPage", $aPage);
		}
		
		$this->tplDisplay("content/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `content` SET"
				." `title` = ".$this->dbQuote($_POST["title"], "text")
				.", `content` = ".$this->dbQuote($_POST["content"], "text")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$this->dbResults(
			"DELETE FROM `content`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/content/?notice=".urlencode("Page removed successfully!"));
	}
	##################################
	
	### Functions ####################
	##################################
}