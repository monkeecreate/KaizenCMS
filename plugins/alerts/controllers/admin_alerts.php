<?php
class admin_alerts extends adminController {
	public $errors;
	
	function __construct() {
		parent::__construct("alerts");
		
		$this->menuPermission("alerts");
		
		$this->errors = array();
	}
	
	### DISPLAY ######################
	function index() {		
		// Clear saved form info
		$_SESSION["admin"]["admin_alerts"] = null;
		
		$this->tplAssign("aAlerts", $this->model->getAlerts(true));
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {		
		if(!empty($_SESSION["admin"]["admin_alerts"])) {
			$aAlert = $_SESSION["admin"]["admin_alerts"];
			$aAlert["datetime_show"] = strtotime($aAlert["datetime_show_date"]." ".$aAlert["datetime_show_Hour"].":".$aAlert["datetime_show_Minute"]." ".$aAlert["datetime_show_Meridian"]);
			$aAlert["datetime_kill"] = strtotime($aAlert["datetime_kill_date"]." ".$aAlert["datetime_kill_Hour"].":".$aAlert["datetime_kill_Minute"]." ".$aAlert["datetime_kill_Meridian"]);
			
			$this->tplAssign("aAlert", $aAlert);
		} else
			$this->tplAssign("aAlert",
				array(
					"datetime_show_date" => date("m/j/Y")
					,"datetime_kill_date" => date("m/j/Y")
					,"active" => 1
				)
			);
		
		$this->tplAssign("sContentCount", $this->model->contentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {		
		if(empty($_POST["title"]) || empty($_POST["content"])) {
			$_SESSION["admin"]["admin_alerts"] = $_POST;
			$this->forward("/admin/alerts/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
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
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aAlerts = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}alerts`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aAlerts)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aAlerts);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sID = $this->dbInsert(
			"alerts",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"content" => (string)substr($_POST["content"], 0, $this->model->contentCharacters)
				,"link" => $_POST["link"]
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		$_SESSION["admin"]["admin_alerts"] = null;
		
				
		$this->forward("/admin/alerts/?info=".urlencode("Alert created successfully!")."&".implode("&", $this->errors));
	}
	function edit() {		
		if(!empty($_SESSION["admin"]["admin_alerts"])) {
			$aAlertRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}alerts`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aAlert = $_SESSION["admin"]["admin_alerts"];
			
			$aAlert["updated_datetime"] = $aAlertRow["updated_datetime"];
			$aAlert["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aAlertRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aAlert", $aAlert);
		} else {
			$aAlert = $this->model->getAlert($this->urlVars->dynamic["id"], null, true);

			$aAlert["datetime_show_date"] = date("m/d/Y", $aAlert["datetime_show"]);
			$aAlert["datetime_kill_date"] = date("m/d/Y", $aAlert["datetime_kill"]);
			
			$aAlert["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aAlert["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aAlert", $aAlert);
		}
		
		$this->tplAssign("sContentCount", $this->model->contentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {		
		if(empty($_POST["title"]) || empty($_POST["content"])) {
			$_SESSION["admin"]["admin_alerts"] = $_POST;
			$this->forward("/admin/alerts/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
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
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);
	
		$aAlerts = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}alerts`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aAlerts)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aAlerts);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$this->dbUpdate(
			"alerts",
			array(
				"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"link" => $_POST["link"]
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$_SESSION["admin"]["admin_alerts"] = null;


		$this->forward("/admin/alerts/?info=".urlencode("Changes saved successfully!"));
	}
	function delete() {		
		$this->dbDelete("alerts", $this->urlVars->dynamic["id"]);
		
		$this->forward("/admin/alerts/?info=".urlencode("Alert removed successfully!"));
	}
}