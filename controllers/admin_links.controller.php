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
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aLinks = $this->dbResults(
			"SELECT `links`.* FROM `links`"
				.$sSQLCategory
				." GROUP BY `links`.`id`"
				." ORDER BY `links`.`name` DESC"
			,"admin->links->index"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aLinks", $aLinks);
		$this->tpl_display("links/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_links"]))
			$this->tpl_assign("aLink", $_SESSION["admin"]["admin_links"]);
		
		else
			$this->tpl_assign("aLink",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("links/add.tpl");
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
					.$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["description"], "text")
					.", ".$this->db_quote($_POST["link"], "text")
					.", ".$this->db_quote($active, "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
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
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_links"]))
		{
			$aLinkRow = $this->dbResults(
				"SELECT * FROM `links`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
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
			
			$this->tpl_assign("aLink", $aLink);
		}
		else
		{
			$aLink = $this->dbResults(
				"SELECT * FROM `links`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
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
			
			$this->tpl_assign("aLink", $aLink);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("links/edit.tpl");
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
				." `name` = ".$this->db_quote($_POST["name"], "text")
				.", `description` = ".$this->db_quote($_POST["description"], "text")
				.", `link` = ".$this->db_quote($_POST["link"], "text")
				.", `active` = ".$this->db_quote($active, "integer")
				.", `updated_datetime` = ".$this->db_quote(time(), "integer")
				.", `updated_by` = ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->links->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->links->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `links_categories_assign`"
					." (`linkid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->links->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$aLink = $this->dbResults(
			"SELECT * FROM `links`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->links->edit"
			,"row"
		);
		@unlink($this->_settings->root_public."uploads/links/".$aLink["link"]);
		
		$this->dbResults(
			"DELETE FROM `links`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->links->delete"
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `linkid` = ".$this->db_quote($aParams["id"], "integer")
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
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("links/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `links_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
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
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->links->categories->edit"
		);

		echo "/admin/links/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `links_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->links->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `links_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
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