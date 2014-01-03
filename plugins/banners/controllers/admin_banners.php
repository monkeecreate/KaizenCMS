<?php
class admin_banners extends adminController {
	function __construct() {
		parent::__construct("banners");

		$this->menuPermission("banners");
	}

	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_banners"] = null;

		$this->tplAssign("aPositions", $this->model->getPositions());
		$this->tplAssign("sPosition", $_GET["position"]);
		$this->tplAssign("aBanners", $this->model->getBanners($_GET["position"]));
		$this->tplDisplay("admin/index.tpl");

		$this->tplAssign("useDescription", $this->model->useDescription);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_banners"])) {
			$aBanner = $_SESSION["admin"]["admin_banners"];
			$aBanner["datetime_show"] = strtotime($aBanner["datetime_show_date"]." ".$aBanner["datetime_show_Hour"].":".$aBanner["datetime_show_Minute"]." ".$aBanner["datetime_show_Meridian"]);
			$aBanner["datetime_kill"] = strtotime($aBanner["datetime_kill_date"]." ".$aBanner["datetime_kill_Hour"].":".$aBanner["datetime_kill_Minute"]." ".$aBanner["datetime_kill_Meridian"]);

			if(empty($aBanner["positions"]))
				$aBanner["positions"] = array();

			$this->tplAssign("aBanner", $aBanner);
		} else
			$this->tplAssign("aBanner",
				array(
					"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"positions" => array()
				)
			);

		$this->tplAssign("aPositions", $this->model->getPositions());
		$this->tplAssign("useDescription", $this->model->useDescription);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"]) || empty($_FILES["banner"]["name"]) || empty($_POST["positions"])) {
			$_SESSION["admin"]["admin_banners"] = $_POST;
			$this->forward("/admin/banners/add/?error=".urlencode("Please fill in all required fields!"));
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
			"banners",
			array(
				"name" => $_POST["name"]
				,"link" => $_POST["link"]
				,"description" => $_POST["description"]
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
				"banners_positions_assign",
				array(
					"bannerid" => $sID,
					"positionid" => $sPosition
				)
			);
		}

		if($_FILES["banner"]["error"] == 1) {
			$this->dbUpdate(
				"banners",
				array(
					"active" => 0
				),
				$sID
			);

			$this->forward("/admin/banners/?error=".urlencode("Banner file size was too large!"));
		} else {
			$upload_dir = $this->settings->rootPublic.substr($this->model->imageFolder, 1);
			$file_ext = pathinfo($_FILES["banner"]["name"], PATHINFO_EXTENSION);
			$upload_file = $sID.".".strtolower($file_ext);

			if(move_uploaded_file($_FILES["banner"]["tmp_name"], $upload_dir.$upload_file)) {
				$this->dbUpdate(
					"banners",
					array(
						"banner" => $upload_file
					),
					$sID
				);
			} else {
				$this->dbUpdate(
					"banners",
					array(
						"active" => 0
					),
					$sID
				);

				$this->forward("/admin/banners/?info=".urlencode("Failed to upload file!"));
			}
		}

		$_SESSION["admin"]["admin_banners"] = null;

		$this->forward("/admin/banners/?info=".urlencode("Banner created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_banners"])) {
			$aBannerRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}banners`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);

			$aBanner = $_SESSION["admin"]["admin_banners"];

			$aBanner["updated_datetime"] = $aBannerRow["updated_datetime"];
			$aBanner["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aBannerRow["updated_by"]
				,"row"
			);

			$this->tplAssign("aBanner", $aBanner);
		} else {
			$aBanner = $this->model->getBanner(null, null, null, $this->urlVars->dynamic["id"], true, false);

			$aBanner["positions"] = $this->dbQuery(
				"SELECT `positions`.`id` FROM `{dbPrefix}banners_positions` AS `positions`"
					." INNER JOIN `{dbPrefix}banners_positions_assign` AS `assign` ON `positions`.`id` = `assign`.`positionid`"
					." WHERE `assign`.`bannerid` = ".$aBanner["id"]
					." GROUP BY `positions`.`id`"
					." ORDER BY `positions`.`name`"
				,"col"
			);

			$aBanner["datetime_show_date"] = date("m/d/Y", $aBanner["datetime_show"]);
			$aBanner["datetime_kill_date"] = date("m/d/Y", $aBanner["datetime_kill"]);

			$aBanner["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aBanner["updated_by"]
				,"row"
			);

			$this->tplAssign("aBanner", $aBanner);
		}

		$this->tplAssign("useDescription", $this->model->useDescription);
		$this->tplAssign("sShortContentCount", $this->model->shortContentCharacters);
		$this->tplAssign("aPositions", $this->model->getPositions());
		$this->tplAssign("imageFolder", $this->model->imageFolder);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"]) || empty($_POST["positions"])) {
			$_SESSION["admin"]["admin_banners"] = $_POST;
			$this->forward("/admin/banners/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
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
			"banners",
			array(
				"name" => $_POST["name"]
				,"link" => $_POST["link"]
				,"description" => $_POST["description"]
				,"datetime_show" => $datetime_show
				,"datetime_kill" => $datetime_kill
				,"use_kill" => $this->boolCheck($_POST["use_kill"])
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);

		$this->dbDelete("banners_positions_assign", $_POST["id"], "bannerid");
		foreach($_POST["positions"] as $sPosition) {
			$this->dbInsert(
				"banners_positions_assign",
				array(
					"bannerid" => $_POST["id"],
					"positionid" => $sPosition
				)
			);
		}

		if(!empty($_FILES["banner"]["name"])) {
			if($_FILES["banner"]["error"] == 1) {
				$this->dbUpdate(
					"banners",
					array(
						"active" => 0
					),
					$_POST["id"]
				);

				$this->forward("/admin/banners/?info=".urlencode("Banner file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic.substr($this->model->imageFolder, 1);
				$file_ext = pathinfo($_FILES["banner"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);

				$sBanner = $this->dbQuery(
					"SELECT `banner` FROM `{dbPrefix}banners`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"one"
				);
				@unlink($upload_dir.$sBanner);

				if(move_uploaded_file($_FILES["banner"]["tmp_name"], $upload_dir.$upload_file)) {
					$this->dbUpdate(
						"banners",
						array(
							"banner" => $upload_file
						),
						$_POST["id"]
					);
				} else {
					$this->dbUpdate(
						"banners",
						array(
							"active" => 0
						),
						$_POST["id"]
					);

					$this->forward("/admin/banners/?info=".urlencode("Failed to upload file!"));
				}
			}
		}

		$_SESSION["admin"]["admin_banners"] = null;

		$this->forward("/admin/banners/?info=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$aBanner = $this->model->getBanner(null, null, null, $this->urlVars->dynamic["id"]);

		$this->dbDelete("banners", $this->urlVars->dynamic["id"]);
		$this->dbDelete("banners_positions_assign", $this->urlVars->dynamic["id"], "bannerid");

		@unlink($this->settings->rootPublic.substr($this->model->imageFolder, 1).$aBanner["banner"]);

		$this->forward("/admin/banners/?info=".urlencode("Banner removed successfully!"));
	}
	function positions_index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_banner_positions"] = null;

		$this->tplAssign("aPositions", $this->model->getPositions());
		$this->tplAssign("aPositionEdit", $this->model->getPosition(null, $_GET["position"]));
		$this->tplDisplay("admin/positions/index.tpl");
	}
	function positions_add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_banner_positions"] = $_POST;
			$this->forward("/admin/banners/positions/add/?error=".urlencode("Please fill in all required fields!"));
		}

		if(empty($_POST["tag"]))
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));

		$sID = $this->dbInsert(
			"banners_positions",
			array(
				"tag" => $sTag
				,"name" => $_POST["name"]
				,"banner_width" => $_POST["banner_width"]
				,"banner_height" => $_POST["banner_height"]
			)
		);

		$_SESSION["admin"]["admin_banner_positions"] = null;

		$this->forward("/admin/banners/positions/?info=".urlencode("Position created successfully!"));
	}
	function positions_edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_banner_positions"] = $_POST;
			$this->forward("/admin/banners/positions/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}

		if(empty($_POST["tag"]))
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));

		$this->dbUpdate(
			"banners_positions",
			array(
				"tag" => $sTag
				,"name" => $_POST["name"]
				,"banner_width" => $_POST["banner_width"]
				,"banner_height" => $_POST["banner_height"]
			),
			$_POST["id"]
		);

		$_SESSION["admin"]["admin_banner_positions"] = null;

		$this->forward("/admin/banners/positions/?info=".urlencode("Changes saved successfully!"));
	}
	function positions_delete() {
		$this->dbDelete("banners_positions", $this->urlVars->dynamic["id"]);
		$this->dbDelete("banners_positions_assign", $this->urlVars->dynamic["id"], "positionid");

		$this->forward("/admin/banners/positions/?info=".urlencode("Position removed successfully!"));
	}
	##################################
}