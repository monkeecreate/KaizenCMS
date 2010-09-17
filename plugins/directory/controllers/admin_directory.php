<?php
class admin_directory extends adminController
{
	function __construct() {
		parent::__construct("directory");
		
		$this->menuPermission("directory");
	}
	
	### DISPLAY ######################
	function index() {
		$oDirectory = $this->loadModel("directory");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_directory"] = null;
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aListings", $oDirectory->getListings($_GET["category"], true));
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oDirectory = $this->loadModel("directory");
		
		if(!empty($_SESSION["admin"]["admin_directory"]))
			$this->tplAssign("aListing", $_SESSION["admin"]["admin_directory"]);
		else
			$this->tplAssign("aListing",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sUseCategories", $oDirectory->useCategories);
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		$this->tplAssign("aStates", $oDirectory->aStates);
		
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oDirectory = $this->loadModel("directory");
		
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"directory",
			array(
				"name" => $_POST["name"]
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
		
		if(!empty($_FILES["image"]["type"]) && $oDirectory->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/directory/?notice=".urlencode("Listing created successfully!"));
	}
	function edit() {
		$oDirectory = $this->loadModel("directory");
		
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
			$aListing = $oDirectory->getListing($this->urlVars->dynamic["id"], true);
			
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
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sUseCategories", $oDirectory->useCategories);
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		$this->tplAssign("aStates", $oDirectory->aStates);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"directory",
			array(
				"name" => $_POST["name"]
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
		
		if(!empty($_FILES["image"]["type"]) && $oDirectory->useImage == true)
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
	function image_upload_s() {
		$oDirectory = $this->loadModel("directory");
		
		if(!is_dir($this->settings->rootPublic.substr($oDirectory->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($oDirectory->imageFolder, 1), 0777);
		
		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->settings->rootPublic.substr($oDirectory->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $oDirectory->imageMinWidth || $aImageSize[1] < $oDirectory->imageMinHeight) {
				$this->forward("/admin/directory/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}
			
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"directory",
					array(
						"photo_x1" => 0
						,"photo_y1" => 0
						,"photo_x2" => $oDirectory->imageMinWidth
						,"photo_y2" => $oDirectory->imageMinHeight
						,"photo_width" => $oDirectory->imageMinWidth
						,"photo_height" => $oDirectory->imageMinHeight
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
		$oDirectory = $this->loadModel("directory");

		if($oDirectory->imageMinWidth < 300) {
			$sPreviewWidth = $oDirectory->imageMinWidth;
			$sPreviewHeight = $oDirectory->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($oDirectory->imageMinHeight * (300 / $oDirectory->imageMinWidth));
		}
		
		$this->tplAssign("aListing", $oDirectory->getListing($this->urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $oDirectory->imageFolder);
		$this->tplAssign("minWidth", $oDirectory->imageMinWidth);
		$this->tplAssign("minHeight", $oDirectory->imageMinHeight);
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
		$oDirectory = $this->loadModel("directory");
		
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
		
		@unlink($this->settings->rootPublic.substr($oDirectory->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/directory/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index() {
		$oDirectory = $this->loadModel("directory");
		
		$_SESSION["admin"]["admin_directory_categories"] = null;
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("aCategoryEdit", $oDirectory->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"directory_categories",
			array(
				"name" => $_POST["name"]
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
}