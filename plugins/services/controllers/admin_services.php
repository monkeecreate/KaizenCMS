<?php
class admin_services extends adminController {
	function __construct() {
		parent::__construct("services");

		$this->menuPermission("services");
	}

	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_services"] = null;

		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}services`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}services`"
			,"one"
		);

		$this->tplAssign("aServices", $this->model->getServices(true));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));

		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_services"]))
			$this->tplAssign("aService", $_SESSION["admin"]["admin_services"]);

		else
			$this->tplAssign("aService",
				array(
					"active" => 1
				)
			);

		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_services"] = $_POST;
			$this->forward("/admin/services/add/?error=".urlencode("Please fill in all required fields!"));
		}

		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

		$aServices = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}services`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aServices)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aServices);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}services`"
			,"one"
		);

		if(empty($sOrder))
			$sOrder = 1;

		$sID = $this->dbInsert(
			"services",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"short_content" => $_POST["short_content"]
				,"content" => $_POST["content"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);

		$_SESSION["admin"]["admin_services"] = null;

		$this->forward("/admin/services/?notice=".urlencode("Service created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_services"])) {
			$aServiceRow = $this->model->getService($this->urlVars->dynamic["id"], null, true);

			$aService = $_SESSION["admin"]["admin_services"];

			$aService["updated_datetime"] = $aServiceRow["updated_datetime"];
			$aService["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aServiceRow["updated_by"]
				,"row"
			);

			$this->tplAssign("aService", $aService);
		} else {
			$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

			$aService["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aService["updated_by"]
				,"row"
			);

			$this->tplAssign("aService", $aService);
		}

		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_services"] = $_POST;
			$this->forward("/admin/services/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}

		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

		$aServices = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}services`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aServices)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aServices);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$this->dbUpdate(
			"services",
			array(
				"title" => $_POST["title"]
				,"tag" => $sTag
				,"short_content" => $_POST["short_content"]
				,"content" => $_POST["content"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);

		$_SESSION["admin"]["admin_services"] = null;

		$this->forward("/admin/services/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

		$this->dbDelete("services", $this->urlVars->dynamic["id"]);

		$this->forward("/admin/services/?notice=".urlencode("Service removed successfully!"));
	}
	function sort() {
		$aService = $this->model->getService($this->urlVars->dynamic["id"], null, true);

		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}services`"
					." WHERE `sort_order` < ".$aService["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}services`"
					." WHERE `sort_order` > ".$aService["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => 0
			),
			$aService["id"]
		);

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => $aService["sort_order"]
			),
			$aOld["id"]
		);

		$this->dbUpdate(
			"services",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aService["id"]
		);

		$this->forward("/admin/services/?notice=".urlencode("Sort order saved successfully!"));
	}
	##################################
}