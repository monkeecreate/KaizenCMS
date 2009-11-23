<?php
class admin_galleries extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_gallery"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `galleries_categories_assign` AS `assign` ON `galleries`.`id` = `assign`.`galleryid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aGalleries = $this->db_results(
			"SELECT `galleries`.* FROM `galleries`"
				.$sSQLCategory
				." ORDER BY `galleries`.`sort_order`"
			,"admin->galleries"
			,"all"
		);
		
		$sMaxSort = $this->db_results(
			"SELECT MAX(`sort_order`) FROM `galleries`"
			,"admin->menu_categories->maxsort"
			,"one"
		);
		
		foreach($aGalleries as $x => $aGallery)
		{
			$aGalleries[$x]["photos"] = $this->db_results(
				"SELECT COUNT(*) FROM `galleries_photos`"
					." WHERE `galleryid` = ".$aGallery["id"]
				,"admin->galleries->photos"
				,"one"
			);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aGalleries", $aGalleries);
		$this->tpl_assign("maxsort", $sMaxSort);
		$this->tpl_display("galleries/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_gallery"]))
			$this->tpl_assign("aGallery", $_SESSION["admin"]["admin_gallery"]);
		else
			$this->tpl_assign("aGallery",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("galleries/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->db_results(
			"SELECT MAX(`sort_order`) + 1 FROM `galleries`"
			,"admin->galleries->add->max_order"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->db_results(
			"INSERT INTO `galleries`"
				." (`name`, `description`, `sort_order`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["description"], "text")
					.", ".$this->db_quote($sOrder, "integer")
				.")"
			,"admin->galleries->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `galleries_categories_assign`"
					." (`galleryid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->galleries->add->categories"
			);
		}
		
		$folder = $this->_settings->root_public."uploads/galleries/".$sID."/";
		@mkdir($folder, 0777);
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery created successfully!"));
	}
	function sort($aParams)
	{
		$aGallery = $this->db_results(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->sort"
			,"row"
		);
		
		if($aParams["sort"] == "up")
		{
			$aOld = $this->db_results(
				"SELECT * FROM `galleries`"
					." WHERE `sort_order` < ".$aGallery["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"admin->galleries->sort->up->new_pos"
				,"row"
			);
			
			$this->db_results(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->db_quote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->db_quote($aGallery["id"], "integer")
				,"admin->galleries->sort->up->update_pos1"
			);
			
			$this->db_results(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->db_quote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->db_quote($aOld["id"], "integer")
				,"admin->galleries->sort->up->update_pos2"
			);
		}
		elseif($aParams["sort"] == "down")
		{
			$aOld = $this->db_results(
				"SELECT * FROM `galleries`"
					." WHERE `sort_order` > ".$aGallery["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"admin->galleries->sort->down->new_pos"
				,"row"
			);
			
			$this->db_results(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->db_quote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->db_quote($aGallery["id"], "integer")
				,"admin->galleries->sort->down->update_pos1"
			);
			
			$this->db_results(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->db_quote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->db_quote($aOld["id"], "integer")
				,"admin->galleries->sort->down->update_pos2"
			);
		}
		
		$this->forward("/admin/galleries/?notice=".urlencode("Sort order saved successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_galleries"]))
			$this->tpl_assign("aGallery", $_SESSION["admin"]["admin_galleries"]);
		else
		{
			$aGallery = $this->db_results(
				"SELECT * FROM `galleries`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->galleries->edit"
				,"row"
			);
			
			$aGallery["categories"] = $this->db_results(
				"SELECT `categories`.`id` FROM `galleries_categories` AS `categories`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `categories`.`id` = `galleries_assign`.`categoryid`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->galleries->edit->categories"
				,"col"
			);
		
			$this->tpl_assign("aGallery", $aGallery);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("galleries/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"UPDATE `galleries` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				.", `description` = ".$this->db_quote($_POST["description"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->galleries->edit"
		);
		
		$this->db_results(
			"DELETE FROM `galleries_categories_assign`"
				." WHERE `galleryid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->galleries->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `galleries_categories_assign`"
					." (`galleryid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->galleries->edit->categories"
			);
		}
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->delete"
		);
		
		$aPhotos = $this->db_results(
			"SELECT * FROM `galleries_photos`"
				." WHERE `galleryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->photos_delete"
			,"all"
		);
		
		foreach($aPhotos as $aPhoto)
		{
			@unlink($this->_settings->root_public."uploads/galleries/".$aParams["id"]."/".$aPhoto["photo"]);
		
			$this->db_results(
				"DELETE FROM `galleries_photos`"
					." WHERE `id` = ".$this->db_quote($aPhoto["id"], "integer")
				,"admin->galleries->photo_delete"
			);
		}
		
		@unlink($this->_settings->root_public."uploads/galleries/".$aParams["id"]."/");
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_galleries_categories"] = null;
		
		$aCategories = $this->db_results(
			"SELECT * FROM `galleries_categories`"
				." ORDER BY `name`"
			,"admin->galleries->categories"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("galleries/categories/index.tpl");
	}
	function categories_add()
	{
		if(!empty($_SESSION["admin"]["admin_galleries_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_galleries_categories"]);
		
		$this->tpl_display("galleries/categories/add.tpl");
	}
	function categories_add_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/categories/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"INSERT INTO `galleries_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
				.")"
			,"admin->galleries->category->add_s"
			,"insert"
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Category added successfully!"));
	}
	function categories_edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_galleries_categories"]))
			$this->tpl_assign("aCategory", $_SESSION["admin"]["admin_galleries_categories"]);
		else
		{
			$aCategory = $this->db_results(
				"SELECT * FROM `galleries_categories`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->galleries->category_edit"
				,"row"
			);
			
			$this->tpl_assign("aCategory", $aCategory);
		}
		
		$this->tpl_display("galleries/categories/edit.tpl");
	}
	function categories_edit_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_galleries_categories"] = $_POST;
			$this->forward("/admin/galleries/categories/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->db_results(
			"UPDATE `galleries_categories` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->galleries->categories->edit"
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `galleries_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->category->delete"
		);
		$this->db_results(
			"DELETE FROM `galleries_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->category->delete_assign"
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Category removed successfully!"));
	}
	function photos_index($aParams)
	{
		$aPhotos = $this->db_results(
			"SELECT * FROM `galleries_photos`"
				." WHERE `galleryid` = ".$aParams["gallery"]
				." ORDER BY `sort_order`"
			,"admin->galleries->photos->index"
			,"all"
		);
		
		$aGallery = $this->db_results(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["gallery"], "integer")
			,"admin->galleries->photos->gallery"
			,"row"
		);
		
		$this->tpl_assign("aPhotos", $aPhotos);
		$this->tpl_assign("aGallery", $aGallery);
		$this->tpl_display("galleries/photos/index.tpl");
	}
	function photos_add($aParams)
	{
		$aGallery = $this->db_results(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["gallery"], "integer")
			,"admin->galleries->photos->gallery"
			,"row"
		);
		
		$this->tpl_assign("aGallery", $aGallery);
		$this->tpl_display("galleries/photos/add.tpl");
	}
	function photos_add_s($aParams)
	{
		if(!empty($_FILES["photo"]["name"]))
		{
			if($_FILES["photo"]["error"] == 1)
				$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/add/?notice=".urlencode("Photo file size was too large!"));
			else
			{
				$sOrder = $this->db_results(
					"SELECT MAX(`sort_order`) + 1 FROM `galleries`"
					,"admin->galleries->add->max_order"
					,"one"
				);
		
				if(empty($sOrder))
					$sOrder = 1;
			
				$sID = $this->db_results(
					"INSERT INTO `galleries_photos`"
						." (`galleryid`, `title`, `description`, `sort_order`)"
						." VALUES"
						." ("
						.$this->db_quote($aParams["gallery"], "integer")
						.", ".$this->db_quote($_POST["title"], "text")
						.", ".$this->db_quote($_POST["description"], "text")
						.", ".$this->db_quote($sOrder, "integer")
						.")"
					,"admin->galleries->photos->add_s"
					,"insert"
				);
				
				$upload_dir = $this->_settings->root_public."uploads/galleries/".$aParams["gallery"]."/";
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
					
				$file_ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
				
				if(move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir.$upload_file))
					$this->db_results(
						"UPDATE `galleries_photos` SET"
							." `photo` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->testimonials->add_video_upload"
					);
				else
				{
					$this->db_results(
						"DELETE FROM `galleries_photos`"
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->galleries->photo->delete"
					);
					echo $upload_dir.$upload_file;die;
					$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/add/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/?notice=".urlencode("Photo added successfully!"));
	}
	function photos_sort($aParams)
	{
		$aItems = explode(",", $_POST["sort"]);
		
		foreach($aItems as $x => $aItem)
		{
			$this->db_results(
				"UPDATE `galleries_photos` SET"
					." `sort_order` = ".($x +1)
					." WHERE `id` = ".$this->db_quote($aItem, "integer")
				,"admin->galleries->photo->sort"
			);
		}
		
		$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/?notice=".urlencode("Sort order saved successfully!"));
	}
	function photos_default($aParams)
	{
		$this->db_results(
			"UPDATE `galleries_photos` SET"
				." `gallery_default` = 0"
				." WHERE `galleryid` = ".$this->db_quote($aParams["gallery"], "integer")
			,"admin->galleries->photo->default->unset"
		);
		
		$this->db_results(
			"UPDATE `galleries_photos` SET"
				." `gallery_default` = 1"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->photo->default->set"
		);
		
		$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/?notice=".urlencode("Default image has been changed!"));
	}
	function photos_edit($aParams)
	{
		$aPhoto = $this->db_results(
			"SELECT * FROM `galleries_photos`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->photos->edit->photo"
			,"row"
		);
		
		$aGallery = $this->db_results(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["gallery"], "integer")
			,"admin->galleries->photos->edit->gallery"
			,"row"
		);
		
		$this->tpl_assign("aGallery", $aGallery);
		$this->tpl_assign("aPhoto", $aPhoto);
		$this->tpl_display("galleries/photos/edit.tpl");
	}
	function photos_edit_s($aParams)
	{
		$this->db_results(
			"UPDATE `galleries_photos` SET"
				." `title` = ".$this->db_quote($_POST["title"], "text")
				.", `description` = ".$this->db_quote($_POST["title"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->galleries->photos->edit"
		);
		
		$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
	}
	function photos_delete($aParams)
	{
		$aPhoto = $this->db_results(
			"SELECT * FROM `galleries_photos`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->galleries->photos->gallery"
			,"row"
		);
		
		@unlink($this->_settings->root_public."uploads/galleries/".$aParams["gallery"]."/".$aPhoto["photo"]);
		
		$this->db_results(
			"DELETE FROM `galleries_photos`"
				." WHERE `id` = ".$this->db_quote($aPhoto["id"], "integer")
			,"admin->galleries->photo->delete"
		);
		
		$this->forward("/admin/galleries/".$aParams["gallery"]."/photos/?notice=".urlencode("Photo removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `galleries_categories`"
				." ORDER BY `name`"
			,"admin->galleries->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}