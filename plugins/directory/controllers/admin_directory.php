<?php
class admin_directory extends adminController {
	function __construct() {
		parent::__construct("directory");
		
		$this->menuPermission("directory");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_directory"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}directory`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}directory`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aListings", $this->model->getListings($_GET["category"], true));
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_directory"]))
			$this->tplAssign("aListing", $_SESSION["admin"]["admin_directory"]);
		else
			$this->tplAssign("aListing",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("aStates", $this->model->aStates);
		
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/add/?error=".urlencode("Please fill in all required fields!"));
		}

		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aListings = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}directory`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aListings)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aListings);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}directory`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"directory",
			array(
				"name" => $_POST["name"]
				,"tag" => $sTag
				,"address1" => $_POST["address1"]
				,"address2" => $_POST["address2"]
				,"city" => $_POST["city"]
				,"state" => $_POST["state"]
				,"zip" => $_POST["zip"]
				,"phone" => $_POST["phone"]
				,"fax" => $_POST["fax"]
				,"website" => $_POST["website"]
				,"email" => $_POST["email"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			"insert"
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"directory_categories_assign",
					array(
						"listingid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_directory"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/directory/?notice=".urlencode("Listing created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_directory"])) {
			$aListingRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aListing = $_SESSION["admin"]["admin_directory"];
			
			$aListing["updated_datetime"] = $aListingRow["updated_datetime"];
			$aListing["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aListingRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aListing", $aListing);
		} else {
			$aListing = $this->model->getListing($this->urlVars->dynamic["id"], null, true);
			
			$aListing["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}directory_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `categories`.`id` = `directory_assign`.`categoryid`"
					." WHERE `directory_assign`.`listingid` = ".$aListing["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aListing["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aListing["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aListing", $aListing);
		}
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("aStates", $this->model->aStates);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aListings = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}directory`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aListings)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aListings);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$this->dbUpdate(
			"directory",
			array(
				"name" => $_POST["name"]
				,"tag" => $sTag
				,"address1" => $_POST["address1"]
				,"address2" => $_POST["address2"]
				,"city" => $_POST["city"]
				,"state" => $_POST["state"]
				,"zip" => $_POST["zip"]
				,"phone" => $_POST["phone"]
				,"fax" => $_POST["fax"]
				,"website" => $_POST["website"]
				,"email" => $_POST["email"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("directory_categories_assign", $_POST["id"], "listingid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"directory_categories_assign",
					array(
						"listingid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_directory"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/directory/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/directory/image/".$_POST["id"]."/edit/");
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/directory/image/".$_POST["id"]."/delete/");
		}
	}
	function delete() {
		$this->dbDelete("directory", $this->urlVars->dynamic["id"]);
		$this->dbDelete("directory_categories_assign", $this->urlVars->dynamic["id"], "listingid");
		
		$this->forward("/admin/directory/?notice=".urlencode("Listing removed successfully!"));
	}
	function sort() {
		$aListing = $this->model->getListing($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory`"
					." WHERE `sort_order` < ".$aListing["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory`"
					." WHERE `sort_order` > ".$aListing["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"directory",
			array(
				"sort_order" => 0
			),
			$aListing["id"]
		);
		
		$this->dbUpdate(
			"directory",
			array(
				"sort_order" => $aListing["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"directory",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aListing["id"]
		);
		
		$this->forward("/admin/directory/?notice=".urlencode("Sort order saved successfully!"));
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
				$this->forward("/admin/directory/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}
			
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"directory",
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

				$this->forward("/admin/directory/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/directory/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/directory/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].")."));
	}
	function image_edit() {
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}
		
		$this->tplAssign("aListing", $this->model->getListing($this->urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"directory",
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

		$this->forward("/admin/directory/?notice=".urlencode("Listing updated."));
	}
	function image_delete() {
		$this->dbUpdate(
			"directory",
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

		$this->forward("/admin/directory/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {
		$_SESSION["admin"]["admin_directory_categories"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}directory_categories`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}directory_categories`"
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
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}directory_categories`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$this->dbInsert(
			"directory_categories",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
			)
		);

		$this->forward("/admin/directory/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"directory_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/directory/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("directory_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("directory_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/directory/categories/?notice=".urlencode("Category removed successfully!"));
	}
	function categories_sort() {
		$aCategory = $this->model->getCategory($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories`"
					." WHERE `sort_order` < ".$aCategory["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories`"
					." WHERE `sort_order` > ".$aCategory["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"directory_categories",
			array(
				"sort_order" => 0
			),
			$aCategory["id"]
		);
		
		$this->dbUpdate(
			"directory_categories",
			array(
				"sort_order" => $aCategory["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"directory_categories",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aCategory["id"]
		);
		
		$this->forward("/admin/directory/categories/?notice=".urlencode("Sort order saved successfully!"));
	}
}