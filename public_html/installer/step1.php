<?php
$sFail = false;

if(!is_file("../inc_config.php")) {
	$sFail = true;
} else {
	if(!is_writable($aConfig["smarty"]["dir"]["compile"])
	 || ($aConfig["smarty"]["cache"]["type"] != false && !is_writable($aConfig["smarty"]["dir"]["cache"]))
	 || !is_writable("uploads/")) {
		$sFail = true;
	}
}

include("inc_header.php");
?>

<h2>Step 1</h2>

<p>
	Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.
</p>

<?php
if(!is_file("../inc_config.php")) {
	echo "Please setup your config file.";
} else {
	if(!is_writable($aConfig["smarty"]["dir"]["compile"])) {
		echo "Compile directory is not writable.<br>";
	} else {
		echo "Compile directory is writable.<br>";
	}
	
	if($aConfig["smarty"]["cache"]["type"] != false) {
		if(!is_writable($aConfig["smarty"]["dir"]["cache"])) {
			echo "Cache directory is not writable.<br>";
		} else {
			echo "Cache directory is writable.<br>";
		}
	}
	
	if(!is_writable("uploads/")) {
		echo "Uploads directory is not writable.<br>";
	} else {
		echo "Uploads directory is writable.<br>";
	}
}
?>
<form name="step1" method="get" action="/">
	<?php
	if($sFail == true) {
		echo "<input type=\"submit\" value=\"Re-try\">\n";
		echo "<input type=\"hidden\" name=\"step\" value=\"1\">\n";
	} else {
		echo "<input type=\"submit\" value=\"Next\">\n";
		echo "<input type=\"hidden\" name=\"step\" value=\"2\">\n";
	}
	?>
</form>

<?php include("inc_footer.php"); ?>