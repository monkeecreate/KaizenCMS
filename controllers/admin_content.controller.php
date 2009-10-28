<?php
class admin_content extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;
		
		$aPages = $this->db_results(
			"SELECT * FROM `content`"
				." ORDER BY `title`"
			,"admin->content->index"
			,"all"
		);
		
		$this->_smarty->assign("pages", $aPages);
		$this->_smarty->assign("domain", $_SERVER["SERVER_NAME"]);
		$this->_smarty->display("content/index.tpl");
	}
	function add()
	{
		$this->_smarty->assign("page", $_SESSION["admin"]["admin_content"]);
		$this->_smarty->display("content/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["header_title"]) || empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"])))));
		
		$aRes = $this->db_results(
			"INSERT INTO `content`"
				." (`tag`, `header_title`, `header_text`, `title`, `content`)"
				." VALUES"
				." ("
					.$this->_db->quote($sTag, "text")
					.", ".$this->_db->quote($_POST["header_title"], "text")
					.", ".$this->_db->quote($_POST["header_text"], "text")
					.", ".$this->_db->quote($_POST["title"], "text")
					.", ".$this->_db->quote($_POST["content"], "text")
				.")"
			,"admin->content->add"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_content"]))
			$this->_smarty->assign("page", $_SESSION["admin"]["admin_content"]);
		else
		{
			$aPage = $this->db_results(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
				,"admin->content->edit"
				,"row"
			);
		
			$this->_smarty->assign("page", $aPage);
		}
		
		$this->_smarty->display("content/edit.tpl");
	}
	function edit_image_upload()
	{
		$aReturn = (object)array(
			"file" => null,
			"error" => null
		);
		
		if($_FILES["image"]["error"] == 1)
			$aReturn->error = "Image size too large!";
		else
		{
			$upload_dir = $this->_settings->root_public."upload/content/";
			$file_ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
			$upload_file = $_POST["id"].".".strtolower($file_ext);
			$aReturn->file = $upload_file;
			
			$sFile = $this->db_results(
				"SELECT `image` FROM `content`"
					." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
				,"admin->content->edit_image_upload->remove image"
				,"one"
			);
			@unlink($upload_dir.$sFile);
			
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_dir.$upload_file))
			{
				$this->db_results(
					"UPDATE `content` SET"
						." `image` = ".$this->_db->quote($upload_file, "text")
						." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
					,"admin->content->edit_image_upload"
				);
			}
			else
				$aReturn->error = "Failed to upload file!";
		}
		
		header("Content-type: text/html");
		echo json_encode($aReturn);
	}
	function edit_delete_image()
	{
		$aReturn = (object)array("error" => null);
		
		$upload_dir = $this->_settings->root_public."upload/content/";
		
		$sFile = $this->db_results(
			"SELECT `image` FROM `content`"
				." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
			,"admin->content->edit_delete_image->remove image"
			,"one"
		);
		@unlink($upload_dir.$sFile);
		
		$this->db_results(
			"UPDATE `content` SET"
				." `image` = ''"
				." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
			,"admin->content->edit_delete_image->update page"
		);
		
		header("Content-type: text/html");
		echo json_encode($aReturn);
	}
	function edit_s()
	{
		$aPage = $this->db_results(
			"SELECT * FROM `content`"
				." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
			,"admin->content->edit"
			,"row"
		);
		
		if($aPage["module"] == 1)
		{
			$aRes = $this->db_results(
				"UPDATE `content` SET"
					." `content` = ".$this->_db->quote($_POST["content"], "text")
					." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
				,"admin->content->edit"
			);
		}
		else
		{
			if(empty($_POST["header_title"]) || empty($_POST["title"]))
			{
				$_SESSION["admin"]["admin_content"] = $_POST;
				$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
			}
		
			$aRes = $this->db_results(
				"UPDATE `content` SET"
					." `header_title` = ".$this->_db->quote($_POST["header_title"], "text")
					.", `header_text` = ".$this->_db->quote(substr($_POST["header_text"],0 ,371), "text")
					.", `title` = ".$this->_db->quote($_POST["title"], "text")
					.", `content` = ".$this->_db->quote($_POST["content"], "text")
					." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
				,"admin->content->edit"
			);
		}
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/");
	}
	function delete($aParams)
	{
		$aRes = $this->db_results(
			"DELETE FROM `content`"
				." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
			,"admin->content->delete"
		);
		
		$this->forward("/admin/content/");
	}
	##################################
	
	### Functions ####################
	##################################
}