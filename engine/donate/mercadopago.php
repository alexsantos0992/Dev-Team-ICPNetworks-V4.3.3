<?php
session_start();
if(isset($_SESSION["ICP_UserName"]) && !empty($_SESSION["ICP_UserName"]) && isset($_GET["quantity"]) && !empty($_GET["quantity"])){
	require_once("../../config/userConfig.php");
	require_once("../connect.php");
	require_once("../engine.php");
	$url = "https://api.mercadopago.com/checkout/preferences";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = array("Accept: application/json","Authorization: Bearer ".$config["mp_token"],"Content-Type: application/json");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$data = '{"items": [{ "title": "'.$config["DONATE_COIN_NAME"].'", "description": "'.$config["DONATE_COIN_NAME"].'", "picture_url": "https://mlb-s2-p.mlstatic.com/686851-MLB46226875509_052021-F.jpg", "quantity": '.$_GET["quantity"].', "currency_id": "'.$config["mp_currency"].'", "unit_price": '.number_format(1/$config["mp_amount"],2,".",".").' }], "external_reference": "'.$_SESSION["ICP_UserName"].'", "external_resource_url": "http'.((isset($_SERVER["HTTPS"]) ? (($_SERVER["HTTPS"]=="on") ? "s" : "") : "")."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]).'"}';
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$resp = curl_exec($ch);
	$obj = json_decode($resp);
	echo $obj->id;
	curl_close($ch);
}else{
	header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]);
	exit();
}