<?php
class admin_links extends adminController {
	function __construct() {
		parent::__construct("links");
		
		$this->menuPermission("links");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_links"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}links`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}links`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aLinks", $this->model->getLinks($_GET["category"], true));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_links"]))
			$this->tplAssign("aLink", $_SESSION["admin"]["admin_links"]);
		
		else
			$this->tplAssign("aLink",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aLinks = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}links`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aLinks)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aLinks);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}links`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"links",
			array(
				"name" => $_POST["name"]
				,"tag" => $sTag
				,"description" => $_POST["description"]
				,"link" => $_POST["link"]
				,"sort_order" => $sOrder
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
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/links/?notice=".urlencode("Link created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_links"])) {
			$aLinkRow = $this->model->getLink($this->urlVars->dynamic["id"], null, true);
			
			$aLink = $_SESSION["admin"]["admin_links"];
			
			$aLink["updated_datetime"] = $aLinkRow["updated_datetime"];
			$aLink["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aLinkRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		} else {
			$aLink = $this->model->getLink($this->urlVars->dynamic["id"], null, true);
			
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("imageFolder", $this->model->imageFolder);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aLinks = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}links`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aLinks)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aLinks);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
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
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true)
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
		$aLink = $this->model->getLink($this->urlVars->dynamic["id"], null, true);
		
		$this->dbDelete("links", $this->urlVars->dynamic["id"]);
		$this->dbDelete("links_categories_assign", $this->urlVars->dynamic["id"], "linkid");
		
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$aLink["image"]);
		
		$this->forward("/admin/links/?notice=".urlencode("Link removed successfully!"));
	}
	function sort() {
		$aLink = $this->model->getLink($this->urlVars->dynamic["id"], null, true);
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links`"
					." WHERE `sort_order` < ".$aLink["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links`"
					." WHERE `sort_order` > ".$aLink["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"links",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aLink["id"]
		);
		
		$this->dbUpdate(
			"links",
			array(
				"sort_order" => $aLink["sort_order"]
			),
			$aOld["id"]
		);
		
		$this->forward("/admin/links/?notice=".urlencode("Sort order saved successfully!"));
	}
	function image_upload_s() {
		if(!is_dir($this->settings->rootPublic.substr($this->model->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($this->model->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->settings->rootPublic.substr($this->model->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $this->model->imageMinWidth || $aImageSize[1] < $this->model->imageMinHeight) {
				$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"links",
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

				$this->forward("/admin/links/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/links/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].")."));
	}
	function image_edit() {
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}
		
		$this->tplAssign("aLink", $this->model->getLink($this->urlVars->dynamic["id"], null, true));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
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
		
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/links/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {
		$_SESSION["admin"]["admin_links_categories"] = null;
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("aCategoryEdit", $this->model->getCategory($_GET["category"]));
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