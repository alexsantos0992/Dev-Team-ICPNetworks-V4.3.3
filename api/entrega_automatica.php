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
$transaction_check = $gameServer->prepare("SELECT status FROM icp_donate_history WHERE transaction_id = ? AND account = ?", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
$transaction_check->execute([$TransacaoID,$login]);
$results = $transaction_check->fetch(\PDO::FETCH_ASSOC);
if($transaction_check->rowCount() == 1){
	if(strtolower($results["status"]) != 'completed' && strtolower($results["status"]) != 'aprovado' && strtolower($results["status"]) != 'approved'){
		$transaction_update = $gameServer->prepare("UPDATE icp_donate_history SET status=? WHERE transaction_id = ? AND account = ?");
		$transaction_update->execute([$status,$TransacaoID,$login]);
	}else{
		exit;
	}
}else{
	$transaction_insert = $gameServer->prepare("INSERT INTO icp_donate_history (account, amount, currency, price, status, transaction_id, date, method) VALUES (?,?,?,?,?,?,?,?)");
	$transaction_insert->execute([$login,$qtd_moedas,$moeda,$valor,$status,$TransacaoID,$data,$metodo]);
}
if((strtolower($status) == 'completed' || strtolower($status) == 'aprovado' || strtolower($status) == 'approved') && !empty($login)){
	$donate_check = $gameServer->prepare("SELECT * FROM icp_donate WHERE login=?", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
	$donate_check->execute([$login]);
	if ($donate_check->rowCount() == 0){
		$crediting = $gameServer->prepare("INSERT INTO icp_donate (login, total) VALUES (?,?)");
		$crediting->execute([$login,$qtd_moedas]);
	}else{
		$crediting = $gameServer->prepare("UPDATE icp_donate SET total = (total + ?) WHERE login = ?");
		$crediting->execute([$qtd_moedas,$login]);
	}
	$donateLog = $gameServer->prepare("INSERT INTO icp_donate_log (description, cost, account) VALUES ('".$metodo." delivered ".$qtd_moedas." ".$config["DONATE_COIN_NAME"].".','0',?)");
	$donateLog->execute([$login]);
}
?>