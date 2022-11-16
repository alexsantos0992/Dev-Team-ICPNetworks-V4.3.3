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
if($db_type){
	$getConfigs = $gameServer->prepare("SELECT * FROM icp_configs ORDER BY id DESC LIMIT 1");
}else{
	$getConfigs = $gameServer->prepare("SELECT TOP 1 * FROM icp_configs ORDER BY id DESC");
}
$getConfigs->execute();
$config = $getConfigs->fetch(\PDO::FETCH_ASSOC);
foreach($config as $key => $val){
	if(is_numeric($val)){
		$config[$key] = ltrim($val,"0");
	}
}
if(file_exists("engine/servers/".$config["SERVER"].".php")){
	require_once("engine/servers/".$config["SERVER"].".php");
	switch ($config["CHRONICLE_ID"]){
		case 0:
			$chronicleName = "C0 - Prelude"; break;
		case 1:
			$chronicleName = "C1 - Harbingers of War"; break;
		case 2:
			$chronicleName = "C2 - Age of Splendor"; break;
		case 3:
			$chronicleName = "C3 - Rise of Darkness"; break;
		case 4:
			$chronicleName = "C4 - Scions of Destiny"; break;
		case 5:
			$chronicleName = "C5 - Oath of Blood"; break;
		case 6:
			$chronicleName = "Interlude"; break;
		case 7:
			$chronicleName = "Kamael"; break;
		case 8:
			$chronicleName = "Hellbound"; break;
		case 9:
			$chronicleName = "Gracia - PT1"; break;
		case 10:
			$chronicleName = "Gracia - PT2"; break;
		case 11:
			$chronicleName = "Gracia Final"; break;
		case 12:
			$chronicleName = "Epilogue"; break;
		case 13:
			$chronicleName = "Freya"; break;
		case 14:
			$chronicleName = "High Five"; break;
		case 15:
			$chronicleName = "GOD - Awakening"; break;
		case 16:
			$chronicleName = "GOD - Harmony"; break;
		case 17:
			$chronicleName = "GOD - Tauti"; break;
		case 18:
			$chronicleName = "GOD - Glory Days"; break;
		case 19:
			$chronicleName = "GOD - Lindvior"; break;
		case 20:
			$chronicleName = "GOD - Valliance"; break;
		case 21:
			$chronicleName = "ETOA - Ertheia"; break;
		case 22:
			$chronicleName = "ETOA - Infinite Odyssey"; break;
		case 23:
			$chronicleName = "ETOA - Hymn of the Soul"; break;
		case 24:
			$chronicleName = "ETOA - Helios"; break;
		case 25:
			$chronicleName = "ETOA - Grand Crusade"; break;
		case 26:
			$chronicleName = "ETOA - Salvation"; break;
		case 27:
			$chronicleName = "ETOA - Etina's Fate"; break;
		case 28:
			$chronicleName = "ETOA - Fafurion"; break;
		case 29:
			$chronicleName = "Prologue - Prelude of War"; break;
		case 30:
			$chronicleName = "Classic - Saviors"; break;
		case 31:
			$chronicleName = "Classic - Zaken"; break;
		case 32:
			$chronicleName = "Classic - Seven Sings"; break;
		case 33:
			$chronicleName = "Classic - Secret of Empire"; break;
		case 34:
			$chronicleName = "Classic - The Kamael"; break;
		case 35:
			$chronicleName = "Essence - Dwelling Of Spirits"; break;
		case 36:
			$chronicleName = "Essence - Frost Lord"; break;
		case 37:
			$chronicleName = "Essence - Battle Chronicle"; break;
		default:
			$chronicleName = "Interlude"; break;
	}
	$config["CHRONICLE"] = $chronicleName;
}else{
	if(!in_array(basename($_SERVER['PHP_SELF']),["mercadopago.php","pagseguro.php","icp_mercadopago.php","icp_pagseguro.php","icp_paypal.php"])){
		die("INVALID SERVER.");
	}
}