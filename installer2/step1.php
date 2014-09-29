<?php
$sFail = false;

if(!is_file(APP."inc_config.php")) {
	$sFail = true;
} else {
	if(!is_writable(APP."inc_config.php")
	 || !is_writable(APP_ROOT."uploads/")) {
		$sFail = true;
	}
}

$currentStep = "Step One";

include("inc_header.php");
?>
			<header>
				<h2>Step One</h2>
			</header>

			<section class="inner-content">
				<p><span class="iconic">i</span> You are moments away from CMS bliss by choosing <strong>KaizenCMS</strong>. This installer will walk you through some quick and simple steps to get you on your way. Make sure to have your database info handy as you will need it.</p>

				<p>You will find an example config file (<em>inc_config_example.php</em>) included. Rename this file to inc_config.php to get started. You will also need to change permissions on the following to make them writable (<em>CHMOD 077</em>): <em>./inc_config.php, ./.compiled/, ./public_html/uploads/</em></p>

				<ul>
				<?php
				if(!is_file(APP."inc_config.php")) {
					echo "<li><span class=\"iconic fail\">x</span> Missing config file</li>";
				} else {
					if(!is_writable(APP."inc_config.php")) {
						echo "<li><span class=\"iconic fail\">x</span> Config file is not writable</li>";
					} else {
						echo "<li><span class=\"iconic success\">y</span> Config file found and is writable</li>";
					}

					if(!is_writable(APP_ROOT."uploads/")) {
						echo "<li><span class=\"iconic fail\">x</span> Uploads directory is not writable</li>";
					} else {
						echo "<li><span class=\"iconic success\">y</span> Uploads directory is writable</li>";
					}
				}
				?>
				</ul>

				<form name="step1" method="get" action="/">
					<?php
					if($sFail == true) {
						echo "<input type=\"submit\" value=\"Check Again\" class=\"gButton right\">\n";
						echo "<input type=\"hidden\" name=\"step\" value=\"1\">\n";
					} else {
						echo "<input type=\"submit\" value=\"Continue &raquo;\" class=\"gButton right\">\n";
						echo "<input type=\"hidden\" name=\"step\" value=\"2\">\n";
					}
					?>
				</form>
			</section>

<?php include("inc_footer.php"); ?>
