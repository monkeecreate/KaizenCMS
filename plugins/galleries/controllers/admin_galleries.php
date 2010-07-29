<?php
class admin_galleries extends adminController
{
	function __construct() {
		parent::__construct("galleries");
		
		$this->menuPermission("galleries");
	}
	
	### DISPLAY ######################
	function index() {
		$oGalleries = $this->loadModel("galleries");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_gallery"] = null;
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aGalleries", $oGalleries->getGalleries($_GET["category"], true));
		$this->tplAssign("maxsort", $oGalleries->getMaxSort());
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oGalleries = $this->loadModel("galleries");
		
		if(!empty($_SESSION["admin"]["admin_gallery"]))
			$this->tplAssign("aGallery", $_SESSION["admin"]["admin_gallery"]);
		else
			$this->tplAssign("aGallery",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("sUseCategories", $oGalleries->useCategories);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}galleries`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"galleries",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
<<<<<<< HEAD
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"galleries_categories_assign",
				array(
					"galleryid" => $sID
					,"categoryid" => $sCategory
				)
			);
=======
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"galleries_categories_assign",
					array(
						"galleryid" => $sID
						,"categoryid" => $sCategory
					)
				);
			}
>>>>>>> categories
		}
		
		$folder = $this->settings->rootPublic."uploads/galleries/".$sID."/";
		@mkdir($folder, 0777);
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery created successfully!"));
	}
<<<<<<< HEAD
	function sort() {
		$oGalleries = $this->loadModel("galleries");
		
		$aGallery = $oGalleries->getGallery($this->urlVars->dynamic["id"]);
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries`"
					." WHERE `sort_order` < ".$aGallery["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries`"
					." WHERE `sort_order` > ".$aGallery["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
		
		$this->dbUpdate(
			"galleries",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aGallery["id"]
		);
		
		$this->dbUpdate(
			"galleries",
			array(
				"sort_order" => $aGallery["sort_order"]
			),
			$aOld["id"]
		);
		
		$this->forward("/admin/galleries/?notice=".urlencode("Sort order saved successfully!"));
	}
=======
>>>>>>> categories
	function edit() {
		$oGalleries = $this->loadModel("galleries");
		
		if(!empty($_SESSION["admin"]["admin_galleries"])) {	
			$aGalleryRow = $oGalleries->getGallery($this->urlVars->dynamic["id"]);
			
			$aGallery = $_SESSION["admin"]["admin_galleries"];
			
			$aGallery["updated_datetime"] = $aGalleryRow["updated_datetime"];
			$aGallery["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aGalleryRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aGallery", $aGallery);
		} else {
			$aGallery = $oGalleries->getGallery($this->urlVars->dynamic["id"]);
			
			$aGallery["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}galleries_categories` AS `categories`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `categories`.`id` = `galleries_assign`.`categoryid`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aGallery["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aGallery["updated_by"]
				,"row"
			);
		
			$this->tplAssign("aGallery", $aGallery);
		}
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("sUseCategories", $oGalleries->useCategories);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"galleries",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
<<<<<<< HEAD
			$_POST["gallery"]
		);
		
		$this->dbDelete("galleries_categories_assign", $_POST["gallery"], "galleryid");
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"galleries_categories_assign",
				array(
					"galleryid" => $_POST["gallery"]
					,"categoryid" => $sCategory
				)
			);
		}
		
		$aItems = explode(",", $_POST["sort"]);
		foreach($aItems as $x => $aItem) {
			$this->dbUpdate(
				"galleries_photos",
				array(
					"sort_order" => ($x +1)
				),
				$aItem
			);
=======
			$_POST["id"]
		);
		
		$this->dbDelete("galleries_categories_assign", $_POST["id"], "galleryid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"galleries_categories_assign",
					array(
						"galleryid" => $_POST["id"]
						,"categoryid" => $sCategory
					)
				);
			}
>>>>>>> categories
		}
		
		$this->dbUpdate(
			"galleries_photos",
			array(
				"gallery_default" => 0
			),
			$_POST["gallery"],
			"galleryid"
		);
		
		$this->dbUpdate(
			"galleries_photos",
			array(
				"gallery_default" => 1
			),
			$_POST["default_photo"]
		);
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/".$_POST["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("galleries", $this->urlVars->dynamic["id"]);
		
		$aPhotos = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_photos`"
				." WHERE `galleryid` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
			,"all"
		);
		
		foreach($aPhotos as $aPhoto) {
			@unlink($this->settings->rootPublic."uploads/galleries/".$this->urlVars->dynamic["id"]."/".$aPhoto["photo"]);
		
			$this->dbDelete("galleries_photos", $aPhoto["id"]);
		}
		
		@unlink($this->settings->rootPublic."uploads/galleries/".$this->urlVars->dynamic["id"]."/");
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery removed successfully!"));
	}
	function sort() {
		$oGalleries = $this->loadModel("galleries");
		
		$aGallery = $oGalleries->getGallery($this->urlVars->dynamic["id"]);
		
		if($this->urlVars->dynamic["sort"] == "up")
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries`"
					." WHERE `sort_order` < ".$aGallery["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		elseif($this->urlVars->dynamic["sort"] == "down")
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries`"
					." WHERE `sort_order` > ".$aGallery["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
			
		$this->dbUpdate(
			"galleries",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aGallery["id"]
		);
		
		$this->dbUpdate(
			"galleries",
			array(
				"sort_order" => $aGallery["sort_order"]
			),
			$aOld["id"]
		);
		
		$this->forward("/admin/galleries/?notice=".urlencode("Sort order saved successfully!"));
	}
	function categories_index() {
		$oGalleries = $this->loadModel("galleries");
		
		$_SESSION["admin"]["admin_galleries_categories"] = null;
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("aCategoryEdit", $oGalleries->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"galleries_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"galleries_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("galleries_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("galleries_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Category removed successfully!"));
	}
	function photos_index() {
		$oGalleries = $this->loadModel("galleries");
		
		$aGallery = $oGalleries->getGallery($this->urlVars->dynamic["gallery"]);
		
		$aGallery["categories"] = $this->dbQuery(
			"SELECT `categories`.`id` FROM `{dbPrefix}galleries_categories` AS `categories`"
				." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `categories`.`id` = `galleries_assign`.`categoryid`"
				." WHERE `galleries_assign`.`galleryid` = ".$this->urlVars->dynamic["gallery"]
				." GROUP BY `categories`.`id`"
				." ORDER BY `categories`.`name`"
			,"col"
		);
		
		$this->tplAssign("aPhotos", $oGalleries->getPhotos($this->urlVars->dynamic["gallery"]));
		$this->tplAssign("aDefaultPhoto", $oGalleries->getPhoto(null, true));
		$this->tplAssign("aGallery", $aGallery);
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("sessionID", session_id());
		$this->tplDisplay("admin/photos/index.tpl");
	}
	function photos_add() {
		$oGalleries = $this->loadModel("galleries");
		
		if(!empty($_FILES["photo"]["name"])) {
			if($_FILES["photo"]["error"] == 1)
				die("Error: File too large!");
			else {
				$sOrder = $this->dbQuery(
					"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}galleries_photos`"
						." WHERE `galleryid` = ".$this->dbQuote($this->urlVars->dynamic["gallery"], "integer")
					,"one"
				);
		
				if(empty($sOrder))
					$sOrder = 1;
				
				$aPhotos = $oGalleries->getPhotos($this->urlVars->dynamic["gallery"]);
				if(empty($aPhotos))
					$sDefault = 1;
				else
					$sDefault = 0;
				
				$sID = $this->dbInsert(
					"galleries_photos",
					array(
						"galleryid" => $this->urlVars->dynamic["gallery"]
						,"title" => $_POST["title"]
						,"description" => $_POST["description"]
						,"gallery_default" => $sDefault
						,"sort_order" => $sOrder
					)
				);
				
				$upload_dir = $this->settings->rootPublic."uploads/galleries/".$this->urlVars->dynamic["gallery"]."/";
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
					
				$file_ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
				
				if(move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir.$upload_file))
					$this->dbUpdate(
						"galleries_photos",
						array(
							"photo" => $upload_file
						),
						$sID
					);
				else {
					$this->dbDelete("galleries_photos", $sID);
					die("Error: Failed to upload file.");
				}
				echo $sID;
			}
		} else
			die("Error: File info not sent");
	}
	function photos_manage() {
		$oGalleries = $this->loadModel("galleries");
		
		if(!empty($_GET["images"]))
			$images = " AND `id` IN (".$_GET["images"].")";
		
		$aPhotos = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_photos`"
				." WHERE `galleryid` = ".$this->dbQuote($this->urlVars->dynamic["gallery"], "integer")
				.$images
				." ORDER BY `sort_order`"
			,"all"
		);
		
		$this->tplAssign("aPhotos", $aPhotos);
		$this->tplAssign("aGallery", $oGalleries->getGallery($this->urlVars->dynamic["gallery"]));
<<<<<<< HEAD
		$this->tplDisplay("admin/photos_manage.tpl");
=======
		$this->tplDisplay("admin/photos/manage.tpl");
>>>>>>> categories
	}
	function photos_manage_s() {
		foreach($_POST["photo"] as $id => $aPhoto) {
			$this->dbUpdate(
				"galleries_photos",
				array(
					"title" => $aPhoto["title"]
					,"description" => $aPhoto["description"]
				),
				$id
<<<<<<< HEAD
			);
		}
		
		$this->forward("/admin/galleries/".$this->urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
	}
	function photos_edit() {
		$this->dbUpdate(
			"galleries_photos",
=======
			);
		}
		
		$this->forward("/admin/galleries/".$this->urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
	}
	function photos_sort() {
		$aItems = explode(",", $_POST["sort"]);
		
		foreach($aItems as $x => $aItem) {
			$this->dbUpdate(
				"galleries_photos",
				array(
					"sort_order" => ($x +1)
				),
				$aItem
			);
		}
		
		$this->dbQuery(
			"UPDATE `{dbPrefix}galleries_photos` SET"
				." `gallery_default` = 0"
				." WHERE `galleryid` = ".$this->dbQuote($this->_urlVars->dynamic["gallery"], "integer")
		);
		
		$this->dbQuery(
			"UPDATE `{dbPrefix}galleries_photos` SET"
				." `gallery_default` = 1"
				." WHERE `id` = ".$this->dbQuote($_POST["default_photo"], "integer")
		);
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Sort order saved successfully!"));
	}
	function photos_default() {
		$this->dbUpdate(
			"galleries_photos",
			array(
				"gallery_default" => 0
			),
			$this->urlVars->dynamic["gallery"],
			"galleryid"
		);
		
		$this->dbUpdate(
			"galleries_photos",
			array(
				"gallery_default" => 1
			),
			$this->urlVars->dynamic["id"]
		);
		
		$this->forward("/admin/galleries/".$this->urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Default image has been changed!"));
	}
	function photos_edit() {
		$oGalleries = $this->loadModel("galleries");
		
		$this->tplAssign("aGallery", $oGalleries->getGallery($this->urlVars->dynamic["gallery"]));
		$this->tplAssign("aPhoto", $oGalleries->getPhoto($this->urlVars->dynamic["id"]));
		$this->tplDisplay("admin/photos/edit.tpl");
	}
	function photos_edit_s() {
		$this->dbUpdate(
			"galleries_photos"
>>>>>>> categories
			array(
				"title" => $_POST["title"]
				,"description" => $_POST["description"]
			),
			$_POST["id"]
		);
		
<<<<<<< HEAD
		echo $_POST["id"];
=======
		$this->forward("/admin/galleries/".$this->urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
>>>>>>> categories
	}
	function photos_delete() {
		$oGalleries = $this->loadModel("galleries");
		
		$aPhoto = $oGalleries->getPhoto($this->urlVars->dynamic["id"]);
		
		@unlink($this->settings->rootPublic."uploads/galleries/".$this->urlVars->dynamic["gallery"]."/".$aPhoto["photo"]);
		
		$this->dbDelete("galleries_photos", $aPhoto["id"]);
		
<<<<<<< HEAD
		if($aPhoto["gallery_default"] == 1) {
			$this->dbQuery("UPDATE `galleries_photos` SET `gallery_default` = 1 WHERE `galleryid` = ".$this->urlVars->dynamic["gallery"]." LIMIT 1");
		}
		
		echo $this->dbQuery(
			"SELECT `id` FROM `galleries_photos`"
				." WHERE `galleryid` = ".$this->urlVars->dynamic["gallery"]
				." AND `gallery_default` = 1"
			,"one"
		);
=======
		$this->forward("/admin/galleries/".$this->urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Photo removed successfully!"));
>>>>>>> categories
	}
	##################################
}