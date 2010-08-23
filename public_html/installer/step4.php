<?php
include("inc_header.php");
?>

			<header>
				<h2>Install Complete</h2>
			</header>

			<section class="inner-content">
				<p>Congratulations you have successfully installed Kaizen CMS. There are a few things below to tidy up before continuing to the admin area. These steps below are <strong>very important</strong> for the security of your website. As soon as those items are completed you are free to move about the cms.</p>

				<ul>
					<?php
					if(is_writable("../inc_config.php")) {
						echo "<li><span class=\"iconic fail\">x</span> Make ./inc_config.php un-writable</li>";
					} else {
						echo "<li><span class=\"iconic success\">y</span> Config is un-writable</li>";
					}
					?>
					<li><span class="iconic fail">x</span> Delete the installer directory</li>
				</ul>
				
				<p><a href="/admin/" title="Check Again" class="gButton right">Check Again</a></p>
			</section>

<?php include("inc_footer.php"); ?>