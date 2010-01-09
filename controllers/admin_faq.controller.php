<?php
class admin_faq extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_faq"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `faq_categories_assign` AS `assign` ON `faq`.`id` = `assign`.`faqid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aQuestions = $this->dbResults(
			"SELECT `faq`.* FROM `faq`"
				.$sSQLCategory
				." GROUP BY `faq`.`id`"
				." ORDER BY `faq`.`sort_order`"
			,"admin->faq->index"
			,"all"
		);
		
		$sMaxSort = $this->dbResults(
			"SELECT MAX(`sort_order`) FROM `faq`"
			,"admin->faq->maxsort"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aQuestions", $aQuestions);
		$this->tplAssign("maxsort", $sMaxSort);
		$this->tplDisplay("faq/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_faq"]))
			$this->tplAssign("aQuestion", $_SESSION["admin"]["admin_faq"]);
		else
			$this->tplAssign("aQuestion",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("faq/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbResults(
			"SELECT MAX(`sort_order`) + 1 FROM `faq`"
			,"admin->faq->add->max_order"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `faq`"
				." (`question`, `answer`, `sort_order`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["question"], "text")
					.", ".$this->dbQuote($_POST["answer"], "text")
					.", ".$this->dbQuote($sOrder, "integer")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->faq->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `faq_categories_assign`"
					." (`faqid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->faq->add->categories"
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Question created successfully!"));
	}
	function sort($aParams)
	{
		$aGallery = $this->dbResults(
			"SELECT * FROM `faq`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->faq->sort"
			,"row"
		);
		
		if($aParams["sort"] == "up")
		{
			$aOld = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `sort_order` < ".$aGallery["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"admin->faq->sort->up->new_pos"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aGallery["id"], "integer")
				,"admin->faq->sort->up->update_pos1"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
				,"admin->faq->sort->up->update_pos2"
			);
		}
		elseif($aParams["sort"] == "down")
		{
			$aOld = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `sort_order` > ".$aGallery["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"admin->faq->sort->down->new_pos"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aGallery["id"], "integer")
				,"admin->faq->sort->down->update_pos1"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
				,"admin->faq->sort->down->update_pos2"
			);
		}
		
		$this->forward("/admin/faq/?notice=".urlencode("Sort order saved successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_faq"]))
		{
			$aQuestionRow = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->faq->edit"
				,"row"
			);
			
			$aQuestion = $_SESSION["admin"]["admin_faq"];
			
			$aQuestion["updated_datetime"] = $aQuestionRow["updated_datetime"];
			$aQuestion["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aQuestionRow["updated_by"]
				,"admin->faq->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		}
		else
		{
			$aQuestion = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->faq->edit"
				,"row"
			);
			
			$aQuestion["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `faq_categories` AS `categories`"
					." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `categories`.`id` = `faq_assign`.`categoryid`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->faq->edit->categories"
				,"col"
			);
			
			$aQuestion["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aQuestion["updated_by"]
				,"admin->faq->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("faq/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `faq` SET"
				." `question` = ".$this->dbQuote($_POST["question"], "text")
				.", `answer` = ".$this->dbQuote($_POST["answer"], "text")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->faq->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `faqid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->faq->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `faq_categories_assign`"
					." (`faqid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->faq->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `faq`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->faq->delete"
		);
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `faqid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->faq->categories_assign_delete"
		);
		
		$this->forward("/admin/faq/?notice=".urlencode("Question removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_faq_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `faq_categories`"
				." ORDER BY `name`"
			,"admin->faq->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("faq/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `faq_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"admin->faq->category->add_s"
			,"insert"
		);

		echo "/admin/faq/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `faq_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->faq->categories->edit"
		);

		echo "/admin/faq/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `faq_categories`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->faq->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->faq->category->delete_assign"
		);

		$this->forward("/admin/faq/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `faq_categories`"
				." ORDER BY `name`"
			,"admin->faq->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}