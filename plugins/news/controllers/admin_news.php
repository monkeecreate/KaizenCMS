<?php
class admin_news extends adminController {
	public $errors;
	
	function __construct() {
		parent::__construct("news");
		
		$this->menuPermission("news");
		
		$this->errors = array();
	}
	
	### DISPLAY ######################
	function index() {		
		// Clear saved form info
		$_SESSION["admin"]["admin_news"] = null;
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aArticles", $this->model->getArticles($_GET["category"], true));
		$this->tplAssign("sUseImage", $this->model->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {		
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplAssign("sTwitterConnect", $this->getSetting("twitter_connect"));
		$this->tplAssign("sFacebookConnect", $this->getSetting("facebook_connect"));
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {		
		if(empty($_POST["title"]) || empty($_POST["content"])) {
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
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aArticles = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}news`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aArticles)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aArticles);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sID = $this->dbInsert(
			"news",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"short_content" => (string)substr($_POST["short_content"], 0, $this->model->shortContentCharacters)
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
		
		if($_POST["post_twitter"] == 1 && $_POST["active"] == 1) {
			$this->postTwitter($sID, $_POST["title"], $sTag);
		}
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else {	
			if($_POST["post_facebook"] == 1 && $_POST["active"] == 1)
				$this->postFacebook($sID, $_POST["title"], (string)substr($_POST["short_content"], 0, $this->model->shortContentCharacters), $sTag, false);
				
			$this->forward("/admin/news/?notice=".urlencode("Article created successfully!")."&".implode("&", $this->errors));
		}
	}
	function edit() {		
		if(!empty($_SESSION["admin"]["admin_news"])) {
			$aArticleRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aArticle = $_SESSION["admin"]["admin_news"];
			
			$aArticle["updated_datetime"] = $aArticleRow["updated_datetime"];
			$aArticle["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aArticleRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aArticle", $aArticle);
		} else {
			$aArticle = $this->model->getArticle($this->urlVars->dynamic["id"], null, true);
			
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplAssign("sTwitterConnect", $this->getSetting("twitter_connect"));
		$this->tplAssign("sFacebookConnect", $this->getSetting("facebook_connect"));
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {		
		if(empty($_POST["title"]) || empty($_POST["content"])) {
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
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aArticles = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}news`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aArticles)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aArticles);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
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
		$aArticle = $this->model->getArticle($_POST["id"]);
		
		if($_POST["post_twitter"] == 1 && $_POST["active"] == 1) {
			$this->postTwitter($_POST["id"], $_POST["title"], $aArticle["tag"]);
		}
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["post_facebook"] == 1 && $_POST["active"] == 1)
				$this->postFacebook($_POST["id"], $_POST["title"], (string)substr($_POST["short_content"], 0, $this->model->shortContentCharacters), $aArticle["tag"], false);

			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/news/?notice=".urlencode("Changes saved successfully!")."&".implode("&", $this->errors));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?".implode("&", $this->errors));
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/news/image/".$_POST["id"]."/delete/?".implode("&", $this->errors));
		}
	}
	function delete() {		
		$this->dbDelete("news", $this->urlVars->dynamic["id"]);
		$this->dbDelete("news_categories_assign", $this->urlVars->dynamic["id"], "articleid");
		
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");
		
		$this->forward("/admin/news/?notice=".urlencode("Article removed successfully!"));
	}
	function image_upload_s() {		
		if(!empty($_GET["post_facebook"]))
			$sPostFacebook = $_GET["post_facebook"];
		else
			$sPostFacebook = $_POST["post_facebook"];
		
		if(!is_dir($this->settings->rootPublic.substr($this->model->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($this->model->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->settings->rootPublic.substr($this->model->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $this->model->imageMinWidth || $aImageSize[1] < $this->model->imageMinHeight) {
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"news",
					array(
						"photo_x1" => 0
						,"photo_y1" => 0
						,"photo_x2" => $this->model->imageMinWidth
						,"photo_y2" => $this->model->imageMinHeight
						,"photo_width" => $this->model->imageMinWidth
						,"photo_height" => $this->model->imageMinHeight
					),
					$_POST["id"]
				);
				
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?post_facebook=".$sPostFacebook);
			} else
				$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image.")."&post_facebook=".$sPostFacebook);
		} else
			$this->forward("/admin/news/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].").")."&post_facebook=".$sPostFacebook);
	}
	function image_edit() {		
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}
		
		$this->tplAssign("aArticle", $this->model->getArticle($this->urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
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
		
		$aArticle = $this->model->getArticle($_POST["id"]);
		
		if($_POST["post_facebook"] == 1)
			$this->postFacebook($aArticle["id"], $aArticle["title"], $aArticle["short_content"], $aArticle["tag"], true);
		
		$this->forward("/admin/news/?notice=".urlencode("Article updated."));
	}
	function image_delete() {		
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
			$this->urlVars->dynamic["id"]
		);
		
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/news/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {		
		$_SESSION["admin"]["admin_news_categories"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}news_categories`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}news_categories`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("aCategoryEdit", $this->model->getCategory($_GET["category"]));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sortCategory)));
		
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}news_categories`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$this->dbInsert(
			"news_categories",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
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
		$this->dbDelete("news_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("news_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/news/categories/?notice=".urlencode("Category removed successfully!"));
	}
	function categories_sort() {
		$aCategory = $this->model->getCategory($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news_categories`"
					." WHERE `sort_order` < ".$aCategory["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news_categories`"
					." WHERE `sort_order` > ".$aCategory["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"news_categories",
			array(
				"sort_order" => 0
			),
			$aCategory["id"]
		);
		
		$this->dbUpdate(
			"news_categories",
			array(
				"sort_order" => $aCategory["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"news_categories",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aCategory["id"]
		);
		
		$this->forward("/admin/news/categories/?notice=".urlencode("Sort order saved successfully!"));
	}
	##################################
	
	function postTwitter($sID, $sTitle, $sTag) {
		$oTwitter = $this->loadTwitter();
		
		if($oTwitter != false) {
			$sPrefix = 'http';
			if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
				$sPrefix .= "://";
			
			$sUrl = $this->urlShorten($sPrefix.$_SERVER["HTTP_HOST"]."/news/".$sTag."/");
			
			$aParameters = array("status" => $_POST["title"]." ".$sUrl);
			$status = $oTwitter->post("statuses/update", $aParameters);
			
			if($oTwitter->http_code != 200) {
				$this->errors[] = "errors[]=".urlencode("Error posting to Twitter. Please try again later.");
			}
		} else {
			$this->errors[] = "errors[]=".urlencode("Unable to connect with Twitter. Please try again later.");
		}
	}
	function postFacebook($sID, $sTitle, $sShortContent, $sTag, $sImage) {
		$aFacebook = $this->loadFacebook();
		
		$sPrefix = 'http';
		if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
			$sPrefix .= "://";
		
		if($sImage == false)
			$sImage = $sPrefix.$_SERVER["HTTP_HOST"].'/images/facebookConnect.png';
		else
			$sImage = $sPrefix.$_SERVER["HTTP_HOST"].'/image/news/'.$sID.'/?width=90';
		
		try {
			$aFacebook["obj"]->api('/me/feed/', 'post', array("access_token" => $aFacebook["access_token"], "name" => $sTitle, "description" => $sShortContent, "link" => $sPrefix.$_SERVER["HTTP_HOST"].'/news/'.$sTag.'/', "picture" => $sImage));
		} catch (FacebookApiException $e) {
			error_log($e);
			$this->errors[] = "errors[]=".urlencode("Error posting to Facebook. Please try again later.");
		}
	}
}