<?php
class admin_links extends adminController
{
	function admin_links()
	{
		parent::adminController();
		
		$this->menuPermission("links");
	}
	
	### DISPLAY ######################
	function index()
	{
		$oLinks = $this->loadModel("links");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aLinks", $oLinks->getLinks($_GET["category"]));
		$this->tplDisplay("links/index.tpl");
	}
	function add()
	{
		$oLinks = $this->loadModel("links");
		
		if(!empty($_SESSION["admin"]["admin_links"]))
			$this->tplAssign("aLink", $_SESSION["admin"]["admin_links"]);
		
		else
			$this->tplAssign("aLink",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplDisplay("links/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbResults(
			"INSERT INTO `links`"
				." (`name`, `description`, `link`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["description"], "text")
					.", ".$this->dbQuote($_POST["link"], "text")
					.", ".$this->boolCheck($_POST["active"])
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `links_categories_assign`"
					." (`linkid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Link created successfully!"));
	}
	function edit()
	{
		$oLinks = $this->loadModel("links");
		
		if(!empty($_SESSION["admin"]["admin_links"]))
		{
			$aLinkRow = $oLinks->getLink($this->_urlVars->dynamic["id"]);
			
			$aLink = $_SESSION["admin"]["admin_links"];
			
			$aLink["updated_datetime"] = $aLinkRow["updated_datetime"];
			$aLink["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aLinkRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		else
		{
			$aLink = $oLinks->getLink($this->_urlVars->dynamic["id"]);
			
			$aLink["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `categories`.`id` = `links_assign`.`categoryid`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aLink["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aLink["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplDisplay("links/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `links` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `description` = ".$this->dbQuote($_POST["description"], "text")
				.", `link` = ".$this->dbQuote($_POST["link"], "text")
				.", `active` = ".$this->boolCheck($_POST["active"])
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `links_categories_assign`"
					." (`linkid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$oLinks = $this->loadModel("links");
		
		$aLink = $oLinks->getLink($this->_urlVars->dynamic["id"]);
		
		$this->dbResults(
			"DELETE FROM `links`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/links/?notice=".urlencode("Link removed successfully!"));
	}
	function categories_index()
	{
		$oLinks = $this->loadModel("links");
		
		$_SESSION["admin"]["admin_links_categories"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplDisplay("links/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `links_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		echo "/admin/links/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `links_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		echo "/admin/links/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `links_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}