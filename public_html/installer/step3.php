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
	$sConfig = changeConfig("installer", "", "true", $sConfig, false);
	$sConfig = changeConfig("options", "timezone", $_POST["timezone"], $sConfig);
	
	if($_POST["format_date"] == "custom") {
		$sConfig = changeConfig("options", "formatDate", $_POST["format_date_custom"], $sConfig);
	} else {
		$sConfig = changeConfig("options", "formatDate", $_POST["format_date"], $sConfig);
	}
	
	if($_POST["format_time"] == "custom") {
		$sConfig = changeConfig("options", "formatTime", $_POST["format_time_custom"], $sConfig);
	} else {
		$sConfig = changeConfig("options", "formatTime", $_POST["format_time"], $sConfig);
	}
	
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
			.", `super` = 1"
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
	$_POST["title"] = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings` WHERE `tag` = 'site-title'")->fetchOne();
	$_POST["contact"] = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings` WHERE `tag` = 'contact-email'")->fetchOne();
	
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
						
						<label for="timezone">Timezone:</label>
						<?php
						$aTimezones = array(
							"Africa" => array(
								"Africa/Abidjan", "Africa/Accra", "Africa/Addis_Ababa", "Africa/Algiers", "Africa/Asmara", "Africa/Asmera", "Africa/Bamako", "Africa/Bangui", "Africa/Banjul", "Africa/Bissau", "Africa/Blantyre", "Africa/Brazzaville", "Africa/Bujumbura", "Africa/Cairo", "Africa/Casablanca", "Africa/Ceuta", "Africa/Conakry", "Africa/Dakar", "Africa/Dar_es_Salaam", "Africa/Djibouti", "Africa/Douala", "Africa/El_Aaiun", "Africa/Freetown", "Africa/Gaborone", "Africa/Harare", "Africa/Johannesburg", "Africa/Kampala", "Africa/Khartoum", "Africa/Kigali", "Africa/Kinshasa", "Africa/Lagos", "Africa/Libreville", "Africa/Lome", "Africa/Luanda", "Africa/Lubumbashi", "Africa/Lusaka", "Africa/Malabo", "Africa/Maputo", "Africa/Maseru", "Africa/Mbabane", "Africa/Mogadishu", "Africa/Monrovia", "Africa/Nairobi", "Africa/Ndjamena", "Africa/Niamey", "Africa/Nouakchott", "Africa/Ouagadougou", "Africa/Porto-Novo", "Africa/Sao_Tome", "Africa/Timbuktu", "Africa/Tripoli", "Africa/Tunis", "Africa/Windhoek"
							),
							"America" => array(
								"America/Adak", "America/Anchorage", "America/Anguilla", "America/Antigua", "America/Araguaina", "America/Argentina/Buenos_Aires", "America/Argentina/Catamarca", "America/Argentina/ComodRivadavia", "America/Argentina/Cordoba", "America/Argentina/Jujuy", "America/Argentina/La_Rioja", "America/Argentina/Mendoza", "America/Argentina/Rio_Gallegos", "America/Argentina/Salta", "America/Argentina/San_Juan", "America/Argentina/San_Luis", "America/Argentina/Tucuman", "America/Argentina/Ushuaia", "America/Aruba", "America/Asuncion", "America/Atikokan", "America/Atka", "America/Bahia", "America/Bahia_Banderas", "America/Barbados", "America/Belem", "America/Belize", "America/Blanc-Sablon", "America/Boa_Vista", "America/Bogota", "America/Boise", "America/Buenos_Aires", "America/Cambridge_Bay", "America/Campo_Grande", "America/Cancun", "America/Caracas", "America/Catamarca", "America/Cayenne", "America/Cayman", "America/Chicago", "America/Chihuahua", "America/Coral_Harbour", "America/Cordoba", "America/Costa_Rica", "America/Cuiaba", "America/Curacao", "America/Danmarkshavn", "America/Dawson", "America/Dawson_Creek", "America/Denver", "America/Detroit", "America/Dominica", "America/Edmonton", "America/Eirunepe", "America/El_Salvador", "America/Ensenada", "America/Fortaleza", "America/Fort_Wayne", "America/Glace_Bay", "America/Godthab", "America/Goose_Bay", "America/Grand_Turk", "America/Grenada", "America/Guadeloupe", "America/Guatemala", "America/Guayaquil", "America/Guyana", "America/Halifax", "America/Havana", "America/Hermosillo", "America/Indiana/Indianapolis", "America/Indiana/Knox", "America/Indiana/Marengo", "America/Indiana/Petersburg", "America/Indiana/Tell_City", "America/Indiana/Vevay", "America/Indiana/Vincennes", "America/Indiana/Winamac", "America/Indianapolis", "America/Inuvik", "America/Iqaluit", "America/Jamaica", "America/Jujuy", "America/Juneau", "America/Kentucky/Louisville", "America/Kentucky/Monticello", "America/Knox_IN", "America/La_Paz", "America/Lima", "America/Los_Angeles", "America/Louisville", "America/Maceio", "America/Managua", "America/Manaus", "America/Marigot", "America/Martinique", "America/Matamoros", "America/Mazatlan", "America/Mendoza", "America/Menominee", "America/Merida", "America/Mexico_City", "America/Miquelon", "America/Moncton", "America/Monterrey", "America/Montevideo", "America/Montreal", "America/Montserrat", "America/Nassau", "America/New_York", "America/Nipigon", "America/Nome", "America/Noronha", "America/North_Dakota/Center", "America/North_Dakota/New_Salem", "America/Ojinaga", "America/Panama", "America/Pangnirtung", "America/Paramaribo", "America/Phoenix", "America/Port-au-Prince", "America/Porto_Acre", "America/Port_of_Spain", "America/Porto_Velho", "America/Puerto_Rico", "America/Rainy_River", "America/Rankin_Inlet", "America/Recife", "America/Regina", "America/Resolute", "America/Rio_Branco", "America/Rosario", "America/Santa_Isabel", "America/Santarem", "America/Santiago", "America/Santo_Domingo", "America/Sao_Paulo", "America/Scoresbysund", "America/Shiprock", "America/St_Barthelemy", "America/St_Johns", "America/St_Kitts", "America/St_Lucia", "America/St_Thomas", "America/St_Vincent", "America/Swift_Current", "America/Tegucigalpa", "America/Thule", "America/Thunder_Bay", "America/Tijuana", "America/Toronto", "America/Tortola", "America/Vancouver", "America/Virgin", "America/Whitehorse", "America/Winnipeg", "America/Yakutat", "America/Yellowknife"
							),
							"Antarctica" => array(
								"Antarctica/Casey", "Antarctica/Davis", "Antarctica/DumontDUrville", "Antarctica/Macquarie", "Antarctica/Mawson", "Antarctica/McMurdo", "Antarctica/Palmer", "Antarctica/Rothera", "Antarctica/South_Pole", "Antarctica/Syowa", "Antarctica/Vostok"
							),
							"Arctic" => array(
								"Arctic/Longyearbyen"
							),
							"Asia" => array(
								"Asia/Aden", "Asia/Almaty", "Asia/Amman", "Asia/Anadyr", "Asia/Aqtau", "Asia/Aqtobe", "Asia/Ashgabat", "Asia/Ashkhabad", "Asia/Baghdad", "Asia/Bahrain", "Asia/Baku", "Asia/Bangkok", "Asia/Beirut", "Asia/Bishkek", "Asia/Brunei", "Asia/Calcutta", "Asia/Choibalsan", "Asia/Chongqing", "Asia/Chungking", "Asia/Colombo", "Asia/Dacca", "Asia/Damascus", "Asia/Dhaka", "Asia/Dili", "Asia/Dubai", "Asia/Dushanbe", "Asia/Gaza", "Asia/Harbin", "Asia/Ho_Chi_Minh", "Asia/Hong_Kong", "Asia/Hovd", "Asia/Irkutsk", "Asia/Istanbul", "Asia/Jakarta", "Asia/Jayapura", "Asia/Jerusalem", "Asia/Kabul", "Asia/Kamchatka", "Asia/Karachi", "Asia/Kashgar", "Asia/Kathmandu", "Asia/Katmandu", "Asia/Kolkata", "Asia/Krasnoyarsk", "Asia/Kuala_Lumpur", "Asia/Kuching", "Asia/Kuwait", "Asia/Macao", "Asia/Macau", "Asia/Magadan", "Asia/Makassar", "Asia/Manila", "Asia/Muscat", "Asia/Nicosia", "Asia/Novokuznetsk", "Asia/Novosibirsk", "Asia/Omsk", "Asia/Oral", "Asia/Phnom_Penh", "Asia/Pontianak", "Asia/Pyongyang", "Asia/Qatar", "Asia/Qyzylorda", "Asia/Rangoon", "Asia/Riyadh", "Asia/Riyadh87", "Asia/Riyadh88", "Asia/Riyadh89", "Asia/Saigon", "Asia/Sakhalin", "Asia/Samarkand", "Asia/Seoul", "Asia/Shanghai", "Asia/Singapore", "Asia/Taipei", "Asia/Tashkent", "Asia/Tbilisi", "Asia/Tehran", "Asia/Tel_Aviv", "Asia/Thimbu", "Asia/Thimphu", "Asia/Tokyo", "Asia/Ujung_Pandang", "Asia/Ulaanbaatar", "Asia/Ulan_Bator", "Asia/Urumqi", "Asia/Vientiane", "Asia/Vladivostok", "Asia/Yakutsk", "Asia/Yekaterinburg", "Asia/Yerevan"
							),
							"Atlantic" => array(
								"Atlantic/Azores", "Atlantic/Bermuda", "Atlantic/Canary", "Atlantic/Cape_Verde", "Atlantic/Faeroe", "Atlantic/Faroe", "Atlantic/Jan_Mayen", "Atlantic/Madeira", "Atlantic/Reykjavik", "Atlantic/South_Georgia", "Atlantic/Stanley", "Atlantic/St_Helena"
							),
							"Australia" => array(
								"Australia/ACT", "Australia/Adelaide", "Australia/Brisbane", "Australia/Broken_Hill", "Australia/Canberra", "Australia/Currie", "Australia/Darwin", "Australia/Eucla", "Australia/Hobart", "Australia/LHI", "Australia/Lindeman", "Australia/Lord_Howe", "Australia/Melbourne", "Australia/North", "Australia/NSW", "Australia/Perth", "Australia/Queensland", "Australia/South", "Australia/Sydney", "Australia/Tasmania", "Australia/Victoria", "Australia/West", "Australia/Yancowinna"
							),
							"Europe" => array(
								"Europe/Amsterdam", "Europe/Andorra", "Europe/Athens", "Europe/Belfast", "Europe/Belgrade", "Europe/Berlin", "Europe/Bratislava", "Europe/Brussels", "Europe/Bucharest", "Europe/Budapest", "Europe/Chisinau", "Europe/Copenhagen", "Europe/Dublin", "Europe/Gibraltar", "Europe/Guernsey", "Europe/Helsinki", "Europe/Isle_of_Man", "Europe/Istanbul", "Europe/Jersey", "Europe/Kaliningrad", "Europe/Kiev", "Europe/Lisbon", "Europe/Ljubljana", "Europe/London", "Europe/Luxembourg", "Europe/Madrid", "Europe/Malta", "Europe/Mariehamn", "Europe/Minsk", "Europe/Monaco", "Europe/Moscow", "Europe/Nicosia", "Europe/Oslo", "Europe/Paris", "Europe/Podgorica", "Europe/Prague", "Europe/Riga", "Europe/Rome", "Europe/Samara", "Europe/San_Marino", "Europe/Sarajevo", "Europe/Simferopol", "Europe/Skopje", "Europe/Sofia", "Europe/Stockholm", "Europe/Tallinn", "Europe/Tirane", "Europe/Tiraspol", "Europe/Uzhgorod", "Europe/Vaduz", "Europe/Vatican", "Europe/Vienna", "Europe/Vilnius", "Europe/Volgograd", "Europe/Warsaw", "Europe/Zagreb", "Europe/Zaporozhye", "Europe/Zurich"
							),
							"Indian" => array(
								"Indian/Antananarivo", "Indian/Chagos", "Indian/Christmas", "Indian/Cocos", "Indian/Comoro", "Indian/Kerguelen", "Indian/Mahe", "Indian/Maldives", "Indian/Mauritius", "Indian/Mayotte", "Indian/Reunion"
							),
							"Pacific" => array(
								"Pacific/Apia", "Pacific/Auckland", "Pacific/Chatham", "Pacific/Chuuk", "Pacific/Easter", "Pacific/Efate", "Pacific/Enderbury", "Pacific/Fakaofo", "Pacific/Fiji", "Pacific/Funafuti", "Pacific/Galapagos", "Pacific/Gambier", "Pacific/Guadalcanal", "Pacific/Guam", "Pacific/Honolulu", "Pacific/Johnston", "Pacific/Kiritimati", "Pacific/Kosrae", "Pacific/Kwajalein", "Pacific/Majuro", "Pacific/Marquesas", "Pacific/Midway", "Pacific/Nauru", "Pacific/Niue", "Pacific/Norfolk", "Pacific/Noumea", "Pacific/Pago_Pago", "Pacific/Palau", "Pacific/Pitcairn", "Pacific/Pohnpei", "Pacific/Ponape", "Pacific/Port_Moresby", "Pacific/Rarotonga", "Pacific/Saipan", "Pacific/Samoa", "Pacific/Tahiti", "Pacific/Tarawa", "Pacific/Tongatapu", "Pacific/Truk", "Pacific/Wake", "Pacific/Wallis", "Pacific/Yap"
							),
							"UTC" => array(
								"UTC"
							), 
							"Manual Offsets" => array(
								"UTC-12", "UTC-11.5", "UTC-11", "UTC-10.5", "UTC-10", "UTC-9.5", "UTC-9", "UTC-8.5", "UTC-8", "UTC-7.5", "UTC-7", "UTC-6.5", "UTC-6", "UTC-5.5", "UTC-5", "UTC-4.5", "UTC-4", "UTC-3.5", "UTC-3", "UTC-2.5", "UTC-2", "UTC-1.5", "UTC-1", "UTC-0.5", "UTC+0", "UTC+0.5", "UTC+1", "UTC+1.5", "UTC+2", "UTC+2.5", "UTC+3", "UTC+3.5", "UTC+4", "UTC+4.5", "UTC+5", "UTC+5.5", "UTC+5.75", "UTC+6", "UTC+6.5", "UTC+7", "UTC+7.5", "UTC+8", "UTC+8.5", "UTC+8.75", "UTC+9", "UTC+9.5", "UTC+10", "UTC+10.5", "UTC+11", "UTC+11.5", "UTC+12", "UTC+12.75", "UTC+13", "UTC+13.75", "UTC+14"
							)
						)
						?><select name="timezone">
							<?php foreach($aTimezones as $sGroup => $aGroup) { ?>
								<optgroup label="<?php echo $sGroup; ?>"> 
									<?php foreach($aGroup as $sTimezone) { ?>
										<option value="<?php echo $sTimezone; ?>"<?php if($aConfig["options"]["timezone"] == $sTimezone){echo " selected=\"selected\"";} ?>><?php echo str_replace(array($sGroup."/", "_", "/"), array("", " ", " - "), $sTimezone); ?></option> 
									<?php } ?>
								</optgroup>
							<?php } ?>
						</select>
						
						<?php
						$aDateFormats = array(
							"F j, Y",
							"Y/m/d",
							"m/d/Y",
							"d/m/Y"	
						)
						?>
						<label for="format_date" class="alt">Date Format:</label>
						<ul>
						<?php foreach($aDateFormats as $sDateFormat) { ?>
							<li><input type="radio" name="format_date" value="<?php echo $sDateFormat; ?>"<?php if($aConfig["options"]["formatDate"] == $sDateFormat){echo " checked=\"checked\"";} ?>> <?php echo date($sDateFormat); ?></li>
						<?php } ?>
						<li><input type="radio" name="format_date" value="custom"<?php if(!in_array($aConfig["options"]["formatDate"], $aDateFormats)){echo " checked=\"checked\"";} ?>> Custom: <input type="text" name="format_date_custom" value="<?php echo $aConfig["options"]["formatDate"]; ?>"> <?php echo date($aConfig["options"]["formatDate"]); ?></li>
						</ul>
						
						<?php
						$aTimeFormats = array(
							"h:i a",
							"h:i A",
							"m/d/Y",
							"H:i"	
						)
						?>
						<label for="format_time" class="alt">Time Format:</label>
						<ul>
						<?php foreach($aTimeFormats as $sTimeFormat) { ?>
							<li><input type="radio" name="format_time" value="<?php echo $sTimeFormat; ?>"<?php if($aConfig["options"]["formatTime"] == $sTimeFormat){echo " checked=\"checked\"";} ?>> <?php echo date($sTimeFormat); ?></li>
						<?php } ?>
						<li><input type="radio" name="format_time" value="custom"<?php if(!in_array($aConfig["options"]["formatTime"], $aTimeFormats)){echo " checked=\"checked\"";} ?>> Custom: <input type="text" name="format_time_custom" value="<?php echo $aConfig["options"]["formatTime"]; ?>"> <?php echo date($aConfig["options"]["formatTime"]); ?></li>
						</ul>
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