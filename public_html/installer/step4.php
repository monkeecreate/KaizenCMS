<?php
// Use database info, and save to config
$sConfig = file_get_contents("../inc_config.php");

// Database
$sConfig = changeConfig("installer", "", "true", $sConfig, false);

file_put_contents("../inc_config.php", $sConfig);
	
include("inc_header.php");
?>

<h2>Finished</h2>

<p>
	Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.
</p>

<p>
	Final Steps:
	<ul>
		<li>Make inc_config.php un-writable</li>
		<li>Delete the installer directory</li>
	</ul>
</p>

<?php include("inc_footer.php"); ?>