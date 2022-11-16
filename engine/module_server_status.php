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
require_once("classes/ServerDetails.php");
$getServerInfo = new ICPNetworks\ServerInfo($db_type, $loginServer, $gameServer, $config);
if($tpl->exists("BLOCK_SERVER_DETAILS",true)){
	if($config["allow_server_stats"]){
		foreach($getServerInfo->serverDetails() as $rank){
			if($tpl->exists("SERVER_TOTAL_ACCOUNTS"))
				$tpl->SERVER_TOTAL_ACCOUNTS = $rank["totalAccounts"];
			if($tpl->exists("SERVER_TOTAL_CHARACTERS"))
				$tpl->SERVER_TOTAL_CHARACTERS = $rank["totalCharacters"];
			if($tpl->exists("SERVER_TOTAL_ONLINE"))
				$tpl->SERVER_TOTAL_ONLINE = $rank["totalCharactersOnline"];
			if($tpl->exists("SERVER_TOTAL_CLANS"))
				$tpl->SERVER_TOTAL_CLANS = $rank["totalClans"];
			$tpl->block("BLOCK_SERVER_DETAILS");
		}
	}
}
if($tpl->exists("BLOCK_FACEBOOK",true))
	if(!empty($config["FACEBOOK"]))
		$tpl->block("BLOCK_FACEBOOK");
if($tpl->exists("BLOCK_INSTAGRAM",true))
	if(!empty($config["INSTAGRAM"]))
		$tpl->block("BLOCK_INSTAGRAM");
if($tpl->exists("BLOCK_YOUTUBE",true))
	if(!empty($config["YOUTUBE"]))
		$tpl->block("BLOCK_YOUTUBE");
if($tpl->exists("BLOCK_DISCORD",true))
	if(!empty($config["DISCORD"]))
		$tpl->block("BLOCK_DISCORD");
if($tpl->exists("BLOCK_STAFF",true)){
	foreach($getServerInfo->showStaff() as $rank){
		if($tpl->exists("STAFF_IMG"))
			$tpl->STAFF_IMG = empty($rank["staffImg"]) ? "noimage.jpg" : $rank["staffImg"];
		if($tpl->exists("STAFF_NAME"))
			$tpl->STAFF_NAME = $rank["staffName"];
		if($tpl->exists("STAFF_EMAIL"))
			$tpl->STAFF_EMAIL = $rank["staffEmail"];
		$tpl->block("BLOCK_STAFF");
	}
}
if($tpl->exists("BLOCK_NEWS",true)){
	if($config["enable_news"]){
		$pag = empty($getServerInfo->filter($_GET["page"] ?? "")) ? 0 : $getServerInfo->filter($_GET["page"] ?? "");
		$reg_inicial = $pag * $config["MAX_NEWS"];
		$quant_pag = ceil(count($getServerInfo->showNews(0,0))/$config["MAX_NEWS"]);
		if($tpl->exists("BLOCK_PAGINATION",true)){
			if($tpl->exists("PAGINATION"))
				$tpl->PAGINATION = $getServerInfo->paginationPanel($pag, $quant_pag);
			$tpl->block("BLOCK_PAGINATION");
		}else{
			if($tpl->exists("PAGINATION"))
				$tpl->PAGINATION = $getServerInfo->pagination($pag, $quant_pag);
		}
		$newsId = empty($getServerInfo->filter($_GET["id"] ?? "")) ? 0 : $getServerInfo->filter($_GET["id"] ?? "");
		foreach($getServerInfo->showNews($newsId,$reg_inicial.", ".$config["MAX_NEWS"]) as $rank){
			if($tpl->exists("NEWS_ID"))
				$tpl->NEWS_ID = $rank["newsId"];
			if($tpl->exists("NEWS_TEXT_MINI"))
				$tpl->NEWS_TEXT_MINI = strip_tags(substr($rank["newsText"], 0, 130).(strlen($rank["newsText"]) > 130 ? "..." : null));
			if($tpl->exists("NEWS_TEXT_FULL"))
				$tpl->NEWS_TEXT_FULL = nl2br($rank["newsText"]);
			if($tpl->exists("NEWS_TITLE"))
				$tpl->NEWS_TITLE = $rank["newsTitle"];
			if($tpl->exists("NEWS_DATE"))
				$tpl->NEWS_DATE = $rank["newsDate"];
			if($tpl->exists("NEWS_PROFILE"))
				$tpl->NEWS_PROFILE = !empty($rank["newsImage"]) ? $rank["newsImage"] : "noimage.jpg";
			if($tpl->exists("NEWS_AUTHOR"))
				$tpl->NEWS_AUTHOR = !empty($rank["newsAuthor"]) ? $rank["newsAuthor"] : "GM Anonymous";
			$tpl->block("BLOCK_NEWS");
		}
	}
}
if($tpl->exists("BLOCK_GAME_SERVER_ONLINE",true)){
	if($config["enable_servers_check"]){
		if(@fsockopen($db_conn["db_ip"], 7777, $errno, $errstr, 0.1) >= 1){
			$tpl->block("BLOCK_GAME_SERVER_ONLINE");
		}else{
			if($tpl->exists("BLOCK_GAME_SERVER_OFFLINE",true))
				$tpl->block("BLOCK_GAME_SERVER_OFFLINE");
		}
	}else{
		if($config["force_game_server"]){
			$tpl->block("BLOCK_GAME_SERVER_ONLINE");
		}else{
			if($tpl->exists("BLOCK_GAME_SERVER_OFFLINE",true))
				$tpl->block("BLOCK_GAME_SERVER_OFFLINE");
		}
	}
}
if($tpl->exists("BLOCK_LOGIN_SERVER_ONLINE",true)){
	if($config["enable_servers_check"]){
		if(@fsockopen($db_conn["db_ip"], 2106, $errno, $errstr, 0.1) >= 1){
			$tpl->block("BLOCK_LOGIN_SERVER_ONLINE");
		}else{
			if($tpl->exists("BLOCK_LOGIN_SERVER_OFFLINE",true))
				$tpl->block("BLOCK_LOGIN_SERVER_OFFLINE");
		}
	}else{
		if($config["force_login_server"]){
			$tpl->block("BLOCK_LOGIN_SERVER_ONLINE");
		}else{
			if($tpl->exists("BLOCK_LOGIN_SERVER_OFFLINE",true))
				$tpl->block("BLOCK_LOGIN_SERVER_OFFLINE");
		}
	}
}