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
$home = "home"; // Página principal
$path_pages = "pags"; // Pasta raiz onde ficam os arquivos html
$pagina = !empty(trim($_GET["icp"] ?? "")) ? trim($_GET["icp"]) : $home;
$pagina = $currentTemplate == "ICP_Control_Panel" ? "panel" : $pagina;
switch ($pagina) {
    case "panel":
		$pagina = !empty(trim($_GET["show"] ?? "")) ? trim($_GET["show"]) : $home;
		if(!isset($_SESSION["ICP_UserName"])){
			$allowedPagesOffline = array("login","register","recovery","add-email","activate");
			if(!in_array($pagina,$allowedPagesOffline))
				$pagina = "login";
		}
        $path_page = $path_pages."/";
		$currentTemplate = "ICP_Control_Panel";
        break;
    case "ranking":
		$pagina = !empty(trim($_GET["type"] ?? "")) ? trim($_GET["type"]) : $home;
		$path_page = $pagina == "home" ? $path_pages."/" : $path_pages."/rankings/";
        break;
    default:
        $path_page = $path_pages."/";
}
if(isset($pagina)){
	define("PATH_TEMPLATE","./templates/".$currentTemplate."/");
	if(file_exists(PATH_TEMPLATE.$path_page.$pagina.'.html')){
		$page = $pagina;
	}else{
		$page = $home;
        $path_page = $path_pages."/";
	}
}else{
	$page = $home;
}