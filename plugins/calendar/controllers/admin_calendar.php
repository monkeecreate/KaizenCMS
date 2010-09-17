<?php
class admin_calendar extends adminController
{
	function __construct() {
		parent::__construct("calendar");
		
		$this->menuPermission("calendar");
	}
	
	### DISPLAY ######################
	function index() {
		$oCalendar = $this->loadModel("calendar");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_calendar"] = null;
		
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aEvents", $oCalendar->getEvents($_GET["category"], true));
		$this->tplAssign("sUseImage", $oCalendar->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oCalendar = $this->loadModel("calendar");
		
		if(!empty($_SESSION["admin"]["admin_calendar"])) {
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			$aEvent["datetime_start"] = strtotime($aEvent["datetime_start_date"]." ".$aEvent["datetime_start_Hour"].":".$aEvent["datetime_start_Minute"]." ".$aEvent["datetime_start_Meridian"]);
			$aEvent["datetime_end"] = strtotime($aEvent["datetime_end_date"]." ".$aEvent["datetime_end_Hour"].":".$aEvent["datetime_end_Minute"]." ".$aEvent["datetime_end_Meridian"]);
			$aEvent["datetime_show"] = strtotime($aEvent["datetime_show_date"]." ".$aEvent["datetime_show_Hour"].":".$aEvent["datetime_show_Minute"]." ".$aEvent["datetime_show_Meridian"]);
			$aEvent["datetime_kill"] = strtotime($aEvent["datetime_kill_date"]." ".$aEvent["datetime_kill_Hour"].":".$aEvent["datetime_kill_Minute"]." ".$aEvent["datetime_kill_Meridian"]);
			
			$this->tplAssign("aEvent", $aEvent);
		} else
			$this->tplAssign("aEvent",
				array(
					"datetime_start_date" => date("m/d/Y")
					,"datetime_end_date" => date("m/d/Y")
					,"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("sUseCategories", $oCalendar->useCategories);
		$this->tplAssign("sUseImage", $oCalendar->useImage);
		$this->tplAssign("minWidth", $oCalendar->imageMinWidth);
		$this->tplAssign("minHeight", $oCalendar->imageMinHeight);
		$this->tplAssign("sShortContentCount", $oCalendar->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oCalendar = $this->loadModel("calendar");
		
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_calendar"] = $_POST;
			$this->forward("/admin/calendar/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_start = strtotime(
			$_POST["datetime_start_date"]." "
			.((!empty($_POST["datetime_start_Hour"]))?$_POST["datetime_start_Hour"].":".$_POST["datetime_start_Minute"]." ".$_POST["datetime_start_Meridian"]:"")
		);
		$datetime_end = strtotime(
			$_POST["datetime_end_date"]." "
			.((!empty($_POST["datetime_end_Hour"]))?$_POST["datetime_end_Hour"].":".$_POST["datetime_end_Minute"]." ".$_POST["datetime_end_Meridian"]:"")
		);
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
			"calendar",
			array(
				"title" => $_POST["title"]
				,"title" => $_POST["title"]
				,"short_content" => (string)substr($_POST["short_content"], 0, $oCalendar->shortContentCharacters)
				,"content" => $_POST["content"]
				,"allday" => $this->boolCheck($_POST["allday"])
				,"datetime_start" => $datetime_start
				,"datetime_end" => $datetime_end
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
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
					"calendar_categories_assign",
					array(
						"eventid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		if($_POST["post_twitter"] == 1) {
			$this->postTwitter($sID, $_POST["title"]);
		}
		
		if($_POST["post_facebook"] == 1) {
			$this->postFacebook($sID, $_POST["title"], (string)substr($_POST["short_content"], 0, $oCalendar->shortContentCharacters), $datetime_start, $datetime_end);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oCalendar->useImage == true) {
			$_POST["id"] = $sID;
			$this->image_upload_s();
		} else			
			$this->forward("/admin/calendar/?notice=".urlencode("Event created successfully!"));
	}
	function edit() {
		$oCalendar = $this->loadModel("calendar");
		
		if(!empty($_SESSION["admin"]["admin_calendar"])) {
			$aEventRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}calendar`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			
			$aEvent["updated_datetime"] = $aEventRow["updated_datetime"];
			$aEvent["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aEventRow["updated_by"]
				,"row"
			);
		} else {
			$aEvent = $oCalendar->getEvent($this->urlVars->dynamic["id"], true);
			
			$aEvent["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}calendar_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}calendar_categories_assign` AS `calendar_assign` ON `categories`.`id` = `calendar_assign`.`categoryid`"
					." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aEvent["datetime_start_date"] = date("m/d/Y", $aEvent["datetime_start"]);
			$aEvent["datetime_end_date"] = date("m/d/Y", $aEvent["datetime_end"]);
			$aEvent["datetime_show_date"] = date("m/d/Y", $aEvent["datetime_show"]);
			$aEvent["datetime_kill_date"] = date("m/d/Y", $aEvent["datetime_kill"]);
			
			$aEvent["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aEvent["updated_by"]
				,"row"
			);
		}
		
		$this->tplAssign("aEvent", $aEvent);
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("sUseCategories", $oCalendar->useCategories);
		$this->tplAssign("sUseImage", $oCalendar->useImage);
		$this->tplAssign("minWidth", $oCalendar->imageMinWidth);
		$this->tplAssign("minHeight", $oCalendar->imageMinHeight);
		$this->tplAssign("sShortContentCount", $oCalendar->shortContentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		$oCalendar = $this->loadModel("calendar");
		
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_calendar"] = $_POST;
			$this->forward("/admin/calendar/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_start = strtotime(
			$_POST["datetime_start_date"]." "
			.((!empty($_POST["datetime_start_Hour"]))?$_POST["datetime_start_Hour"].":".$_POST["datetime_start_Minute"]." ".$_POST["datetime_start_Meridian"]:"")
		);
		$datetime_end = strtotime(
			$_POST["datetime_end_date"]." "
			.((!empty($_POST["datetime_end_Hour"]))?$_POST["datetime_end_Hour"].":".$_POST["datetime_end_Minute"]." ".$_POST["datetime_end_Meridian"]:"")
		);
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
			"calendar",
			array(
				"title" => $_POST["title"]
				,"short_content" => (string)substr($_POST["short_content"], 0, $oCalendar->shortContentCharacters)
				,"content" => $_POST["content"]
				,"allday" => $this->boolCheck($_POST["allday"])
				,"datetime_start" => $datetime_start
				,"datetime_end" => $datetime_end
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("calendar_categories_assign", $_POST["id"], "eventid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"calendar_categories_assign",
					array(
						"eventid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		if($_POST["post_facebook"] == 1 || !empty($_POST["facebook_id"])) {
			$this->postFacebook($_POST["id"], $_POST["title"], (string)substr($_POST["short_content"], 0, $oCalendar->shortContentCharacters), $datetime_start, $datetime_end, $_POST["facebook_id"]);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		if(!empty($_FILES["image"]["type"]) && $oCalendar->useImage == true)
			$this->image_upload_s();
		else {
			if($_POST["submit"] == "Save Changes")
				$this->forward("/admin/calendar/?notice=".urlencode("Changes saved successfully!"));
			elseif($_POST["submit"] == "edit")
				$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/");
			elseif($_POST["submit"] == "delete")
				$this->forward("/admin/calendar/image/".$_POST["id"]."/delete/");
		}
	}
	function delete() {
		$oCalendar = $this->loadModel("calendar");
		
		$this->dbDelete("calendar", $this->urlVars->dynamic["id"]);
		$this->dbDelete("calendar_categories_assign", $this->urlVars->dynamic["id"], "eventid");
		
		@unlink($this->settings->rootPublic.substr($oCalendar->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");
		
		$this->forward("/admin/calendar/?notice=".urlencode("Event removed successfully!"));
	}
	function image_upload_s() {			
		$oCalendar = $this->loadModel("calendar");
				
		if(!is_dir($this->settings->rootPublic.substr($oCalendar->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($oCalendar->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		) {
			$sFile = $this->settings->rootPublic.substr($oCalendar->imageFolder, 1).$_POST["id"].".jpg";
			
			$aImageSize = getimagesize($_FILES["image"]["tmp_name"]);
			if($aImageSize[0] < $oCalendar->imageMinWidth || $aImageSize[1] < $oCalendar->imageMinHeight) {
				$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/?error=".urlencode("Image does not meet the minimum width and height requirements."));
			}
			
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile)) {
				$this->dbUpdate(
					"calendar",
					array(
						"photo_x1" => 0
						,"photo_y1" => 0
						,"photo_x2" => $oCalendar->imageMinWidth
						,"photo_y2" => $oCalendar->imageMinHeight
						,"photo_width" => $oCalendar->imageMinWidth
						,"photo_height" => $oCalendar->imageMinHeight
					),
					$_POST["id"]
				);
				
				$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/");
			} else
				$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/?error=".urlencode("Unable to upload image."));
		} else
			$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit() {
		$oCalendar = $this->loadModel("calendar");
		
		// Preview Size
		if($oCalendar->imageMinWidth < 300) {
			$sPreviewWidth = $oCalendar->imageMinWidth;
			$sPreviewHeight = $oCalendar->imageMinHeight;
		} else {
			$sPreviewWidth = 300;
			$sPreviewHeight = ceil($oCalendar->imageMinHeight * (300 / $oCalendar->imageMinWidth));
		}
		
		$this->tplAssign("aEvent", $oCalendar->getEvent($this->urlVars->dynamic["id"], true));
		$this->tplAssign("sFolder", $oCalendar->imageFolder);
		$this->tplAssign("minWidth", $oCalendar->imageMinWidth);
		$this->tplAssign("minHeight", $oCalendar->imageMinHeight);
		$this->tplAssign("previewWidth", $sPreviewWidth);
		$this->tplAssign("previewHeight", $sPreviewHeight);

		$this->tplDisplay("admin/image.tpl");
	}
	function image_edit_s() {
		$this->dbUpdate(
			"calendar",
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
		
		$this->forward("/admin/calendar/?notice=".urlencode("Event successfully saved."));
	}
	function image_delete() {
		$oCalendar = $this->loadModel("calendar");
		
		$this->dbUpdate(
			"calendar",
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
		
		@unlink($this->settings->rootPublic.substr($oCalendar->imageFolder, 1).$this->urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/calendar/?notice=".urlencode("Event successfully saved."));
	}
	function categories_index() {
		$oCalendar = $this->loadModel("calendar");
		
		$_SESSION["admin"]["admin_calendar_categories"] = null;

		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("aCategoryEdit", $oCalendar->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"calendar_categories",
			array(
				"name" => $_POST["name"]
			)
		);
	
		$this->forward("/admin/calendar/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"calendar_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);
		
		$this->forward("/admin/calendar/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("calendar_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("calendar_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/calendar/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	function postFacebook($sID, $sTitle, $sShortContent, $sStartTime, $sEndTime, $sFacebookID) {
		$aFacebook = $this->loadFacebook();
		
		$sPrefix = 'http';
		if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
			$sPrefix .= "://";
			
		$sTitleUrl = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($sTitle)))));
		
		if(strlen($sTitleUrl) > 50)
			$sTitleUrl = substr($sTitleUrl, 0, 50)."...";
		
		try {
			$aFacebookResult = $aFacebook["obj"]->api('/me/events/', 'post', array("access_token" => $aFacebook["access_token"], "name" => $sTitle, "description" => $sShortContent.' More information at '.$sPrefix.$_SERVER["HTTP_HOST"].'/calendar/'.$sID.'/'.$sTitleUrl.'/', "start_time" => date("c", $sStartTime), "end_time" => date("c", $sEndTime)));
			
			if(empty($sFacebookID)) {
				$this->dbUpdate(
					"calendar",
					array(
						"facebook_id" => sprintf("%30.0f", $aFacebookResult["id"])
					),
					$sID
				);
			}
		} catch (FacebookApiException $e) {
			error_log($e);
			$this->errors[] = "errors[]=".urlencode("Error posting to Facebook. Please try again later.");
		}
	}
}