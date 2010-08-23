<?php
require("MDB2.php");
$objDB = MDB2::connect($aConfig["database"]["dsn"], $aConfig["database"]["options"]);
if (PEAR::isError($objDB)) {
	$aUserInfo = preg_split('/\] \[/',str_replace(array("_doConnect: [", "]\n[", "]\n"),array(null, "] [", null),$objDB->userinfo));
	$aMessage = preg_split('/: /',$aUserInfo[0], 2);
	$sFail = "Failed to connect to database: ".$aMessage[1];
}

if($_POST["setup"] == 1) {
	$objDB->query("UPDATE `".$aConfig["database"]["prefix"]."settings` SET `value` = ".$objDB->quote($_POST["title"], "text")." WHERE `tag` = 'title'");
	$objDB->query("UPDATE `".$aConfig["database"]["prefix"]."settings` SET `value` = ".$objDB->quote($_POST["contact"], "text")." WHERE `tag` = 'email'");
	
	$sConfig = file_get_contents("../inc_config.php");
	$sConfig = changeConfig("admin_info", "", "array(\"name\" => \"".addslashes($_POST["admin_fname"]." ".$_POST["admin_lname"])."\", \"email\" => \"".addslashes($_POST["admin_email"])."\")", $sConfig, false);
	file_put_contents("../inc_config.php", $sConfig);
	
	if(!empty($_POST["admin_password"])) {
		$objDB->query("TRUNCATE TABLE `".$aConfig["database"]["prefix"]."users`");
		$sResults = $objDB->query("INSERT INTO `".$aConfig["database"]["prefix"]."users`"
			." SET `username` = ".$objDB->quote($_POST["admin_username"], "text")
			.", `password` = ".$objDB->quote(sha1($_POST["admin_password"]), "text")
			.", `fname` = ".$objDB->quote($_POST["admin_fname"], "text")
			.", `lname` = ".$objDB->quote($_POST["admin_lname"], "text")
			.", `email_address` = ".$objDB->quote($_POST["admin_email"], "text")
			.", `created_datetime` = ".time()
			.", `created_by` = 0"
			.", `updated_datetime` = ".time()
			.", `updated_by` = 0"
		);
		
		header("Location: /admin/");
		exit;
	} else {
		$sFail = "Admin password is required!";
	}
} else {
	$_POST["title"] = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings` WHERE `tag` = 'title'")->fetchOne();
	$_POST["contact"] = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings` WHERE `tag` = 'email'")->fetchOne();
	
	$sAdmin = $objDB->query("SELECT * FROM `".$aConfig["database"]["prefix"]."users` WHERE `id` = 1")->fetchRow();
	$_POST["admin_username"] = $sAdmin["username"];
	$_POST["admin_fname"] = $sAdmin["fname"];
	$_POST["admin_lname"] = $sAdmin["lname"];
	$_POST["admin_email"] = $sAdmin["email"];
}

$currentStep = "Step Three";

include("inc_header.php");
?>

			<header>
				<h2>Step Three</h2>
			</header>

			<section class="inner-content">
				<div id="formErrors">
					<?php if(!empty($sFail)) {
						echo "<ul>";
						echo "<li><span class=\"iconic fail\">x</span> ".$sFail."</li>";
						echo "</ul>";
					} ?>
				</div>

				<form name="setp3" method="post" action="/?step=3">
					<fieldset>
						<legend>Site Info</legend>
						
						<label for="title">Site Title:</label>
						<input type="text" name="title" value="<?php echo $_POST["title"]; ?>"><br />
						<label for="contact">Contact Email:</label>
						<input type="text" name="contact" value="<?php echo $_POST["contact"]; ?>"><br />
					</fieldset>
					
					<fieldset>
						<legend>Admin Info</legend>
						
						<label for="admin_username">Username:</label>
						<input type="text" name="admin_username" value="<?php echo $_POST["admin_username"]; ?>"><br />
						<label for="admin_password">Password:</label>
						<input type="password" name="admin_password" value="<?php echo $_POST["admin_password"]; ?>"><br />
						<label for="admin_fname">First Name:</label>
						<input type="text" name="admin_fname" value="<?php echo $_POST["admin_fname"]; ?>"><br />
						<label for="admin_lname">Last Name:</label>
						<input type="text" name="admin_lname" value="<?php echo $_POST["admin_lname"]; ?>"><br />
						<label for="admin_email">Email:</label>
						<input type="text" name="admin_email" value="<?php echo $_POST["admin_email"]; ?>"><br />
					</fieldset>

					<input type="submit" value="Continue &raquo;" class="gButton right">
					<input type="hidden" name="setup" value="1">
				</form>
			</section>
			
			<script type="text/javascript">
			$(function(){
				$("form").validateForm([
					"required,title,Site title is required",
					"required,contact,Contact email is required",
					"required,admin_username,Admin username is required",
					"required,admin_password,Admin password is required",
					"required,admin_fname,Admin first name is required",
					"required,admin_lname,Admin last name is required",
					"required,admin_email,Admin email is required"
				]);
			});
			</script>
		
<?php include("inc_footer.php"); ?>