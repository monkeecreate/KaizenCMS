<?php
class admin_news extends adminController
{
	function __construct() {
		parent::__construct("news");
		
		$this->menuPermission("news");
	}
	
	### DISPLAY ######################
	function index() {
		$oNews = $this->loadModel("news");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_news"] = null;
		
		$this->tplAssign("aCategories", $oNews->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aArticles", $oNews->getArticles($_GET["category"], true));
		$this->tplAssign("sUseImage", $oNews->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oNews = $this->loadModel("news");
		
		if(!empty($_SESSION["admin"]["admin_news"])) {
			$aArticle = $_SESSION["admin"]["admin_news"];
			$aArticle["datetime_show"] = strtotime($aArticle["datetime_show_date"]." ".$aArticle["datetime_show_Hour"].":".$aArticle["datetime_show_Minute"]." ".$aArticle["datetime_show_Meridian"]);
			$aArticle["datetime_kill"] = strtotime($aArticle["datetime_kill_date"]." ".$aArticle["datetime_kill_Hour"].":".$aArticle["datetime_kill_Minute"]." ".$aArticle["datetime_kill_Meridian"]);
			
			$this->tplAssign("aArticle", $aArticle);
		} else
			$this->tplAssign("aArticle",
				array(
					"datetime_show_date" => date("m/j/Y")
					,"datetime_kill_date" => date("m/j/Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oNews->getCategories());
		$this->tplAssign("sUseCategories", $oNews->useCategories);
		$this->tplAssign("sUseImage", $oNews->useImage);
		$this->tplAssign("minWidth", $oNews->imageMinWidth);
		$this->tplAssign("minHeight", $oNews->imageMinHeight);
		$this->tplAssign("sShortContentCount", $oNews->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oNews = $this->loadModel("news");
		
		if(empty($_POST["title"])) {
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
		
		$sID = $this->dbInsert(
			"news",
			array(
				"title" => $_POST["title"]
				,"short_content" => (string)substr($_POST["short_content"], 0, $oNews->shortContentCharacters)
				,"content" => $_POST["content"]
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"sticky" => $this->boolCheck($_POST["sticky"])
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$sID = $this->dbInsert(
					"news_categories_assign",
					array(
						"articleid" => $sID
						,"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_news"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oNews->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/news/?notice=".urlencode("Article created successfully!"));
	}
	function edit() {
		$oNews = $this->loadModel("news");
		
		if(!empty($_SESSION["admin"]["admin_news"])) {
			$aArticleRow = $this->dbQuery(
				"SELECT * FROM `news`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aArticle = $_SESSION["admin"]["admin_news"];
			
			$aArticle["updated_datetime"] = $aArticleRow["updated_datetime"];
			$aArticle["updated_by"] = $this->dbQuery(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aArticleRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aArticle", $aArticle);
		} else {
			$aArticle = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aArticle["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}news_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}news_categories_assign` AS `news_assign` ON `categories`.`id` = `news_assign`.`categoryid`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aArticle["datetime_show_date"] = date("m/d/Y", $aArticle["datetime_show"]);
			$aArticle["datetime_kill_date"] = date("m/d/Y", $aArticle["datetime_kill"]);
			
			$aArticle["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aArticle["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aArticle", $aArticle);
		}
		
		$this->tplAssign("aCategories", $oNews->getCategories());
		$this->tplAssign("sUseCategories", $oNews->useCategories);
		$this->tplAssign("sUseImage", $oNews->useImage);
		$this->tplAssign("minWidth", $oNews->imageMinWidth);
		$this->tplAssign("minHeight", $oNews->imageMinHeight);
		$this->tplAssign("sShortContentCount", $oNews->shortContentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["title"])) {
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
		
		$this->dbUpdate(
			"news",
			array(
				"title" => $_POST["title"]
				,"short_content" => $_POST["short_content"]
				,"content" => $_POST["content"]
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"sticky" => $this->boolCheck($_POST["sticky"])
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("news_categories_assign", $_POST["id"], "articleid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"news_categories_assign",
					array(
						"articleid" => $_POST["id"]
						,"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_news"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oNews->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/news/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/");
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/news/image/".$_POST["id"]."/delete/");
		}
	}
	function delete() {
		$oNews = $this->loadModel("news");
		
		$this->dbDelete("news", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("news_categories_assign", $this->_urlVars->dynamic["id"], "articleid");
		
		@unlink($this->_settings->rootPublic.substr($oNews->imageFolder, 1).$this->_urlVars->dynamic["id"].".jpg");
		
		$this->forward("/admin/news/?notice=".urlencode("Article removed successfully!"));
	}
	function image_upload_s() {
		$oNews = $this->loadModel("news");
		
		if(!is_dir($this->_settings->rootPublic.substr($oNews->imageFolder, 1)))
			mkdir($this->_settings->rootPublic.substr($oNews->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->_settings->rootPublic.substr($oNews->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $oNews->imageMinWidth || $aImageSize[1] < $oNews->imageMinHeight) {
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"news",
					array(
						"photo_x1" => 0
						,"photo_y1" => 0
						,"photo_x2" => $oNews->imageMinWidth
						,"photo_y2" => $oNews->imageMinHeight
						,"photo_width" => $oNews->imageMinWidth
						,"photo_height" => $oNews->imageMinHeight
					),
					$_POST["id"]
				);

				$this->forward("/admin/news/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit() {
		$oNews = $this->loadModel("news");

		if($oNews->imageMinWidth < 300) {
			$sPreviewWidth = $oNews->imageMinWidth;
			$sPreviewHeight = $oNews->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($oNews->imageMinHeight * (300 / $oNews->imageMinWidth));
		}
		
		$this->tplAssign("aArticle", $oNews->getArticle($this->_urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $oNews->imageFolder);
		$this->tplAssign("minWidth", $oNews->imageMinWidth);
		$this->tplAssign("minHeight", $oNews->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"news",
			array(
				"photo_x1" => $_POST["x1"]
				,"photo_y1" => $_POST["y1"]
				,"photo_x2" => $_POST["x2"]
				,"photo_y2" => $_POST["y2"]
				,"photo_width" => $_POST["width"]
				,"photo_height" => $_POST["height"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/news/?notice=".urlencode("Article updated."));
	}
	function image_delete() {
		$oNews = $this->loadModel("news");
		
		$this->dbUpdate(
			"news",
			array(
				"photo_x1" => 0
				,"photo_y1" => 0
				,"photo_x2" => 0
				,"photo_y2" => 0
				,"photo_width" => 0
				,"photo_height" => 0
			),
			$this->_urlVars->dynamic["id"]
		);
		
		@unlink($this->_settings->rootPublic.substr($oNews->imageFolder, 1).$this->_urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/news/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {
		$oNews = $this->loadModel("news");
		
		$_SESSION["admin"]["admin_news_categories"] = null;
		
		$this->tplAssign("aCategories", $oNews->getCategories());
		$this->tplAssign("aCategoryEdit", $oNews->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"news_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/news/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"news_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/news/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("news_categories", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("news_categories_assign", $this->_urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/news/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}