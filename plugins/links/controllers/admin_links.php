<?php
class admin_links extends adminController
{
	function __construct() {
		parent::__construct("links");
		
		$this->menuPermission("links");
	}
	
	### DISPLAY ######################
	function index() {
		$oLinks = $this->loadModel("links");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aLinks", $oLinks->getLinks($_GET["category"], true));
		$this->tplAssign("sUseImage", $oLinks->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
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
		$this->tplAssign("sUseCategories", $oLinks->useCategories);
		$this->tplAssign("sUseImage", $oLinks->useImage);
		$this->tplAssign("minWidth", $oLinks->imageMinWidth);
		$this->tplAssign("minHeight", $oLinks->imageMinHeight);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oLinks = $this->loadModel("links");
		
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"links",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"link" => $_POST["link"]
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"links_categories_assign",
					array(
						"linkid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oLinks->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/links/?notice=".urlencode("Link created successfully!"));
	}
	function edit() {
		$oLinks = $this->loadModel("links");
		
		if(!empty($_SESSION["admin"]["admin_links"])) {
			$aLinkRow = $oLinks->getLink($this->urlVars->dynamic["id"]);
			
			$aLink = $_SESSION["admin"]["admin_links"];
			
			$aLink["updated_datetime"] = $aLinkRow["updated_datetime"];
			$aLink["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aLinkRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		} else {
			$aLink = $oLinks->getLink($this->urlVars->dynamic["id"]);
			
			$aLink["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}links_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `categories`.`id` = `links_assign`.`categoryid`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aLink["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aLink["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sUseCategories", $oLinks->useCategories);
		$this->tplAssign("sUseImage", $oLinks->useImage);
		$this->tplAssign("minWidth", $oLinks->imageMinWidth);
		$this->tplAssign("minHeight", $oLinks->imageMinHeight);
		$this->tplAssign("imageFolder", $oLinks->imageFolder);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		$oLinks = $this->loadModel("links");
		
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"links",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"link" => $_POST["link"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("links_categories_assign", $_POST["id"], "linkid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"links_categories_assign",
					array(
						"linkid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oLinks->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/links/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/links/image/".$_POST["id"]."/edit/");
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/links/image/".$_POST["id"]."/delete/");
		}
	}
	function delete() {
		$oLinks = $this->loadModel("links");
		
		$aLink = $oLinks->getLink($this->urlVars->dynamic["id"]);
		
		$this->dbDelete("links", $this->urlVars->dynamic["id"]);
		$this->dbDelete("links_categories_assign", $this->urlVars->dynamic["id"], "linkid");
		
		@unlink($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$aLink["image"]);
		
		$this->forward("/admin/links/?notice=".urlencode("Link removed successfully!"));
	}
	function image_upload_s() {
		$oLinks = $this->loadModel("links");
		
		if(!is_dir($this->settings->rootPublic.substr($oLinks->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($oLinks->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->settings->rootPublic.substr($oLinks->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $oLinks->imageMinWidth || $aImageSize[1] < $oLinks->imageMinHeight) {
				$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"links",
					array(
						"photo_x1" => 0
						,"photo_y1" => 0
						,"photo_x2" => $oLinks->imageMinWidth
						,"photo_y2" => $oLinks->imageMinHeight
						,"photo_width" => $oLinks->imageMinWidth
						,"photo_height" => $oLinks->imageMinHeight
					),
					$_POST["id"]
				);

				$this->forward("/admin/links/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].")."));
	}
	function image_edit() {
		$oLinks = $this->loadModel("links");

		if($oLinks->imageMinWidth < 300) {
			$sPreviewWidth = $oLinks->imageMinWidth;
			$sPreviewHeight = $oLinks->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($oLinks->imageMinHeight * (300 / $oLinks->imageMinWidth));
		}
		
		$this->tplAssign("aLink", $oLinks->getLink($this->urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $oLinks->imageFolder);
		$this->tplAssign("minWidth", $oLinks->imageMinWidth);
		$this->tplAssign("minHeight", $oLinks->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"links",
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

		$this->forward("/admin/links/?notice=".urlencode("Link updated."));
	}
	function image_delete() {
		$oLinks = $this->loadModel("links");
		
		$this->dbUpdate(
			"links",
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
		
		@unlink($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/links/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {
		$oLinks = $this->loadModel("links");
		
		$_SESSION["admin"]["admin_links_categories"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("aCategoryEdit", $oLinks->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"links_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"links_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("links_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("links_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/links/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}