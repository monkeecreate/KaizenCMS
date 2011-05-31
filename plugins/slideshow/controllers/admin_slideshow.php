<?php
class admin_slideshow extends adminController {
	function __construct() {
		parent::__construct("slideshow");
		
		$this->menuPermission("slideshow");
	}	
	### DISPLAY ######################
	function index() {
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}slideshow`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}slideshow`"
			,"one"
		);
		
		$this->tplAssign("aSlides", $this->model->getSlides(true));
		$this->tplAssign("imageMinWidth", $this->model->imageMinWidth);
		$this->tplAssign("imageMinHeight", $this->model->imageMinHeight);
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {		
		if(!empty($_SESSION["admin"]["admin_slideshow"])) {
			$aSlide = $_SESSION["admin"]["admin_slideshow"];
			
			$this->tplAssign("aSlide", $aSlide);
		} else
			$this->tplAssign("aSlide",
				array(
					"active" => 1
				)
			);
		
		$this->tplAssign("useDescription", $this->model->useDescription);
		$this->tplAssign("imageMinWidth", $this->model->imageMinWidth);
		$this->tplAssign("imageMinHeight", $this->model->imageMinHeight);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {		
		if(empty($_POST["title"]) || empty($_FILES["image"]["type"])) {
			$_SESSION["admin"]["admin_slideshow"] = $_POST;
			$this->forward("/admin/slideshow/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}slideshow`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"slideshow",
			array(
				"title" => $_POST["title"]
				,"description" => (string)substr($_POST["description"], 0, $this->model->shortContentCharacters)
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		$_SESSION["admin"]["admin_slideshow"] = null;
		
		$_POST["id"] = $sID;
		$this->image_upload_s();
	}
	function edit() {
		$aSlide = $this->model->getSlide($this->urlVars->dynamic["id"]);
		
		$aSlide["updated_by"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}users`"
				." WHERE `id` = ".$aSlide["updated_by"]
			,"row"
		);
		
		$this->tplAssign("aSlide", $aSlide);
		$this->tplAssign("useDescription", $this->model->useDescription);
		$this->tplAssign("imageMinWidth", $this->model->imageMinWidth);
		$this->tplAssign("imageMinHeight", $this->model->imageMinHeight);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {		
		$this->dbUpdate(
			"slideshow",
			array(
				"title" => $_POST["title"]
				,"description" => (string)substr($_POST["description"], 0, $this->model->shortContentCharacters)
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		if(!empty($_FILES["image"]["type"]))
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/slideshow/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/slideshow/image/".$_POST["id"]."/edit/");
		}
		
		$this->forward("/admin/slideshow/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("slideshow", $this->urlVars->dynamic["id"]);
		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");
		
		$this->forward("/admin/slideshow/?notice=".urlencode("Image removed successfully!"));
	}
	function sort() {
		$aSlide = $this->model->getSlide($this->urlVars->dynamic["id"]);
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}slideshow`"
					." WHERE `sort_order` < ".$aSlide["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}slideshow`"
					." WHERE `sort_order` > ".$aSlide["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"slideshow",
			array(
				"sort_order" => 0
			),
			$aSlide["id"]
		);
		
		$this->dbUpdate(
			"slideshow",
			array(
				"sort_order" => $aSlide["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"slideshow",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aSlide["id"]
		);
		
		$this->forward("/admin/slideshow/?notice=".urlencode("Sort order saved successfully!"));
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
				$this->forward("/admin/slideshow/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}
			
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {			
				$this->dbUpdate(
					"slideshow",
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
				
				$this->forward("/admin/slideshow/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/slideshow/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/slideshow/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit() {		
		// Preview Size
		if($this->model->imageMinWidth < 300) {
			$sPreviewWidth = $this->model->imageMinWidth;
			$sPreviewHeight = $this->model->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($this->model->imageMinHeight * (300 / $this->model->imageMinWidth));
		}
		
		$this->tplAssign("aSlide", $this->model->getSlide($this->urlVars->dynamic["id"], true));
		$this->tplAssign("sFolder", $this->model->imageFolder);
		$this->tplAssign("imageMinWidth", $this->model->imageMinWidth);
		$this->tplAssign("imageMinHeight", $this->model->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);
		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"slideshow",
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

		$this->forward("/admin/slideshow/?notice=".urlencode("Image cropped successfully!"));
	}
	##################################
}