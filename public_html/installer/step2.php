<?php
if($_POST["setup"] == 1) {
	// Use database info, and save to config
	$sConfig = file_get_contents("../inc_config.php");
	
	// Database
	$sConfig = changeConfig("database", "type", $_POST["type"], $sConfig);
	$sConfig = changeConfig("database", "host", $_POST["host"], $sConfig);
	$sConfig = changeConfig("database", "username", $_POST["username"], $sConfig);
	$sConfig = changeConfig("database", "password", $_POST["password"], $sConfig);
	$sConfig = changeConfig("database", "database", $_POST["database"], $sConfig);
	$sConfig = changeConfig("database", "prefix", $_POST["prefix"], $sConfig);
	
	// Encryption
	$sConfig = changeConfig("encryption", "key", $_POST["enc_key"], $sConfig);
	$sConfig = changeConfig("encryption", "salt", $_POST["enc_salt"], $sConfig);
	
	// Mail
	$sConfig = changeConfig("mail", "type", $_POST["location"], $sConfig);
	switch($_POST["mail"]) {
		case "mail":
			$sConfig = preg_replace(
				'/\$aConfig\["mail"\]\["params"\] = array\(\);/',
				'$aConfig["mail"]["params"] = array();',
				$sConfig, 1
			);
			break;
		case "sendmail":
			$sConfig = preg_replace(
				'/\$aConfig\["mail"\]\["params"\] = array\(\);/',
				'$aConfig["mail"]["params"] = array();',
				$sConfig, 1
			);
			break;
		case "smtp":
			$sConfig = preg_replace(
				'/\$aConfig\["mail"\]\["params"\] = array\(\);/',
				'$aConfig["mail"]["params"] = array(
	"host" => "'.trim($_POST["smtp_host"]).'",
	"port" => "'.trim($_POST["smtp_port"]).'",
	"auth" => '.(($_POST["smtp_auth"] == 1)?"true":"false").',
	"username" => "'.trim($_POST["smtp_username"]).'",
	"password" => "'.trim($_POST["smtp_password"]).'"
);',
				$sConfig, 1
			);
			break;
	}
	
	file_put_contents("../inc_config.php", $sConfig);
	
	require("MDB2.php");
	$sDSN = $_POST["type"]."://".$_POST["username"].":".$_POST["password"]."@".$_POST["host"]."/".$_POST["database"];
	$objDB = MDB2::connect($sDSN, array('debug' => 2, 'quote_identifier' => true));
	if (PEAR::isError($objDB)) {
		$aUserInfo = preg_split('/\] \[/',str_replace(array("_doConnect: [", "]\n[", "]\n"),array(null, "] [", null),$objDB->userinfo));
		$aMessage = preg_split('/: /',$aUserInfo[0], 2);
		$sFail = "Failed to connect to database: ".$aMessage[1];
	} else {
		include("database.php");
		
		$objDB->loadModule('Manager');

		foreach($aTables as $sTable => $aTable) {
			$sTable = $_POST["prefix"].$sTable;
			
			$objDB->dropTable($sTable);
			
			// Add tables
			$oTable = $objDB->createTable($sTable, $aTable["fields"]);
			if (PEAR::isError($oTable)) {
				die($sTable.": ".$oTable->getMessage());
			}
			
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
			
			if(is_array($aTable["data"])) {
				$objDB->loadModule('Extended');
				foreach($aTable["data"] as $aData) {
					$oResult = $objDB->extended->autoExecute(
						$sTable,
						$aData,
						MDB2_AUTOQUERY_INSERT
					);
				}
			}
		}
		
		header("Location: /?step=3");
		exit;
	}
} else {
	$_POST["mysql"] = $aConfig["database"]["type"];
	$_POST["host"] = $aConfig["database"]["host"];
	$_POST["database"] = $aConfig["database"]["database"];
	$_POST["username"] = $aConfig["database"]["username"];
	$_POST["password"] = $aConfig["database"]["password"];
	$_POST["prefix"] = $aConfig["database"]["prefix"];
	
	$_POST["enc_key"] = $aConfig["encryption"]["key"];
	$_POST["enc_salt"] = $aConfig["encryption"]["salt"];
	
	$_POST["mail"] = $aConfig["mail"]["type"];
	
	if($_POST["mail"] == "") {
		$_POST["sendmail_path"] = $aConfig["mail"]["params"]["sendmail_path"];
		$_POST["sendmail_arg"] = $aConfig["mail"]["params"]["sendmail_args"];
	}
	
	if($_POST["mail"] == "smtp") {
		$_POST["smtp_host"] = $aConfig["mail"]["params"]["host"];
		$_POST["smtp_port"] = $aConfig["mail"]["params"]["port"];
		$_POST["smtp_auth"] = $aConfig["mail"]["params"]["auth"];
		$_POST["smtp_username"] = $aConfig["mail"]["params"]["username"];
		$_POST["smtp_password"] = $aConfig["mail"]["params"]["password"];
	}
}

include("inc_header.php");
?>

<h2>Step 2</h2>

<?php
if(!empty($sFail)) {
	echo "<p class=\"error\">";
	echo $sFail;
	echo "</p>";
}
?>

<p>
	Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.
</p>

<form name="setp2" method="post" action="/?step=2">
	<p>
		<!-- <label>Database Type:</label>
		<select name="type">
		<option value="mysql">MySQL</option>
		</select><br> -->
		<input type="hidden" name="type" value="mysql">
		<label>Database Host:</label>
		<input type="text" name="host" value="<?=$_POST["host"]?>"><br>
		<label>Database:</label>
		<input type="text" name="database" value="<?=$_POST["database"]?>"><br>
		<label>Database Username:</label>
		<input type="text" name="username" value="<?=$_POST["username"]?>"><br>
		<label>Database Password:</label>
		<input type="text" name="password" value="<?=$_POST["password"]?>"><br>
		<label>Database Prefix:</label>
		<input type="text" name="prefix" value="<?=$_POST["prefix"]?>"><br>
	</p>
	<p>
		<label>Mail Delivery Method:</label>
		<select name="mail">
			<option value="mail">PHP Mail</option>
			<option value="sendmail"<?php if($_POST["mail"] == "sendmail") echo " selected=\"selected\""; ?>>Sendmail</option>
			<option value="smtp"<?php if($_POST["mail"] == "smtp") echo " selected=\"selected\""; ?>>SMTP</option>
		</select><br>
	</p>
	<p>
		<b>Sendmail Options:</b><br>
		<label>Sendmail Path:</label>
		<input type="text" name="sendmail_path" value="<?=$_POST["sendmail_path"]?>"><br>
		<label>Sendmail Arguments:</label>
		<input type="text" name="sendmail_arg" value="<?=$_POST["sendmail_arg"]?>"><br>
	</p>
	<p>
		<b>SMTP Options:</b><br>
		<label>Host:</label>
		<input type="text" name="smtp_host" value="<?=$_POST["smtp_host"]?>"><br>
		<label>Port:</label>
		<input type="text" name="smtp_port" value="<?=$_POST["smtp_port"]?>"><br>
		<label>Authentication:</label>
		<select name="smtp_auth">
			<option value="1">Yes</option>
			<option value="0"<?php if($_POST["smtp_auth"] == false) echo " selected=\"selected\""; ?>>No</option>
		</select><br>
		<label>Username:</label>
		<input type="text" name="smtp_username" value="<?=$_POST["smtp_username"]?>"><br>
		<label>Password:</label>
		<input type="text" name="smtp_password" value="<?=$_POST["smtp_password"]?>"><br>
	</p>
	<p>
		<b>Encryption:</b><br>
		<label>Key:</label>
		<input type="text" name="enc_key" value="<?=$_POST["enc_key"]?>"><br>
		<label>Salt:</label>
		<input type="text" name="enc_salt" value="<?=$_POST["enc_salt"]?>"><br>
	</p>
	
	<input type="submit" value="Next">
	<input type="hidden" name="setup" value="1">
</form>

<?php include("inc_footer.php"); ?>