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
		$this->tplDisplay("promos/index.tpl");
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
		$this->tplDisplay("promos/add.tpl");
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
		
		$sID = $this->dbResults(
			"INSERT INTO `promos`"
				." (`name`, `link`, `datetime_show`, `datetime_kill`, `use_kill`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["link"], "text")
					.", ".$this->dbQuote($datetime_show, "integer")
					.", ".$this->dbQuote($datetime_kill, "integer")
					.", ".$this->boolCheck($_POST["use_kill"])
					.", ".$this->boolCheck($_POST["active"])
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		foreach($_POST["positions"] as $sPosition) {
			$this->dbResults(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$sID.", ".$sPosition.")"
			);
		}

		if($_FILES["promo"]["error"] == 1) {
			$this->dbResults(
				"UPDATE `promos` SET"
					." `active` = 0"
					." WHERE `id` = ".$this->dbQuote($sID, "integer")
				,"update"
			);
			
			$this->forward("/admin/promos/?error=".urlencode("Promo file size was too large!"));
		} else {
			$upload_dir = $this->_settings->rootPublic."uploads/promos/";
			$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
			$upload_file = $sID.".".strtolower($file_ext);
		
			if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file)) {
				$this->dbResults(
					"UPDATE `promos` SET"
						." `promo` = ".$this->dbQuote($upload_file, "text")
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"update"
				);
			} else {
				$this->dbResults(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"update"
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
			$aPromoRow = $this->dbResults(
				"SELECT * FROM `promos`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPromo = $_SESSION["admin"]["admin_promos"];
			
			$aPromo["updated_datetime"] = $aPromoRow["updated_datetime"];
			$aPromo["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPromoRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		} else {
			$aPromo = $this->dbResults(
				"SELECT * FROM `promos`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aPromo["positions"] = $this->dbResults(
				"SELECT `positions`.`id` FROM `promos_positions` AS `positions`"
					." INNER JOIN `promos_positions_assign` AS `assign` ON `positions`.`id` = `assign`.`positionid`"
					." WHERE `assign`.`promoid` = ".$aPromo["id"]
					." GROUP BY `positions`.`id`"
					." ORDER BY `positions`.`name`"
				,"col"
			);
			
			$aPromo["datetime_show_date"] = date("m/d/Y", $aPromo["datetime_show"]);
			$aPromo["datetime_kill_date"] = date("m/d/Y", $aPromo["datetime_kill"]);
			
			$aPromo["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPromo["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		}
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplDisplay("promos/edit.tpl");
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
		
		$this->dbResults(
			"UPDATE `promos` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `link` = ".$this->dbQuote($_POST["link"], "text")
				.", `datetime_show` = ".$this->dbQuote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->dbQuote($datetime_kill, "integer")
				.", `use_kill` = ".$this->boolCheck($_POST["use_kill"])
				.", `active` = ".$this->boolCheck($_POST["active"])
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"update"
		);
		
		$this->dbResults(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->dbQuote($_POST["id"], "integer")
			,"delete"
		);
		foreach($_POST["positions"] as $sPosition) {
			$this->dbResults(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sPosition.")"
			);
		}
		
		if(!empty($_FILES["promo"]["name"])) {
			if($_FILES["promo"]["error"] == 1) {
				$this->dbResults(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"update"
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Promo file size was too large!"));
			} else {
				$upload_dir = $this->_settings->rootPublic."uploads/promos/";
				$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPromo = $this->dbResults(
					"SELECT `promo` FROM `promos`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"one"
				);
				@unlink($upload_dir.$sPromo);
			
				if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file)) {
					$this->dbResults(
						"UPDATE `promos` SET"
							." `promo` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					);
				} else {
					$this->dbResults(
						"UPDATE `promos` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
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
		
		$this->dbResults(
			"DELETE FROM `promos`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		@unlink($this->_settings->rootPublic.substr($oPromos->imageFolder, 1).$aPromo["promo"]);
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo removed successfully!"));
	}
	function positions_index() {
		$oPromos = $this->loadModel("promos");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->tplAssign("aPositions", $oPromos->getPositions());
		$this->tplDisplay("promos/positions/index.tpl");
	}
	function positions_add() {	
		$this->tplAssign("aPosition", $_SESSION["admin"]["admin_promo_positions"]);
		$this->tplDisplay("promos/positions/add.tpl");
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
		
		$sID = $this->dbResults(
			"INSERT INTO `promos_positions`"
				." (`tag`, `name`, `promo_width`, `promo_height`)"
				." VALUES"
				." ("
					.$this->dbQuote($sTag, "text")
					.", ".$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["promo_width"], "integer")
					.", ".$this->dbQuote($_POST["promo_height"], "integer")
				.")"
			,"insert"
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position created successfully!"));
	}
	function positions_edit() {
		if(!empty($_SESSION["admin"]["admin_promo_positions"])) {	
			$aPosition = $_SESSION["admin"]["admin_promo_positions"];
			
			$this->tplAssign("aPosition", $aPosition);
		} else {
			$aPosition = $this->dbResults(
				"SELECT * FROM `promos_positions`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
		
			$this->tplAssign("aPosition", $aPosition);
		}
		
		$this->tplDisplay("promos/positions/edit.tpl");
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
		
		$this->dbResults(
			"UPDATE `promos_positions` SET"
				." `tag` = ".$this->dbQuote($sTag, "text")
				.", `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `promo_width` = ".$this->dbQuote($_POST["promo_width"], "integer")
				.", `promo_height` = ".$this->dbQuote($_POST["promo_height"], "integer")
			,"admin->promos->positions->edit"
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;

		$this->forward("/admin/promos/positions/?notice=".urlencode("Changes saved successfully!"));
	}
	function positions_delete() {
		$this->dbResults(
			"DELETE FROM `promos_positions`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position removed successfully!"));
	}
	##################################
}