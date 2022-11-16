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
if(!empty($_GET["show"]) && $_GET["show"] == "logout"){
	session_destroy();
	header("Location: index.php?icp=panel");
	exit;
}
require_once("engine/pages_filter.php");
require_once("engine/classes/Template.php");
if(file_exists(PATH_TEMPLATE."index.html")){
	$tpl = new raelgc\view\Template(PATH_TEMPLATE."index.html");
	if($tpl->exists("PAGES")){
		$tpl->addFile("PAGES", PATH_TEMPLATE.$path_page.$page.".html");
	}
	if($tpl->exists("TEMPLATE")){
		$tpl->TEMPLATE = PATH_TEMPLATE;
	}
	if($tpl->exists("INCLUDE_RULES")){
		if(file_exists(PATH_TEMPLATE.$path_page."rules.html")){
			$tpl->addFile("INCLUDE_RULES", PATH_TEMPLATE.$path_page."rules.html");
		}
	}
	$vars = $tpl->getVars();
	for($x=0;$x<count($vars);$x++){
		if($vars[$x] != "TEMPLATE" && $vars[$x] != "PAGES"){
			if(isset($config[$vars[$x]]) && !empty($config[$vars[$x]])){
				if($vars[$x] == "SITE_TITLE" && isset($page) && strtolower($page) != $home && strtolower($page) != "adm-configs"){
					$tpl->{$vars[$x]} = ucwords(strtolower(str_replace("_"," ",str_replace("-"," ",$page))))." - ".$config[$vars[$x]];
				}else{
					$tpl->{$vars[$x]} = $config[$vars[$x]];
				}
			}
		}
		if(isset($_SESSION[$vars[$x]]) && !empty($_SESSION[$vars[$x]])){
			if($vars[$x] == "ICP_UserEmail"){
				$hideEmailPt1 = explode("@",$_SESSION[$vars[$x]]);
				$hideEmailPt2 = explode(".",$hideEmailPt1[1]);
				$hideEmailPt3 = null;
				for($emailX=1;$emailX<count($hideEmailPt2);$emailX++){
					$hideEmailPt3 .= ".".$hideEmailPt2[$emailX];
				}
				$tpl->{$vars[$x]} = substr_replace($hideEmailPt1[0], str_repeat("*", strlen($hideEmailPt1[0]) - 2), 1, strlen($hideEmailPt1[0]) - 2)."@".substr_replace($hideEmailPt2[0], str_repeat("*", strlen($hideEmailPt2[0]) - 2), 1, strlen($hideEmailPt2[0]) - 2).$hideEmailPt3;
			}else{
				$tpl->{$vars[$x]} = ucfirst($_SESSION[$vars[$x]]);
			}
		}
	}
	require_once("engine/classes/Miscellaneous.php");
	if(count($_POST) > 0){
		require_once("engine/post.php");
	}
	require_once("engine/module_server_status.php");
	require_once("engine/module_rankings.php");
	if(!empty($_GET["icp"]) && $_GET["icp"] == "panel" || $currentTemplate == "ICP_Control_Panel"){
		require_once("engine/module_icp_panel.php");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CONNECTED",true)){
		if(isset($_SESSION["ICP_UserName"])){
			$tpl->block("BLOCK_ICP_PANEL_CONNECTED");
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_DISCONNECTED",true)){
				$tpl->block("BLOCK_ICP_PANEL_DISCONNECTED");
			}
		}
	}
	$tpl->show();
}else{
	echo "ERROR: Invalid template!";
}