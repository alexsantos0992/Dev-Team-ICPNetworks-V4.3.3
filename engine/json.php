<?php
if(!empty($_GET["json"])){
	if($_GET["json"] == "informer"){
		if(in_array($config["CHRONICLE_ID"],array(0,1,2,3,4,5))){
			$npcsTable = "icp_npc_c4";
			$iconsTable = "icp_icons_c4";
		}elseif(in_array($config["CHRONICLE_ID"],array(6,7))){
			$npcsTable = "icp_npc_interlude";
			$iconsTable = "icp_icons_interlude";
		}elseif(in_array($config["CHRONICLE_ID"],array(8,9,10,11,12))){
			$npcsTable = "icp_npc_gracia";
			$iconsTable = "icp_icons_gracia";
		}elseif($config["CHRONICLE_ID"] == 13){
			$npcsTable = "icp_npc_freya";
			$iconsTable = "icp_icons_freya";
		}elseif($config["CHRONICLE_ID"] == 14){
			$npcsTable = "icp_npc_high_five";
			$iconsTable = "icp_icons_high_five";
		}elseif(in_array($config["CHRONICLE_ID"],array(15,16,17,18,19,20,21,22,23,24,25,26,27,28,29))){
			$npcsTable = "icp_npc_god";
			$iconsTable = "icp_icons_god";
		}elseif(in_array($config["CHRONICLE_ID"],array(30,31,32,33,34))){
			$npcsTable = "icp_npc_classic";
			$iconsTable = "icp_icons_classic";
		}elseif(in_array($config["CHRONICLE_ID"],array(35,36,37))){
			$npcsTable = "icp_npc_essence";
			$iconsTable = "icp_icons_essence";
		}else{
			$npcsTable = "icp_npc_interlude";
			$iconsTable = "icp_icons_interlude";
		}
		$searchInformer = $gameServer->prepare("SELECT itemId AS id, itemName AS name, 'Item' as type FROM ".$iconsTable." WHERE itemName LIKE CONCAT('%',?,'%') UNION SELECT id, name, 'NPC' as type FROM ".$npcsTable." WHERE name LIKE CONCAT('%',?,'%') ORDER BY type DESC, name ASC", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
		$searchInformer-> execute([$_GET["term"],$_GET["term"]]);
		if($searchInformer->rowCount() > 0){
			echo "{\"informer\":{";
			$x=1;
			while ($row = $searchInformer->fetchObject()) {
				$virgula = $x == $searchInformer->rowCount() ? null : ",";
				echo "\"".$row->type." - ".$row->name."\":\"".$row->name."\"".$virgula;
				$x++;
			}
			echo "}}";
		}else{
			echo "{\"informer\":{}}";
		}
	}
	if($_GET["json"] == "accounts"){
		$table = $db_type ? "accounts" : "user_auth";
		$colLogin = $db_type ? "login" : "account";
		$searchAccounts = $loginServer->prepare("SELECT ".$colLogin." FROM ".$table." WHERE ".$colLogin." LIKE CONCAT(?,'%') ORDER BY ".$colLogin." ASC", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
		$searchAccounts-> execute([$_GET["term"]]);
		if($searchAccounts->rowCount() > 0){
			echo "{\"accounts\":{";
			$x=1;
			while ($row = $searchAccounts->fetchObject()) {
				$virgula = $x == $searchAccounts->rowCount() ? null : ",";
				echo "\"".$row->{$colLogin}."\":\"".$row->{$colLogin}."\"".$virgula;
				$x++;
			}
			echo "}}";
		}else{
			echo "{\"accounts\":{}}";
		}
	}
	exit;
}