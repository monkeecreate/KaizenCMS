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
		
		header("Location: /?step=4");
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

include("inc_header.php");
?>

			<header>
				<h2>Step Three</h2>
			</header>

			<section class="inner-content">

				<?php
				if(!empty($sFail)) {
					echo "<p class=\"error\">";
					echo $sFail;
					echo "</p>";
				}
				?>

				<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>

				<form name="setp3" method="post" action="/?step=3">
					<p>
						<label>Site Title:</label>
						<input type="text" name="title" value="<?=$_POST["title"]?>"><br>
						<label>Contact Email:</label>
						<input type="text" name="contact" value="<?=$_POST["contact"]?>"><br>
					</p>
					<p>
						<b>Admin Login:</b><br>
						<label>Username:</label>
						<input type="text" name="admin_username" value="<?=$_POST["admin_username"]?>"><br>
						<label>Password:</label>
						<input type="password" name="admin_password" value="<?=$_POST["admin_password"]?>"><br>
						<label>First Name:</label>
						<input type="text" name="admin_fname" value="<?=$_POST["admin_fname"]?>"><br>
						<label>Last Name:</label>
						<input type="text" name="admin_lname" value="<?=$_POST["admin_lname"]?>"><br>
						<label>Email:</label>
						<input type="text" name="admin_email" value="<?=$_POST["admin_email"]?>"><br>
					</p>

					<input type="submit" value="Step Three">
					<input type="hidden" name="setup" value="1">
				</form>
			</section>
		
<?php include("inc_footer.php"); ?>