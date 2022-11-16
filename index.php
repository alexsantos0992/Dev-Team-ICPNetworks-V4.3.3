<?php
//=======================================================================\\
//  ## ####### #######                                                   \\
//  ## ##      ##   ##                                                   \\
//  ## ##      ## ####  |\  | |¯¯¯ ¯¯|¯¯ \      / |¯¯¯| |¯¯¯| | / |¯¯¯|  \\
//  ## ##      ##       | \ | |--    |    \    /  | | | | |_| |<   ¯\_   \\
//  ## ####### ##       |  \| |___   |     \/\/   |___| | |\  | \ |___|  \\
// --------------------------------------------------------------------- \\
//    Brazillian Developer / WebSite: http://www.icpnetworks.com.br      \\
//                 Email & Skype: ivan1507@gmail.com.br                  \\
//=======================================================================\\
session_start();
if(!file_exists("config/userConfig.php")){
	require_once("engine/ICP_install.php");
	exit;
}
require_once("config/userConfig.php");
require_once("engine/connect.php");
require_once("engine/engine.php");
date_default_timezone_set($config["TIME_ZONE"]);
$jsonArr = array("informer","accounts");
if(!empty($_GET["json"]) && in_array($_GET["json"],$jsonArr)){
	require_once("engine/json.php");
}
$currentTemplate = isset($config["TEMPLATE"]) && !empty($config["TEMPLATE"]) ? $config["TEMPLATE"] : null;
if(isset($currentTemplate) && !empty($currentTemplate)){
	require_once("engine/module_template.php");
}else{
	echo "ERROR: Invalid template!";
}
