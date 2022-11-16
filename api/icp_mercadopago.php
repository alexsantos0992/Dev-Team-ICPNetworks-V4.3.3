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
require_once("../config/userConfig.php");
require_once("../engine/connect.php");
if($gameServer && isset($_GET["data_id"]) && !empty($_GET["data_id"])){
    require_once("../engine/engine.php");
    $url = "https://api.mercadopago.com/v1/payments/".$_GET["data_id"];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    $headers = array("Accept: application/json","Authorization: Bearer ".$config["mp_token"]);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($resp);
    $TransacaoID = $obj->order->id;
    $status = $obj->status;
    $valor = $obj->transaction_amount;
    $qtd_moedas = !empty($obj->additional_info->items[0]->quantity ?? 0) ? $obj->additional_info->items[0]->quantity ?? 0 : 0;
    $data = $obj->date_last_updated;
    $moeda = $obj->currency_id;
    $login = $obj->external_reference;
    $metodo = "Mercado Pago";
    if (!empty($TransacaoID) && !empty($login)){
        require_once("entrega_automatica.php");
    }
    curl_close($ch);
}else{
    header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]);
    exit();
}