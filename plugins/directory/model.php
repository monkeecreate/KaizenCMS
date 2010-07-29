<?php
class directory_model extends appModel
{
	public $useImage = true;
	public $imageFolder = "/uploads/directory/";
	public $useCategories = true;
	public $perPage = 5;
	public $aStates = array('AL'=>"Alabama",  
							'AK'=>"Alaska",  
							'AZ'=>"Arizona",  
							'AR'=>"Arkansas",  
							'CA'=>"California",  
							'CO'=>"Colorado",  
							'CT'=>"Connecticut",  
							'DE'=>"Delaware",  
							'DC'=>"District Of Columbia",  
							'FL'=>"Florida",  
							'GA'=>"Georgia",  
							'HI'=>"Hawaii",  
							'ID'=>"Idaho",  
							'IL'=>"Illinois",  
							'IN'=>"Indiana",  
							'IA'=>"Iowa",  
							'KS'=>"Kansas",  
							'KY'=>"Kentucky",  
							'LA'=>"Louisiana",  
							'ME'=>"Maine",  
							'MD'=>"Maryland",  
							'MA'=>"Massachusetts",  
							'MI'=>"Michigan",  
							'MN'=>"Minnesota",  
							'MS'=>"Mississippi",  
							'MO'=>"Missouri",  
							'MT'=>"Montana",
							'NE'=>"Nebraska",
							'NV'=>"Nevada",
							'NH'=>"New Hampshire",
							'NJ'=>"New Jersey",
							'NM'=>"New Mexico",
							'NY'=>"New York",
							'NC'=>"North Carolina",
							'ND'=>"North Dakota",
							'OH'=>"Ohio",  
							'OK'=>"Oklahoma",  
							'OR'=>"Oregon",  
							'PA'=>"Pennsylvania",  
							'RI'=>"Rhode Island",  
							'SC'=>"South Carolina",  
							'SD'=>"South Dakota",
							'TN'=>"Tennessee",  
							'TX'=>"Texas",  
							'UT'=>"Utah",  
							'VT'=>"Vermont",  
							'VA'=>"Virginia",  
							'WA'=>"Washington",  
							'WV'=>"West Virginia",  
							'WI'=>"Wisconsin",  
							'WY'=>"Wyoming");
	
	function getListings($sCategory, $sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `directory`.`id` > 0";// Allways true
		
		if($sAll == false)
			$sWhere .= " AND `directory`.`active` = 1";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aListings = $this->dbQuery(
			"SELECT `directory`.* FROM `{dbPrefix}directory` AS `directory`"
				." LEFT JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `directory`.`id` = `directory_assign`.`listingid`"
				." LEFT JOIN `{dbPrefix}directory_categories` AS `categories` ON `directory_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `directory`.`id`"
				." ORDER BY `directory`.`name`"
			,"all"
		);
	
		foreach($aListings as $x => &$aListing)
			$aListing = $this->_getListingInfo($aListing);
		
		return $aListings;
	}
	private function _getListingInfo($aListing) {
		$aListing["categories"] = $this->dbQuery(
			"SELECT `id`, `name` FROM `{dbPrefix}directory_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `directory_assign`.`categoryid` = `categories`.`id`"
				." WHERE `directory_assign`.`listingid` = ".$aListing["id"]
			,"all"
		);
		
		if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aListing["file"])
		 && $this->useImage == true)
			$aListing["image"] = 1;
		else
			$aListing["image"] = 0;
			
		return $aListing;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory)
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}directory_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}