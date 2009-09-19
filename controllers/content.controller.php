<?php
class content extends AppController
{
	### DISPLAY ######################
	function index()
	{
		$this->_smarty->display("index.tpl");
	}
	function siteinfo()
	{
		$this->site_info();
	}
	function view($aUrlParam = null, $aParams)
	{
		if(!empty($aUrlParam["page"]))
			$sPage = $aUrlParam["page"];
		elseif(!empty($aParams["page"]))
			$sPage = $aParams["page"];
		else
			$this->error("404");
		
		if($this->template_exists("content_".$sPage.".tpl"))
			$this->_smarty->display("content_".$sPage.".tpl");
		else
		{
			$rContent = $this->_db->query("SELECT * FROM `content`"
				." WHERE `tag` = ".$this->_db->quote($sPage, "text")
				." AND `active` = 1"
				." LIMIT 1"
			);
			
			if(PEAR::isError($rContent))
				$this->send_error("content->view->tag", "dberror", $rContent);
			
			$aContent = $rContent->fetchRow();
			
			if(!empty($aContent))
			{
				$this->_smarty->assign("aContent", $aContent);
				$this->_smarty->display("content.tpl");
			}
			else
				$this->error("404");
		}
	}
	##################################
	
	### Functions ####################
	##################################
}