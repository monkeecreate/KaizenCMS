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
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aTestimonials = $this->db_results(
			"SELECT `testimonials`.* FROM `testimonials`"
				.$sSQLCategory
				." ORDER BY `testimonials`.`name`"
			,"admin->testimonials"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aTestimonials", $aTestimonials);
		$this->tpl_display("testimonials/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_testimonials"]))
			$this->tpl_assign("aTestimonial", $_SESSION["admin"]["admin_testimonials"]);
		else
		{
			$aTestimonial = array(
				"menu" => array()
				,"categories" => array()
				,"active" => 1
			);
			
			$this->tpl_assign("aTestimonial", $aTestimonial);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("testimonials/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["homepage"]))
			$homepage = 1;
		else
			$homepage = 0;
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->db_results(
			"INSERT INTO `testimonials`"
				." (`name`, `sub_name`, `text`, `homepage`, `active`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["sub_name"], "text")
					.", ".$this->db_quote($_POST["text"], "text")
					.", ".$this->db_quote($homepage, "integer")
					.", ".$this->db_quote($active, "integer")
				.")"
			,"admin->testimonials->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
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
				$this->db_results(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($sID, "integer")
					,"admin->testimonials->failed_video_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Video file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/testimonials/";
				$file_ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["video"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `video` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->testimonials->add_video_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($sID, "integer")
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
				$this->db_results(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($sID, "integer")
					,"admin->testimonials->failed_poster_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Poster file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/testimonials/posters/";
				$file_ext = pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["poster"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `poster` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->testimonials->add_poster_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->testimonials->failed_poster_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_testimonials"]))
			$this->tpl_assign("aTestimonial", $_SESSION["admin"]["admin_testimonials"]);
		else
		{
			$aTestimonial = $this->db_results(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->testimonials->edit"
				,"row"
			);
			
			$aTestimonial["categories"] = $this->db_results(
				"SELECT `categories`.`id` FROM `testimonials_categories` AS `categories`"
					." INNER JOIN `testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
					." WHERE `assign`.`testimonialid` = ".$aTestimonial["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->testimonials->edit->categories"
				,"col"
			);
		
			$this->tpl_assign("aTestimonial", $aTestimonial);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("testimonials/edit.tpl");
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
		
		$this->db_results(
			"UPDATE `testimonials` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				.", `sub_name` = ".$this->db_quote($_POST["sub_name"], "text")
				.", `text` = ".$this->db_quote($_POST["text"], "text")
				.", `homepage` = ".$this->db_quote($homepage, "integer")
				.", `active` = ".$this->db_quote($active, "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->testimonials->edit"
		);
		
		$this->db_results(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->testimonials->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `testimonials_categories_assign`"
					." (`testimonialid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->testimonials->edit->categories"
			);
		}
		
		if(!empty($_FILES["video"]["name"]))
		{
			if($_FILES["video"]["error"] == 1)
			{
				$this->db_results(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->testimonials->failed_video_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Video file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/testimonials/";
				$file_ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sVideo = $this->db_results(
					"SELECT `video` FROM `testimonials`"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->testimonials->edit"
					,"one"
				);
				@unlink($upload_dir.$sVideo);
			
				if(move_uploaded_file($_FILES["video"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `video` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->testimonials->edit_video_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
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
				$this->db_results(
					"UPDATE `testimonials` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->testimonials->failed_poster_upload"
				);
				
				$this->forward("/admin/testimonials/?notice=".urlencode("Poster file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/testimonials/posters/";
				$file_ext = pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPoster = $this->db_results(
					"SELECT `poster` FROM `testimonials`"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->testimonials->edit"
					,"one"
				);
				@unlink($upload_dir.$sPoster);
			
				if(move_uploaded_file($_FILES["poster"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `poster` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->testimonials->edit_poster_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `testimonials` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->testimonials->edit_failed_poster_upload"
					);
					
					$this->forward("/admin/testimonials/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/");
	}
	function delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `testimonials`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->testimonials->delete"
		);
		$this->db_results(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->testimonials->categories_assign_delete"
		);
		
		$this->forward("/admin/testimonials/");
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_testimonials_categories"] = null;
		
		$aCategories = $this->db_results(
			"SELECT * FROM `testimonials_categories`"
				." ORDER BY `name`"
			,"admin->testimonials->categories"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("testimonials/categories/index.tpl");
	}
	function categories_add()
	{
		if(!empty($_SESSION["admin"]["admin_testimonials_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_testimonials_categories"]);
		
		$this->tpl_display("testimonials/categories/add.tpl");
	}
	function categories_add_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/categories/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"INSERT INTO `testimonials_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
				.")"
			,"admin->testimonials->category->add_s"
			,"insert"
		);

		$this->forward("/admin/testimonials/categories/");
	}
	function categories_edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_testimonials_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_testimonials_categories"]);
		else
		{
			$aCategory = $this->db_results(
				"SELECT * FROM `testimonials_categories`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->testimonials->category_edit"
				,"row"
			);
			
			$this->tpl_assign("aCategory", $aCategory);
		}
		
		$this->tpl_display("testimonials/categories/edit.tpl");
	}
	function categories_edit_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_testimonials_categories"] = $_POST;
			$this->forward("/admin/testimonials/categories/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"UPDATE `testimonials_categories` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->testimonials->categories->edit"
		);

		$this->forward("/admin/testimonials/categories/");
	}
	function categories_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `testimonials_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->testimonials->category->delete"
		);
		$this->db_results(
			"DELETE FROM `testimonials_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->testimonials->category->delete_assign"
		);

		$this->forward("/admin/testimonials/categories/");
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `testimonials_categories`"
				." ORDER BY `name`"
			,"admin->testimonials->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}