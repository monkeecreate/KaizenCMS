<?php
class galleries extends appController
{
	function index($aParams)
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `galleries_categories`"
				." ORDER BY `name`"
			,"galleries->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE GALLERIES
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `galleries`.`id` > 0";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->_db->quote($_GET["category"], "integer");
		
		// Get all gallerys for paging
		$aGalleries = $this->db_results(
			"SELECT `galleries`.* FROM `galleries` AS `galleries`"
				." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries`.`id` = `galleries_assign`.`galleryid`"
				." INNER JOIN `galleries_categories` AS `categories` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `galleries`.`id`"
			,"galleries->all_galleries_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aGalleries), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aGalleries = $this->db_results(
			"SELECT `galleries`.* FROM `galleries` AS `galleries`"
				." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries`.`id` = `galleries_assign`.`galleryid`"
				." INNER JOIN `galleries_categories` AS `categories` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `galleries`.`id`"
				." ORDER BY `galleries`.`name`"
				." LIMIT ".$start.",".$sPerPage
			,"galleries->current_page"
			,"all"
		);
	
		foreach($aGalleries as $x => $aGallery)
		{
			$aGalleries[$x]["photo"] = $this->db_results(
				"SELECT `photo` FROM `galleries_photos`"
					." WHERE `galleryid` = ".$aGallery["id"]
					." AND `gallery_default` = 1"
				,"galleries->all->gallery_default_photo"
				,"one"
			);
			
			/*# Categories #*/
			$aGalleryCategories = $this->db_results(
				"SELECT `name` FROM `galleries_categories` AS `categories`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `categories`.`id`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
				,"galleries->gallery_categories"
				,"col"
			);
		
			$aGalleries[$x]["categories"] = implode(", ", $aGalleryCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."upload/galleries/".$aGallery["id"].".jpg"))
				$aGallerys[$x]["image"] = 1;
			/*# Image #*/
		}

		$this->_smarty->assign("aCategories", $aCategories);
		$this->_smarty->assign("aGalleries", $aGalleries);
		$this->_smarty->assign("aPaging", $oPage->build_array());
		
		$this->_smarty->display("galleries/index.tpl");
	}
	function gallery($aParams)
	{
		$aGallery = $this->db_results(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->db_quote($aParams["gallery"], "integer")
			,"galleries->gallery"
			,"row"
		);
		
		if(empty($aGallery))
			$this->error('404');

		$aCategories = $this->db_results(
			"SELECT `name` FROM `galleries_categories` AS `category`"
				." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `category`.`id`"
				." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
			,"galleries->gallery->categories"
			,"col"
		);

		$aGallery["categories"] = implode(", ", $aCategories);
		
		$aGallery["photos"] = $this->db_results(
			"SELECT * FROM `galleries_photos`"
				." WHERE `galleryid` = ".$this->db_quote($aGallery["id"], "integer")
			,"galleries->gallery"
			,"all"
		);
		
		$this->_smarty->assign("aGallery", $aGallery);
		$this->_smarty->display("galleries/gallery.tpl");
	}
}