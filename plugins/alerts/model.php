<?php
class alerts_model extends appModel {
	public $perPage;
	public $contentCharacters;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getAlerts($sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`alerts`.`datetime_show` < ".time();
			$aWhere[] = "(`alerts`.`use_kill` = 0 OR `alerts`.`datetime_kill` > ".time().")";
			$aWhere[] = "`alerts`.`active` = 1";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		$aAlerts = $this->dbQuery(
			"SELECT `alerts`.* FROM `{dbPrefix}alerts` AS `alerts`"
				.$sJoin
				.$sWhere
				." GROUP BY `alerts`.`id`"
				." ORDER BY `alerts`.`datetime_show` DESC"
			,"all"
		);
		
		foreach($aAlerts as &$aAlert) {
			$this->_getAlertInfo($aAlert);
		}
		
		return $aAlerts;
	}
	function getAlert($sId, $sTag = "", $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `alerts`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `alerts`.`tag` = ".$this->dbQuote($sTag, "text");
			
		if($sAll == false) {
			$sWhere .= " AND `alerts`.`active` = 1";
			$sWhere .= " AND `alerts`.`datetime_show` < ".time();
			$sWhere .= " AND (`alerts`.`use_kill` = 0 OR `alerts`.`datetime_kill` > ".time().")";
		}
		
		$aAlert = $this->dbQuery(
			"SELECT `alerts`.* FROM `{dbPrefix}alerts` AS `alerts`"
				.$sWhere
			,"row"
		);
		
		$this->_getAlertInfo($aAlert);
		
		return $aAlert;
	}
	private function _getAlertInfo(&$aAlert) {
		if(!empty($aAlert)) {
			if(!empty($aAlert["created_by"]))
				$aAlert["user"] = $this->getUser($aAlert["created_by"]);
		
			$aAlert["title"] = htmlspecialchars(stripslashes($aAlert["title"]));
			$aAlert["content"] = nl2br(htmlspecialchars(stripslashes($aAlert["content"])));

			$aAlert["url"] = "/alerts/".$aAlert["tag"]."/";
		}
	}
	function getURL($sID) {
		$aAlert = $this->getAlert($sID);
		
		if(!empty($aAlert)) {
			return $aAlert["url"];
		} else {
			return false;
		}
	}
}