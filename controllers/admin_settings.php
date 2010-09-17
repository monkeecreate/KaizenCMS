<?php
class admin_settings extends adminController
{
	function __construct() {
		parent::__construct();
		
		$this->menuPermission("settings");
	}
	
	### DISPLAY ######################
	function index() {
		$aSettingsFull = $this->dbQuery(
			"SELECT `settings`.*, `groups`.`name` AS `group` FROM `{dbPrefix}settings` AS `settings`"
				." LEFT JOIN `{dbPrefix}settings_groups` as `groups` ON `settings`.`group` = `groups`.`id`"
				." WHERE `settings`.`active` = 1"
				." AND `groups`.`active` = 1"
				." ORDER BY `groups`.`sort_order`, `sortOrder`, `title`"
			,"all"
		);
		
		$aSettings = array();
		include($this->settings->root."helpers/Form.php");
		foreach($aSettingsFull as $aSetting) {
			$oField = new Form($aSetting);
			
			$aSettings[$aSetting["group"]][]["html"] = $oField->setting->html();
		}
		
		$this->tplAssign("aSettings", $aSettings);
		$this->tplAssign("curGroup", "");
		$this->tplDisplay("settings/index.tpl");
	}
	function save() {
		$aSettings = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings`"
				." ORDER BY `group`, `sortOrder`, `title`"
			,"all"
		);
		
		include($this->settings->root."helpers/Form.php");
		foreach($aSettings as $aSetting) {
			$oField = new Form($aSetting);	
			$this->dbUpdate(
				"settings",
				array(
					"value" => $oField->setting->save($_POST["settings"][$aSetting["tag"]])
				),
				$aSetting["tag"],
				"tag",
				"text"
			);
		}
		
		$this->forward("/admin/settings/?notice=".urlencode("Settings saved successfully!"));
	}
	function manageIndex() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		// Clear saved form info
		$_SESSION["admin"]["admin_settings"] = null;
		
		$aSettings = $this->dbQuery(
			"SELECT `settings`.*, `groups`.`name` AS `group` FROM `{dbPrefix}settings` AS `settings`"
				." LEFT JOIN `{dbPrefix}settings_groups` as `groups` ON `settings`.`group` = `groups`.`id`"
				." ORDER BY `groups`.`sort_order`, `settings`.`sortOrder`, `settings`.`title`"
			,"all"
		);
		
		$this->tplAssign("aSettings", $aSettings);
		$this->tplDisplay("settings/manage/index.tpl");
	}
	function manageAdd() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		if(!empty($_SESSION["admin"]["admin_settings"]))
			$this->tplAssign("aSetting", $_SESSION["admin"]["admin_settings"]);
		else {
			$this->tplAssign("aSetting",
				array(
					"active" => 1
				)
			);
		}
		
		$aSettingGroups = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings_groups`"
			,"all"
		);

		$this->tplAssign("aSettingGroups", $aSettingGroups);
		$this->tplDisplay("settings/manage/add.tpl");
	}
	function manageAdd_s() {
		if(empty($_POST["tag"]) || empty($_POST["title"])) {
			$_SESSION["admin"]["admin_settings"] = $_POST;
			$this->forward("/admin/settings/manage/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"settings",
			array(
				"group" => $_POST["group"]
				,"tag" => $_POST["tag"]
				,"title" => $_POST["title"]
				,"text" => $_POST["text"]
				,"value" => $_POST["value"]
				,"type" => $_POST["type"]
				,"sortOrder" => $_POST["sortorder"]
				,"active" => $this->boolCheck($_POST["active"])
			)
		);
		
		$_SESSION["admin"]["admin_settings"] = null;
		
		$this->forward("/admin/settings/manage/?notice=".urlencode("Setting created successfully!"));
	}
	function manageEdit() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		if(!empty($_SESSION["admin"]["admin_settings"])) {
			$aSetting = $_SESSION["admin"]["admin_settings"];
			
			$this->tplAssign("aSetting", $aSetting);
		} else {
			$aSetting = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}settings`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$this->tplAssign("aSetting", $aSetting);
		}
		
		$aSettingGroups = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings_groups`"
			,"all"
		);

		$this->tplAssign("aSettingGroups", $aSettingGroups);		
		$this->tplDisplay("settings/manage/edit.tpl");
	}
	function manageEdit_s() {
		if(empty($_POST["tag"]) || empty($_POST["title"])) {
			$_SESSION["admin"]["admin_settings"] = $_POST;
			$this->forward("/admin/settings/manage/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"settings",
			array(
				"group" => $_POST["group"]
				,"tag" => $_POST["tag"]
				,"title" => $_POST["title"]
				,"text" => $_POST["text"]
				,"value" => $_POST["value"]
				,"type" => $_POST["type"]
				,"sortOrder" => $_POST["sortorder"]
				,"active" => $this->boolCheck($_POST["active"])
			),
			$_POST["id"]
		);
		
		$_SESSION["admin"]["admin_settings"] = null;
		
		$this->forward("/admin/settings/manage/?notice=".urlencode("Changes saved successfully!"));
	}
	function manageDelete() {
		$this->dbDelete("settings", $this->urlVars->dynamic["id"]);
		
		$this->forward("/admin/settings/manage/?notice=".urlencode("Setting removed successfully!"));
	}
	
	function manageGroupsIndex() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		// Clear saved form info
		$_SESSION["admin"]["admin_settings_groups"] = null;
		
		$aGroups = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings_groups`"
				." ORDER BY `sort_order`, `name`"
			,"all"
		);
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}settings_groups`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}settings_groups`"
			,"one"
		);
		
		$this->tplAssign("aGroups", $aGroups);
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplDisplay("settings/manage/groups/index.tpl");
	}
	function manageGroupsAdd() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		if(!empty($_SESSION["admin"]["admin_settings_groups"]))
			$this->tplAssign("aGroup", $_SESSION["admin"]["admin_settings_groups"]);
		else {
			$this->tplAssign("aGroup",
				array(
					"active" => 1
				)
			);
		}
		
		$this->tplDisplay("settings/manage/groups/add.tpl");
	}
	function manageGroupsAdd_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_settings_groups"] = $_POST;
			$this->forward("/admin/settings/manage/groups/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}settings_groups`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"settings_groups",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
			)
		);
		
		$_SESSION["admin"]["admin_settings_groups"] = null;
		
		$this->forward("/admin/settings/manage/groups/?notice=".urlencode("Group created successfully!"));
	}
	function manageGroupsEdit() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		if(!empty($_SESSION["admin"]["admin_settings_groups"])) {
			$aGroup = $_SESSION["admin"]["admin_settings_groups"];
			
			$this->tplAssign("aGroup", $aGroup);
		} else {
			$aGroup = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}settings_groups`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$this->tplAssign("aGroup", $aGroup);
		}
		
		$this->tplDisplay("settings/manage/groups/edit.tpl");
	}
	function manageGroupsEdit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_settings_groups"] = $_POST;
			$this->forward("/admin/settings/manage/groups/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"settings_groups",
			array(
				"name" => $_POST["name"]
				,"active" => $this->boolCheck($_POST["active"])
			),
			$_POST["id"]
		);
		
		$_SESSION["admin"]["admin_settings_groups"] = null;
		
		$this->forward("/admin/settings/manage/groups/?notice=".urlencode("Changes saved successfully!"));
	}
	function manageGroupsDelete() {
		$this->dbDelete("settings_groups", $this->urlVars->dynamic["id"]);
		
		$this->forward("/admin/settings/manage/groups/?notice=".urlencode("Group removed successfully!"));
	}
	function manageGroupsSort() {
		$aGroup = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings_groups`"
				." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
			,"row"
		);
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}settings_groups`"
					." WHERE `sort_order` < ".$aGroup["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}settings_groups`"
					." WHERE `sort_order` > ".$aGroup["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"settings_groups",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aGroup["id"]
		);
		
		$this->dbUpdate(
			"settings_groups",
			array(
				"sort_order" => $aGroup["sort_order"]
			),
			$aOld["id"]
		);
		
		$this->forward("/admin/settings/manage/groups/?notice=".urlencode("Sort order saved successfully!"));
	}
	function plugins_index() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		// Loop plugins. Find installed, and not installed
		$aPlugins = array();
		
		$oPlugins = dir($this->settings->root."plugins");
		while (false !== ($sPlugin = $oPlugins->read())) {
			if(substr($sPlugin, 0, 1) != ".")
				$aPlugins[] = $sPlugin;
		}
		$oPlugins->close();
		
		foreach($aPlugins as &$aPlugin) {
			$aPluginInstalled = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}plugins`"
					." WHERE `plugin` = ".$this->dbQuote($aPlugin, "text")
				,"row"
			);
			
			// Load config
			$aPluginInfo = array();
			if(is_file($this->settings->root."plugins/".$aPlugin."/config.php"))
				include($this->settings->root."plugins/".$aPlugin."/config.php");
			
			$aPlugin = array(
				"tag" => $aPlugin,
				"version" => $aPluginInfo["version"],
				"author" => $aPluginInfo["author"],
				"website" => $aPluginInfo["website"]
			);
			
			if(!empty($aPluginInfo["name"]))
				$aPlugin["name"] = $aPluginInfo["name"];
			else
				$aPlugin["name"] = $aPlugin["tag"];
			
			if(!empty($aPluginInstalled))
				$aPlugin["status"] = 1;
			else
				$aPlugin["status"] = 0;
		}
		
		$this->tplAssign("aPlugins", $aPlugins);
		$this->tplDisplay("settings/plugins/index.tpl");
	}
	function plugins_install() {
		global $objDB;
		
		$sPlugin = $this->urlVars->dynamic["plugin"];
		
		// Set defaults
		$aTables = $aSettings = $aMenuAdmin = array();
		
		// Include isntall
		$sPluginStatus = 1;
		if(is_file($this->settings->root."plugins/".$sPlugin."/install.php"))
			include($this->settings->root."plugins/".$sPlugin."/install.php");
		
		// Database
		$objDB->loadModule('Manager');

		foreach($aTables as $sTable => $aTable) {
			$sTable = $this->settings->dbPrefix.$sTable;
			
			// Add database
			$oTable = $objDB->createTable($sTable, $aTable["fields"]);
			
			// Add indexes
			$aDefinitions = array(
				"fields" => array(
				)
			);
			
			if(is_array($aTable["index"])) {
				foreach($aTable["index"] as $x => $sIndex) {
					if($x == 0)
						$sName = $sIndex;
				
					$aDefinitions["fields"][$sIndex] = array();
				}
			}
			
			if(!empty($sName))
				$objDB->createIndex($sTable, $sName, $aDefinitions);
			
			if(is_array($aTable["fulltext"]))
				$this->dbQuery("ALTER TABLE  `".$sTable."` ADD FULLTEXT (`".implode("`,`", $aTable["fulltext"])."`);", "alter");
			
			if(is_array($aTable["search"])) {
				$this->dbInsert(
					"search",
					array(
						"plugin" => $sPlugin
						,"table" => $sTable
						,"column_title" => $aTable["search"]["title"]
						,"column_content" => $aTable["search"]["content"]
						,"rows" => json_encode($aTable["search"]["rows"])
					)
				);
			}
		}
		
		// Settings
		foreach($aSettings as $aSetting) {
			$sGroup = $this->dbQuery("SELECT `id` FROM `{dbPrefix}settings_groups`"
					." WHERE `name` = ".$this->dbQuote($aSetting["group"], "text")
				, "one"
			);
			
			if(empty($sGroup)) {
				$sOrder = $this->dbQuery(
					"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}settings_groups`"
					,"one"
				);
	
				if(empty($sOrder))
					$sOrder = 1;
				
				$sGroup = $this->dbInsert(
					"settings_groups",
					array(
						"name" => $aSetting["group"],
						"sort_order" => $sOrder,
						"active" => 1
					)
				);
			}
			
			$this->dbInsert(
				"settings",
				array(
					"group" => $sGroup
					,"tag" => $aSetting["tag"]
					,"title" => $aSetting["title"]
					,"text" => $aSetting["text"]
					,"value" => $aSetting["value"]
					,"type" => $aSetting["type"]
					,"sortOrder" => $aSetting["order"]
				)
			);
		}
		
		// Admin Menu
		if(!empty($aMenuAdmin)) {
			$sOrder = $this->dbQuery(
				"SELECT MAX(`sort_order`) FROM `{dbPrefix}menu_admin`"
				,"one"
			);
			$sOrder++;
			
			$this->dbInsert(
				"menu_admin",
				array(
					"tag" => $sPlugin
					,"sort_order" => $sOrder
					,"info" => json_encode($aMenuAdmin)
				)
			);
		}
		
		// Plugin Status
		$this->dbInsert(
			"plugins",
			array(
				"plugin" => $sPlugin
			)
		);
		
		$this->forward("/admin/settings/plugins/?notice=".urlencode("Plugin installed successfully!"));
	}
	function plugins_uninstall() {
		global $objDB;
		
		$sPlugin = $this->urlVars->dynamic["plugin"];
		
		// Set defaults
		$aTables = $aSettings = $aMenuAdmin = array();
		
		// Include isntall
		$sPluginStatus = 0;
		if(is_file($this->settings->root."plugins/".$sPlugin."/install.php"))
			include($this->settings->root."plugins/".$sPlugin."/install.php");
			
		// Database
		$objDB->loadModule('Manager');

		foreach($aTables as $sTable => $aTable) {
			$objDB->dropTable($this->settings->dbPrefix.$sTable);
		}
		
		// Settings
		foreach($aSettings as $aSetting) {
			$this->dbDelete("settings", $aSetting["tag"], "tag", "text");
		}
		
		//Search
		$this->dbDelete("search", $sPlugin, "plugin", "text");
		
		// Admin Menu
		$this->dbDelete("menu_admin", $sPlugin, "tag", "text");
		
		// Plugin status
		$this->dbDelete("plugins", $sPlugin, "plugin", "text");
		
		$this->forward("/admin/settings/plugins/?notice=".urlencode("Plugin uninstalled successfully!"));
	}
	function admin_menu_index() {
		if($this->superAdmin == false)
			$this->forward("/admin/settings/?error=".urlencode("You do not have permissions to view that page."));
		
		$aAdminMenuResult = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}menu_admin`"
				." ORDER BY `sort_order`"
			,"all"
		);
		
		$aAdminMenu = array();
		foreach($aAdminMenuResult as $aMenu) {
			$aAdminMenu[$aMenu["tag"]] = json_decode($aMenu["info"], true);
		}
		
		$this->tplAssign("aAdminMenu", $aAdminMenu);		
		$this->tplDisplay("settings/admin_menu/index.tpl");
	}
	function admin_menu_s() {
		foreach($_POST["admin_menu"] as $sSortOrder => $aMenuTag) {
			$this->dbUpdate(
				"menu_admin",
				array(
					"sort_order" => $sSortOrder
				),
				$aMenuTag,
				"tag",
				"text"
			);
		}
		
		$this->forward("/admin/settings/admin-menu/?notice=".urlencode("Menu updated successfully!"));
	}
	##################################
}