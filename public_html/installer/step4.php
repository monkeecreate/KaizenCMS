<?php
// Use database info, and save to config
$sConfig = file_get_contents("../inc_config.php");

// Database
$sConfig = changeConfig("installer", "", "true", $sConfig, false);

file_put_contents("../inc_config.php", $sConfig);
	
include("inc_header.php");
?>

			<header>
				<h2>Install Complete</h2>
			</header>

			<section class="inner-content">
				<p>Congratulations you have successfully installed Kaizen CMS. There are a few things below to tidy up before continuing to the admin area. These steps below are <strong>very important</strong> for the security of your website. As soon as those items are completed you are free to move about the cms. <a href="/admin/" title="Login Here">Admin Login Here</a></p>

				<ul>
					<li><span class="iconic">L</span> Make ./inc_config.php un-writable</li>
					<li><span class="iconic">t</span> Delete the installer directory</li>
				</ul>
			</section>

<?php include("inc_footer.php"); ?>