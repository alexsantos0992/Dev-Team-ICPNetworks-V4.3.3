<?php
session_start();
if(isset($_SESSION["ICP_UserName"]) && !empty($_SESSION["ICP_UserName"])){
	require_once("../../config/userConfig.php");
	require_once("../connect.php");
	require_once("../engine.php");
	$ps_data["email"] = $config["ps_email"];
	$ps_data["token"] = $config["ps_token"];
	$ps_data["currency"] = $config["ps_currency"];
	$ps_data["itemId1"] = "1";
	$ps_data["itemDescription1"] = $config["DONATE_COIN_NAME"];
	$ps_data["itemAmount1"] = number_format(1/$config["ps_amount"],2,".",".");
	$ps_data["itemQuantity1"] = $_GET["quantity"];
	$ps_data["reference"] = $_SESSION["ICP_UserName"];
	$ps_data = http_build_query($ps_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://ws.pagseguro.uol.com.br/v2/checkout/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $ps_data);
	$output = curl_exec($ch);
	curl_close($ch);
	$obj = simplexml_load_string($output);
	echo $obj->code;
}