<?php
class admin_promos extends adminController
{
	function __construct() {
		parent::__construct("promos");
		
		$this->menuPermission("promos");
	}
	
	### DISPLAY ######################
	function index() {
		$oPromos = $this->loadModel("promos");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_promos"] = null;
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplAssign("sPosition", $_GET["position"]);
		$this->tplAssign("aPromos", $oPromos->getPromos($_GET["position"]));
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oPromos = $this->loadModel("promos");
		
		if(!empty($_SESSION["admin"]["admin_promos"])) {
			$aPromo = $_SESSION["admin"]["admin_promos"];
			$aPromo["datetime_show"] = strtotime($aPromo["datetime_show_date"]." ".$aPromo["datetime_show_Hour"].":".$aPromo["datetime_show_Minute"]." ".$aPromo["datetime_show_Meridian"]);
			$aPromo["datetime_kill"] = strtotime($aPromo["datetime_kill_date"]." ".$aPromo["datetime_kill_Hour"].":".$aPromo["datetime_kill_Minute"]." ".$aPromo["datetime_kill_Meridian"]);
			
			$this->tplAssign("aPromo", $aPromo);
		} else
			$this->tplAssign("aPromo",
				array(
					"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"positions" => array()
				)
			);
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"]) || empty($_FILES["promo"]["name"]) || empty($_POST["positions"])) {
			$_SESSION["admin"]["admin_promos"] = $_POST;
			$this->forward("/admin/promos/add/?error=".urlencode("Please fill in all required fields!"));
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
		
		$sID = $this->dbInsert(
			"promos",
			array(
				"name" => $_POST["name"]
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
		
		foreach($_POST["positions"] as $sPosition) {
			$this->dbInsert(
				"promos_positions_assign",
				array(
					"promoid" => $sID,
					"positionid" => $sPosition
				)
			);
		}

		if($_FILES["promo"]["error"] == 1) {
			$this->dbUpdate(
				"promos",
				array(
					"active" => 0
				),
				$sID
			);
			
			$this->forward("/admin/promos/?error=".urlencode("Promo file size was too large!"));
		} else {
			$upload_dir = $this->settings->rootPublic."uploads/promos/";
			$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
			$upload_file = $sID.".".strtolower($file_ext);
		
			if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file)) {
				$this->dbUpdate(
					"promos",
					array(
						"promo" => $upload_file
					),
					$sID
				);
			} else {
				$this->dbUpdate(
					"promos",
					array(
						"active" => 0
					),
					$sID
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Failed to upload file!"));
			}
		}
		
		$_SESSION["admin"]["admin_promos"] = null;
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo created successfully!"));
	}
	function edit() {
		$oPromos = $this->loadModel("promos");
		
		if(!empty($_SESSION["admin"]["admin_promos"])) {
			$aPromoRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}promos`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPromo = $_SESSION["admin"]["admin_promos"];
			
			$aPromo["updated_datetime"] = $aPromoRow["updated_datetime"];
			$aPromo["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPromoRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		} else {
			$aPromo = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}promos`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPromo["positions"] = $this->dbQuery(
				"SELECT `positions`.`id` FROM `{dbPrefix}promos_positions` AS `positions`"
					." INNER JOIN `promos_positions_assign` AS `assign` ON `positions`.`id` = `assign`.`positionid`"
					." WHERE `assign`.`promoid` = ".$aPromo["id"]
					." GROUP BY `positions`.`id`"
					." ORDER BY `positions`.`name`"
				,"col"
			);
			
			$aPromo["datetime_show_date"] = date("m/d/Y", $aPromo["datetime_show"]);
			$aPromo["datetime_kill_date"] = date("m/d/Y", $aPromo["datetime_kill"]);
			
			$aPromo["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPromo["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		}
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplAssign("imageFolder", $oPromos->imageFolder);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"]) || empty($_POST["positions"])) {
			$_SESSION["admin"]["admin_promos"] = $_POST;
			$this->forward("/admin/promos/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
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
		
		$this->dbUpdate(
			"promos",
			array(
				"name" => $_POST["name"]
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
		
		$this->dbDelete("promos_positions_assign", $_POST["id"], "promoid");
		foreach($_POST["positions"] as $sPosition) {
			$this->dbInsert(
				"promos_positions_assign",
				array(
					"promoid" => $_POST["id"],
					"positionid" => $sPosition
				)
			);
		}
		
		if(!empty($_FILES["promo"]["name"])) {
			if($_FILES["promo"]["error"] == 1) {
				$this->dbUpdate(
					"promos",
					array(
						"active" => 0
					),
					$_POST["id"]
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Promo file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic."uploads/promos/";
				$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPromo = $this->dbQuery(
					"SELECT `promo` FROM `promos`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"one"
				);
				@unlink($upload_dir.$sPromo);
			
				if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file)) {
					$this->dbUpdate(
						"promos",
						array(
							"promo" => $upload_file
						),
						$_POST["id"]
					);
				} else {
					$this->dbUpdate(
						"promos",
						array(
							"active" => 0
						),
						$_POST["id"]
					);
					
					$this->forward("/admin/promos/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_promos"] = null;
		
		$this->forward("/admin/promos/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$oPromos = $this->loadModel("promos");
		
		$aPromo = $oPromos->getPromo(null, null, null, $this->_urlVars->dynamic["id"]);
		
		$this->dbDelete("promos", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("promos_positions_assign", $this->_urlVars->dynamic["id"], "promoid");
		
		@unlink($this->settings->rootPublic.substr($oPromos->imageFolder, 1).$aPromo["promo"]);
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo removed successfully!"));
	}
	function positions_index() {
		$oPromos = $this->loadModel("promos");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplAssign("aPositionEdit", $oPromos->getPosition(null, $_GET["position"]));
		$this->tplDisplay("admin/positions/index.tpl");
	}
	function positions_add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_promo_positions"] = $_POST;
			$this->forward("/admin/promos/positions/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(empty($_POST["tag"]))				
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));
		
		$sID = $this->dbInsert(
			"promos_positions",
			array(
				"tag" => $sTag
				,"name" => $_POST["name"]
				,"promo_width" => $_POST["promo_width"]
				,"promo_height" => $_POST["promo_height"]
			)
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position created successfully!"));
	}
	function positions_edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_promo_positions"] = $_POST;
			$this->forward("/admin/promos/positions/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(empty($_POST["tag"]))				
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));
		
		$this->dbUpdate(
			"promos_positions",
			array(
				"tag" => $sTag
				,"name" => $_POST["name"]
				,"promo_width" => $_POST["promo_width"]
				,"promo_height" => $_POST["promo_height"]
			),
			$_POST["id"]
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Changes saved successfully!"));
	}
	function positions_delete() {
		$this->dbDelete("promos_positions", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("promos_positions_assign", $this->_urlVars->dynamic["id"], "positionid");
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position removed successfully!"));
	}
	##################################
}