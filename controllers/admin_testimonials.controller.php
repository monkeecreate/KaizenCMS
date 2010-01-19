<?php
class admin_testimonials extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `testimonials_categories_assign` AS `assign` ON `testimonials`.`id` = `assign`.`testimonialid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aTestimonials = $this->dbResults(
			"SELECT `testimonials`.* FROM `testimonials`"
				.$sSQLCategory
				." ORDER BY `testimonials`.`name`"
			,"admin->testimonials"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplDisplay("testimonials/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_testimonials"]))
			$this->tplAssign("aTestimonial", $_SESSION["admin"]["admin_testimonials"]);
		else
		{
			$aTestimonial = array(
				"menu" => array()
				,"categories" => array()
				,"active" => 1
			);
			
			$this->tplAssign("aTestimonial", $aTestimonial);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("testimonials/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `testimonials`"
				." (`name`, `sub_name`, `text`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["sub_name"], "text")
					.", ".$this->dbQuote($_POST["text"], "text")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->testimonials->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `testimonials_categories_assign`"
					." (`testimonialid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->testimonials->add->categories"
			);
		}
		
		if(!empty($_FILES["video"]["name"]))
		{
			if($_FILES["video"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"admin->testimonials->failed_video_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Video file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/testimonials/";
				$file_ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["video"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `video` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->testimonials->add_video_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->testimonials->failed_video_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		if(!empty($_FILES["poster"]["name"]))
		{
			if($_FILES["poster"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"admin->testimonials->failed_poster_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Poster file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/testimonials/posters/";
				$file_ext = pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["poster"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `poster` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->testimonials->add_poster_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->testimonials->failed_poster_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial created successfully!"));
	}
	function edit()
	{
		if(!empty($_SESSION["admin"]["admin_testimonials"]))
		{
			$aTestimonialRow = $this->dbResults(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->testimonials->edit"
				,"row"
			);
			
			$aTestimonial = $_SESSION["admin"]["admin_news"];
			
			$aTestimonial["updated_datetime"] = $aTestimonialRow["updated_datetime"];
			$aTestimonial["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aTestimonialRow["updated_by"]
				,"admin->testimonials->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aTestimonial", $aTestimonial);
		}
		else
		{
			$aTestimonial = $this->dbResults(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->testimonials->edit"
				,"row"
			);
			
			$aTestimonial["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `testimonials_categories` AS `categories`"
					." INNER JOIN `testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
					." WHERE `assign`.`testimonialid` = ".$aTestimonial["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->testimonials->edit->categories"
				,"col"
			);
			
			$aTestimonial["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aTestimonial["updated_by"]
				,"admin->testimonials->edit->updated_by"
				,"row"
			);
		
			$this->tplAssign("aTestimonial", $aTestimonial);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("testimonials/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["homepage"]))
			$homepage = 1;
		else
			$homepage = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `testimonials` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `sub_name` = ".$this->dbQuote($_POST["sub_name"], "text")
				.", `text` = ".$this->dbQuote($_POST["text"], "text")
				.", `homepage` = ".$this->dbQuote($homepage, "integer")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->testimonials->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->testimonials->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `testimonials_categories_assign`"
					." (`testimonialid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->testimonials->edit->categories"
			);
		}
		
		if(!empty($_FILES["video"]["name"]))
		{
			if($_FILES["video"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->testimonials->failed_video_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Video file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/testimonials/";
				$file_ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sVideo = $this->dbResults(
					"SELECT `video` FROM `testimonials`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->testimonials->edit"
					,"one"
				);
				@unlink($upload_dir.$sVideo);
			
				if(move_uploaded_file($_FILES["video"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `video` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->testimonials->edit_video_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->testimonials->edit_failed_video_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		if(!empty($_FILES["poster"]["name"]))
		{
			if($_FILES["poster"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->testimonials->failed_poster_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Poster file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/testimonials/posters/";
				$file_ext = pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPoster = $this->dbResults(
					"SELECT `poster` FROM `testimonials`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->testimonials->edit"
					,"one"
				);
				@unlink($upload_dir.$sPoster);
			
				if(move_uploaded_file($_FILES["poster"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `poster` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->testimonials->edit_poster_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->testimonials->edit_failed_poster_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$this->dbResults(
			"DELETE FROM `testimonials`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->testimonials->delete"
		);
		$this->dbResults(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->testimonials->categories_assign_delete"
		);
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_testimonials_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `testimonials_categories`"
				." ORDER BY `name`"
			,"admin->testimonials->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("testimonials/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `testimonials_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"admin->testimonials->category->add_s"
			,"insert"
		);

		echo "/admin/testimonials/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `testimonials_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->testimonials->categories->edit"
		);

		echo "/admin/testimonials/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `testimonials_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->testimonials->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->testimonials->category->delete_assign"
		);

		$this->forward("/admin/testimonials/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `testimonials_categories`"
				." ORDER BY `name`"
			,"admin->testimonials->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}