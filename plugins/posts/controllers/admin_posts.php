<?php
class admin_posts extends adminController {
	public $errors;
	
	function __construct() {
		parent::__construct("posts");
		
		$this->menuPermission("posts");
		
		$this->errors = array();
	}
	
	function index() {		
		// Clear saved form info
		$_SESSION["admin"]["admin_posts"] = null;
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aPosts", $this->model->getPosts($_GET["category"], true));
		$this->tplAssign("sUseImage", $this->model->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {		
		if(!empty($_SESSION["admin"]["admin_posts"])) {
			$aPost = $_SESSION["admin"]["admin_posts"];
			$aPost["publish_on"] = strtotime($aPost["publish_on_date"]." ".$aPost["publish_on_Hour"].":".$aPost["publish_on_Minute"]." ".$aPost["publish_on_Meridian"]);
			
			$this->tplAssign("aPost", $aPost);
		} else
			$this->tplAssign("aPost",
				array(
					"publish_on_date" => date("l, F d, Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aUsers", $this->dbQuery("SELECT * FROM `{dbPrefix}users`", "all"));
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("useComments", $this->model->useComments);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("sExcerptCharacters", $this->model->excerptCharacters);
		$this->tplAssign("sTwitterConnect", $this->getSetting("twitter_connect"));
		$this->tplAssign("sFacebookConnect", $this->getSetting("facebook_connect"));
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["title"]) || empty($_POST["content"])) {
			$_SESSION["admin"]["admin_posts"] = $_POST;
			$this->forward("/admin/posts/add/?error=".urlencode("Please fill in all required fields!"));
		}

		if($_POST["submit-type"] === "Save Draft")
			$sActive = 0;
		elseif($_POST["submit-type"] === "Publish")
			$sActive = 1;
		
		$publish_on = strtotime(
			$_POST["publish_on_date"]." "
			.$_POST["publish_on_Hour"].":".$_POST["publish_on_Minute"]." "
			.$_POST["publish_on_Meridian"]
		);
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aPosts = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}posts`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aPosts)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aPosts);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sID = $this->dbInsert(
			"posts",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"excerpt" => (string)substr($_POST["excerpt"], 0, $this->model->excerptCharacters)
				,"content" => $_POST["content"]
				,"tags" => $_POST["tags"]
				,"publish_on" => $publish_on
				,"allow_comments" => $this->boolCheck($_POST["allow_comments"])
				,"allow_sharing" => $this->boolCheck($_POST["allow_sharing"])
				,"sticky" => $this->boolCheck($_POST["sticky"])
				,"active" => $this->boolCheck($sActive)
				,"authorid" => $_POST["authorid"]
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$sID = $this->dbInsert(
					"posts_categories_assign",
					array(
						"postid" => $sID
						,"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_posts"] = null;
		
		if($_POST["post_twitter"] == 1 && $_POST["active"] == 1) {
			$this->postTwitter($sID);
		}
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else {	
			if($_POST["post_facebook"] == 1 && $_POST["active"] == 1)
				$this->postFacebook($sID);
				
			$this->forward("/admin/posts/?info=".urlencode("Post created successfully!")."&".implode("&", $this->errors));
		}
	}
	function edit() {		
		if(!empty($_SESSION["admin"]["admin_posts"])) {
			$aPostRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}posts`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPost = $_SESSION["admin"]["admin_posts"];
			
			$aPost["updated_datetime"] = $aPostRow["updated_datetime"];
			$aPost["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPostRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPost", $aPost);
		} else {
			$aPost = $this->model->getPost($this->urlVars->dynamic["id"], null, true);
			
			$aPost["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}posts_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}posts_categories_assign` AS `posts_assign` ON `categories`.`id` = `posts_assign`.`categoryid`"
					." WHERE `posts_assign`.`articleid` = ".$aPost["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aPost["publish_on_date"] = date("m/d/Y", $aPost["publish_on"]);
			
			$aPost["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPost["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPost", $aPost);
		}
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("useComments", $this->model->useComments);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("sExcerptCharacters", $this->model->excerptCharacters);
		$this->tplAssign("sTwitterConnect", $this->getSetting("twitter_connect"));
		$this->tplAssign("sFacebookConnect", $this->getSetting("facebook_connect"));
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {		
		if(empty($_POST["title"]) || empty($_POST["content"])) {
			$_SESSION["admin"]["admin_posts"] = $_POST;
			$this->forward("/admin/posts/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$publish_on = strtotime(
			$_POST["publish_on_date"]." "
			.$_POST["publish_on_Hour"].":".$_POST["publish_on_Minute"]." "
			.$_POST["publish_on_Meridian"]
		);
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aPosts = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}posts`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aPosts)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aPosts);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$this->dbUpdate(
			"posts",
			array(
				"title" => $_POST["title"]
				,"excerpt" => (string)substr($_POST["excerpt"], 0, $this->model->excerptCharacters)
				,"content" => $_POST["content"]
				,"publish_on" => $publish_on
				,"sticky" => $this->boolCheck($_POST["sticky"])
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("posts_categories_assign", $_POST["id"], "postid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"posts_categories_assign",
					array(
						"postid" => $_POST["id"]
						,"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_posts"] = null;
		$aPost = $this->model->getPost($_POST["id"]);
		
		if($_POST["post_twitter"] == 1 && $_POST["active"] == 1) {
			$this->postTwitter($_POST["id"]);
		}
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["post_facebook"] == 1 && $_POST["active"] == 1)
				$this->postFacebook($_POST["id"]);

			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/posts/?info=".urlencode("Changes saved successfully!")."&".implode("&", $this->errors));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/posts/image/".$_POST["id"]."/edit/?".implode("&", $this->errors));
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/posts/image/".$_POST["id"]."/delete/?".implode("&", $this->errors));
		}
	}
	function delete() {		
		$this->dbDelete("posts", $this->urlVars->dynamic["id"]);
		$this->dbDelete("posts_categories_assign", $this->urlVars->dynamic["id"], "postid");
		
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");
		
		$this->forward("/admin/posts/?info=".urlencode("Post removed successfully!"));
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
				$this->forward("/admin/posts/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"posts",
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
				
				$this->forward("/admin/posts/image/".$_POST["id"]."/edit/?post_facebook=".$sPostFacebook);
			} else
				$this->forward("/admin/posts/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image.")."&post_facebook=".$sPostFacebook);
		} else
			$this->forward("/admin/posts/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].").")."&post_facebook=".$sPostFacebook);
	}
	function image_edit() {		
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}
		
		$this->tplAssign("aPost", $this->model->getPost($this->urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {		
		$this->dbUpdate(
			"posts",
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
		
		$aPost = $this->model->getPost($_POST["id"]);
		
		if($_POST["post_facebook"] == 1)
			$this->postFacebook($aPost["id"]);
		
		$this->forward("/admin/posts/?info=".urlencode("Post updated."));
	}
	function image_delete() {		
		$this->dbUpdate(
			"posts",
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

		$this->forward("/admin/posts/?info=".urlencode("Image removed successfully!"));
	}
	function categories_index() {		
		$_SESSION["admin"]["admin_posts_categories"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}posts_categories`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}posts_categories`"
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
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}posts_categories`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$this->dbInsert(
			"posts_categories",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
			)
		);

		$this->forward("/admin/posts/categories/?info=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"posts_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/posts/categories/?info=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("posts_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("posts_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/posts/categories/?info=".urlencode("Category removed successfully!"));
	}
	function categories_sort() {
		$aCategory = $this->model->getCategory($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}posts_categories`"
					." WHERE `sort_order` < ".$aCategory["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}posts_categories`"
					." WHERE `sort_order` > ".$aCategory["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"posts_categories",
			array(
				"sort_order" => 0
			),
			$aCategory["id"]
		);
		
		$this->dbUpdate(
			"posts_categories",
			array(
				"sort_order" => $aCategory["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"posts_categories",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aCategory["id"]
		);
		
		$this->forward("/admin/posts/categories/?info=".urlencode("Sort order saved successfully!"));
	}
	
	/**
	 * Send post to Twitter
	 * @param  integer $sID    Unique post ID.
	 */
	function postTwitter($sID) {
		$oTwitter = $this->loadTwitter();
		$aPost = $this->model->getPost($sID);
		
		if($oTwitter != false) {
			$sPrefix = 'http';
			if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
				$sPrefix .= "://";
			
			$sUrl = $this->urlShorten($sPrefix.$_SERVER["HTTP_HOST"].$aPost["url"]);
			
			$aParameters = array("status" => $aPost["title"]." ".$aPost["url"]);
			$status = $oTwitter->post("statuses/update", $aParameters);
			
			if($oTwitter->http_code != 200) {
				$this->errors[] = "errors[]=".urlencode("Error posting to Twitter. Please try again later.");
			}
		} else {
			$this->errors[] = "errors[]=".urlencode("Unable to connect with Twitter. Please try again later.");
		}
	}

	/**
	 * Send post to Facebook
	 * @param  integer $sID Unique post ID.
	 */
	function postFacebook($sID) {
		$aFacebook = $this->loadFacebook();
		$aPost = $this->model->getPost($sID);
		
		$sPrefix = 'http';
		if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
			$sPrefix .= "://";
		
		if($sImage == false)
			$sImage = $sPrefix.$_SERVER["HTTP_HOST"].'/images/facebookConnect.png';
		else
			$sImage = $sPrefix.$_SERVER["HTTP_HOST"].'/image/posts/'.$aPost["id"].'/?width=90';
		
		try {
			$aFacebook["obj"]->api('/me/feed/', 'post', array("access_token" => $aFacebook["access_token"], "name" => $aPost["title"], "description" => $aPost["excerpt"], "link" => $sPrefix.$_SERVER["HTTP_HOST"].$aPost["url"], "picture" => $aPost["image"]));
		} catch (FacebookApiException $e) {
			error_log($e);
			$this->errors[] = "errors[]=".urlencode("Error posting to Facebook. Please try again later.");
		}
	}
}