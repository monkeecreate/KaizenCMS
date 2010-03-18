<?php
class admin_galleries extends adminController
{
	function admin_galleries()
	{
		parent::adminController();
		
		$this->menuPermission("galleries");
	}
	
	### DISPLAY ######################
	function index()
	{
		$oGalleries = $this->loadModel("galleries");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_gallery"] = null;
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aGalleries", $oGalleries->getGalleries($_GET["category"]));
		$this->tplAssign("maxsort", $oGalleries->getMaxSort());
		$this->tplDisplay("galleries/index.tpl");
	}
	function add()
	{
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
		$this->tplDisplay("galleries/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbResults(
			"SELECT MAX(`sort_order`) + 1 FROM `galleries`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbResults(
			"INSERT INTO `galleries`"
				." (`name`, `description`, `sort_order`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["description"], "text")
					.", ".$this->dbQuote($sOrder, "integer")
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
				"INSERT INTO `galleries_categories_assign`"
					." (`galleryid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		$folder = $this->_settings->rootPublic."uploads/galleries/".$sID."/";
		@mkdir($folder, 0777);
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery created successfully!"));
	}
	function sort()
	{
		$oGalleries = $this->loadModel("galleries");
		
		$aGallery = $oGalleries->getGallery($this->_urlVars->dynamic["id"]);
		
		if($this->_urlVars->dynamic["sort"] == "up")
		{
			$aOld = $this->dbResults(
				"SELECT * FROM `galleries`"
					." WHERE `sort_order` < ".$aGallery["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aGallery["id"], "integer")
			);
			
			$this->dbResults(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->dbQuote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
			);
		}
		elseif($this->_urlVars->dynamic["sort"] == "down")
		{
			$aOld = $this->dbResults(
				"SELECT * FROM `galleries`"
					." WHERE `sort_order` > ".$aGallery["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aGallery["id"], "integer")
			);
			
			$this->dbResults(
				"UPDATE `galleries` SET"
					." `sort_order` = ".$this->dbQuote($aGallery["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
			);
		}
		
		$this->forward("/admin/galleries/?notice=".urlencode("Sort order saved successfully!"));
	}
	function edit()
	{
		$oGalleries = $this->loadModel("galleries");
		
		if(!empty($_SESSION["admin"]["admin_galleries"]))
		{
			
			$aGalleryRow = $oGalleries->getGallery($this->_urlVars->dynamic["id"]);
			
			$aGallery = $_SESSION["admin"]["admin_galleries"];
			
			$aGallery["updated_datetime"] = $aGalleryRow["updated_datetime"];
			$aGallery["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aGalleryRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aGallery", $aGallery);
		}
		else
		{
			$aGallery = $oGalleries->getGallery($this->_urlVars->dynamic["id"]);
			
			$aGallery["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `galleries_categories` AS `categories`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `categories`.`id` = `galleries_assign`.`categoryid`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aGallery["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aGallery["updated_by"]
				,"row"
			);
		
			$this->tplAssign("aGallery", $aGallery);
		}
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplDisplay("galleries/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_galleries"] = $_POST;
			$this->forward("/admin/galleries/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `galleries` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `description` = ".$this->dbQuote($_POST["description"], "text")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->dbResults(
			"DELETE FROM `galleries_categories_assign`"
				." WHERE `galleryid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `galleries_categories_assign`"
					." (`galleryid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_galleries"] = null;
		
		$this->forward("/admin/galleries/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$this->dbResults(
			"DELETE FROM `galleries`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$aPhotos = $this->dbResults(
			"SELECT * FROM `galleries_photos`"
				." WHERE `galleryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"all"
		);
		
		foreach($aPhotos as $aPhoto)
		{
			@unlink($this->_settings->rootPublic."uploads/galleries/".$this->_urlVars->dynamic["id"]."/".$aPhoto["photo"]);
		
			$this->dbResults(
				"DELETE FROM `galleries_photos`"
					." WHERE `id` = ".$this->dbQuote($aPhoto["id"], "integer")
			);
		}
		
		@unlink($this->_settings->rootPublic."uploads/galleries/".$this->_urlVars->dynamic["id"]."/");
		
		$this->forward("/admin/galleries/?notice=".urlencode("Gallery removed successfully!"));
	}
	function categories_index()
	{
		$oGalleries = $this->loadModel("galleries");
		
		$_SESSION["admin"]["admin_galleries_categories"] = null;
		
		$this->tplAssign("aCategories", $oGalleries->getCategories());
		$this->tplDisplay("galleries/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `galleries_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		echo "/admin/galleries/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `galleries_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		echo "/admin/galleries/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `galleries_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `galleries_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);

		$this->forward("/admin/galleries/categories/?notice=".urlencode("Category removed successfully!"));
	}
	function photos_index()
	{
		$oGalleries = $this->loadModel("galleries");
				
		$this->tplAssign("aPhotos", $oGalleries->getPhotos($this->_urlVars->dynamic["gallery"]));
		$this->tplAssign("aGallery", $oGalleries->getGallery($this->_urlVars->dynamic["gallery"]));
		$this->tplDisplay("galleries/photos/index.tpl");
	}
	function photos_add()
	{
		$oGalleries = $this->loadModel("galleries");
		
		$this->tplAssign("aGallery", $oGalleries->getGallery($this->_urlVars->dynamic["gallery"]));
		$this->tplDisplay("galleries/photos/add.tpl");
	}
	function photos_add_s()
	{
		if(!empty($_FILES["photo"]["name"]))
		{
			if($_FILES["photo"]["error"] == 1)
				$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/add/?notice=".urlencode("Photo file size was too large!"));
			else
			{
				$sOrder = $this->dbResults(
					"SELECT MAX(`sort_order`) + 1 FROM `galleries_photos`"
					,"one"
				);
		
				if(empty($sOrder))
					$sOrder = 1;
			
				$sID = $this->dbResults(
					"INSERT INTO `galleries_photos`"
						." (`galleryid`, `title`, `description`, `sort_order`)"
						." VALUES"
						." ("
						.$this->dbQuote($this->_urlVars->dynamic["gallery"], "integer")
						.", ".$this->dbQuote($_POST["title"], "text")
						.", ".$this->dbQuote($_POST["description"], "text")
						.", ".$this->dbQuote($sOrder, "integer")
						.")"
					,"insert"
				);
				
				$upload_dir = $this->_settings->rootPublic."uploads/galleries/".$this->_urlVars->dynamic["gallery"]."/";
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
					
				$file_ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
				
				if(move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir.$upload_file))
					$this->dbResults(
						"UPDATE `galleries_photos` SET"
							." `photo` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
					);
				else
				{
					$this->dbResults(
						"DELETE FROM `galleries_photos`"
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
					);
					echo $upload_dir.$upload_file;die;
					$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/add/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Photo added successfully!"));
	}
	function photos_sort()
	{
		$aItems = explode(",", $_POST["sort"]);
		
		foreach($aItems as $x => $aItem)
		{
			$this->dbResults(
				"UPDATE `galleries_photos` SET"
					." `sort_order` = ".($x +1)
					." WHERE `id` = ".$this->dbQuote($aItem, "integer")
			);
		}
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Sort order saved successfully!"));
	}
	function photos_default()
	{
		$this->dbResults(
			"UPDATE `galleries_photos` SET"
				." `gallery_default` = 0"
				." WHERE `galleryid` = ".$this->dbQuote($this->_urlVars->dynamic["gallery"], "integer")
		);
		
		$this->dbResults(
			"UPDATE `galleries_photos` SET"
				." `gallery_default` = 1"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Default image has been changed!"));
	}
	function photos_edit()
	{
		$oGalleries = $this->loadModel("galleries");
		
		$this->tplAssign("aGallery", $oGalleries->getGallery($this->_urlVars->dynamic["gallery"]));
		$this->tplAssign("aPhoto", $oGalleries->getPhoto($this->_urlVars->dynamic["id"]));
		$this->tplDisplay("galleries/photos/edit.tpl");
	}
	function photos_edit_s()
	{
		$this->dbResults(
			"UPDATE `galleries_photos` SET"
				." `title` = ".$this->dbQuote($_POST["title"], "text")
				.", `description` = ".$this->dbQuote($_POST["title"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Changes saved successfully!"));
	}
	function photos_delete()
	{
		$oGalleries = $this->loadModel("galleries");
		
		$aPhoto = $oGalleries->getPhoto($this->_urlVars->dynamic["id"]);
		
		@unlink($this->_settings->rootPublic."uploads/galleries/".$this->_urlVars->dynamic["gallery"]."/".$aPhoto["photo"]);
		
		$this->dbResults(
			"DELETE FROM `galleries_photos`"
				." WHERE `id` = ".$this->dbQuote($aPhoto["id"], "integer")
		);
		
		$this->forward("/admin/galleries/".$this->_urlVars->dynamic["gallery"]."/photos/?notice=".urlencode("Photo removed successfully!"));
	}
	##################################
}