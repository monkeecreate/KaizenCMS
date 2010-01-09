<?php
class admin_events extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_events"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `events_categories_assign` AS `assign` ON `events`.`id` = `assign`.`eventid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aEvents = $this->dbResults(
			"SELECT `events`.* FROM `events`"
				.$sSQLCategory
				." GROUP BY `events`.`id`"
				." ORDER BY `events`.`datetime_start` DESC"
			,"admin->events->index"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aEvents", $aEvents);
		$this->tplDisplay("events/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_events"]))
		{
			$aEvent = $_SESSION["admin"]["admin_events"];
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
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("events/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_events"] = $_POST;
			$this->forward("/admin/events/add/?error=".urlencode("Please fill in all required fields!"));
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
		
		$sID = $this->dbResults(
			"INSERT INTO `events`"
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
			,"admin->events->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `events_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->events->add->categories"
			);
		}
		
		$_SESSION["admin"]["admin_events"] = null;
		
		if($_POST["next"] == "Add Event & Add Image")
			$this->forward("/admin/events/image/".$sID."/upload/");
		else
			$this->forward("/admin/events/?notice=".urlencode("Event created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_events"]))
		{
			$aEventRow = $this->dbResults(
				"SELECT * FROM `events`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->events->edit"
				,"row"
			);
			
			$aEvent = $_SESSION["admin"]["admin_events"];
			
			$aEvent["updated_datetime"] = $aEventRow["updated_datetime"];
			$aEvent["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEventRow["updated_by"]
				,"admin->events->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aEvent", $aEvent);
		}
		else
		{
			$aEvent = $this->dbResults(
				"SELECT * FROM `events`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->events->edit"
				,"row"
			);
			
			$aEvent["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `events_categories` AS `categories`"
					." INNER JOIN `events_categories_assign` AS `events_assign` ON `categories`.`id` = `events_assign`.`categoryid`"
					." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->events->edit->categories"
				,"col"
			);
			
			$aEvent["datetime_start_date"] = date("m/d/Y", $aEvent["datetime_start"]);
			$aEvent["datetime_end_date"] = date("m/d/Y", $aEvent["datetime_end"]);
			$aEvent["datetime_show_date"] = date("m/d/Y", $aEvent["datetime_show"]);
			$aEvent["datetime_kill_date"] = date("m/d/Y", $aEvent["datetime_kill"]);
			
			$aEvent["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aEvent["updated_by"]
				,"admin->events->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aEvent", $aEvent);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("events/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["title"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_events"] = $_POST;
			$this->forward("/admin/events/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
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
		
		$this->dbResults(
			"UPDATE `events` SET"
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
			,"admin->events->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `events_categories_assign`"
				." WHERE `eventid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->events->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `events_categories_assign`"
					." (`eventid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->events->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_events"] = null;
		
		$this->forward("/admin/events/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `events`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->content->delete"
		);
		$this->dbResults(
			"DELETE FROM `events_categories_assign`"
				." WHERE `eventid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->content->categories_assign_delete"
		);
		
		$this->forward("/admin/events/?notice=".urlencode("Event removed successfully!"));
	}
	function image_upload($aParams)
	{
		$oEvents = $this->loadModel("events");
		
		$aEvent = $this->dbResults(
			"SELECT * FROM `events`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->events->image->upload"
			,"row"
		);

		$this->tplAssign("aEvent", $aEvent);
		$this->tplAssign("minWidth", $oEvents->imageMinWidth);
		$this->tplAssign("minHeight", $oEvents->imageMinHeight);
		$this->tplDisplay("events/image/upload.tpl");
	}
	function image_upload_s()
	{
		$oEvents = $this->loadModel("events");
		$folder = $this->_settings->root_public."uploads/events/";
		
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
				if($aImageSize[0] < $oEvents->imageMinWidth || $aImageSize[1] < $oEvents->imageMinHeight) {
					@unlink($folder + $id.".jpg");
					$this->forward("/admin/events/image/".$_POST["id"]."/upload/?error=".urlencode("Image does not meet the minimum width and height requirements."));
				} else {
					$this->dbResults(
						"UPDATE `events` SET"
							." `photo_x1` = 0"
							.", `photo_y1` = 0"
							.", `photo_x2` = 194"
							.", `photo_y2` = 129"
							.", `photo_width` = 194"
							.", `photo_height` = 129"
							." WHERE `id` = ".$_POST["id"]
						,"admin->events->image->upload"
					);

					$this->forward("/admin/events/image/".$_POST["id"]."/edit/");
				}
			}
			else
				$this->forward("/admin/events/image/".$_POST["id"]."/upload/?error=".urlencode("Unable to upload image."));
		}
		else
			$this->forward("/admin/events/image/".$_POST["id"]."/upload/?error=".urlencode("Image not a jpg. Image is (".$_FILES["file"]["type"].")."));
	}
	function image_edit($aParams)
	{
		$folder = $this->_settings->root_public."uploads/events/";

		if(!is_file($folder.$aParams["id"].".jpg"))
			$this->forward("/admin/events/image/".$aParams["id"]."/upload/");

		$aEvent = $this->dbResults(
			"SELECT * FROM `events`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->events->image->edit"
			,"row"
		);

		$this->tplAssign("aEvent", $aEvent);
		$this->tplAssign("sFolder", "/uploads/events/");

		$this->tplDisplay("events/image/edit.tpl");
	}
	function image_edit_s()
	{
		$this->dbResults(
			"UPDATE `events` SET"
				." photo_x1 = ".$this->dbQuote($_POST["x1"], "integer")
				.", photo_y1 = ".$this->dbQuote($_POST["y1"], "integer")
				.", photo_x2 = ".$this->dbQuote($_POST["x2"], "integer")
				.", photo_y2 = ".$this->dbQuote($_POST["y2"], "integer")
				.", photo_width = ".$this->dbQuote($_POST["width"], "integer")
				.", photo_height = ".$this->dbQuote($_POST["height"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->events->image->edit_s"
		);

		$this->forward("/admin/events/?notice=".urlencode("Image cropped successfully!"));
	}
	function image_delete($aParams)
	{
		$this->dbResults(
			"UPDATE `events` SET"
				." photo_x1 = 0"
				.", photo_y1 = 0"
				.", photo_x2 = 0"
				.", photo_y2 = 0"
				.", photo_width = 0"
				.", photo_height = 0"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->events->image->delete"
		);
		
		@unlink($this->_settings->root_public."upload/events/".$id.".jpg");

		$this->forward("/admin/events/?notice=".urlencode("Image removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_events_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `events_categories`"
				." ORDER BY `name`"
			,"admin->events->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("events/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `events_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"admin->events->category->add_s"
			,"insert"
		);

		echo "/admin/events/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `events_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->events->categories->edit"
		);

		echo "/admin/events/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `events_categories`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->events->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `events_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->events->category->delete_assign"
		);

		$this->forward("/admin/events/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `events_categories`"
				." ORDER BY `name`"
			,"admin->events->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}