<?php
class admin_services extends adminController {
	function __construct() {
		parent::__construct("services");

		$this->menuPermission("services");
	}

	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_services"] = null;

		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}services`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}services`"
			,"one"
		);

		$this->tplAssign("aServices", $this->model->getServices(true));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));

		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_services"]))
			$this->tplAssign("aService", $_SESSION["admin"]["admin_services"]);

		else
			$this->tplAssign("aService",
				array(
					"active" => 1
				)
			);

		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_services"] = $_POST;
			$this->forward("/admin/services/add/?error=".urlencode("Please fill in all required fields!"));
		}

		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

		$aServices = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}services`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aServices)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aServices);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}services`"
			,"one"
		);

		if(empty($sOrder))
			$sOrder = 1;

		$sID = $this->dbInsert(
			"services",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"short_content" => $_POST["short_content"]
				,"content" => $_POST["content"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);

		$_SESSION["admin"]["admin_services"] = null;

		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else
			$this->forward("/admin/services/?notice=".urlencode("Service created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_services"])) {
			$aServiceRow = $this->model->getService($this->urlVars->dynamic["id"], null, true);

			$aService = $_SESSION["admin"]["admin_services"];

			$aService["updated_datetime"] = $aServiceRow["updated_datetime"];
			$aService["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aServiceRow["updated_by"]
				,"row"
			);

			$this->tplAssign("aService", $aService);
		} else {
			$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

			$aService["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aService["updated_by"]
				,"row"
			);

			$this->tplAssign("aService", $aService);
		}

		$this->tplAssign("sUseImage", $this->model->useImage);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("imageFolder", $this->model->imageFolder);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_services"] = $_POST;
			$this->forward("/admin/services/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}

		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

		$aServices = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}services`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aServices)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aServices);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$this->dbUpdate(
			"services",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"short_content" => $_POST["short_content"]
				,"content" => $_POST["content"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);

		$_SESSION["admin"]["admin_services"] = null;

		if(!empty($_FILES["image"]["type"]) && $this->model->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/services/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/services/image/".$_POST["id"]."/edit/");
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/services/image/".$_POST["id"]."/delete/");
		}
	}
	function delete() {
		$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

		$this->dbDelete("services", $this->urlVars->dynamic["id"]);
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$aService["image"]);

		$this->forward("/admin/services/?notice=".urlencode("Service removed successfully!"));
	}
	function sort() {
		$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}services`"
					." WHERE `sort_order` < ".$aService["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}services`"
					." WHERE `sort_order` > ".$aService["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => 0
			),
			$aService["id"]
		);

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => $aService["sort_order"]
			),
			$aOld["id"]
		);

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aService["id"]
		);

		$this->forward("/admin/services/?notice=".urlencode("Sort order saved successfully!"));
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
				$this->forward("/admin/services/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"services",
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

				$this->forward("/admin/services/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/services/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/services/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["image"]["type"].")."));
	}
	function image_edit() {
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}

		$this->tplAssign("aService", $this->model->getService($this->urlVars->dynamic["id"], null, true));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("minWidth", $this->model->imageMinWidth);
		$this->tplAssign("minHeight", $this->model->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"services",
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

		$this->forward("/admin/services/?notice=".urlencode("Service updated."));
	}
	function image_delete() {
		$this->dbUpdate(
			"services",
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

		$this->forward("/admin/services/?notice=".urlencode("Image removed successfully!"));
	}
	##################################
}