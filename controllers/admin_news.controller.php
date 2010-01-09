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
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aArticles = $this->dbResults(
			"SELECT `news`.* FROM `news`"
				.$sSQLCategory
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
			,"admin->news->index"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aArticles", $aArticles);
		$this->tplDisplay("news/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_news"]))
		{
			$aArticle = $_SESSION["admin"]["admin_news"];
			$aArticle["datetime_show"] = strtotime($aArticle["datetime_show_date"]." ".$aArticle["datetime_show_Hour"].":".$aArticle["datetime_show_Minute"]." ".$aArticle["datetime_show_Meridian"]);
			$aArticle["datetime_kill"] = strtotime($aArticle["datetime_kill_date"]." ".$aArticle["datetime_kill_Hour"].":".$aArticle["datetime_kill_Minute"]." ".$aArticle["datetime_kill_Meridian"]);
			
			$this->tplAssign("aArticle", $aArticle);
		}
		else
			$this->tplAssign("aArticle",
				array(
					"datetime_show_date" => date("m/j/Y")
					,"datetime_kill_date" => date("m/j/Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("news/add.tpl");
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
		
		$sID = $this->dbResults(
			"INSERT INTO `news`"
				." (`title`, `short_content`, `content`, `datetime_show`, `datetime_kill`, `use_kill`, `sticky`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["title"], "text")
					.", ".$this->dbQuote($_POST["short_content"], "text")
					.", ".$this->dbQuote($_POST["content"], "text")
					.", ".$this->dbQuote($datetime_show, "integer")
					.", ".$this->dbQuote($datetime_kill, "integer")
					.", ".$this->dbQuote($use_kill, "integer")
					.", ".$this->dbQuote($sticky, "integer")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->news->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
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
		{
			$aArticleRow = $this->dbResults(
				"SELECT * FROM `news`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->news->edit"
				,"row"
			);
			
			$aArticle = $_SESSION["admin"]["admin_news"];
			
			$aArticle["updated_datetime"] = $aArticleRow["updated_datetime"];
			$aArticle["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aArticleRow["updated_by"]
				,"admin->news->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aArticle", $aArticle);
		}
		else
		{
			$aArticle = $this->dbResults(
				"SELECT * FROM `news`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->news->edit"
				,"row"
			);
			
			$aArticle["categories"] = $this->dbResults(
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
			
			$aArticle["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aArticle["updated_by"]
				,"admin->news->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aArticle", $aArticle);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("news/edit.tpl");
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
		
		$this->dbResults(
			"UPDATE `news` SET"
				." `title` = ".$this->dbQuote($_POST["title"], "text")
				.", `short_content` = ".$this->dbQuote($_POST["short_content"], "text")
				.", `content` = ".$this->dbQuote($_POST["content"], "text")
				.", `datetime_show` = ".$this->dbQuote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->dbQuote($datetime_kill, "integer")
				.", `use_kill` = ".$this->dbQuote($use_kill, "integer")
				.", `sticky` = ".$this->dbQuote($sticky, "integer")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->news->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `news_categories_assign`"
				." WHERE `articleid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->news->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `news_categories_assign`"
					." (`articleid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->news->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_news"] = null;
		
		$this->forward("/admin/news/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `news`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->delete"
		);
		$this->dbResults(
			"DELETE FROM `news_categories_assign`"
				." WHERE `articleid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->categories_assign_delete"
		);
		
		$this->forward("/admin/news/?notice=".urlencode("Article removed successfully!"));
	}
	
	function image_upload($aParams)
	{
		$oNews = $this->loadModel("news");
		
		$aArticle = $this->dbResults(
			"SELECT * FROM `news`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->image->upload"
			,"row"
		);

		$this->tplAssign("aArticle", $aArticle);
		$this->tplAssign("minWidth", $oNews->imageMinWidth);
		$this->tplAssign("minHeight", $oNews->imageMinHeight);
		$this->tplDisplay("news/image/upload.tpl");
	}
	function image_upload_s()
	{
		$oNews = $this->loadModel("news");
		$folder = $this->_settings->rootPublic."uploads/news/";
		
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
				$aImageSize = getimagesize($folder.$_POST["id"].".jpg");
				if($aImageSize[0] < $oNews->imageMinWidth || $aImageSize[1] < $oNews->imageMinHeight) {
					@unlink($folder + $id.".jpg");
					$this->forward("/admin/news/image/".$_POST["id"]."/upload/?error=".urlencode("Image does not meet the minimum width and height requirements."));
				} else {				
					$this->dbResults(
						"UPDATE `news` SET"
							." `photo_x1` = 0"
							.", `photo_y1` = 0"
							.", `photo_x2` = ".$oNews->imageMinWidth
							.", `photo_y2` = ".$oNews->imageMinHeight
							.", `photo_width` = ".$oNews->imageMinWidth
							.", `photo_height` = ".$oNews->imageMinHeight
							." WHERE `id` = ".$_POST["id"]
						,"admin->news->image->upload"
					);

					$this->forward("/admin/news/image/".$_POST["id"]."/edit/");
				}
			}
			else
				$this->forward("/admin/news/image/".$_POST["id"]."/upload/?error=".urlencode("Unable to upload image."));
		}
		else
			$this->forward("/admin/news/image/".$_POST["id"]."/upload/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit($aParams)
	{
		$oNews = $this->loadModel("news");
		
		$folder = $this->_settings->rootPublic."uploads/news/";

		if(!is_file($folder.$aParams["id"].".jpg"))
			$this->forward("/admin/news/image/".$aParams["id"]."/upload/");

		$aArticle = $this->dbResults(
			"SELECT * FROM `news`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->image->edit"
			,"row"
		);

		$this->tplAssign("aArticle", $aArticle);
		$this->tplAssign("sFolder", "/uploads/news/");
		$this->tplAssign("minWidth", $oNews->imageMinWidth);
		$this->tplAssign("minHeight", $oNews->imageMinHeight);

		$this->tplDisplay("news/image/edit.tpl");
	}
	function image_edit_s()
	{
		$this->dbResults(
			"UPDATE `news` SET"
				." photo_x1 = ".$this->dbQuote($_POST["x1"], "integer")
				.", photo_y1 = ".$this->dbQuote($_POST["y1"], "integer")
				.", photo_x2 = ".$this->dbQuote($_POST["x2"], "integer")
				.", photo_y2 = ".$this->dbQuote($_POST["y2"], "integer")
				.", photo_width = ".$this->dbQuote($_POST["width"], "integer")
				.", photo_height = ".$this->dbQuote($_POST["height"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->news->image->edit_s"
		);

		$this->forward("/admin/news/?notice=".urlencode("Image cropped successfully!"));
	}
	function image_delete($aParams)
	{
		$this->dbResults(
			"UPDATE `news` SET"
				." photo_x1 = 0"
				.", photo_y1 = 0"
				.", photo_x2 = 0"
				.", photo_y2 = 0"
				.", photo_width = 0"
				.", photo_height = 0"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->image->delete"
		);
		
		@unlink($this->_settings->rootPublic."upload/news/".$id.".jpg");

		$this->forward("/admin/news/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_news_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"admin->news->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("news/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `news_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"admin->news->category->add_s"
			,"insert"
		);
		

		echo "/admin/news/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `news_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->news->categories->edit"
		);

		echo "/admin/news/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `news_categories`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `news_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->news->category->delete_assign"
		);

		$this->forward("/admin/news/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"admin->news->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}