<?php
class admin_promos extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_promos"] = null;
		
		$aPromos = $this->db_results(
			"SELECT `promos`.* FROM `promos`"
				." ORDER BY `promos`.`datetime_show` DESC"
			,"admin->promos->index"
			,"all"
		);
		
		$this->tpl_assign("aPromos", $aPromos);
		$this->tpl_display("promos/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_promos"]))
		{
			$aPromo = $_SESSION["admin"]["admin_promos"];
			$aPromo["datetime_show"] = strtotime($aPromo["datetime_show_date"]." ".$aPromo["datetime_show_Hour"].":".$aPromo["datetime_show_Minute"]." ".$aPromo["datetime_show_Meridian"]);
			$aPromo["datetime_kill"] = strtotime($aPromo["datetime_kill_date"]." ".$aPromo["datetime_kill_Hour"].":".$aPromo["datetime_kill_Minute"]." ".$aPromo["datetime_kill_Meridian"]);
			
			$this->tpl_assign("aPromo", $aPromo);
		}
		else
			$this->tpl_assign("aPromo",
				array(
					"datetime_show_date" => date("m/d/Y")
					,"datetime_kill_date" => date("m/d/Y")
					,"active" => 1
					,"positions" => array()
				)
			);
		
		$this->tpl_assign("aPositions", $this->get_positions());
		$this->tpl_display("promos/add.tpl");
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
		
		$sID = $this->db_results(
			"INSERT INTO `promos`"
				." (`name`, `link`, `datetime_show`, `datetime_kill`, `use_kill`, `active`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["link"], "integer")
					.", ".$this->db_quote($datetime_show, "integer")
					.", ".$this->db_quote($datetime_kill, "integer")
					.", ".$this->db_quote($use_kill, "integer")
					.", ".$this->db_quote($active, "integer")
				.")"
			,"admin->promos->add"
			,"insert"
		);
		
		foreach($_POST["positions"] as $sPosition)
		{
			$this->db_results(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$sID.", ".$sPosition.")"
				,"admin->promos->edit->positions"
			);
		}

		if($_FILES["promo"]["error"] == 1)
		{
			$this->db_results(
				"UPDATE `promos` SET"
					." `active` = 0"
					." WHERE `id` = ".$this->db_quote($sID, "integer")
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
				$this->db_results(
					"UPDATE `promos` SET"
						." `promo` = ".$this->db_quote($upload_file, "text")
						." WHERE `id` = ".$this->db_quote($sID, "integer")
					,"admin->promos->add_promo_upload"
				);
			}
			else
			{
				$this->db_results(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($sID, "integer")
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
			$this->tpl_assign("aPromo", $_SESSION["admin"]["admin_promos"]);
		else
		{
			$aPromo = $this->db_results(
				"SELECT * FROM `promos`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->promos->edit"
				,"row"
			);
			
			$aPromo["positions"] = $this->db_results(
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
			
			$this->tpl_assign("aPromo", $aPromo);
		}
		
		$this->tpl_assign("aPositions", $this->get_positions());
		$this->tpl_display("promos/edit.tpl");
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
		
		$this->db_results(
			"UPDATE `promos` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				.", `link` = ".$this->db_quote($_POST["link"], "text")
				.", `datetime_show` = ".$this->db_quote($datetime_show, "integer")
				.", `datetime_kill` = ".$this->db_quote($datetime_kill, "integer")
				.", `use_kill` = ".$this->db_quote($use_kill, "integer")
				.", `active` = ".$this->db_quote($active, "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->promos->edit"
		);
		
		$this->db_results(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->promos->edit->remove_positions"
		);
		foreach($_POST["positions"] as $sPosition)
		{
			$this->db_results(
				"INSERT INTO `promos_positions_assign`"
					." (`promoid`, `positionid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sPosition.")"
				,"admin->promos->edit->positions"
			);
		}
		
		if(!empty($_FILES["promo"]["name"]))
		{
			if($_FILES["promo"]["error"] == 1)
			{
				$this->db_results(
					"UPDATE `promos` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->promos->failed_promo_upload"
				);
				
				$this->forward("/admin/promos/?notice=".urlencode("Promo file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/promos/";
				$file_ext = pathinfo($_FILES["promo"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sPromo = $this->db_results(
					"SELECT `promo` FROM `promos`"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->promos->edit"
					,"one"
				);
				@unlink($upload_dir.$sPromo);
			
				if(move_uploaded_file($_FILES["promo"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `promos` SET"
							." `promo` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->promos->edit_promo_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `promo` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
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
		$this->db_results(
			"DELETE FROM `promos`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->promos->delete"
		);
		$this->db_results(
			"DELETE FROM `promos_positions_assign`"
				." WHERE `promoid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->promos->positions_assign_delete"
		);
		
		$this->forward("/admin/promos/?notice=".urlencode("Promo removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_positions()
	{
		$aPositions = $this->db_results(
			"SELECT * FROM `promos_positions`"
				." ORDER BY `name`"
			,"admin->promos->get_positions"
			,"all"
		);
		
		return $aPositions;
	}
	##################################
}