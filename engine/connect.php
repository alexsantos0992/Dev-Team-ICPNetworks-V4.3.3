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
require_once("classes/ICP_Connect.php");
$db_type = $db_conn["db_type"] ? true : false;
if($db_conn["db_login_server"] == $db_conn["db_game_server"] || empty($db_conn["db_login_server"])){
	$gameServer = ICPConnect::connect("game",$db_type,$db_conn["db_ip"],$db_conn["db_game_server"],$db_conn["username"],$db_conn["password"]);
	$loginServer = $gameServer;
}else{
	$gameServer = ICPConnect::connect("game",$db_type,$db_conn["db_ip"],$db_conn["db_game_server"],$db_conn["username"],$db_conn["password"]);
	$loginServer = ICPConnect::connect("login",$db_type,$db_conn["db_ip"],$db_conn["db_login_server"],$db_conn["username"],$db_conn["password"]);
}