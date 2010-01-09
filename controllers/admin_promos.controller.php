<?php
class admin_promos extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_promos"] = null;
		
		$aPositions = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." ORDER BY `name`"
			,"admin->promos->index->positions"
			,"all"
		);
		
		if(!empty($_GET["position"]))
		{
			$sSQLPosition = " INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`";
			$sSQLPosition .= " WHERE `assign`.`positionid` = ".$this->dbQuote($_GET["position"], "integer");
		}
		
		$aPromos = $this->dbResults(
			"SELECT `promos`.* FROM `promos`"
				.$sSQLPosition
				." ORDER BY `promos`.`datetime_show` DESC"
			,"admin->promos->index->promos"
			,"all"
		);
		
		$this->tplAssign("aPositions", $aPositions);
		$this->tplAssign("sPosition", $_GET["position"]);
		$this->tplAssign("aPromos", $aPromos);
		$this->tplDisplay("promos/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_promos"]))
		{
			$aPromo = $_SESSION["admin"]["admin_promos"];
			$aPromo["datetime_show"] = strtotime($aPromo["datetime_show_date"]." ".$aPromo["datetime_show_Hour"].":".$aPromo["datetime_show_Minute"]." ".$aPromo["datetime_show_Meridian"]);
			$aPromo["datetime_kill"] = strtotime($aPromo["datetime_kill_date"]." ".$aPromo["datetime_kill_Hour"].":".$aPromo["datetime_kill_Minute"]." ".$aPromo["datetime_kill_Meridian"]);
			
			$this->tplAssign("aPromo", $aPromo);
		}
		else
			$this->tplAssign("aPromo",
				array(
					"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"positions" => array()
				)
			);
		
		$this->tplAssign("aPositions", $this->get_positions());
		$this->tplDisplay("promos/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || empty($_FILES["promo"]["name"]) || empty($_POST["positions"]))
		{
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
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
			
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `promos`"
				." (`name`, `link`, `datetime_show`, `datetime_kill`, `use_kill`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["link"], "text")
					.", ".$this->dbQuote($datetime_show, "integer")
					.", ".$this->dbQuote($datetime_kill, "integer")
					.", ".$this->dbQuote($use_kill, "integer")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->promos->add"
			,"insert"
		);
		
		foreach($_POST["positions"] as $sPosition)
		{
			$this->dbResults(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$sID.", ".$sPosition.")"
				,"admin->promos->edit->positions"
			);
		}

		if($_FILES["promo"]["error"] == 1)
		{
			$this->dbResults(
				"UPDATE `promos` SET"
					." `active` = 0"
					." WHERE `id` = ".$this->dbQuote($sID, "integer")
				,"admin->promos->failed_promo_upload"
			);
			
			$this->forward("/admin/promos/?error=".urlencode("Promo file size was too large!"));
		}
		else
		{
			$upload_dir = $this->_settings->root_public."uploads/promos/";
			$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
			$upload_file = $sID.".".strtolower($file_ext);
		
			if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file))
			{
				$this->dbResults(
					"UPDATE `promos` SET"
						." `promo` = ".$this->dbQuote($upload_file, "text")
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"admin->promos->add_promo_upload"
				);
			}
			else
			{
				$this->dbResults(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"admin->promos->failed_promo_upload"
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Failed to upload file!"));
			}
		}
		
		$_SESSION["admin"]["admin_promos"] = null;
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_promos"]))
		{
			$aPromoRow = $this->dbResults(
				"SELECT * FROM `promos`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->promos->edit"
				,"row"
			);
			
			$aPromo = $_SESSION["admin"]["admin_promos"];
			
			$aPromo["updated_datetime"] = $aPromoRow["updated_datetime"];
			$aPromo["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPromoRow["updated_by"]
				,"admin->promos->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		}
		else
		{
			$aPromo = $this->dbResults(
				"SELECT * FROM `promos`"
					." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
				,"admin->promos->edit"
				,"row"
			);
			
			$aPromo["positions"] = $this->dbResults(
				"SELECT `positions`.`id` FROM `promos_positions` AS `positions`"
					." INNER JOIN `promos_positions_assign` AS `assign` ON `positions`.`id` = `assign`.`positionid`"
					." WHERE `assign`.`promoid` = ".$aPromo["id"]
					." GROUP BY `positions`.`id`"
					." ORDER BY `positions`.`name`"
				,"admin->promos->edit->positions"
				,"col"
			);
			
			$aPromo["datetime_show_date"] = date("m/d/Y", $aPromo["datetime_show"]);
			$aPromo["datetime_kill_date"] = date("m/d/Y", $aPromo["datetime_kill"]);
			
			$aPromo["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPromo["updated_by"]
				,"admin->promos->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aPromo", $aPromo);
		}
		
		$this->tplAssign("aPositions", $this->get_positions());
		$this->tplDisplay("promos/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || empty($_POST["positions"]))
		{
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
		
		if(!empty($_POST["use_kill"]))
			$use_kill = 1;
		else
			$use_kill = 0;
			
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `promos` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `link` = ".$this->dbQuote($_POST["link"], "text")
				.", `datetime_show` = ".$this->dbQuote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->dbQuote($datetime_kill, "integer")
				.", `use_kill` = ".$this->dbQuote($use_kill, "integer")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->promos->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->promos->edit->remove_positions"
		);
		foreach($_POST["positions"] as $sPosition)
		{
			$this->dbResults(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sPosition.")"
				,"admin->promos->edit->positions"
			);
		}
		
		if(!empty($_FILES["promo"]["name"]))
		{
			if($_FILES["promo"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->promos->failed_promo_upload"
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Promo file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/promos/";
				$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPromo = $this->dbResults(
					"SELECT `promo` FROM `promos`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->promos->edit"
					,"one"
				);
				@unlink($upload_dir.$sPromo);
			
				if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `promos` SET"
							." `promo` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->promos->edit_promo_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `promo` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->promos->edit_failed_promo_upload"
					);
					
					$this->forward("/admin/promos/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_promos"] = null;
		
		$this->forward("/admin/promos/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `promos`"
				." WHERE `id` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->promos->delete"
		);
		$this->dbResults(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->dbQuote($aParams["id"], "integer")
			,"admin->promos->positions_assign_delete"
		);
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo removed successfully!"));
	}
	function positions_index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$aPositions = $this->get_positions();
		
		$this->tpl_assign("aPositions", $aPositions);
		$this->tpl_display("promos/positions/index.tpl");
	}
	function positions_add()
	{	
		$this->tpl_assign("aPosition", $_SESSION["admin"]["admin_promo_positions"]);
		$this->tpl_display("promos/positions/add.tpl");
	}
	function positions_add_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_promo_positions"] = $_POST;
			$this->forward("/admin/promos/positions/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(empty($_POST["tag"]))				
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));
		
		$sID = $this->db_results(
			"INSERT INTO `promos_positions`"
				." (`tag`, `name`, `promo_width`, `promo_height`)"
				." VALUES"
				." ("
					.$this->db_quote($sTag, "text")
					.", ".$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["promo_width"], "integer")
					.", ".$this->db_quote($_POST["promo_height"], "integer")
				.")"
			,"admin->promos->positions->add"
			,"insert"
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position created successfully!"));
	}
	function positions_edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_promo_positions"]))
		{	
			$aPosition = $_SESSION["admin"]["admin_promo_positions"];
			
			$this->tpl_assign("aPosition", $aPosition);
		}
		else
		{
			$aPosition = $this->db_results(
				"SELECT * FROM `promos_positions`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->promos->positions->edit"
				,"row"
			);
		
			$this->tpl_assign("aPosition", $aPosition);
		}
		
		$this->tpl_display("promos/positions/edit.tpl");
	}
	function positions_edit_s()
	{
		if(empty($_POST["name"]))
		{
			$_SESSION["admin"]["admin_promo_positions"] = $_POST;
			$this->forward("/admin/promos/positions/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(empty($_POST["tag"]))				
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"])))));
		else
			$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))));
		
		$this->db_results(
			"UPDATE `promos_positions` SET"
				." `tag` = ".$this->db_quote($sTag, "text")
				.", `name` = ".$this->db_quote($_POST["name"], "text")
				.", `promo_width` = ".$this->db_quote($_POST["promo_width"], "integer")
				.", `promo_height` = ".$this->db_quote($_POST["promo_height"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->promos->positions->edit"
		);
		
		$_SESSION["admin"]["admin_promo_positions"] = null;

		$this->forward("/admin/promos/positions/?notice=".urlencode("Changes saved successfully!"));
	}
	function positions_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `promos_positions`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->promos-positions->delete"
		);
		
		$this->forward("/admin/promos/positions/?notice=".urlencode("Position removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_positions()
	{
		$aPositions = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." ORDER BY `name`"
			,"admin->promos->get_positions"
			,"all"
		);
		
		return $aPositions;
	}
	##################################
}