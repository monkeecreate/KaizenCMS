<?php
class admin_calendar extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_calendar"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `calendar_categories_assign` AS `assign` ON `calendar`.`id` = `assign`.`eventid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aEvents = $this->db_results(
			"SELECT `calendar`.* FROM `calendar`"
				.$sSQLCategory
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start` DESC"
			,"admin->calendar->index"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aEvents", $aEvents);
		$this->tpl_display("calendar/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_calendar"]))
		{
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			$aEvent["datetime_start"] = strtotime($aEvent["datetime_start_date"]." ".$aEvent["datetime_start_Hour"].":".$aEvent["datetime_start_Minute"]." ".$aEvent["datetime_start_Meridian"]);
			$aEvent["datetime_end"] = strtotime($aEvent["datetime_end_date"]." ".$aEvent["datetime_end_Hour"].":".$aEvent["datetime_end_Minute"]." ".$aEvent["datetime_end_Meridian"]);
			$aEvent["datetime_show"] = strtotime($aEvent["datetime_show_date"]." ".$aEvent["datetime_show_Hour"].":".$aEvent["datetime_show_Minute"]." ".$aEvent["datetime_show_Meridian"]);
			$aEvent["datetime_kill"] = strtotime($aEvent["datetime_kill_date"]." ".$aEvent["datetime_kill_Hour"].":".$aEvent["datetime_kill_Minute"]." ".$aEvent["datetime_kill_Meridian"]);
			
			$this->tpl_assign("aEvent", $aEvent);
		}
		else
			$this->tpl_assign("aEvent",
				array(
					"datetime_start_date" => date("m/d/Y")
					,"datetime_end_date" => date("m/d/Y")
					,"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("calendar/add.tpl");
	}
	function add_s()
	{
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
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["allday"]))
			$allday = 1;
		else
			$allday = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->db_results(
			"INSERT INTO `calendar`"
				." (`title`, `short_content`, `content`, `allday`, `datetime_start`, `datetime_end`, `datetime_show`, `datetime_kill`, `use_kill`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["title"], "text")
					.", ".$this->db_quote($_POST["short_content"], "text")
					.", ".$this->db_quote($_POST["content"], "text")
					.", ".$this->db_quote($allday, "integer")
					.", ".$this->db_quote($datetime_start, "integer")
					.", ".$this->db_quote($datetime_end, "integer")
					.", ".$this->db_quote($datetime_show, "integer")
					.", ".$this->db_quote($datetime_kill, "integer")
					.", ".$this->db_quote($use_kill, "integer")
					.", ".$this->db_quote($active, "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->calendar->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `calendar_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->calendar->add->categories"
			);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		if($_POST["next"] == "Add Event & Add Image")
			$this->forward("/admin/calendar/image/".$sID."/upload/");
		else
			$this->forward("/admin/calendar/?notice=".urlencode("Event created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_calendar"]))
		{
			$aEventRow = $this->db_results(
				"SELECT * FROM `calendar`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->calendar->edit"
				,"row"
			);
			
			$aEvent = $_SESSION["admin"]["admin_calendar"];
			
			$aEvent["updated_datetime"] = $aEventRow["updated_datetime"];
			$aEvent["updated_by"] = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEventRow["updated_by"]
				,"admin->calendar->edit->updated_by"
				,"row"
			);
			
			$this->tpl_assign("aEvent", $aEvent);
		}
		else
		{
			$aEvent = $this->db_results(
				"SELECT * FROM `calendar`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->calendar->edit"
				,"row"
			);
			
			$aEvent["categories"] = $this->db_results(
				"SELECT `categories`.`id` FROM `calendar_categories` AS `categories`"
					." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `categories`.`id` = `calendar_assign`.`categoryid`"
					." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->calendar->edit->categories"
				,"col"
			);
			
			$aEvent["datetime_start_date"] = date("m/d/Y", $aEvent["datetime_start"]);
			$aEvent["datetime_end_date"] = date("m/d/Y", $aEvent["datetime_end"]);
			$aEvent["datetime_show_date"] = date("m/d/Y", $aEvent["datetime_show"]);
			$aEvent["datetime_kill_date"] = date("m/d/Y", $aEvent["datetime_kill"]);
			
			$aEvent["updated_by"] = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEvent["updated_by"]
				,"admin->calendar->edit->updated_by"
				,"row"
			);
			
			$this->tpl_assign("aEvent", $aEvent);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("calendar/edit.tpl");
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
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
		
		if(!empty($_POST["allday"]))
			$allday = 1;
		else
			$allday = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->db_results(
			"UPDATE `calendar` SET"
				." `title` = ".$this->db_quote($_POST["title"], "text")
				.", `short_content` = ".$this->db_quote($_POST["short_content"], "text")
				.", `content` = ".$this->db_quote($_POST["content"], "text")
				.", `allday` = ".$this->db_quote($allday, "integer")
				.", `datetime_start` = ".$this->db_quote($datetime_start, "integer")
				.", `datetime_end` = ".$this->db_quote($datetime_end, "integer")
				.", `datetime_show` = ".$this->db_quote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->db_quote($datetime_kill, "integer")
				.", `use_kill` = ".$this->db_quote($use_kill, "integer")
				.", `active` = ".$this->db_quote($active, "integer")
				.", `updated_datetime` = ".$this->db_quote(time(), "integer")
				.", `updated_by` = ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->calendar->edit"
		);
		
		$this->db_results(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `eventid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->calendar->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `calendar_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->calendar->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_calendar"] = null;
		
		$this->forward("/admin/calendar/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `calendar`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->content->delete"
		);
		$this->db_results(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `eventid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->content->categories_assign_delete"
		);
		
		$this->forward("/admin/calendar/?notice=".urlencode("Event removed successfully!"));
	}
	
	function image_upload($aParams)
	{
		$oCalendar = $this->loadModel("calendar");
		
		$aEvent = $this->db_results(
			"SELECT * FROM `calendar`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->calendar->image->upload"
			,"row"
		);

		$this->tpl_assign("aEvent", $aEvent);
		$this->tpl_assign("minWidth", $oCalendar->imageMinWidth);
		$this->tpl_assign("minHeight", $oCalendar->imageMinHeight);
		$this->tpl_display("calendar/image/upload.tpl");
	}
	function image_upload_s()
	{
		$oCalendar = $this->loadModel("calendar");
		$folder = $this->_settings->root_public."uploads/calendar/";
		
		if(!is_dir($folder))
			mkdir($folder, 0777);

		if($_FILES["image"]["type"] == "image/jpeg"
		 || $_FILES["image"]["type"] == "image/jpg"
		 || $_FILES["image"]["type"] == "image/pjpeg"
		)
		{
			@unlink($folder.$_POST["id"].".jpg");

			if(move_uploaded_file($_FILES["image"]["tmp_name"], $folder.$_POST["id"].".jpg"))
			{
				$aImageSize = getimagesize($folder.$_POST["id"].".jpg");
				if($aImageSize[0] < $oCalendar->imageMinWidth || $aImageSize[1] < $oCalendar->imageMinHeight) {
					@unlink($folder + $id.".jpg");
					$this->forward("/admin/calendar/image/".$_POST["id"]."/upload/?error=".urlencode("Image does not meet the minimum width and height requirements."));
				} else {
					$this->db_results(
						"UPDATE `calendar` SET"
							." `photo_x1` = 0"
							.", `photo_y1` = 0"
							.", `photo_x2` = 194"
							.", `photo_y2` = 129"
							.", `photo_width` = 194"
							.", `photo_height` = 129"
							." WHERE `id` = ".$_POST["id"]
						,"admin->calendar->image->upload"
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
	function image_edit($aParams)
	{
		$folder = $this->_settings->root_public."uploads/calendar/";

		if(!is_file($folder.$aParams["id"].".jpg"))
			$this->forward("/admin/calendar/image/".$aParams["id"]."/upload/");

		$aEvent = $this->db_results(
			"SELECT * FROM `calendar`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->calendar->image->edit"
			,"row"
		);

		$this->tpl_assign("aEvent", $aEvent);
		$this->tpl_assign("sFolder", "/uploads/calendar/");

		$this->tpl_display("calendar/image/edit.tpl");
	}
	function image_edit_s()
	{
		$this->db_results(
			"UPDATE `calendar` SET"
				." photo_x1 = ".$this->db_quote($_POST["x1"], "integer")
				.", photo_y1 = ".$this->db_quote($_POST["y1"], "integer")
				.", photo_x2 = ".$this->db_quote($_POST["x2"], "integer")
				.", photo_y2 = ".$this->db_quote($_POST["y2"], "integer")
				.", photo_width = ".$this->db_quote($_POST["width"], "integer")
				.", photo_height = ".$this->db_quote($_POST["height"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->calendar->image->edit_s"
		);

		$this->forward("/admin/calendar/?notice=".urlencode("Image cropped successfully!"));
	}
	function image_delete($aParams)
	{
		$this->db_results(
			"UPDATE `calendar` SET"
				." photo_x1 = 0"
				.", photo_y1 = 0"
				.", photo_x2 = 0"
				.", photo_y2 = 0"
				.", photo_width = 0"
				.", photo_height = 0"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->calendar->image->delete"
		);
		
		@unlink($this->_settings->root_public."upload/calendar/".$id.".jpg");

		$this->forward("/admin/calendar/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_calendar_categories"] = null;
		
		$aCategories = $this->db_results(
			"SELECT `categories`.* FROM `calendar_categories` AS `categories`"
				." ORDER BY `categories`.`name`"
			,"admin->calendar->categories"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("calendar/categories.tpl");
	}
	function categories_add_s()
	{
		$this->db_results(
			"INSERT INTO `calendar_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
				.")"
			,"admin->calendar->category->add_s"
			,"insert"
		);

		echo "/admin/calendar/categories/?notice=".urlencode("Category created successfully!");
	}
	function categories_edit_s()
	{
		$this->db_results(
			"UPDATE `calendar_categories` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->calendar->categories->edit"
		);

		echo "/admin/calendar/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `calendar_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->calendar->category->delete"
		);
		$this->db_results(
			"DELETE FROM `calendar_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->calendar->category->delete_assign"
		);

		$this->forward("/admin/calendar/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `calendar_categories`"
				." ORDER BY `name`"
			,"admin->calendar->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}