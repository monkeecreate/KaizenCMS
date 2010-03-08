<?php
###############################################
$aConfig["encryption"]["key"] = ""; // example: sha1("domain.com")
$aConfig["encryption"]["salt"] = ""; // example: sha1("random string")
###############################################

### ADMIN INFO ################################
// Info used to send when an error happens when debug is off
$aConfig["admin_info"] = array(
	"name" => "",
	"email" => ""
);
###############################################

### OPTIONS ###################################
$aConfig["options"]["pear"] = "server"; //PEAR file locations; server = packages installed on server, folder = packages sit with site in .pear
$aConfig["options"]["urlcache"] = 30; //Time memcache stores pattern found for url; (int) = time in minutes, (false) = turns off url cache
$aConfig["options"]["debug"] = true;
###############################################

### SOFTWARE ##################################
$aConfig["software"]["memcache"] = true; // Set if you want to use/have Memcache
$aConfig["software"]["firephp"] = true; // Set if you want to use/have FirePHP
###############################################

### MEMCACHE ##################################
# http://us.php.net/memcache
$aConfig["memcache"]["server"] = "localhost";
$aConfig["memcache"]["port"] = "11210";
$aCongig["memcache"]["salt"] = md5("cms"); //Encrypt data sent to memcache server
###############################################

### PEAR ######################################
# PEAR MDB2
# http://pear.php.net/MDB2/
$aConfig["database"]["type"] = "mysql";
$aConfig["database"]["host"] = "localhost";
$aConfig["database"]["username"] = "";
$aConfig["database"]["password"] = "";
$aConfig["database"]["database"] = "";
$aConfig["database"]["fetch"] = 2; // 1 = Ordered (0=>value,1=>value), 2 = Assoc (col=>value,col=>val), 3 = Object {col->value,col->value}

$aConfig["database"]["dsn"] = $aConfig["database"]["type"]."://".$aConfig["database"]["username"].":".$aConfig["database"]["password"]."@".$aConfig["database"]["host"]."/".$aConfig["database"]["database"];
$aConfig["database"]["options"] = array(
	"quote_identifier" => true
);

# PEAR MAIL
# http://pear.php.net/mail/
//SMTP
/*$aConfig["mail"] = array(
	"type" => "smtp",
	"params" => array(
		"host" => "smtp.digimedia.com",
		//"port" => "25",
		"auth" => true,
		"username" => "formadresponse@digimedia.com",
		"password" => "stats818"
	)
);*/
//sendmail
/*$aConfig["mail"] = array(
	"type" => "sendmail",
	"params" => array(
		"sendmail_path" => "",
		"sendmail_args" => ""
	)
);*/
//mail
$aConfig["mail"] = array(
	"type" => "mail",
	"params" => array()
);
###############################################

### TEMPLATES #################################
# PHP Smarty Template Engine
# http://smarty.php.net/
$aConfig["smarty"]["dir"]["smarty"] = $site_root.".smarty/Smarty.class.php";
$aConfig["smarty"]["dir"]["tpl"] = $site_root."views";
$aConfig["smarty"]["dir"]["tplc"] = $site_root.".compiled";
$aConfig["smarty"]["dir"]["cache"] = $site_root.".cache";
$aConfig["smarty"]["dir"]["plugins"] = array(
	$site_root."components"
);

/* Caching */
$aConfig["smarty"]["cache"]["type"] = false;// false, 1 = 1 lifetime, 2 = lifetime per template;
$aConfig["smarty"]["cache"]["lifetime"] = 30;// -1 = never expire, 0 = always regenerate, seconds;

/* Filters */
$aConfig["smarty"]["filters"] = Array(
	//[0] = Type (pre,post,output), [1] = name of filter
	array("output", "move_to_head")
);

/* Settings */
$aConfig["smarty"]["subdirs"] = FALSE;//Potential Speed Boost on large sites while on
$aConfig["smarty"]["debug"] = FALSE;//Javascript popup of assigned variables
$aConfig["smarty"]["debug_ctrl"] = "URL";//NONE = No alt method, URL = "SMARTY_DEBUG" found in query string, ingnored when debug = true;
###############################################