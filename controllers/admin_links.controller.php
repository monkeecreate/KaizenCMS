<?php
class admin_links extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_links"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `links_categories_assign` AS `assign` ON `links`.`id` = `assign`.`linkid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aLinks = $this->dbResults(
			"SELECT `links`.* FROM `links`"
				.$sSQLCategory
				." GROUP BY `links`.`id`"
				." ORDER BY `links`.`name` DESC"
			,"admin->links->index"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aLinks", $aLinks);
		$this->tplDisplay("links/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_links"]))
			$this->tplAssign("aLink", $_SESSION["admin"]["admin_links"]);
		
		else
			$this->tplAssign("aLink",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("links/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `links`"
				." (`name`, `description`, `link`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["description"], "text")
					.", ".$this->dbQuote($_POST["link"], "text")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->links->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `links_categories_assign`"
					." (`linkid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->links->add->categories"
			);
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Link created successfully!"));
	}
	function edit()
	{
		if(!empty($_SESSION["admin"]["admin_links"]))
		{
			$aLinkRow = $this->dbResults(
				"SELECT * FROM `links`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->links->edit"
				,"row"
			);
			
			$aLink = $_SESSION["admin"]["admin_links"];
			
			$aLink["updated_datetime"] = $aLinkRow["updated_datetime"];
			$aLink["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aLinkRow["updated_by"]
				,"admin->links->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		else
		{
			$aLink = $this->dbResults(
				"SELECT * FROM `links`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->links->edit"
				,"row"
			);
			
			$aLink["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `categories`.`id` = `links_assign`.`categoryid`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->links->edit->categories"
				,"col"
			);
			
			$aLink["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aLink["updated_by"]
				,"admin->links->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("links/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `links` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `description` = ".$this->dbQuote($_POST["description"], "text")
				.", `link` = ".$this->dbQuote($_POST["link"], "text")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->links->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->links->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `links_categories_assign`"
					." (`linkid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->links->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$aLink = $this->dbResults(
			"SELECT * FROM `links`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->links->edit"
			,"row"
		);
		@unlink($this->_settings->rootPublic."uploads/links/".$aLink["link"]);
		
		$this->dbResults(
			"DELETE FROM `links`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->links->delete"
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->links->categories_assign_delete"
		);
		
		$this->forward("/admin/links/?notice=".urlencode("Link removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_links_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `links_categories`"
				." ORDER BY `name`"
			,"admin->links->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
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
			,"admin->links->category->add_s"
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
			,"admin->links->categories->edit"
		);

		echo "/admin/links/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `links_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->links->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->links->category->delete_assign"
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `links_categories`"
				." ORDER BY `name`"
			,"admin->links->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}