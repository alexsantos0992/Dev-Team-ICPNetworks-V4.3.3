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
header('Content-Type: text/html; charset=ISO-8859-1');
class PagSeguroNpi {
	private $timeout = 20; // Timeout em segundos
	public function notificationPost() {
		$postdata = 'Comando=validar&Token='.TOKEN;
		foreach ($_POST as $key => $value) {
			$valued    = $this->clearStr($value);
			$postdata .= "&$key=$valued";
		}
		return $this->verify($postdata);
	}
	private function clearStr($str) {
		if (!get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
		return $str;
	}
	private function verify($data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = trim(curl_exec($curl));
		curl_close($curl);
		return $result;
	}
}
require_once("../config/userConfig.php");
require_once("../engine/connect.php");
if($gameServer){
	require_once("../engine/engine.php");
	define('TOKEN', $config["ps_token"]);
	$npi = new PagSeguroNpi();
	$result = $npi->notificationPost();
	if($result == "VERIFICADO"){
		$TransacaoID = $_POST['TransacaoID'];
		$status = $_POST['StatusTransacao'];
		$valor = ceil($_POST['ProdValor_1'] ?? 0 * $_POST["ProdQuantidade_1"] ?? 0);
		$qtd_moedas = !empty($_POST["ProdQuantidade_1"] ?? 0) ? $_POST["ProdQuantidade_1"] ?? 0 : 0;
		$moeda = $_POST["currency"] ?? $config["ps_currency"];
		$data = $_POST['DataTransacao'];
		$login = $_POST['Referencia'];
		$metodo = "PagSeguro";
		if (!empty($TransacaoID) && !empty($login)){
			require_once("entrega_automatica.php");
		}
	}else{
		Header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]); exit();
	}
}else{
	Header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? "https://" : "http://").$_SERVER["HTTP_HOST"]); exit();
}
?>