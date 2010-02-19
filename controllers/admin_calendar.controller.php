<?php
class admin_calendar extends adminController
{
	### DISPLAY ######################
	function index()
	{
		$oCalendar = $this->loadModel("calendar");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_calendar"] = null;
		
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aEvents", $oCalendar->getEvents($_GET["category"], true));
		$this->tplAssign("sUseImage", $oCalendar->useImage);
		
		$this->tplDisplay("calendar/index.tpl");
	}
	function add()
	{
		$oCalendar = $this->loadModel("calendar");
		
		if(!empty($_SESSION["admin"]["admin_calendar"]))
		{
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			$aEvent["datetime_start"] = strtotime($aEvent["datetime_start_date"]." ".$aEvent["datetime_start_Hour"].":".$aEvent["datetime_start_Minute"]." ".$aEvent["datetime_start_Meridian"]);
			$aEvent["datetime_end"] = strtotime($aEvent["datetime_end_date"]." ".$aEvent["datetime_end_Hour"].":".$aEvent["datetime_end_Minute"]." ".$aEvent["datetime_end_Meridian"]);
			$aEvent["datetime_show"] = strtotime($aEvent["datetime_show_date"]." ".$aEvent["datetime_show_Hour"].":".$aEvent["datetime_show_Minute"]." ".$aEvent["datetime_show_Meridian"]);
			$aEvent["datetime_kill"] = strtotime($aEvent["datetime_kill_date"]." ".$aEvent["datetime_kill_Hour"].":".$aEvent["datetime_kill_Minute"]." ".$aEvent["datetime_kill_Meridian"]);
			
			$this->tplAssign("aEvent", $aEvent);
		}
		else
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
		$this->tplAssign("sUseImage", $oCalendar->useImage);
		$this->tplDisplay("calendar/add.tpl");
	}
	function add_s()
	{
		$oCalendar = $this->loadModel("calendar");
		
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_calendar"] = $_POST;
			$this->forward("/admin/calendar/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_start = strtotime(
			$_POST["datetime_start_date"]." "
			.$_POST["datetime_start_Hour"].":".$_POST["datetime_start_Minute"]." "
			.$_POST["datetime_start_Meridian"]
		);
		$datetime_end = strtotime(
			$_POST["datetime_end_date"]." "
			.$_POST["datetime_end_Hour"].":".$_POST["datetime_end_Minute"]." "
			.$_POST["datetime_end_Meridian"]
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
		
		if(!empty($_POST["allday"]))
			$allday = 1;
		else
			$allday = 0;
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `calendar`"
				." (`title`, `short_content`, `content`, `allday`, `datetime_start`, `datetime_end`, `datetime_show`, `datetime_kill`, `use_kill`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["title"], "text")
					.", ".$this->dbQuote($_POST["short_content"], "text")
					.", ".$this->dbQuote($_POST["content"], "text")
					.", ".$this->dbQuote($allday, "integer")
					.", ".$this->dbQuote($datetime_start, "integer")
					.", ".$this->dbQuote($datetime_end, "integer")
					.", ".$this->dbQuote($datetime_show, "integer")
					.", ".$this->dbQuote($datetime_kill, "integer")
					.", ".$this->dbQuote($use_kill, "integer")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `calendar_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		if($_POST["next"] == "Add Event & Add Image" && $oCalendar->useImage == true)
			$this->forward("/admin/calendar/image/".$sID."/upload/");
		else
			$this->forward("/admin/calendar/?notice=".urlencode("Event created successfully!"));
	}
	function edit()
	{
		$oCalendar = $this->loadModel("calendar");
		
		if(!empty($_SESSION["admin"]["admin_calendar"]))
		{
			$aEventRow = $this->dbResults(
				"SELECT * FROM `calendar`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			
			$aEvent["updated_datetime"] = $aEventRow["updated_datetime"];
			$aEvent["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEventRow["updated_by"]
				,"row"
			);
		}
		else
		{
			$aEvent = $this->dbResults(
				"SELECT * FROM `calendar`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aEvent["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `calendar_categories` AS `categories`"
					." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `categories`.`id` = `calendar_assign`.`categoryid`"
					." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aEvent["datetime_start_date"] = date("m/d/Y", $aEvent["datetime_start"]);
			$aEvent["datetime_end_date"] = date("m/d/Y", $aEvent["datetime_end"]);
			$aEvent["datetime_show_date"] = date("m/d/Y", $aEvent["datetime_show"]);
			$aEvent["datetime_kill_date"] = date("m/d/Y", $aEvent["datetime_kill"]);
			
			$aEvent["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEvent["updated_by"]
				,"row"
			);
		}
		
		$this->tplAssign("aEvent", $aEvent);
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplDisplay("calendar/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_calendar"] = $_POST;
			$this->forward("/admin/calendar/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$datetime_start = strtotime(
			$_POST["datetime_start_date"]." "
			.$_POST["datetime_start_Hour"].":".$_POST["datetime_start_Minute"]." "
			.$_POST["datetime_start_Meridian"]
		);
		$datetime_end = strtotime(
			$_POST["datetime_end_date"]." "
			.$_POST["datetime_end_Hour"].":".$_POST["datetime_end_Minute"]." "
			.$_POST["datetime_end_Meridian"]
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
		
		if(!empty($_POST["allday"]))
			$allday = 1;
		else
			$allday = 0;
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `calendar` SET"
				." `title` = ".$this->dbQuote($_POST["title"], "text")
				.", `short_content` = ".$this->dbQuote($_POST["short_content"], "text")
				.", `content` = ".$this->dbQuote($_POST["content"], "text")
				.", `allday` = ".$this->dbQuote($allday, "integer")
				.", `datetime_start` = ".$this->dbQuote($datetime_start, "integer")
				.", `datetime_end` = ".$this->dbQuote($datetime_end, "integer")
				.", `datetime_show` = ".$this->dbQuote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->dbQuote($datetime_kill, "integer")
				.", `use_kill` = ".$this->dbQuote($use_kill, "integer")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->dbResults(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `eventid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `calendar_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		$this->forward("/admin/calendar/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$this->dbResults(
			"DELETE FROM `calendar`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `eventid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/calendar/?notice=".urlencode("Event removed successfully!"));
	}
	
	function image_upload()
	{
		$oCalendar = $this->loadModel("calendar");
		
		$aEvent = $this->dbResults(
			"SELECT * FROM `calendar`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"row"
		);

		$this->tplAssign("aEvent", $aEvent);
		$this->tplAssign("minWidth", $oCalendar->imageMinWidth);
		$this->tplAssign("minHeight", $oCalendar->imageMinHeight);
		$this->tplDisplay("calendar/image/upload.tpl");
	}
	function image_upload_s()
	{
		$oCalendar = $this->loadModel("calendar");
		
		if(!is_dir($this->_settings->rootPublic.substr($oCalendar->imageFolder, 1)))
			mkdir($this->_settings->rootPublic.substr($oCalendar->imageFolder, 1), 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		)
		{
			$sFile = $this->_settings->rootPublic.substr($oCalendar->imageFolder, 1).$_POST["id"].".jpg";
			
			@unlink($sFile);

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $sFile))
			{
				$aImageSize = getimagesize($sFile);
				if($aImageSize[0] < $oCalendar->imageMinWidth || $aImageSize[1] < $oCalendar->imageMinHeight) {
					@unlink($sFile);
					$this->forward("/admin/calendar/image/".$_POST["id"]."/upload/?error=".urlencode("Image does not meet the minimum width and height requirements."));
				} else {
					$this->dbResults(
						"UPDATE `calendar` SET"
							." `photo_x1` = 0"
							.", `photo_y1` = 0"
							.", `photo_x2` = ".$oCalendar->imageMinWidth
							.", `photo_y2` = ".$oCalendar->imageMinHeight
							.", `photo_width` = ".$oCalendar->imageMinWidth
							.", `photo_height` = ".$oCalendar->imageMinHeight
							." WHERE `id` = ".$_POST["id"]
					);

					$this->forward("/admin/calendar/image/".$_POST["id"]."/edit/");
				}
			}
			else
				$this->forward("/admin/calendar/image/".$_POST["id"]."/upload/?error=".urlencode("Unable to upload image."));
		}
		else
			$this->forward("/admin/calendar/image/".$_POST["id"]."/upload/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit()
	{
		$oCalendar = $this->loadModel("calendar");

		if(!is_file($this->_settings->rootPublic.substr($oCalendar->imageFolder, 1).$this->_urlVars->dynamic["id"].".jpg"))
			$this->forward("/admin/calendar/image/".$this->_urlVars->dynamic["id"]."/upload/");

		$this->tplAssign("aEvent", $oCalendar->getEvent($this->_urlVars->dynamic["id"]));
		$this->tplAssign("sFolder", $oCalendar->imageFolder);
		$this->tplAssign("minWidth", $oCalendar->imageMinWidth);
		$this->tplAssign("minHeight", $oCalendar->imageMinHeight);

		$this->tplDisplay("calendar/image/edit.tpl");
	}
	function image_edit_s()
	{
		$this->dbResults(
			"UPDATE `calendar` SET"
				." photo_x1 = ".$this->dbQuote($_POST["x1"], "integer")
				.", photo_y1 = ".$this->dbQuote($_POST["y1"], "integer")
				.", photo_x2 = ".$this->dbQuote($_POST["x2"], "integer")
				.", photo_y2 = ".$this->dbQuote($_POST["y2"], "integer")
				.", photo_width = ".$this->dbQuote($_POST["width"], "integer")
				.", photo_height = ".$this->dbQuote($_POST["height"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		$this->forward("/admin/calendar/?notice=".urlencode("Image cropped successfully!"));
	}
	function image_delete()
	{
		$oCalendar = $this->loadModel("calendar");
		
		$this->dbResults(
			"UPDATE `calendar` SET"
				." photo_x1 = 0"
				.", photo_y1 = 0"
				.", photo_x2 = 0"
				.", photo_y2 = 0"
				.", photo_width = 0"
				.", photo_height = 0"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		@unlink($this->_settings->rootPublic.substr($oCalendar->imageFolder, 1).$this->_urlVars->dynamic["id"].".jpg");

		$this->forward("/admin/calendar/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_calendar_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT `categories`.* FROM `calendar_categories` AS `categories`"
				." ORDER BY `categories`.`name`"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("calendar/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `calendar_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		echo "/admin/calendar/categories/?notice=".urlencode("Category created successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `calendar_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		echo "/admin/calendar/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `calendar_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);

		$this->forward("/admin/calendar/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}