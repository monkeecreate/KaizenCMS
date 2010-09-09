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
	$sConfig = changeConfig("mail", "type", $_POST["mail"], $sConfig);
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
				'$aConfig["mail"]["params"] = array("sendmail_path" => "'.trim($_POST["sendmail_path"]).'", "sendmail_args" => "'.trim($_POST["sendmail_args"]).'");',
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
		$sFail = "Failed to connect to database.<br />".$aMessage[1];
	} else {
		include("database.php");
		
		$objDB->loadModule('Manager');

		foreach($aTables as $sTable => $aTable) {
			$sTable = $_POST["prefix"].$sTable;
			
			$objDB->dropTable($sTable);
			
			// Add tables
			$oTable = $objDB->createTable($sTable, $aTable["fields"]);
			if (PEAR::isError($oTable)) {
				$aUserInfo = preg_split('/\] \[/',str_replace(array("_doQuery: [", "]\n[", "]\n"),array(null, "] [", null),$objDB->userinfo));
				$aMessage = preg_split('/: /',$aUserInfo[0], 2);
				$sFail = "Failed to import database: ".$oTable->getMessage();
			} else {
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
		}
	}
	
	if(empty($sFail)) {
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

$currentStep = "Step Two";

include("inc_header.php");
?>
			<header>
				<h2>Step Two</h2>
			</header>

			<section class="inner-content">
				<div id="formErrors">
					<?php if(!empty($sFail)) {
						echo "<ul>";
						echo "<li><span class=\"iconic fail\">x</span> ".$sFail."</li>";
						echo "</ul>";
					} ?>
				</div>
				
				

				<form name="step2" method="post" action="/?step=2">
					<fieldset>
						<legend>Database Info</legend>
						
						<!-- <label>Database Type:</label>
						<select name="type">
						<option value="mysql">MySQL</option>
						</select><br> -->
						<input type="hidden" name="type" value="mysql">
						<label for="host">Database Host:</label>
						<input type="text" name="host" value="<?php echo $_POST["host"]; ?>"><br />
						<label for="database">Database Name:</label>
						<input type="text" name="database" value="<?php echo $_POST["database"]; ?>"><br />
						<label for="username">Database Username:</label>
						<input type="text" name="username" value="<?php echo $_POST["username"]; ?>"><br />
						<label for="password">Database Password:</label>
						<input type="text" name="password" value="<?php echo $_POST["password"]; ?>"><br />
						<label for="prefix">Database Prefix:</label>
						<input type="text" name="prefix" value="<?php echo $_POST["prefix"]; ?>"><br />
					</fieldset>
					
					<a href="#advancedSettings" class="showAdvanced">Advanced Settings: Mail, Encryption</a>
					
					<span id="advancedSettings" class="hidden">
						<fieldset>
							<legend>Mail Setup</legend>
						
							<label for="mail">Mail Delivery Method:</label>
							<select name="mail">
								<option value="mail">PHP Mail</option>
								<option value="sendmail"<?php if($_POST["mail"] == "sendmail") echo " selected=\"selected\""; ?>>Sendmail</option>
								<option value="smtp"<?php if($_POST["mail"] == "smtp") echo " selected=\"selected\""; ?>>SMTP</option>
							</select><br />
							
							<span id="sendmail" class="mailOption hidden">
								<h3>Sendmail Options</h3>
								<label for="sendmail_path">Sendmail Path:</label>
								<input type="text" name="sendmail_path" value="<?php echo $_POST["sendmail_path"]; ?>"><br />
								<label for="sendmail_arg">Sendmail Arguments:</label>
								<input type="text" name="sendmail_arg" value="<?php echo $_POST["sendmail_arg"]; ?>"><br />
							</span>
							
							<span id="smtp" class="mailOption hidden">
								<h3>SMTP Options</h3>
								<label for="smtp_host">Host:</label>
								<input type="text" name="smtp_host" value="<?php echo $_POST["smtp_host"]; ?>"><br />
								<label for="smtp_port">Port:</label>
								<input type="text" name="smtp_port" value="<?php echo $_POST["smtp_port"]; ?>"><br />
								<label for="smtp_auth">Authentication:</label>
								<select name="smtp_auth">
									<option value="1">Yes</option>
									<option value="0"<?php if($_POST["smtp_auth"] == false) echo " selected=\"selected\""; ?>>No</option>
								</select><br />
								<label for="smtp_username">Username:</label>
								<input type="text" name="smtp_username" value="<?php echo $_POST["smtp_username"]; ?>"><br />
								<label for="smtp_password">Password:</label>
								<input type="text" name="smtp_password" value="<?php echo $_POST["smtp_password"]; ?>"><br />
							</span>
						</fieldset>
					
						<fieldset>
							<legend>Encryption</legend>
							
							<label for="enc_key">Key:</label>
							<input type="text" name="enc_key" value="<?php echo $_POST["enc_key"]; ?>"><br />
							<label for="enc_salt">Salt:</label>
							<input type="text" name="enc_salt" value="<?php echo $_POST["enc_salt"]; ?>"><br />
						</fieldset>
					</span>
	
					<input type="submit" value="Continue &raquo;" class="gButton right">
					<input type="hidden" name="setup" value="1">
				</form>
			</section>
			
			<script type="text/javascript">
			$(function(){
				$("form").validateForm([
					"required,host,Database host is required",
					"required,database,Database name is required",
					"required,username,Database username is required",
					"required,password,Database password is required"
				]);
			});
			</script>

<?php include("inc_footer.php"); ?>