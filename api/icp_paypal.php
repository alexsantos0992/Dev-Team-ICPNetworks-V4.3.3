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
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
if ( !($res = curl_exec($ch)) ) {
	curl_close($ch);
	exit;
}
curl_close($ch);
if (strcmp ($res, "VERIFIED") == 0) {
	if(count($_POST) > 0) {
		require_once("../config/userConfig.php");
		require_once("../engine/connect.php");
		if($gameServer){
			require_once("../engine/engine.php");
			$TransacaoID = $_POST['txn_id'];
			$status = $_POST['payment_status'];
			$valor = $_POST['mc_gross'];
			$qtd_moedas = !empty($_POST["quantity"] ?? 0) ? $_POST["quantity"] ?? 0 : 0;
			$moeda = $_POST['mc_currency'];
			$data = $_POST['payment_date'];
			$login = $_POST['custom'];
			$metodo = "PayPal";
			if (!empty($TransacaoID) && !empty($login)){
				require_once("entrega_automatica.php");
			}
		}else{
			Header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]); exit();
		}
	}else{
		Header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]); exit();
	}
}else{
	Header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]); exit();
}