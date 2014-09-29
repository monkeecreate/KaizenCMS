<?php
class faq_model extends appModel {
	public $useCategories;
	public $perPage;
	public $sort;
	public $sortCategory;

	function __construct() {
		parent::__construct();

		include(dirname(__file__)."/config.php");

		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}

	function getQuestions($sCategory = null, $sAll = false, $sRandom = false) {
		$aWhere = array();
		$sJoin = "";

		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`faq`.`active` = 1";
		}

		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`";
		}

		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}

		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}

		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `question` ".$sSortDirection;
		}

		if($sRandom == true)
			$sOrderBy = " ORDER BY RAND() ";

		// Get all faq for paging
		$aQuestions = $this->dbQuery(
			"SELECT `faq`.* FROM `{dbPrefix}faq` AS `faq`"
				.$sJoin
				.$sWhere
				.$sOrderBy
			,"all"
		);

		foreach($aQuestions as $x => &$aQuestion) {
			$aQuestion = $this->_getQuestionInfo($aQuestion);
		}

		return $aQuestions;
	}
	function getQuestion($sId, $sTag = null, $sAll = false) {
		$aWhere = array();

		if(!empty($sId))
			$aWhere[] = "`id` = ".$this->dbQuote($sId, "integer");
		else
			$aWhere[] = "`tag` = ".$this->dbQuote($sTag, "text");

		if($sAll == false)
			$aWhere[] = "`active` = 1";

		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}

		$aQuestion = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}faq`"
				.$sWhere
			,"row"
		);

		$aQuestion = $this->_getQuestionInfo($aQuestion);

		return $aQuestion;
	}
	private function _getQuestionInfo($aQuestion) {
		if(!empty($aQuestion)) {
			$aQuestion["question"] = nl2br(htmlspecialchars(stripslashes($aQuestion["question"])));
			$aQuestion["answer"] = stripslashes($aQuestion["answer"]);
			$aQuestion["url"] = "/faq/".$aQuestion["tag"]."/";

			$aQuestion["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq_assign`.`categoryid` = `categories`.`id`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
				,"all"
			);

			foreach($aQuestion["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		}

		return $aQuestion;
	}
	function getURL($sID) {
		$aQuestion = $this->getQuestion($sID);

		return $aQuestion["url"];
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";

		if($sEmpty == false) {
			$sJoin .= " INNER JOIN `{dbPrefix}faq_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}faq_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		}

		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sortCategory));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}

		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sortCategory))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "items":
				$sOrderBy = " ORDER BY `items` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}

		$aCategories = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}faq_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);

		foreach($aCategories as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}

		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}

		$aCategory = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}faq_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}faq_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);

		if(!empty($aCategory)) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}

		return $aCategory;
	}
}