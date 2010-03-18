<?php
$errors = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Kaizen CMS Install</title>
	<style type="text/css">
	body {
		background: #DFDFDF;
		font: 90% Verdana, Arial, Helvetica, Geneva, sans-serif;
	}
	
	#container {
		width: 700px;
		margin: 0 auto;
	}
	
	header {
		text-align: center;
	}
	
	.item {
		background: #EFEFEF;
		border: 5px solid #9F9F9F;
		padding: 15px 15px 15px 60px;
		margin-bottom:20px
	}
	
	.error {
		background: #EFEFEF url(/images/admin/icons/cross.png) no-repeat 20px 50%;
		color: #CF6767;
	}
	.ok {
		background: #EFEFEF url(/images/admin/icons/accept.png) no-repeat 20px 50%;
		color: #2F7F33;
	}
	</style>
</head>
<body>
<div id="container">
	<header>
		<h1>Kaizen CMS Install</h1>
	</header>
	<?php
	if(!is_file("../inc_config.php"))
		createBox("error", "Create your config file before continueing.");
	else {
		
		/*## SMARTY ##*/
		if(!is_dir($aConfig["smarty"]["dir"]["compile"]))
			createBox("error", "Create Smarty compile directory at '".$aConfig["smarty"]["dir"]["compile"]."' and make it writable.");
		else {
			if(!is_writable($aConfig["smarty"]["dir"]["compile"]))
				createBox("error", "Make the Smarty compile directory writable.");
		}
		if(!is_dir($aConfig["smarty"]["dir"]["cache"]) && $aConfig["smarty"]["cache"]["type"] != false)
			createBox("error", "Create Smarty cache directory at '".$aConfig["smarty"]["dir"]["tplc"]."' and make it writable., or turn caching off.");
		elseif($aConfig["smarty"]["cache"]["type"] != false) {
			if(!is_writable($aConfig["smarty"]["dir"]["cache"]))
				createBox("error", "Make the Smarty cache directory writable.");
		}
		/*## SMARTY ##*/
		
		if(!is_dir("uploads/"))
			createBox("error", "Create upload directory at '".$site_public_root."' and make it writable.");
		else {
			if(!is_writable("uploads/"))
				createBox("error", "Make the uploads directory writable.");
		}
		
		/*## CONFIG ##*/
		if(empty($aConfig["admin_info"]["email"]))
			createBox("error", "Config - Insert your admin info.");
		if(empty($aConfig["encryption"]["key"]))
			createBox("error", "Config - Set an encryption key.");
		if(empty($aConfig["encryption"]["salt"]))
			createBox("error", "Config - Set an encryption salt.");
		/*## CONFIG ##*/
	}
	if($error == 0)
		createBox("ok", "You may now delete '".$site_public_root."install.php'.");
	?>
</div>
</body>
</html>
<?php
function createBox($type, $message) {
	global $error;
	
	echo "<div class=\"item ".$type."\">\n";
	echo "\t".$message."\n";
	echo "</div>\n";
	
	$error = 1;
}
?>