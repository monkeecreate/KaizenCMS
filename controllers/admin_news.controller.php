<?php
class admin_news extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_news"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `news_categories_assign` AS `assign` ON `news`.`id` = `assign`.`articleid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aArticles = $this->db_results(
			"SELECT `news`.* FROM `news`"
				.$sSQLCategory
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
			,"admin->news->index"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aArticles", $aArticles);
		$this->tpl_display("news/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_news"]))
		{
			$aArticle = $_SESSION["admin"]["admin_news"];
			$aArticle["datetime_show"] = strtotime($aArticle["datetime_show_date"]." ".$aArticle["datetime_show_Hour"].":".$aArticle["datetime_show_Minute"]." ".$aArticle["datetime_show_Meridian"]);
			$aArticle["datetime_kill"] = strtotime($aArticle["datetime_kill_date"]." ".$aArticle["datetime_kill_Hour"].":".$aArticle["datetime_kill_Minute"]." ".$aArticle["datetime_kill_Meridian"]);
			
			$this->tpl_assign("aArticle", $aArticle);
		}
		else
			$this->tpl_assign("aArticle",
				array(
					"datetime_show_date" => date("m/j/Y")
					,"datetime_kill_date" => date("m/j/Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("news/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_news"] = $_POST;
			$this->forward("/admin/news/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_show = strtotime(
			$_POST["datetime_show_date"]." "
			.$_POST["datetime_show_Hour"].":".$_POST["datetime_show_Minute"]." "
			.$_POST["datetime_show_Meridian"]
		);
		$datetime_kill = strtotime(
			$_POST["datetime_kill_date"]." "
			.$_POST["datetime_kill_Hour"].":".$_POST["datetime_kill_Minute"]." "
			.$_POST["datetime_kill_Meridian"]
		);
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["sticky"]))
			$sticky = 1;
		else
			$sticky = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->db_results(
			"INSERT INTO `news`"
				." (`title`, `short_content`, `content`, `datetime_show`, `datetime_kill`, `use_kill`, `sticky`, `active`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["title"], "text")
					.", ".$this->db_quote($_POST["short_content"], "text")
					.", ".$this->db_quote($_POST["content"], "text")
					.", ".$this->db_quote($datetime_show, "integer")
					.", ".$this->db_quote($datetime_kill, "integer")
					.", ".$this->db_quote($use_kill, "integer")
					.", ".$this->db_quote($sticky, "integer")
					.", ".$this->db_quote($active, "integer")
				.")"
			,"admin->news->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `news_categories_assign`"
					." (`articleid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->news->add->categories"
			);
		}
		
		$_SESSION["admin"]["admin_news"] = null;
		
		if($_POST["next"] == "Add Article & Add Image")
			$this->forward("/admin/news/image/".$sID."/upload/");
		else
			$this->forward("/admin/news/?notice=".urlencode("Article created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_news"]))
			$this->tpl_assign("aArticle", $_SESSION["admin"]["admin_news"]);
		else
		{
			$aArticle = $this->db_results(
				"SELECT * FROM `news`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->news->edit"
				,"row"
			);
			
			$aArticle["categories"] = $this->db_results(
				"SELECT `categories`.`id` FROM `news_categories` AS `categories`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `categories`.`id` = `news_assign`.`categoryid`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->news->edit->categories"
				,"col"
			);
			
			$aArticle["datetime_show_date"] = date("m/d/Y", $aArticle["datetime_show"]);
			$aArticle["datetime_kill_date"] = date("m/d/Y", $aArticle["datetime_kill"]);
			$aArticle["updated_at"] = date("Y-m-d h:i:s", strtotime($aArticle["updated_at"]));
			
			$this->tpl_assign("aArticle", $aArticle);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("news/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_news"] = $_POST;
			$this->forward("/admin/news/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_show = strtotime(
			$_POST["datetime_show_date"]." "
			.$_POST["datetime_show_Hour"].":".$_POST["datetime_show_Minute"]." "
			.$_POST["datetime_show_Meridian"]
		);
		$datetime_kill = strtotime(
			$_POST["datetime_kill_date"]." "
			.$_POST["datetime_kill_Hour"].":".$_POST["datetime_kill_Minute"]." "
			.$_POST["datetime_kill_Meridian"]
		);
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["sticky"]))
			$sticky = 1;
		else
			$sticky = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->db_results(
			"UPDATE `news` SET"
				." `title` = ".$this->db_quote($_POST["title"], "text")
				.", `short_content` = ".$this->db_quote($_POST["short_content"], "text")
				.", `content` = ".$this->db_quote($_POST["content"], "text")
				.", `datetime_show` = ".$this->db_quote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->db_quote($datetime_kill, "integer")
				.", `use_kill` = ".$this->db_quote($use_kill, "integer")
				.", `sticky` = ".$this->db_quote($sticky, "integer")
				.", `active` = ".$this->db_quote($active, "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->news->edit"
		);
		
		$this->db_results(
			"DELETE FROM `news_categories_assign`"
				." WHERE `articleid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->news->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `news_categories_assign`"
					." (`articleid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->news->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_news"] = null;
		
		$this->forward("/admin/news/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `news`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->delete"
		);
		$this->db_results(
			"DELETE FROM `news_categories_assign`"
				." WHERE `articleid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->categories_assign_delete"
		);
		
		$this->forward("/admin/news/?notice=".urlencode("Article removed successfully!"));
	}
	
	function image_upload($aParams)
	{
		$aArticle = $this->db_results(
			"SELECT * FROM `news`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->image->upload"
			,"row"
		);

		$this->tpl_assign("aArticle", $aArticle);

		$this->tpl_display("news/image/upload.tpl");
	}
	function image_upload_s()
	{
		$folder = $this->_settings->root_public."uploads/news/";
		
		if(!is_dir($folder))
			mkdir($folder, 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		)
		{
			@unlink($folder.$_POST["id"].".jpg");

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $folder.$_POST["id"].".jpg"))
			{
				$this->db_results(
					"UPDATE `news` SET"
						." `photo_x1` = 0"
						.", `photo_y1` = 0"
						.", `photo_x2` = 194"
						.", `photo_y2` = 129"
						.", `photo_width` = 194"
						.", `photo_height` = 129"
						." WHERE `id` = ".$_POST["id"]
					,"admin->news->image->upload"
				);

				$this->forward("/admin/news/image/".$_POST["id"]."/edit/");
			}
			else
				$this->forward("/admin/news/image/".$_POST["id"]."/upload/?error=".urlencode("Unable to upload image."));
		}
		else
			$this->forward("/admin/news/image/".$_POST["id"]."/upload/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit($aParams)
	{
		$folder = $this->_settings->root_public."uploads/news/";

		if(!is_file($folder.$aParams["id"].".jpg"))
			$this->forward("/admin/news/image/".$aParams["id"]."/upload/");

		$aArticle = $this->db_results(
			"SELECT * FROM `news`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->image->edit"
			,"row"
		);

		$this->tpl_assign("aArticle", $aArticle);
		$this->tpl_assign("sFolder", "/uploads/news/");

		$this->tpl_display("news/image/edit.tpl");
	}
	function image_edit_s()
	{
		$this->db_results(
			"UPDATE `news` SET"
				." photo_x1 = ".$this->db_quote($_POST["x1"], "integer")
				.", photo_y1 = ".$this->db_quote($_POST["y1"], "integer")
				.", photo_x2 = ".$this->db_quote($_POST["x2"], "integer")
				.", photo_y2 = ".$this->db_quote($_POST["y2"], "integer")
				.", photo_width = ".$this->db_quote($_POST["width"], "integer")
				.", photo_height = ".$this->db_quote($_POST["height"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->news->image->edit_s"
		);

		$this->forward("/admin/news/?notice=".urlencode("Image cropped successfully!"));
	}
	function image_delete($aParams)
	{
		$this->db_results(
			"UPDATE `news` SET"
				." photo_x1 = 0"
				.", photo_y1 = 0"
				.", photo_x2 = 0"
				.", photo_y2 = 0"
				.", photo_width = 0"
				.", photo_height = 0"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->image->delete"
		);
		
		@unlink($this->_settings->root_public."upload/news/".$id.".jpg");

		$this->forward("/admin/news/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_news_categories"] = null;
		
		$aCategories = $this->db_results(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"admin->news->categories"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("news/categories/index.tpl");
	}
	function categories_add()
	{
		if(!empty($_SESSION["admin"]["admin_news_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_news_categories"]);
		
		$this->tpl_display("news/categories/add.tpl");
	}
	function categories_add_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_news"] = $_POST;
			$this->forward("/admin/news/categories/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$debug_this = $this->db_results(
			"INSERT INTO `news_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
				.")"
			,"admin->news->category->add_s"
			,"insert"
		);
		

		$this->forward("/admin/news/categories/?notice=".urlencode("Category added successfully!"));
	}
	function categories_edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_news_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_news_categories"]);
		else
		{
			$aCategory = $this->db_results(
				"SELECT * FROM `news_categories`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->news->category_edit"
				,"row"
			);
			
			$this->tpl_assign("aCategory", $aCategory);
		}
		
		$this->tpl_display("news/categories/edit.tpl");
	}
	function categories_edit_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_news_categories"] = $_POST;
			$this->forward("/admin/news/categories/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"UPDATE `news_categories` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->news->categories->edit"
		);

		$this->forward("/admin/news/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `news_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->category->delete"
		);
		$this->db_results(
			"DELETE FROM `news_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->news->category->delete_assign"
		);

		$this->forward("/admin/news/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"admin->news->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}