<?php
class admin_content extends adminController
{
	function __construct() {
		parent::__construct();

		$this->menuPermission("content");
	}

	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;

		$aPages = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." ORDER BY `title`"
			,"all"
		);

		foreach($aPages as &$aPage) {
			$aPage["title"] = htmlspecialchars(stripslashes($aPage["title"]));
			$aPage["content"] = stripslashes($aPage["content"]);
			$aPage["tags"] = htmlspecialchars(stripslashes($aPage["tags"]));
		}

		$this->tplAssign("aPages", $aPages);
		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplDisplay("content/index.php");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_content"])) {
			$this->tplAssign("aPage", $_SESSION["admin"]["admin_content"]);
		} else {
			$this->tplAssign("aPage",
				array(
					"active" => 1
				)
			);
		}

		$this->tplAssign("aTemplates", $this->get_templates(($this->superAdmin ? true : false)));
		$this->tplDisplay("content/add.php");
	}
	function add_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}

		if($_POST["submit-type"] === "Save Draft")
			$sActive = 0;
		elseif($_POST["submit-type"] === "Publish")
			$sActive = 1;

		if($this->superAdmin && !empty($_POST["tag"]))
			$sTag = $_POST["tag"];
		else
			$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

		$aPages = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}content`"
				." ORDER BY `tag`"
			,"all"
		);

		if (in_array(array('tag' => $sTag), $aPages)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aPages);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}

		$sID = $this->dbInsert(
			"content",
			array(
				"tag" => $sTag
				,"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"tags" => $_POST["tags"]
				,"template" => $_POST["template"]
				,"active" => $sActive
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);

		if($this->superAdmin) {
			$this->dbUpdate(
				"content",
				array(
					"tag" => $sTag
					,"permanent" => $this->boolCheck($_POST["permanent"])
				),
				$sID
			);
		}

		$_SESSION["admin"]["admin_content"] = null;

		$this->forward("/admin/content/?success=".urlencode("Page created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_content"])) {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);

			$aPage = $_SESSION["admin"]["admin_content"];

			$aPage["revisions"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content_revisions`"
					." WHERE `pageid` = ".$this->dbQuote($aPage["id"], "integer")
				,"all"
			);

			$aPage["updated_datetime"] = $aPageRow["updated_datetime"];
			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPageRow["updated_by"]
				,"row"
			);

			$this->tplAssign("aPage", $aPage);
		} else {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);

			$aPage["title"] = htmlspecialchars(stripslashes($aPage["title"]));
			$aPage["content"] = stripslashes($aPage["content"]);
			$aPage["tags"] = htmlspecialchars(stripslashes($aPage["tags"]));

			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPage["updated_by"]
				,"row"
			);

			$this->tplAssign("aPage", $aPage);
		}

		$this->tplAssign("aTemplates", $this->get_templates(($this->superAdmin ? true : false)));
		$this->tplDisplay("content/edit.php");
	}
	function edit_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}

		$aPage = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"row"
		);

		$this->dbInsert(
			"content_revisions",
			array(
				"pageid" => $aPage["id"]
				,"title" => $aPage["title"]
				,"content" => $aPage["content"]
				,"updated_datetime" => $aPage["updated_datetime"]
				,"updated_by" => $aPage["updated_by"]
			)
		);

		if($_POST["submit-type"] === "Save Draft") {
			$sActive = 0;
		} elseif($_POST["submit-type"] === "Publish") {
			$sActive = 1;
		} else {
			if($_POST["active"] == 1)
				$sActive = 1;
			else
				$sActive = 0;
		}

		$this->dbUpdate(
			"content",
			array(
				"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"tags" => $_POST["tags"]
				,"template" => $_POST["template"]
				,"active" => $sActive
				,"updated_datetime" =>time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);

		if($this->superAdmin) {
			if(!empty($_POST["tag"]))
				$sTag = $_POST["tag"];
			else
				$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"]))))),0,100);

			$aPages = $this->dbQuery(
				"SELECT `tag` FROM `{dbPrefix}content`"
					." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
					." ORDER BY `tag`"
				,"all"
			);

			if (in_array(array('tag' => $sTag), $aPages)) {
				$i = 1;
				do {
					$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
					$i++;
					$checkDuplicate = in_array(array('tag' => $sTempTag), $aPages);
				} while ($checkDuplicate);
				$sTag = $sTempTag;
			}

			$this->dbUpdate(
				"content",
				array(
					"tag" => $sTag
					,"permanent" => $this->boolCheck($_POST["permanent"])
				),
				$_POST["id"]
			);
		}

		$_SESSION["admin"]["admin_content"] = null;

		$this->forward("/admin/content/?success=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("content", $this->urlVars->dynamic["id"]);
		$this->dbDelete("content_revisions", $this->urlVars->dynamic["id"], "pageid");

		$this->forward("/admin/content/?success=".urlencode("Page removed successfully!"));
	}
	function excerpts() {
		// Clear saved form info
		$_SESSION["admin"]["admin_content_excerpt"] = null;

		$aPages = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}content_excerpts`"
				." ORDER BY `title`"
			,"all"
		);

		foreach($aPages as &$aPage) {
			$aPage["title"] = htmlspecialchars(stripslashes($aPage["title"]));
			$aPage["content"] = stripslashes($aPage["content"]);
			$aPage["description"] = nl2br(htmlspecialchars(stripslashes($aPage["description"])));
		}

		$this->tplAssign("aPages", $aPages);
		$this->tplDisplay("content/excerpts/index.php");
	}
	function excerpts_add() {
		$this->tplAssign("aPage", $_SESSION["admin"]["admin_content_excerpts"]);
		$this->tplDisplay("content/excerpts/add.php");
	}
	function excerpts_add_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content_excerpts"] = $_POST;
			$this->forward("/admin/content/excerpts/add/?error=".urlencode("Please fill in all required fields!"));
		}

		$sID = $this->dbInsert(
			"content_excerpts",
			array(
				"title" => $_POST["title"]
				,"tag" => substr(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))),0,255)
				,"content" => $_POST["content"]
				,"description" => $_POST["description"]
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);

		$_SESSION["admin"]["admin_content_excerpts"] = null;

		$this->forward("/admin/content/excerpts/?success=".urlencode("Excerpt created successfully!"));
	}
	function excerpts_edit() {
		if(!empty($_SESSION["admin"]["admin_content_excerpts"])) {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content_excerpts`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);

			$aPage = $_SESSION["admin"]["admin_content_excerpts"];

			$aPage["updated_datetime"] = $aPageRow["updated_datetime"];
			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPageRow["updated_by"]
				,"row"
			);

			$this->tplAssign("aPage", $aPage);
		} else {
			$aPage = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}content_excerpts`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);

			$aPage["title"] = htmlspecialchars(stripslashes($aPage["title"]));
			$aPage["content"] = stripslashes($aPage["content"]);
			$aPage["description"] = nl2br(htmlspecialchars(stripslashes($aPage["description"])));

			$aPage["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aPage["updated_by"]
				,"row"
			);

			$this->tplAssign("aPage", $aPage);
		}

		$this->tplDisplay("content/excerpts/edit.php");
	}
	function excerpts_edit_s() {
		if(empty($_POST["title"])) {
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/excerpts/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}

		$this->dbUpdate(
			"content_excerpts",
			array(
				"title" => $_POST["title"]
				,"content" => $_POST["content"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);

		if($this->superAdmin) {
			$this->dbUpdate(
				"content_excerpts",
				array(
					"tag" => substr(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["tag"])))),0,255)
					,"description" => $_POST["description"]
				),
				$_POST["id"]
			);
		}

		$_SESSION["admin"]["admin_content_excerpts"] = null;

		$this->forward("/admin/content/excerpts/?success=".urlencode("Changes saved successfully!"));
	}
	function excerpts_delete() {
		$this->dbDelete("content_excerpts", $this->urlVars->dynamic["id"]);

		$this->forward("/admin/content/excerpts/?success=".urlencode("Excerpt removed successfully!"));
	}
	##################################

	### Functions ####################
	function getTemplates() {
		$aTemplates = array();
		$aFiles = scandir($this->settings->root."views/content/");
		foreach($aFiles as $sFile) {
			if($sFile != "." && $sFile != "..")
				$aTemplates[] = $sFile;
		}

		return $aTemplates;
	}
	##################################
}
