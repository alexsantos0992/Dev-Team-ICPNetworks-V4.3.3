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
require_once("engine/classes/ICP_Panel.php");
$getPanelInfo = new ICPNetworks\ICP_Panel($db_type, $loginServer, $gameServer, $config);
if($pagina == "activate"){
	if(isset($_GET["acc"]) && !empty($_GET["acc"])){
		if($getPanelInfo->activateAcc($_GET["acc"])){
			$getPanelInfo->resposta("Account successfully activated!<br>You can now login","Success!","success","?icp=panel");
		}else{
			$getPanelInfo->resposta("Error trying to activate account!<br>Check if your account has not already been activated.<br>If you have any problems accessing your account, please contact us.","Oops...","error","?icp=panel");
		}
	}else{
		$getPanelInfo->resposta("An error has occurred!","Oops...","error","?icp=panel");
	}
	exit();
}
if(isset($_SESSION["ICP_UserName"]) && !empty($_SESSION["ICP_UserName"])){
	$badgesHtml = "<span class=\"bg-secondary\" style=\"position:absolute; top:8px; right:7px; color:#fff; font-size:11px; border:1px solid #666; border-radius:3px; padding:1px 4px;\">MSGS_COUNT</span>";
	if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_MENUS",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] > 5){
			if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_MENU_TITLE"))
				$tpl->BLOCK_ICP_PANEL_ADMIN_MENU_TITLE = "<li class=\"nav-item border-bottom border-top menu-title\"><span class=\"nav-link\" href=\"#\">Admin panel</span></li>";
			if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS",true)){
				$accessLevels = array(array(6,"Profile","?icp=panel&show=adm-profile","icp-panel-adm-profile","camera"),array(8,"News","?icp=panel&show=adm-news","icp-panel-adm-news","globe"),array(6,"Messages","?icp=panel&show=adm-messages","icp-panel-adm-messages","message-square"),array(7,"Screenshots","?icp=panel&show=adm-screenshots","icp-panel-adm-screenshots","image"),array(7,"Videos","?icp=panel&show=adm-videos","icp-panel-adm-videos","video"),array(9,"Prime Shop","?icp=panel&show=adm-prime-shop","icp-panel-adm-prime-shop","shopping-cart"),array(9,"Send donations","?icp=panel&show=adm-donation","icp-panel-adm-donation","send"),array(10,"Give privileges","?icp=panel&show=adm-privileges","icp-panel-adm-privileges","user-plus"),array(10,"Configs","?icp=panel&show=adm-configs","icp-panel-adm-configs","settings"));
				foreach($accessLevels as $key => $val){
					if($_SESSION["ICP_UserAccessLevel"] >= $val[0]){
						$badges = null;
						if($val[1] == "Messages" || $val[1] == "News" || $val[1] == "Screenshots" || $val[1] == "Videos" || $val[1] == "Prime Shop"){
							if($val[1] == "Screenshots" && $config["enable_screenshots"]){
								$ssNum = count($getPanelInfo->showScreenshots(0,"id DESC",0,false));
								$badges = $ssNum > 0 ? str_replace("MSGS_COUNT",$ssNum,$badgesHtml) : null;
								if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
									$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
							}elseif($val[1] == "Videos" && $config["enable_videos"]){
								$vidNum = count($getPanelInfo->showVideos(0,"id DESC",0,false));
								$badges = $vidNum > 0 ? str_replace("MSGS_COUNT",$vidNum,$badgesHtml) : null;
								if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
									$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
							}elseif($val[1] == "Prime Shop" && $config["ENABLE_PRIME_SHOP"]){
								if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
									$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
							}elseif($val[1] == "News" && $config["enable_news"]){
								if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
									$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
							}elseif($val[1] == "Messages" && $config["enable_messages"]){
								$msgsNum = $getPanelInfo->getNumMessages();
								$badges = $msgsNum > 0 ? str_replace("MSGS_COUNT",$msgsNum,$badgesHtml) : null;
								if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
									$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_ADMIN_LINKS"))
								$tpl->BLOCK_ICP_PANEL_ADMIN_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$val[3]."\" aria-current=\"page\" href=\"".$val[2]."\"><span data-feather=\"".$val[4]."\"></span>".$val[1]."</a></li>";
							$tpl->block("BLOCK_ICP_PANEL_ADMIN_LINKS");
						}
					}
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_ADMIN_MENUS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_USERS_MENUS",true)){
		$menuLinks = array(array("User panel",array(array("Home page","?icp=panel","icp-panel-home","home",""),array("My account","?icp=panel&show=accounts","icp-panel-accounts","user",""),array("My Screenshots","?icp=panel&show=my-screenshots","icp-panel-my-screenshots","image","enable_screenshots"),array("My Videos","?icp=panel&show=my-videos","icp-panel-my-videos","video","enable_videos"),array("Rewards","?icp=panel&show=rewards","icp-panel-rewards","gift","ENABLE_REWARD_SYSTEM"))),array("Donations",array(array("Make a donation","?icp=panel&show=donation","icp-panel-donation","dollar-sign",""),array("Donation history","?icp=panel&show=donation-history","icp-panel-donation-history","search",""))),array("Shops",array(array("Prime shop","?icp=panel&show=prime-shop","icp-panel-prime-shop","shopping-cart","ENABLE_PRIME_SHOP"),array("Item broker","?icp=panel&show=item-broker","icp-panel-item-broker","shopping-cart","ENABLE_ITEM_BROKER"),array("Character broker","?icp=panel&show=character-broker","icp-panel-character-broker","shopping-cart","ENABLE_CHARACTER_BROKER"))),array("Services",array(array("Safe enchant","?icp=panel&show=enchantment","icp-panel-enchantment","shield","ENABLE_SAFE_ENCHANT_SYSTEM"),array("Character changes","?icp=panel&show=character-changes","icp-panel-character-changes","shuffle","enable_character_changes"))),array("Support",array(array("Messages","?icp=panel&show=messages","icp-panel-messages","message-square","enable_messages"),array("Contact us","?icp=panel&show=contact","icp-panel-contact","mail",""))));
		for($m=0;$m<count($menuLinks);$m++){
			$resultsChecked = 0;
			$resultsTrue = 0;
			$total = count($menuLinks[$m][1]);
			for($ml=0;$ml<$total;$ml++){
				if(!empty($menuLinks[$m][1][$ml][4])){
					if($config["{$menuLinks[$m][1][$ml][4]}"])
						$resultsTrue++;
					$resultsChecked++;
				}
			}
			if($total - ($resultsChecked - $resultsTrue) > 0){
				for($ml2=0;$ml2<=$total;$ml2++){
					if($ml2 == 0){
						if($tpl->exists("BLOCK_ICP_PANEL_USER_LINKS"))
							$tpl->BLOCK_ICP_PANEL_USER_LINKS = "<li class=\"nav-item position-relative border-bottom border-top menu-title\"><span class=\"nav-link\" href=\"#\">".$menuLinks[$m][0]."</span></li>";
						$tpl->block("BLOCK_ICP_PANEL_USERS_MENUS");
					}else{
						if(!empty($menuLinks[$m][1][($ml2-1)][4])){
							if($config["{$menuLinks[$m][1][($ml2-1)][4]}"]){
								if($tpl->exists("BLOCK_ICP_PANEL_USER_LINKS"))
									$tpl->BLOCK_ICP_PANEL_USER_LINKS = "<li class=\"nav-item position-relative\"><a class=\"nav-link\" id=\"".$menuLinks[$m][1][($ml2-1)][2]."\" aria-current=\"page\" href=\"".$menuLinks[$m][1][($ml2-1)][1]."\"><span data-feather=\"".$menuLinks[$m][1][($ml2-1)][3]."\"></span>".$menuLinks[$m][1][($ml2-1)][0]."</a></li>";
								$tpl->block("BLOCK_ICP_PANEL_USERS_MENUS");
							}
						}else{
							$badges = null;
							if($menuLinks[$m][1][($ml2-1)][0] == "Messages"){
								$msgsNum = $getPanelInfo->getNumMessages($_SESSION["ICP_UserName"]);
								$badges = $msgsNum > 0 ? str_replace("MSGS_COUNT",$msgsNum,$badgesHtml) : null;
							}
							if($tpl->exists("BLOCK_ICP_PANEL_USER_LINKS"))
								$tpl->BLOCK_ICP_PANEL_USER_LINKS = "<li class=\"nav-item position-relative\">".$badges."<a class=\"nav-link\" id=\"".$menuLinks[$m][1][($ml2-1)][2]."\" aria-current=\"page\" href=\"".$menuLinks[$m][1][($ml2-1)][1]."\"><span data-feather=\"".$menuLinks[$m][1][($ml2-1)][3]."\"></span>".$menuLinks[$m][1][($ml2-1)][0]."</a></li>";
							$tpl->block("BLOCK_ICP_PANEL_USERS_MENUS");
						}
					}
				}
			}
		}
	}
	if($tpl->exists("BLOCK_DONATE_BALANCE",true)){
		if($tpl->exists("PANEL_DONATE_BALANCE"))
			$tpl->PANEL_DONATE_BALANCE = $getPanelInfo->donateBalance($_SESSION["ICP_UserName"]);
		$tpl->block("BLOCK_DONATE_BALANCE");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ACCOUNT_VIP",true)){
		if($tpl->exists("ACCOUNT_VIP_STATUS"))
			$tpl->ACCOUNT_VIP_STATUS = $_SESSION["ICP_UserVipEnd"] != "Disabled" ? strtotime($_SESSION["ICP_UserVipEnd"]) > time() ? $_SESSION["ICP_UserVip"] : "Disabled" : "Disabled";
		if($tpl->exists("ACCOUNT_VIP_END"))
			$tpl->ACCOUNT_VIP_END = $_SESSION["ICP_UserVipEnd"] != "Disabled" ? strtotime($_SESSION["ICP_UserVipEnd"]) > time() ? $_SESSION["ICP_UserVipEnd"] : "Disabled" : "Disabled";
		$tpl->block("BLOCK_ICP_PANEL_ACCOUNT_VIP");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ACCOUNT_DETAILS",true)){
		foreach($getPanelInfo->accDetails($_SESSION["ICP_UserName"]) as $rank){
			if($tpl->exists("ACCOUNT_TOTAL_CHARACTERS"))
				$tpl->ACCOUNT_TOTAL_CHARACTERS = $rank["totalChars"];
			if($config["enable_screenshots"]){
				if($tpl->exists("ACCOUNT_TOTAL_SCREENSHOTS"))
					$tpl->ACCOUNT_TOTAL_SCREENSHOTS = "Uploaded ScreenShots: ".$rank["totalScreenshots"]."<br>";
			}
			if($config["enable_videos"]){
				if($tpl->exists("ACCOUNT_TOTAL_VIDEOS"))
					$tpl->ACCOUNT_TOTAL_VIDEOS = "Uploaded Videos: ".$rank["totalVideos"];
			}
			$tpl->block("BLOCK_ICP_PANEL_ACCOUNT_DETAILS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_DONATE_DETAILS",true)){
		foreach($getPanelInfo->donateDetails($_SESSION["ICP_UserName"]) as $rank){
			if($tpl->exists("DONATE_TOTAL_VALUE"))
				$tpl->DONATE_TOTAL_VALUE = $rank["totalDonate"];
			if($tpl->exists("DONATE_TOTAL_COINS"))
				$tpl->DONATE_TOTAL_COINS = $rank["totalCoins"];
			if($tpl->exists("DONATE_USED_COINS"))
				$tpl->DONATE_USED_COINS = $rank["totalUsed"];
			if($tpl->exists("DONATE_BALANCE_COINS"))
				$tpl->DONATE_BALANCE_COINS = $rank["totalBalance"];
			if($tpl->exists("DONATE_CURRENCY"))
				$tpl->DONATE_CURRENCY = $rank["currency"];
			$tpl->block("BLOCK_ICP_PANEL_DONATE_DETAILS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_DONATE_HISTORY",true)){
		$donateHistory = $getPanelInfo->donateHistory($_SESSION["ICP_UserName"]);
		$totalDonated = 0.00;
		if(count($donateHistory) > 0){
			foreach($donateHistory as $rank){
				if($tpl->exists("PANEL_DONATE_CURRENCY"))
					$tpl->PANEL_DONATE_CURRENCY = $rank["currency"];
				if($tpl->exists("PANEL_DONATE_STATUS"))
					$tpl->PANEL_DONATE_STATUS = $rank["status"];
				if($tpl->exists("PANEL_DONATE_METHOD"))
					$tpl->PANEL_DONATE_METHOD = $rank["method"];
				if($tpl->exists("PANEL_DONATE_AMOUNT"))
					$tpl->PANEL_DONATE_AMOUNT = $rank["amount"];
				if($tpl->exists("PANEL_DONATE_VALUE"))
					$tpl->PANEL_DONATE_VALUE = $rank["price"];
				if($tpl->exists("PANEL_DONATE_DATE"))
					$tpl->PANEL_DONATE_DATE = $rank["date"];
				$tpl->block("BLOCK_ICP_PANEL_DONATE_HISTORY");
				$totalDonated += $rank["status"] == "Completed" || $rank["status"] == "Aprovado" ? $rank["price"] : 0;
			}
			if($tpl->exists("PANEL_TOTAL_DONATED"))
				$tpl->PANEL_TOTAL_DONATED = number_format($totalDonated, 2);
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_DONATE_HISTORY_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_DONATE_HISTORY_NULL");
			if($tpl->exists("PANEL_TOTAL_DONATED"))
				$tpl->PANEL_TOTAL_DONATED = number_format($totalDonated, 2);
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_DONATE_LOG",true)){
		$donateLog = $getPanelInfo->donateLog($_SESSION["ICP_UserName"]);
		if(count($donateLog) > 0){
			foreach($donateLog as $rank){
				if($tpl->exists("PANEL_DONATE_DATE"))
					$tpl->PANEL_DONATE_DATE = $rank["date"];
				if($tpl->exists("PANEL_DONATE_DESCRIPTION"))
					$tpl->PANEL_DONATE_DESCRIPTION = $rank["description"];
				if($tpl->exists("PANEL_DONATE_COST"))
					$tpl->PANEL_DONATE_COST = $rank["cost"];
				$tpl->block("BLOCK_ICP_PANEL_DONATE_LOG");
				$totalDonated += $rank["cost"];
			}
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_DONATE_LOG_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_DONATE_LOG_NULL");
		}
		if($tpl->exists("PANEL_TOTAL_BALANCE"))
			$tpl->PANEL_TOTAL_BALANCE = $getPanelInfo->donateBalance($_SESSION["ICP_UserName"]);
	}
	if($tpl->exists("BLOCK_ICP_PANEL_LOG_IP",true)){
		foreach($getPanelInfo->logIP($_SESSION["ICP_UserName"]) as $rank){
			if($tpl->exists("LOG_IP_DATE"))
				$tpl->LOG_IP_DATE = $rank["logIpDate"];
			if($tpl->exists("LOG_IP_NUMBER"))
				$tpl->LOG_IP_NUMBER = $rank["logIpNumber"];
			$tpl->block("BLOCK_ICP_PANEL_LOG_IP");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CHARACTER_INFO",true)){
		$charStatus = $getPanelInfo->charStatus($_SESSION["ICP_UserName"]);
		if(count($charStatus) > 0){
			foreach($charStatus as $rank){
				if($tpl->exists("CHARACTER_INFO_BASE_CLASS"))
					$tpl->CHARACTER_INFO_BASE_CLASS = $rank["baseClass"];
				if($tpl->exists("CHARACTER_INFO_SUB_CLASS"))
					$tpl->CHARACTER_INFO_SUB_CLASS = $rank["subClass"];
				if($tpl->exists("CHARACTER_INFO_NOBLESS"))
					$tpl->CHARACTER_INFO_NOBLESS = $rank["nobles"];
				if($tpl->exists("CHARACTER_INFO_HERO"))
					$tpl->CHARACTER_INFO_HERO = $rank["hero"];
				if($tpl->exists("CHARACTER_INFO_KARMA"))
					$tpl->CHARACTER_INFO_KARMA = $rank["karma"];
				if($tpl->exists("CHARACTER_INFO_LEVEL"))
					$tpl->CHARACTER_INFO_LEVEL = $rank["baseLevel"];
				if($tpl->exists("CHARACTER_INFO_SEX"))
					$tpl->CHARACTER_INFO_SEX = $rank["sex"];
				if($tpl->exists("CHARACTER_INFO_ONLINE_TIME"))
					$tpl->CHARACTER_INFO_ONLINE_TIME = $rank["onlineTime"];
				if($tpl->exists("CHARACTER_INFO_LAST_ACCESS"))
					$tpl->CHARACTER_INFO_LAST_ACCESS = $rank["lastAccess"];
				if($tpl->exists("CHARACTER_INFO_CREST_CLAN"))
					$tpl->CHARACTER_INFO_CREST_CLAN = $rank["crest"];
				if($tpl->exists("CHARACTER_INFO_CLAN"))
					$tpl->CHARACTER_INFO_CLAN = $rank["clan"];
				if($tpl->exists("CHARACTER_INFO_ALLYANCE"))
					$tpl->CHARACTER_INFO_ALLYANCE = $rank["allyance"];
				if($tpl->exists("CHARACTER_INFO_PVP"))
					$tpl->CHARACTER_INFO_PVP = $rank["pvp"];
				if($tpl->exists("CHARACTER_INFO_PK"))
					$tpl->CHARACTER_INFO_PK = $rank["pk"];
				if($tpl->exists("CHARACTER_INFO_LOC"))
					$tpl->CHARACTER_INFO_LOC = $rank["loc"];
				if($tpl->exists("CHARACTER_INFO_ID"))
					$tpl->CHARACTER_INFO_ID = $rank["char_id"];
				if($tpl->exists("CHARACTER_INFO_NAME"))
					$tpl->CHARACTER_INFO_NAME = $rank["char_name"];
				if($tpl->exists("CHARACTER_INFO_PROFILE"))
					$tpl->CHARACTER_INFO_PROFILE = $rank["char_image"];
				if($config["ENABLE_CHARACTER_BROKER"]){
					if($tpl->exists("PANEL_CHAR_CAN_SELL")){
						if($rank["baseLevel"] >= $config["MIN_CHARACTER_BROKER_LEVEL"]){
							if(!$rank["char_inStore"])
								$tpl->PANEL_CHAR_CAN_SELL = "<td style=\"border-bottom:0px;\"><a href=\"#\" class=\"btn btn-primary btn-sm modal-alert w-100\" style=\"margin:5px 5px 0px 5px;\" data-toggle=\"modal\" data-target=\"#modal-alert\" description=\"<p>Are you sure you want to put your character for sale?</p>".($config["ALLOW_AUCTION_CHARACTER_BROKER"] ? "<p>You can sell your characters in two ways:</p><ul><li>Quick sale, sell now! By fixed price!</li><li>Slow sale, ".$config["AUCTION_CHARACTER_BROKER_DAYS"]." day(s) in auction!</li></ul>" : null)."<p>Read the rules:</p><ul><li>You cannot sell a character if he is selling in the Item Broker.</li>".($config["ALLOW_AUCTION_CHARACTER_BROKER"] ? "<li>Auction sales can only be canceled if there is no bid yet.</li>" : null)."<li>The character must be offline.</li></ul><p>If you are in compliance with the rules, choose below the price and select the option for sale.</p><form action='' method='post' id='sellChar'><input type='hidden' name='char_id' value='".$rank["char_id"]."'><div class='row'><div class='col'><input class='form-control form-control-sm' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57' name='saleValue' type='number' placeholder='Price in {DONATE_COIN_NAME}' min='1' max='1999' required></div><div class='col'><select class='form-select form-select-sm' name='saleType' required><option value='1'>Normal sale</option>".($config["ALLOW_AUCTION_CHARACTER_BROKER"] ? "<option value='2'>Auction sale</option>" : null)."</select></div></div></form>\" footer='<button name=\"putCharForSale\" class=\"btn btn-primary\" type=\"submit\" form=\"sellChar\">Yes, put it on sale!</button>'><span data-feather=\"tag\"></span> Sell ".$rank["char_name"]."</a></td>";
							else
								$tpl->PANEL_CHAR_CAN_SELL = "<td style=\"border-bottom:0px;\"><a href=\"?icp=panel&show=character-broker\" class=\"btn btn-primary btn-sm w-100\" style=\"margin:5px 5px 0px 5px;\"><span data-feather=\"tag\"></span> For sale</a></td>";
						}else{
							$tpl->PANEL_CHAR_CAN_SELL = "";
						}
					}
				}
				$tpl->block("BLOCK_ICP_PANEL_CHARACTER_INFO");
			}
		}
	}
	$allowedBlockItems = array("BLOCK_ICP_PANEL_EQUIPED_ITEMS","BLOCK_ICP_PANEL_INVENTORY_ITEMS","BLOCK_ICP_PANEL_WAREHOUSE_ITEMS","BLOCK_ICP_PANEL_ENCHANT_EQUIPED_ITEMS","BLOCK_ICP_PANEL_ENCHANT_INVENTORY_ITEMS","BLOCK_ICP_PANEL_ENCHANT_WAREHOUSE_ITEMS");
	for($x=0;$x<count($model);$x++){
		if(in_array($model[$x],$allowedBlockItems)){
			if($tpl->exists($model[$x],true)){
				$itemShow = $getPanelInfo->showCharacterItems($model[$x] != $allowedBlockItems[0] && $model[$x] != $allowedBlockItems[3] ? $model[$x] == $allowedBlockItems[1] || $model[$x] == $allowedBlockItems[4] ? "INVENTORY" : "WAREHOUSE" : "PAPERDOLL",$_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],$model[$x] == $allowedBlockItems[3] || $model[$x] == $allowedBlockItems[4] || $model[$x] == $allowedBlockItems[5] ? true : false,$config["ALLOW_ENCHANT_PVP_ITEMS"]);
				if(count($itemShow) > 0){
					if($config["ENABLE_ITEM_BROKER"] && ($model[$x] == "BLOCK_ICP_PANEL_EQUIPED_ITEMS" || $model[$x] == "BLOCK_ICP_PANEL_INVENTORY_ITEMS" || $model[$x] == "BLOCK_ICP_PANEL_WAREHOUSE_ITEMS")){
						if($tpl->exists("PANEL_ITEM_FORM_SELL"))
							$tpl->PANEL_ITEM_FORM_SELL = "<form action='' method='post' style='margin:0px;padding:0px;display:none;' id='itemSale'></form><div class='col-sm-3 p-2 pt-1'><input class='form-control w-100' form='itemSale' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57' name='saleValue' type='number' placeholder='Price in {DONATE_COIN_NAME}' min='1' max='1999' required></div><div class='col-sm-3 p-2 pt-1'><select class='form-select w-100' form='itemSale' name='saleType' required><option value='0'>Normal sale</option>".($config["ALLOW_AUCTION_ITEM_BROKER"] ? "<option value='1'>Auction sale</option>" : null)."</select></div><div class='col-sm-3 p-2 pt-1'><input type='hidden' name='char_id' form='itemSale' value='".$_GET["char_id"]."'><input type='submit' name='putItemsForSale' form='itemSale' class='btn btn-primary w-100' value='Sell selected item(s)'></div>";
						if($tpl->exists("PANEL_ITEM_TITLE_SELL"))
							$tpl->PANEL_ITEM_TITLE_SELL = "Sell";
					}
					foreach($itemShow as $rank){
						if($tpl->exists("PANEL_ITEM_IMG"))
							$tpl->PANEL_ITEM_IMG = $rank["itemImg"];
						if($tpl->exists("PANEL_ITEM_NAME"))
							$tpl->PANEL_ITEM_NAME = $rank["itemName"];
						if($tpl->exists("PANEL_ITEM_ENCHANT"))
							$tpl->PANEL_ITEM_ENCHANT = $rank["itemEnchant"];
						if($tpl->exists("PANEL_ITEM_CAN_SELL"))
							$tpl->PANEL_ITEM_CAN_SELL = $rank["buttonSell"];
						if($tpl->exists("PANEL_ITEM_CAN_ENCHANT"))
							$tpl->PANEL_ITEM_CAN_ENCHANT = $rank["buttonEnchant"];
						if($tpl->exists("PANEL_ITEM_OWNER_ID"))
							$tpl->PANEL_ITEM_OWNER_ID = $rank["itemOwnerId"];
						if($tpl->exists("PANEL_ITEM_OWNER_NAME"))
							$tpl->PANEL_ITEM_OWNER_NAME = $rank["itemOwnerName"];
						if($tpl->exists("PANEL_ITEM_DETAILS"))
							$tpl->PANEL_ITEM_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
						$tpl->block($model[$x]);
					}
				}else{
					if($tpl->exists($model[$x]."_NULL",true))
						$tpl->block($model[$x]."_NULL");
				}
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_QUESTS",true)){
		$charQuests = $getPanelInfo->showCharQuests($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"]);
		if(count($charQuests) > 0){
			foreach($charQuests as $rank){
				if($tpl->exists("PANEL_QUEST_OWNER_NAME"))
					$tpl->PANEL_QUEST_OWNER_NAME = $rank["questOwner"];
				if($tpl->exists("PANEL_QUEST_NAME"))
					$tpl->PANEL_QUEST_NAME = $rank["questName"];
				if($tpl->exists("PANEL_QUEST_STAGE"))
					$tpl->PANEL_QUEST_STAGE = $rank["questValue"];
				$tpl->block("BLOCK_ICP_PANEL_QUESTS");
			}
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_QUESTS_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_QUESTS_NULL");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_SKILLS_CLASS",true)){
		$charSkillsClass = $getPanelInfo->showSubClasses($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"]);
		if(!empty($charSkillsClass)){
			$charSkillsClassArr = explode(";",$charSkillsClass);
			if(count($charSkillsClassArr) > 0){
				$btn = null;
				for($x=0;$x<(count($charSkillsClassArr)-1);$x++){
					$btn .= "<div class='col p-2 pt-1'><button class=\"btn btn-primary w-100 subclassLink\" onclick=\"openMenuSubclass(event, '".$getPanelInfo->classe_name($charSkillsClassArr[$x])."');return false;\"".($x == 0 ? " id=\"baseClass\"" : null).">".$getPanelInfo->classe_name($charSkillsClassArr[$x])."</button></div>";
					if($tpl->exists("PANEL_SKILLS_CLASS"))
						$tpl->PANEL_SKILLS_CLASS = $getPanelInfo->classe_name($charSkillsClassArr[$x]);
					if($tpl->exists("PANEL_SKILLS_BUTTONS"))
						$tpl->PANEL_SKILLS_BUTTONS = "<div class='col p-2 pt-1'><button class=\"btn btn-primary w-100\" onclick=\"openMenuSubclass(event, '".$getPanelInfo->classe_name($charSkillsClassArr[$x])."');\"".($x == 0 ? " id=\"baseClass\"" : null).">".$getPanelInfo->classe_name($charSkillsClassArr[$x])."</button></div>";
					if($tpl->exists("BLOCK_ICP_PANEL_SKILLS",true)){
						if(isset($config["class_index"]) && $config["class_index"]){
							$charSkills = $getPanelInfo->showCharSkills($charSkillsClassArr[$x],$_GET["char_id"] ?? "",$_SESSION["ICP_UserName"]);
						}else{
							$charSkills = $getPanelInfo->showCharSkills($x,$_GET["char_id"] ?? "",$_SESSION["ICP_UserName"]);
						}
						if(count($charSkills) > 0){
							foreach($charSkills as $rank){
								if($tpl->exists("PANEL_SKILLS_OWNER_NAME"))
									$tpl->PANEL_SKILLS_OWNER_NAME = $rank["skillOwner"];
								if($tpl->exists("PANEL_SKILLS_IMG"))
									$tpl->PANEL_SKILLS_IMG = $rank["skillImg"];
								if($tpl->exists("PANEL_SKILLS_DETAILS"))
									$tpl->PANEL_SKILLS_DETAILS = $rank["skillDetails"];
								$tpl->block("BLOCK_ICP_PANEL_SKILLS");
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_SKILLS_NULL",true))
								$tpl->block("BLOCK_ICP_PANEL_SKILLS_NULL");
						}
					}
					$tpl->block("BLOCK_ICP_PANEL_SKILLS_CLASS");
				}
				if($tpl->exists("PANEL_SKILLS_BUTTONS"))
					$tpl->PANEL_SKILLS_BUTTONS = $btn;
			}
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_SKILLS_CLASS_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_SKILLS_CLASS_NULL");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CHARACTER_LIST",true)){
		$charList = $getPanelInfo->myCharList($_SESSION["ICP_UserName"]);
		if(count($charList) > 0){
			foreach($charList as $rank){
				if($tpl->exists("PANEL_CHARACTER_LIST_NAME"))
					$tpl->PANEL_CHARACTER_LIST_NAME = $rank["charName"];
				if($tpl->exists("PANEL_CHARACTER_LIST_ID"))
					$tpl->PANEL_CHARACTER_LIST_ID = $rank["charId"];
				if($tpl->exists("PANEL_CHARACTER_LIST_ONLINE"))
					$tpl->PANEL_CHARACTER_LIST_ONLINE = $rank["charOnline"] == 1 ? " disabled><em>Online -> </em" : "";
				if($tpl->exists("BLOCK_ICP_PANEL_CHARACTER_LIST_DETAILS",true))
					$tpl->block("BLOCK_ICP_PANEL_CHARACTER_LIST_DETAILS");
			}
			$tpl->block("BLOCK_ICP_PANEL_CHARACTER_LIST");
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_CHARACTER_LIST_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_CHARACTER_LIST_NULL");
		}
	}
	if($tpl->exists("BLOCK_GALLERY_MY_SCREENSHOTS",true)){
		if($config["enable_screenshots"]){
			$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
			$reg_inicial = $pag * $config["MAX_SCREENSHOTS_GALLERY"];
			$reg_atual = $reg_inicial + 1;
			$reg_final = $reg_inicial + $config["MAX_SCREENSHOTS_GALLERY"];
			$sql_qtd_reg = $getPanelInfo->showMyScreenshots(1,"id DESC",0,$_SESSION["ICP_UserName"]);
			$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
			$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
			$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_SCREENSHOTS_GALLERY"]);
			if($tpl->exists("BLOCK_PAGINATION",true)){
				if($tpl->exists("PAGINATION"))
					$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
				$tpl->block("BLOCK_PAGINATION");
			}
			if($tpl->exists("INDEX_SCREENSHOTS_TOTAL"))
				$tpl->INDEX_SCREENSHOTS_TOTAL = $qtd_total_reg;
			if($tpl->exists("INDEX_SCREENSHOTS_START"))
				$tpl->INDEX_SCREENSHOTS_START = empty($reg_final) ? 0 : $reg_atual;
			if($tpl->exists("INDEX_SCREENSHOTS_STOP"))
				$tpl->INDEX_SCREENSHOTS_STOP = $reg_final;
			foreach($getPanelInfo->showMyScreenshots(1,"id DESC",$reg_inicial.", ".$config["MAX_SCREENSHOTS_GALLERY"],$_SESSION["ICP_UserName"]) as $rank){
				if($tpl->exists("INDEX_SCREENSHOTS_IMG"))
					$tpl->INDEX_SCREENSHOTS_IMG = $rank["screenshotImg"];
				if($tpl->exists("INDEX_SCREENSHOTS_AUTHOR"))
					$tpl->INDEX_SCREENSHOTS_AUTHOR = $rank["screenshotAuthor"];
				if($tpl->exists("INDEX_SCREENSHOTS_LEGEND"))
					$tpl->INDEX_SCREENSHOTS_LEGEND = $rank["screenshotLegend"];
				if($tpl->exists("INDEX_SCREENSHOTS_DATE"))
					$tpl->INDEX_SCREENSHOTS_DATE = $rank["screenshotDate"];
				$tpl->block("BLOCK_GALLERY_MY_SCREENSHOTS");
			}
		}
	}
	if($tpl->exists("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING",true)){
		if($config["enable_screenshots"]){
			$screenshots = $getPanelInfo->showMyScreenshots(0,"id DESC",0,$_SESSION["ICP_UserName"]);
			if(count($screenshots) > 0){
				foreach($screenshots as $rank){
					if($tpl->exists("INDEX_SCREENSHOTS_IMG"))
						$tpl->INDEX_SCREENSHOTS_IMG = $rank["screenshotImg"];
					if($tpl->exists("INDEX_SCREENSHOTS_AUTHOR"))
						$tpl->INDEX_SCREENSHOTS_AUTHOR = $rank["screenshotAuthor"];
					if($tpl->exists("INDEX_SCREENSHOTS_LEGEND"))
						$tpl->INDEX_SCREENSHOTS_LEGEND = $rank["screenshotLegend"];
					if($tpl->exists("INDEX_SCREENSHOTS_DATE"))
						$tpl->INDEX_SCREENSHOTS_DATE = $rank["screenshotDate"];
					if($tpl->exists("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING_LIST",true))
						$tpl->block("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING_LIST");
				}
				$tpl->block("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING");
			}else{
				if($tpl->exists("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING_NULL",true))
					$tpl->block("BLOCK_GALLERY_MY_SCREENSHOTS_WAITING_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_GALLERY_MY_VIDEOS",true)){
		if($config["enable_videos"]){
			$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
			$reg_inicial = $pag * $config["MAX_VIDEOS_GALLERY"];
			$reg_atual = $reg_inicial + 1;
			$reg_final = $reg_inicial + $config["MAX_VIDEOS_GALLERY"];
			$sql_qtd_reg = $getPanelInfo->showMyVideos(1,"id DESC",0,$_SESSION["ICP_UserName"]);
			$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
			$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
			$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_VIDEOS_GALLERY"]);
			if($tpl->exists("BLOCK_PAGINATION",true)){
				if($tpl->exists("PAGINATION"))
					$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
				$tpl->block("BLOCK_PAGINATION");
			}
			if($tpl->exists("INDEX_VIDEOS_TOTAL"))
				$tpl->INDEX_VIDEOS_TOTAL = $qtd_total_reg;
			if($tpl->exists("INDEX_VIDEOS_START"))
				$tpl->INDEX_VIDEOS_START = empty($reg_final) ? 0 : $reg_atual;
			if($tpl->exists("INDEX_VIDEOS_STOP"))
				$tpl->INDEX_VIDEOS_STOP = $reg_final;
			foreach($getPanelInfo->showMyVideos(1,"id DESC",$reg_inicial.", ".$config["MAX_VIDEOS_GALLERY"],$_SESSION["ICP_UserName"]) as $rank){
				if($tpl->exists("INDEX_VIDEOS_IMG"))
					$tpl->INDEX_VIDEOS_IMG = $rank["videoImg"];
				if($tpl->exists("INDEX_VIDEOS_AUTHOR"))
					$tpl->INDEX_VIDEOS_AUTHOR = $rank["videoAuthor"];
				if($tpl->exists("INDEX_VIDEOS_LEGEND"))
					$tpl->INDEX_VIDEOS_LEGEND = $rank["videoLegend"];
				if($tpl->exists("INDEX_VIDEOS_DATE"))
					$tpl->INDEX_VIDEOS_DATE = $rank["videoDate"];
				if($tpl->exists("INDEX_VIDEOS_URL"))
					$tpl->INDEX_VIDEOS_URL = $rank["videosUrl"];
				$tpl->block("BLOCK_GALLERY_MY_VIDEOS");
			}
		}
	}
	if($tpl->exists("BLOCK_GALLERY_MY_VIDEOS_WAITING",true)){
		if($config["enable_videos"]){
			$videos = $getPanelInfo->showMyVideos(0,"id DESC",0,$_SESSION["ICP_UserName"]);
			if(count($videos) > 0){
				foreach($videos as $rank){
					if($tpl->exists("INDEX_VIDEOS_IMG"))
						$tpl->INDEX_VIDEOS_IMG = $rank["videoImg"];
					if($tpl->exists("INDEX_VIDEOS_AUTHOR"))
						$tpl->INDEX_VIDEOS_AUTHOR = $rank["videoAuthor"];
					if($tpl->exists("INDEX_VIDEOS_LEGEND"))
						$tpl->INDEX_VIDEOS_LEGEND = $rank["videoLegend"];
					if($tpl->exists("INDEX_VIDEOS_DATE"))
						$tpl->INDEX_VIDEOS_DATE = $rank["videoDate"];
					if($tpl->exists("INDEX_VIDEOS_URL"))
						$tpl->INDEX_VIDEOS_URL = $rank["videosUrl"];
					if($tpl->exists("BLOCK_GALLERY_MY_VIDEOS_WAITING_LIST",true))
						$tpl->block("BLOCK_GALLERY_MY_VIDEOS_WAITING_LIST");
				}
				$tpl->block("BLOCK_GALLERY_MY_VIDEOS_WAITING");
			}else{
				if($tpl->exists("BLOCK_GALLERY_MY_VIDEOS_WAITING_NULL",true))
					$tpl->block("BLOCK_GALLERY_MY_VIDEOS_WAITING_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_PRIME_SHOP",true)){
		if($config["ENABLE_PRIME_SHOP"]){
			$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
			$reg_inicial = $pag * $config["MAX_PRIME_SHOP_ITEMS"];
			$reg_atual = $reg_inicial + 1;
			$reg_final = $reg_inicial + $config["MAX_PRIME_SHOP_ITEMS"];
			$sql_qtd_reg = $getPanelInfo->primeShop(0,0);
			$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
			$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
			$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_PRIME_SHOP_ITEMS"]);
			if($tpl->exists("BLOCK_PAGINATION",true)){
				if($tpl->exists("PAGINATION"))
					$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
				$tpl->block("BLOCK_PAGINATION");
			}
			if($tpl->exists("PANEL_PRIME_TOTAL"))
				$tpl->PANEL_PRIME_TOTAL = $qtd_total_reg;
			if($tpl->exists("PANEL_PRIME_START"))
				$tpl->PANEL_PRIME_START = empty($reg_final) ? 0 : $reg_atual;
			if($tpl->exists("PANEL_PRIME_STOP"))
				$tpl->PANEL_PRIME_STOP = $reg_final;
			$primeShop = $getPanelInfo->primeShop(0,$reg_inicial.", ".$config["MAX_PRIME_SHOP_ITEMS"]);
			if(count($primeShop) > 0){
				foreach($primeShop as $rank){
					if($tpl->exists("PANEL_PRIME_IMG"))
						$tpl->PANEL_PRIME_IMG = $rank["itemImg"];
					if($tpl->exists("PANEL_PRIME_NAME"))
						$tpl->PANEL_PRIME_NAME = $rank["itemName"];
					if($tpl->exists("PANEL_PRIME_ENCHANT"))
						$tpl->PANEL_PRIME_ENCHANT = $rank["itemEnchant"];
					if($tpl->exists("PANEL_PRIME_COUNT"))
						$tpl->PANEL_PRIME_COUNT = $rank["itemAmount"];
					if($tpl->exists("PANEL_PRIME_PRICE"))
						$tpl->PANEL_PRIME_PRICE = $rank["itemPrice"];
					if($tpl->exists("PANEL_PRIME_ID"))
						$tpl->PANEL_PRIME_ID = $rank["itemId"];
					if($tpl->exists("PANEL_PRIME_DETAILS"))
						$tpl->PANEL_PRIME_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
					$tpl->block("BLOCK_ICP_PANEL_PRIME_SHOP");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_PRIME_SHOP_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_PRIME_SHOP_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION",true)){
		if($config["ENABLE_PRIME_SHOP"]){
			$id = $_GET["id"] ?? 0;
			$primeShop = $getPanelInfo->primeShop(empty($id) || $id <= 0 ? 999999999 : $id,0);
			if(count($primeShop) > 0){
				if($tpl->exists("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION_DETAILS",true)){
					foreach($primeShop as $rank){
						if($tpl->exists("PANEL_PRIME_IMG"))
							$tpl->PANEL_PRIME_IMG = $rank["itemImg"];
						if($tpl->exists("PANEL_PRIME_NAME"))
							$tpl->PANEL_PRIME_NAME = $rank["itemName"];
						if($tpl->exists("PANEL_PRIME_ENCHANT"))
							$tpl->PANEL_PRIME_ENCHANT = $rank["itemEnchant"];
						if($tpl->exists("PANEL_PRIME_COUNT"))
							$tpl->PANEL_PRIME_COUNT = $rank["itemAmount"];
						if($tpl->exists("PANEL_PRIME_PRICE"))
							$tpl->PANEL_PRIME_PRICE = $rank["itemPrice"];
						if($tpl->exists("PANEL_PRIME_ID"))
							$tpl->PANEL_PRIME_ID = $rank["itemId"];
						if($tpl->exists("PANEL_PRIME_DETAILS"))
							$tpl->PANEL_PRIME_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
						$tpl->block("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION_DETAILS");
					}
					$tpl->block("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_PRIME_SHOP_CONFIRMATION_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER",true)){
		if($config["ENABLE_CHARACTER_BROKER"]){
			$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
			$reg_inicial = $pag * $config["MAX_CHARACTER_BROKER_LIST"];
			$reg_atual = $reg_inicial + 1;
			$reg_final = $reg_inicial + $config["MAX_CHARACTER_BROKER_LIST"];
			$sql_qtd_reg = $getPanelInfo->charBroker();
			$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
			$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
			$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_CHARACTER_BROKER_LIST"]);
			if($tpl->exists("BLOCK_PAGINATION",true)){
				if($tpl->exists("PAGINATION"))
					$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
				$tpl->block("BLOCK_PAGINATION");
			}
			if($tpl->exists("PANEL_CHAR_BROKER_TOTAL"))
				$tpl->PANEL_CHAR_BROKER_TOTAL = $qtd_total_reg;
			if($tpl->exists("PANEL_CHAR_BROKER_START"))
				$tpl->PANEL_CHAR_BROKER_START = empty($reg_final) ? 0 : $reg_atual;
			if($tpl->exists("PANEL_CHAR_BROKER_STOP"))
				$tpl->PANEL_CHAR_BROKER_STOP = $reg_final;
			$itemBroker = $getPanelInfo->charBroker(0,null,null,null,$reg_inicial.", ".$config["MAX_ITEM_BROKER_LIST"]);
			if(count($itemBroker) > 0){
				foreach($itemBroker as $rank){
					$charDetails = array();
					foreach($rank["charDetails"][0] as $key => $value){
						array_push($charDetails, $value);
					}
					if($tpl->exists("PANEL_CHAR_BROKER_IMG"))
						$tpl->PANEL_CHAR_BROKER_IMG = $rank["charDetails"][0]["char_image"];
					if($tpl->exists("PANEL_CHAR_BROKER_NAME"))
						$tpl->PANEL_CHAR_BROKER_NAME = $rank["charDetails"][0]["char_name"];
					if($tpl->exists("PANEL_CHAR_BROKER_DETAILS"))
						$tpl->PANEL_CHAR_BROKER_DETAILS = "<div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center m-1 border-bottom' style='color:#fff;'><h5>Information</h5></div><table><tr><td style='padding:5px 10px; text-align:center;' valign='middle'><img src='images/races/".$charDetails[17]."' style='border-radius:5px; border:1px solid #aaa; width:100px; height:100px;'><br>".$charDetails[16]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Base class:</font> ".$charDetails[0]."<br><font color='#b09979'>Subclass:</font> ".$charDetails[1]."<br><font color='#b09979'>Nobless:</font> ".$charDetails[2]."<br><font color='#b09979'>Hero:</font> ".$charDetails[3]."<br><font color='#b09979'>Karma:</font> ".$charDetails[4]."<br><font color='#b09979'>Level:</font> ".$charDetails[5]."<br><font color='#b09979'>Sex:</font> ".$charDetails[6]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Online time:</font> ".$charDetails[7]."<br><font color='#b09979'>Last Access:</font> ".$charDetails[8]."<br><font color='#b09979'>Clan:</font> ".str_replace('"',"'",$charDetails[9]).$charDetails[10]."<br><font color='#b09979'>Ally:</font> ".$charDetails[11]."<br><font color='#b09979'>PvP's:</font> ".$charDetails[12]."<br><font color='#b09979'>Pk's:</font> ".$charDetails[13]."<br><font color='#b09979'>Location:</font> ".$charDetails[14]."</td></tr></table>";
					if($tpl->exists("PANEL_CHAR_BROKER_TYPE")){
						if($rank["charType"] == 1)
							$saleType = "Normal";
						elseif($rank["charType"] == 2)
							$saleType = "<strong style=\"color:#1b6f9b;text-shadow:1px 1px #999;opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["charAuctionTime"]."</span>\">*AUCTION*</strong>";
						else
							$saleType = "Error!!";
						$tpl->PANEL_CHAR_BROKER_TYPE = $saleType;
					}
					if($tpl->exists("PANEL_CHAR_BROKER_PRICE"))
						$tpl->PANEL_CHAR_BROKER_PRICE = $rank["charPrice"];
					if($tpl->exists("PANEL_CHAR_BROKER_ID"))
						$tpl->PANEL_CHAR_BROKER_ID = $rank["charId"];
					if($tpl->exists("PANEL_CHAR_BROKER_BUTTON"))
						$tpl->PANEL_CHAR_BROKER_BUTTON = $rank["charType"] > 1 ? "<a class=\"btn btn-secondary btn-sm w-100\" href=\"?icp=panel&show=character-broker-buy&id=".$rank["charId"]."\">Bid</a>" : "<a class=\"btn btn-primary btn-sm w-100\" href=\"?icp=panel&show=character-broker-buy&id=".$rank["charId"]."\">Buy</a>";
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_DETAILS",true)){
		if($config["ENABLE_CHARACTER_BROKER"]){
			$id = $_GET["id"] ?? 0;
			$charBroker = $getPanelInfo->charBroker(empty($id) || $id <= 0 ? 999999999 : $id);
			if(count($charBroker) > 0){
				foreach($charBroker as $rank){
					$charDetails = array();
					foreach($rank["charDetails"][0] as $key => $value)
						array_push($charDetails, $value);
					if($tpl->exists("PANEL_CHAR_BROKER_IMG"))
						$tpl->PANEL_CHAR_BROKER_IMG = $rank["charDetails"][0]["char_image"];
					if($tpl->exists("PANEL_CHAR_BROKER_NAME"))
						$tpl->PANEL_CHAR_BROKER_NAME = $rank["charDetails"][0]["char_name"];
					if($tpl->exists("PANEL_CHAR_BROKER_CLASS"))
						$tpl->PANEL_CHAR_BROKER_CLASS = $charDetails[0];
					if($tpl->exists("PANEL_CHAR_BROKER_SUBCLASS"))
						$tpl->PANEL_CHAR_BROKER_SUBCLASS = $charDetails[1];
					if($tpl->exists("PANEL_CHAR_BROKER_NOBLESS"))
						$tpl->PANEL_CHAR_BROKER_NOBLESS = $charDetails[2];
					if($tpl->exists("PANEL_CHAR_BROKER_HERO"))
						$tpl->PANEL_CHAR_BROKER_HERO = $charDetails[3];
					if($tpl->exists("PANEL_CHAR_BROKER_KARMA"))
						$tpl->PANEL_CHAR_BROKER_KARMA = $charDetails[4];
					if($tpl->exists("PANEL_CHAR_BROKER_LEVEL"))
						$tpl->PANEL_CHAR_BROKER_LEVEL = $charDetails[5];
					if($tpl->exists("PANEL_CHAR_BROKER_SEX"))
						$tpl->PANEL_CHAR_BROKER_SEX = $charDetails[6];
					if($tpl->exists("PANEL_CHAR_BROKER_ONLINE_TIME"))
						$tpl->PANEL_CHAR_BROKER_ONLINE_TIME = $charDetails[7];
					if($tpl->exists("PANEL_CHAR_BROKER_LAST_ACCESS"))
						$tpl->PANEL_CHAR_BROKER_LAST_ACCESS = $charDetails[8];
					if($tpl->exists("PANEL_CHAR_BROKER_CREST_CLAN"))
						$tpl->PANEL_CHAR_BROKER_CREST_CLAN = $charDetails[9];
					if($tpl->exists("PANEL_CHAR_BROKER_CLAN"))
						$tpl->PANEL_CHAR_BROKER_CLAN = $charDetails[10];
					if($tpl->exists("PANEL_CHAR_BROKER_ALLYANCE"))
						$tpl->PANEL_CHAR_BROKER_ALLYANCE = $charDetails[11];
					if($tpl->exists("PANEL_CHAR_BROKER_PVP"))
						$tpl->PANEL_CHAR_BROKER_PVP = $charDetails[12];
					if($tpl->exists("PANEL_CHAR_BROKER_PK"))
						$tpl->PANEL_CHAR_BROKER_PK = $charDetails[13];
					if($tpl->exists("PANEL_CHAR_BROKER_LOCATION"))
						$tpl->PANEL_CHAR_BROKER_LOCATION = $charDetails[14];
					if($tpl->exists("PANEL_CHAR_BROKER_TYPE")){
						if($rank["charType"] == 1)
							$saleType = "Normal";
						elseif($rank["charType"] == 2)
							$saleType = "<strong style=\"color:#1b6f9b;text-shadow:1px 1px #999;opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-85px;'>Ends in: ".$rank["charAuctionTime"]."</span>\">*AUCTION*</strong>";
						else
							$saleType = "Error!!";
						$tpl->PANEL_CHAR_BROKER_TYPE = $saleType;
					}
					if($tpl->exists("PANEL_CHAR_BROKER_BUTTON")){
						if($rank["charType"] > 1){
							if($config["ALLOW_AUCTION_CHARACTER_BROKER"]){
								$bidOptions = null;
								$initial_bid = empty($rank["charAuctionPrice"]) ? $rank["charPrice"] : $rank["charAuctionPrice"]+$config["AUCTION_CHARACTER_RANGES_BID"];
								for($x=0; $x < 10; $x++)
									$bidOptions .= "<option value='".($initial_bid+($config["AUCTION_CHARACTER_RANGES_BID"]*$x))."'>".($initial_bid+($config["AUCTION_CHARACTER_RANGES_BID"]*$x))." ".$config["DONATE_COIN_NAME"]."</option>";
								$charBrokerButton = "<p>Choose your bid and then confirm.</p><form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">Bid</span></div><select type=\"text\" class=\"form-select\" name=\"bidValue\" required>".$bidOptions."</select></div><input type=\"hidden\" name=\"charBrokerId\" value=\"".$rank["charId"]."\"><input type=\"submit\" name=\"submitCharBroker\" class=\"btn btn-secondary btn-sm w-100\" value=\"Confirm bid\"></form>";
							}else{
								$charBrokerButton = null;
							}
						}else
							$charBrokerButton = "<p>Confirm your purchase.</p><div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">Price:</span></div><span class=\"form-control\">".$rank["charPrice"]." ".$config["DONATE_COIN_NAME"]."</span></div><form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"charBrokerId\" value=\"".$rank["charId"]."\"><input type=\"submit\" name=\"submitCharBroker\" class=\"btn btn-primary btn-sm w-100\" value=\"Confirm purchase\"></form>";
						$tpl->PANEL_CHAR_BROKER_BUTTON = $charBrokerButton;
					}
					if($rank["charType"] > 1){
						if($tpl->exists("BLOCK_ICP_PANEL_AUCTION_BIDS",true)){
							if($config["ALLOW_AUCTION_CHARACTER_BROKER"]){
								if($tpl->exists("PANEL_CHAR_BROKER_INITIAL_BID"))
									$tpl->PANEL_CHAR_BROKER_INITIAL_BID = $rank["charInitialPrice"];
								if($tpl->exists("PANEL_CHAR_BROKER_LAST_BID"))
									$tpl->PANEL_CHAR_BROKER_LAST_BID = empty($rank["charAuctionPrice"]) ? "No bids yet" : $rank["charAuctionPrice"]." ".$config["DONATE_COIN_NAME"];
								if($tpl->exists("PANEL_CHAR_BROKER_INITIAL_BID"))
									$tpl->PANEL_CHAR_BROKER_NEXT_BID = empty($rank["charAuctionPrice"]) ? $rank["charPrice"] : $rank["charAuctionPrice"]+$config["AUCTION_CHARACTER_RANGES_BID"];
								if($tpl->exists("PANEL_CHAR_BROKER_AUCTION_STARTED"))
									$tpl->PANEL_CHAR_BROKER_AUCTION_STARTED = date("Y-m-d H:i:s", (strtotime($rank["charAuctionTime"]) - ($config["AUCTION_CHARACTER_BROKER_DAYS"] * 86400)));
								if($tpl->exists("PANEL_CHAR_BROKER_AUCTION_ENDS"))
									$tpl->PANEL_CHAR_BROKER_AUCTION_ENDS = $getPanelInfo->remainingTime(strtotime($rank["charAuctionTime"]) - time(),true);
								if($tpl->exists("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS",true)){
									$charBids = $getPanelInfo->itemBidHistory($_GET["id"] ?? 0,false);
									if(count($charBids) > 0){
										foreach($charBids as $rankBids){
											if($tpl->exists("PANEL_CHAR_BROKER_AUCTION_BIDS"))
												$tpl->PANEL_CHAR_BROKER_AUCTION_BIDS = $rankBids["bidDate"]." - User ".$rankBids["bidAccount"]." offered ".$rankBids["bidValue"]." ".$config["DONATE_COIN_NAME"]."<br>";
											$tpl->block("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS");
										}
									}else{
										if($tpl->exists("PANEL_CHAR_BROKER_AUCTION_BIDS"))
											$tpl->PANEL_CHAR_BROKER_AUCTION_BIDS = "No bids yet.";
										$tpl->block("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS");
									}
								}
								$tpl->block("BLOCK_ICP_PANEL_AUCTION_BIDS");
							}
						}
					}
					$allowedBlockItems = array("BLOCK_ICP_PANEL_CHAR_BROKER_EQUIPED_ITEMS","BLOCK_ICP_PANEL_CHAR_BROKER_INVENTORY_ITEMS","BLOCK_ICP_PANEL_CHAR_BROKER_WAREHOUSE_ITEMS");
					for($x=0;$x<count($model);$x++){
						if(in_array($model[$x],$allowedBlockItems)){
							if($tpl->exists($model[$x],true)){
								$itemShow = $getPanelInfo->showCharacterItems($model[$x] != $allowedBlockItems[0] ? $model[$x] == $allowedBlockItems[1] ? "INVENTORY" : "WAREHOUSE" : "PAPERDOLL",$charDetails[15],$rank["charAccount"]);
								if(count($itemShow) > 0){
									foreach($itemShow as $rankItem){
										if($tpl->exists("PANEL_ITEM_IMG"))
											$tpl->PANEL_ITEM_IMG = $rankItem["itemImg"];
										if($tpl->exists("PANEL_ITEM_NAME"))
											$tpl->PANEL_ITEM_NAME = $rankItem["itemName"];
										if($tpl->exists("PANEL_ITEM_ENCHANT"))
											$tpl->PANEL_ITEM_ENCHANT = $rankItem["itemEnchant"];
										if($tpl->exists("PANEL_ITEM_OWNER_ID"))
											$tpl->PANEL_ITEM_OWNER_ID = $rankItem["itemOwnerId"];
										if($tpl->exists("PANEL_ITEM_OWNER_NAME"))
											$tpl->PANEL_ITEM_OWNER_NAME = $rankItem["itemOwnerName"];
										if($tpl->exists("PANEL_ITEM_DETAILS"))
											$tpl->PANEL_ITEM_DETAILS = "<div class=\"item-details".(strpos($rankItem["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rankItem["itemDetails"]."\"></div>";
										$tpl->block($model[$x]);
									}
								}else{
									if($tpl->exists($model[$x]."_NULL",true))
										$tpl->block($model[$x]."_NULL");
								}
							}
						}
					}
					if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_CLASS",true)){
						$charSkillsClass = $getPanelInfo->showSubClasses($charDetails[15],$rank["charAccount"]);
						if(!empty($charSkillsClass)){
							$charSkillsClassArr = explode(";",$charSkillsClass);
							if(count($charSkillsClassArr) > 0){
								$btn = null;
								for($x=0;$x<(count($charSkillsClassArr)-1);$x++){
									$btn .= "<div class='col p-2 pt-1'><button class=\"btn btn-primary w-100 subclassLink\" onclick=\"openMenuSubclass(event, '".$getPanelInfo->classe_name($charSkillsClassArr[$x])."');return false;\"".($x == 0 ? " id=\"baseClass\"" : null).">".$getPanelInfo->classe_name($charSkillsClassArr[$x])."</button></div>";
									if($tpl->exists("PANEL_SKILLS_CLASS"))
										$tpl->PANEL_SKILLS_CLASS = $getPanelInfo->classe_name($charSkillsClassArr[$x]);
									if($tpl->exists("PANEL_SKILLS_BUTTONS"))
										$tpl->PANEL_SKILLS_BUTTONS = "<div class='col p-2 pt-1'><button class=\"btn btn-primary w-100\" onclick=\"openMenuSubclass(event, '".$getPanelInfo->classe_name($charSkillsClassArr[$x])."');\"".($x == 0 ? " id=\"baseClass\"" : null).">".$getPanelInfo->classe_name($charSkillsClassArr[$x])."</button></div>";
									if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS",true)){
										if(isset($config["class_index"]) && $config["class_index"]){
											$charSkills = $getPanelInfo->showCharSkills($charSkillsClassArr[$x],$charDetails[15],$rank["charAccount"]);
										}else{
											$charSkills = $getPanelInfo->showCharSkills($x,$charDetails[15],$rank["charAccount"]);
										}
										if(count($charSkills) > 0){
											foreach($charSkills as $rankSkills){
												if($tpl->exists("PANEL_SKILLS_OWNER_NAME"))
													$tpl->PANEL_SKILLS_OWNER_NAME = $rankSkills["skillOwner"];
												if($tpl->exists("PANEL_SKILLS_IMG"))
													$tpl->PANEL_SKILLS_IMG = $rankSkills["skillImg"];
												if($tpl->exists("PANEL_SKILLS_DETAILS"))
													$tpl->PANEL_SKILLS_DETAILS = $rankSkills["skillDetails"];
												$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS");
											}
										}else{
											if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_NULL",true))
												$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_NULL");
										}
									}
									$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_CLASS");
								}
								if($tpl->exists("PANEL_SKILLS_BUTTONS"))
									$tpl->PANEL_SKILLS_BUTTONS = $btn;
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_CLASS_NULL",true))
								$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_SKILLS_CLASS_NULL");
						}
					}
					if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_QUESTS",true)){
						$charQuests = $getPanelInfo->showCharQuests($charDetails[15],$rank["charAccount"]);
						if(count($charQuests) > 0){
							foreach($charQuests as $rank){
								if($tpl->exists("PANEL_QUEST_OWNER_NAME"))
									$tpl->PANEL_QUEST_OWNER_NAME = $rank["questOwner"];
								if($tpl->exists("PANEL_QUEST_NAME"))
									$tpl->PANEL_QUEST_NAME = $rank["questName"];
								if($tpl->exists("PANEL_QUEST_STAGE"))
									$tpl->PANEL_QUEST_STAGE = $rank["questValue"];
								$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_QUESTS");
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_QUESTS_NULL",true))
								$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_QUESTS_NULL");
						}
					}
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_DETAILS");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_DETAILS_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_DETAILS_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER",true)){
		if($config["ENABLE_ITEM_BROKER"]){
			$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
			$reg_inicial = $pag * $config["MAX_ITEM_BROKER_LIST"];
			$reg_atual = $reg_inicial + 1;
			$reg_final = $reg_inicial + $config["MAX_ITEM_BROKER_LIST"];
			$sql_qtd_reg = $getPanelInfo->itemBroker();
			$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
			$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
			$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_ITEM_BROKER_LIST"]);
			if($tpl->exists("BLOCK_PAGINATION",true)){
				if($tpl->exists("PAGINATION"))
					$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
				$tpl->block("BLOCK_PAGINATION");
			}
			if($tpl->exists("PANEL_ITEM_BROKER_TOTAL"))
				$tpl->PANEL_ITEM_BROKER_TOTAL = $qtd_total_reg;
			if($tpl->exists("PANEL_ITEM_BROKER_START"))
				$tpl->PANEL_ITEM_BROKER_START = empty($reg_final) ? 0 : $reg_atual;
			if($tpl->exists("PANEL_ITEM_BROKER_STOP"))
				$tpl->PANEL_ITEM_BROKER_STOP = $reg_final;
			$itemBroker = $getPanelInfo->itemBroker(0,null,null,null,$reg_inicial.", ".$config["MAX_ITEM_BROKER_LIST"]);
			if(count($itemBroker) > 0){
				foreach($itemBroker as $rank){
					if($tpl->exists("PANEL_ITEM_BROKER_IMG"))
						$tpl->PANEL_ITEM_BROKER_IMG = $rank["itemImg"];
					if($tpl->exists("PANEL_ITEM_BROKER_NAME"))
						$tpl->PANEL_ITEM_BROKER_NAME = $rank["itemName"];
					if($tpl->exists("PANEL_ITEM_BROKER_CHAR_NAME"))
						$tpl->PANEL_ITEM_BROKER_CHAR_NAME = $rank["itemCharName"];
					if($tpl->exists("PANEL_ITEM_BROKER_ENCHANT"))
						$tpl->PANEL_ITEM_BROKER_ENCHANT = $rank["itemEnchant"];
					if($tpl->exists("PANEL_ITEM_BROKER_COUNT"))
						$tpl->PANEL_ITEM_BROKER_COUNT = $rank["itemAmount"];
					if($tpl->exists("PANEL_ITEM_BROKER_PRICE"))
						$tpl->PANEL_ITEM_BROKER_PRICE = $rank["itemPrice"];
					if($tpl->exists("PANEL_ITEM_BROKER_TYPE")){
						if($rank["itemType"] == 1)
							$saleType = "Normal";
						elseif($rank["itemType"] == 2)
							$saleType = "<strong style=\"color:#e05151;text-shadow:1px 1px #999;opacity:0.7;\">*COMBO*</strong>";
						elseif($rank["itemType"] == 3)
							$saleType = "<strong style=\"color:#1b6f9b;text-shadow:1px 1px #999;opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\">*AUCTION*</strong>";
						elseif($rank["itemType"] == 4)
							$saleType = "<strong style=\"opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\"><span style=\"color:#e05151;text-shadow:1px 1px #999;\">*COMBO</span>/<span style=\"color:#1b6f9b;text-shadow:1px 1px #999;\">AUCTION*</span></strong>";
						else
							$saleType = "Error!!";
						$tpl->PANEL_ITEM_BROKER_TYPE = $saleType;
					}
					if($tpl->exists("PANEL_ITEM_BROKER_BUTTON"))
						$tpl->PANEL_ITEM_BROKER_BUTTON = $rank["itemType"] > 2 ? "<a class=\"btn btn-secondary btn-sm w-100\" href=\"?icp=panel&show=item-broker-buy&id=".$rank["itemId"]."\">Bid</a>" : "<a class=\"btn btn-primary btn-sm w-100\" href=\"?icp=panel&show=item-broker-buy&id=".$rank["itemId"]."\">Buy</a>";
					if($tpl->exists("PANEL_ITEM_BROKER_DETAILS"))
						$tpl->PANEL_ITEM_BROKER_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION",true)){
		if($config["ENABLE_ITEM_BROKER"]){
			$id = $_GET["id"] ?? 0;
			$itemBroker = $getPanelInfo->itemBroker(empty($id) || $id <= 0 ? 999999999 : $id);
			if(count($itemBroker) > 0){
				if($itemBroker[0]["itemType"] < 3){
					if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_NORMAL_CONFIRMATION",true)){
						if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_NORMAL_CONFIRMATION_DETAILS",true)){
							foreach($itemBroker as $rank){
								if($tpl->exists("PANEL_ITEM_BROKER_IMG"))
									$tpl->PANEL_ITEM_BROKER_IMG = $rank["itemImg"];
								if($tpl->exists("PANEL_ITEM_BROKER_NAME"))
									$tpl->PANEL_ITEM_BROKER_NAME = $rank["itemName"];
								if($tpl->exists("PANEL_ITEM_BROKER_CHAR_NAME"))
									$tpl->PANEL_ITEM_BROKER_CHAR_NAME = $rank["itemCharName"];
								if($tpl->exists("PANEL_ITEM_BROKER_ENCHANT"))
									$tpl->PANEL_ITEM_BROKER_ENCHANT = $rank["itemEnchant"];
								if($tpl->exists("PANEL_ITEM_BROKER_COUNT"))
									$tpl->PANEL_ITEM_BROKER_COUNT = $rank["itemAmount"];
								if($tpl->exists("PANEL_ITEM_BROKER_PRICE"))
									$tpl->PANEL_ITEM_BROKER_PRICE = $rank["itemPrice"];
								if($tpl->exists("PANEL_ITEM_BROKER_TYPE")){
									if($rank["itemType"] == 1)
										$saleType = "pack";
									elseif($rank["itemType"] == 2)
										$saleType = "<strong style=\"color:#e05151;text-shadow:1px 1px #999;opacity:0.7;\">*COMBO*</strong>";
									elseif($rank["itemType"] == 3)
										$saleType = "<strong style=\"color:#1b6f9b;text-shadow:1px 1px #999;opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\">*AUCTION*</strong>";
									elseif($rank["itemType"] == 4)
										$saleType = "<strong style=\"opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\"><span style=\"color:#e05151;text-shadow:1px 1px #999;\">*COMBO</span>/<span style=\"color:#1b6f9b;text-shadow:1px 1px #999;\">AUCTION*</span></strong>";
									else
										$saleType = "Error!!";
									$tpl->PANEL_ITEM_BROKER_TYPE = $saleType;
								}
								if($tpl->exists("PANEL_ITEM_BROKER_ID"))
									$tpl->PANEL_ITEM_BROKER_ID = $rank["itemId"];
								if($tpl->exists("PANEL_ITEM_BROKER_TXT_CONFIRM"))
									$tpl->PANEL_ITEM_BROKER_TXT_CONFIRM = "Choose the character that will receive the item(s) and confirm your purchase.";
								if($tpl->exists("PANEL_ITEM_BROKER_BUTTON"))
									$tpl->PANEL_ITEM_BROKER_BUTTON = "<input type=\"submit\" class=\"btn btn-primary btn-sm w-100\" name=\"submitItemBroker\" value=\"Confirm purchase\">";
								if($tpl->exists("PANEL_ITEM_BROKER_SELECT")){
									$charOptions = null;
									foreach($charList as $rankChar)
										$charOptions .= "<option value=\"".$rankChar["charId"]."\"".($rankChar["charOnline"] == 1 ? " disabled><em>Online -> </em" : "").">".$rankChar["charName"]."</option>";
									$tpl->PANEL_ITEM_BROKER_SELECT = "<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">Character</span></div><select type=\"text\" class=\"form-select\" name=\"charId\" required>".$charOptions."</select></div>";
								}
								if($tpl->exists("PANEL_ITEM_BROKER_DETAILS"))
									$tpl->PANEL_ITEM_BROKER_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
								$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_NORMAL_CONFIRMATION_DETAILS");
							}
						}
						$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_NORMAL_CONFIRMATION");
					}
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION");
				}else{
					if($config["ALLOW_AUCTION_ITEM_BROKER"]){
						if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_AUCTION_CONFIRMATION",true)){
							if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_AUCTION_CONFIRMATION_DETAILS",true)){
								foreach($itemBroker as $rank){
									if($tpl->exists("PANEL_ITEM_BROKER_IMG"))
										$tpl->PANEL_ITEM_BROKER_IMG = $rank["itemImg"];
									if($tpl->exists("PANEL_ITEM_BROKER_NAME"))
										$tpl->PANEL_ITEM_BROKER_NAME = $rank["itemName"];
									if($tpl->exists("PANEL_ITEM_BROKER_CHAR_NAME"))
										$tpl->PANEL_ITEM_BROKER_CHAR_NAME = $rank["itemCharName"];
									if($tpl->exists("PANEL_ITEM_BROKER_ENCHANT"))
										$tpl->PANEL_ITEM_BROKER_ENCHANT = $rank["itemEnchant"];
									if($tpl->exists("PANEL_ITEM_BROKER_COUNT"))
										$tpl->PANEL_ITEM_BROKER_COUNT = $rank["itemAmount"];
									if($tpl->exists("PANEL_ITEM_BROKER_PRICE"))
										$tpl->PANEL_ITEM_BROKER_PRICE = $rank["itemPrice"];
									if($tpl->exists("PANEL_ITEM_BROKER_TYPE")){
										if($rank["itemType"] == 1)
											$saleType = "pack";
										elseif($rank["itemType"] == 2)
											$saleType = "<strong style=\"color:#e05151;text-shadow:1px 1px #999;opacity:0.7;\">*COMBO*</strong>";
										elseif($rank["itemType"] == 3)
											$saleType = "<strong style=\"color:#1b6f9b;text-shadow:1px 1px #999;opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\">*AUCTION*</strong>";
										elseif($rank["itemType"] == 4)
											$saleType = "<strong style=\"opacity:0.7;\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails'>Ends in: ".$rank["itemAuctionTime"]."</span>\"><span style=\"color:#e05151;text-shadow:1px 1px #999;\">*COMBO</span>/<span style=\"color:#1b6f9b;text-shadow:1px 1px #999;\">AUCTION*</span></strong>";
										else
											$saleType = "Error!!";
										$tpl->PANEL_ITEM_BROKER_TYPE = $saleType;
									}
									if($tpl->exists("PANEL_ITEM_BROKER_ID"))
										$tpl->PANEL_ITEM_BROKER_ID = $rank["itemId"];
									if($tpl->exists("PANEL_ITEM_BROKER_SELECT")){
										$bidOptions = null;
										$initial_bid = empty($rank["itemAuctionPrice"]) ? $rank["itemPrice"] : $rank["itemAuctionPrice"]+$config["AUCTION_ITEM_RANGES_BID"];
										for($x=0; $x < 10; $x++)
											$bidOptions .= "<option value='".($initial_bid+($config["AUCTION_ITEM_RANGES_BID"]*$x))."'>".($initial_bid+($config["AUCTION_ITEM_RANGES_BID"]*$x))." ".$config["DONATE_COIN_NAME"]."</option>";
										$tpl->PANEL_ITEM_BROKER_SELECT = "<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">Bid</span></div><select type=\"text\" class=\"form-select\" name=\"bidValue\" required>".$bidOptions."</select></div>";
									}
									if($tpl->exists("PANEL_ITEM_BROKER_TXT_CONFIRM"))
										$tpl->PANEL_ITEM_BROKER_TXT_CONFIRM = "Choose your bid and  then confirm.";
									if($tpl->exists("PANEL_ITEM_BROKER_BUTTON"))
										$tpl->PANEL_ITEM_BROKER_BUTTON = "<input type=\"submit\" class=\"btn btn-secondary btn-sm w-100\" name=\"submitItemBroker\" value=\"Confirm bid\">";
									if($tpl->exists("PANEL_ITEM_BROKER_DETAILS"))
										$tpl->PANEL_ITEM_BROKER_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
									$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_AUCTION_CONFIRMATION_DETAILS");
								}
							}
							$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_AUCTION_CONFIRMATION");
						}
						if($tpl->exists("BLOCK_ICP_PANEL_AUCTION_BIDS",true)){
							foreach($itemBroker as $rank){
								if($tpl->exists("PANEL_ITEM_BROKER_INITIAL_BID"))
									$tpl->PANEL_ITEM_BROKER_INITIAL_BID = $rank["itemInitialPrice"];
								if($tpl->exists("PANEL_ITEM_BROKER_LAST_BID"))
									$tpl->PANEL_ITEM_BROKER_LAST_BID = empty($rank["itemAuctionPrice"]) ? "No bids yet" : $rank["itemAuctionPrice"]." ".$config["DONATE_COIN_NAME"];
								if($tpl->exists("PANEL_ITEM_BROKER_NEXT_BID"))
									$tpl->PANEL_ITEM_BROKER_NEXT_BID = empty($rank["itemAuctionPrice"]) ? $rank["itemPrice"] : $rank["itemAuctionPrice"]+$config["AUCTION_ITEM_RANGES_BID"];
								if($tpl->exists("PANEL_ITEM_BROKER_AUCTION_STARTED"))
									$tpl->PANEL_ITEM_BROKER_AUCTION_STARTED = date("Y-m-d H:i:s", (strtotime($rank["itemAuctionTime"]) - ($config["AUCTION_ITEM_BROKER_DAYS"] * 86400)));
								if($tpl->exists("PANEL_ITEM_BROKER_AUCTION_ENDS"))
									$tpl->PANEL_ITEM_BROKER_AUCTION_ENDS = $getPanelInfo->remainingTime(strtotime($rank["itemAuctionTime"]) - time(),true);
							}
							if($tpl->exists("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS",true)){
								$itemBids = $getPanelInfo->itemBidHistory($_GET["id"] ?? 0);
								if(count($itemBids) > 0){
									foreach($itemBids as $rank){
										if($tpl->exists("PANEL_ITEM_BROKER_AUCTION_BIDS"))
											$tpl->PANEL_ITEM_BROKER_AUCTION_BIDS = $rank["bidDate"]." - User ".$rank["bidAccount"]." offered ".$rank["bidValue"]." ".$config["DONATE_COIN_NAME"]."<br>";
										$tpl->block("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS");
									}
								}else{
									if($tpl->exists("PANEL_ITEM_BROKER_AUCTION_BIDS"))
										$tpl->PANEL_ITEM_BROKER_AUCTION_BIDS = "No bids yet.";
									$tpl->block("BLOCK_ICP_PANEL_AUCTION_LASTS_BIDS");
								}
							}
							$tpl->block("BLOCK_ICP_PANEL_AUCTION_BIDS");
						}
						$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION");
					}else{
						if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION_NULL",true))
							$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION_NULL");
					}
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_CONFIRMATION_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_MY_SALES",true)){
		if($config["ENABLE_CHARACTER_BROKER"]){
			$itemBrokerMySales = $getPanelInfo->charBroker(0,$_SESSION["ICP_UserName"],null,"sales");
			if($tpl->exists("PANEL_ICP_TOTAL_SALES"))
				$tpl->PANEL_ICP_TOTAL_SALES = count($itemBrokerMySales);
			if(count($itemBrokerMySales) > 0){
				foreach($itemBrokerMySales as $rank){
					$charDetails = array();
					foreach($rank["charDetails"][0] as $key => $value)
						array_push($charDetails, $value);
					if($tpl->exists("PANEL_CHAR_BROKER_IMG"))
						$tpl->PANEL_CHAR_BROKER_IMG = $rank["charDetails"][0]["char_image"];
					if($tpl->exists("PANEL_CHAR_BROKER_NAME"))
						$tpl->PANEL_CHAR_BROKER_NAME = $rank["charDetails"][0]["char_name"];
					if($tpl->exists("PANEL_CHAR_BROKER_DETAILS"))
						$tpl->PANEL_CHAR_BROKER_DETAILS = "<div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center m-1 border-bottom' style='color:#fff;'><h5>Information</h5></div><table><tr><td style='padding:5px 10px; text-align:center;' valign='middle'><img src='images/races/".$charDetails[17]."' style='border-radius:5px; border:1px solid #aaa; width:100px; height:100px;'><br>".$charDetails[16]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Base class:</font> ".$charDetails[0]."<br><font color='#b09979'>Subclass:</font> ".$charDetails[1]."<br><font color='#b09979'>Nobless:</font> ".$charDetails[2]."<br><font color='#b09979'>Hero:</font> ".$charDetails[3]."<br><font color='#b09979'>Karma:</font> ".$charDetails[4]."<br><font color='#b09979'>Level:</font> ".$charDetails[5]."<br><font color='#b09979'>Sex:</font> ".$charDetails[6]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Online time:</font> ".$charDetails[7]."<br><font color='#b09979'>Last Access:</font> ".$charDetails[8]."<br><font color='#b09979'>Clan:</font> ".str_replace('"',"'",$charDetails[9]).$charDetails[10]."<br><font color='#b09979'>Ally:</font> ".$charDetails[11]."<br><font color='#b09979'>PvP's:</font> ".$charDetails[12]."<br><font color='#b09979'>Pk's:</font> ".$charDetails[13]."<br><font color='#b09979'>Location:</font> ".$charDetails[14]."</td></tr></table>";
					if($tpl->exists("PANEL_CHAR_BROKER_BUTTON")){
						if($rank["charType"] > 1){
							if(!empty($rank["charAuctionPrice"])){
								if(strtotime($rank["charAuctionTime"]) > time()){
									$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-60px;'>Auction with bids</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
								}else{
									$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-55px;'>Auction ended</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
								}
							}else{
								$btn_my_sales ="<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"charBrokerId\" value=\"".$rank["charId"]."\"><input type=\"submit\" class=\"btn btn-secondary btn-sm w-100\" name=\"charBrokerReturn\" value=\"Return\"></form>";
							}
						}else{
							$btn_my_sales ="<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"charBrokerId\" value=\"".$rank["charId"]."\"><input type=\"submit\" class=\"btn btn-primary btn-sm w-100\" name=\"charBrokerReturn\" value=\"Return\"></form>";
						}
						$tpl->PANEL_CHAR_BROKER_BUTTON = $btn_my_sales;
					}
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_MY_SALES");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_MY_SALES_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_MY_SALES_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_MY_SALES",true)){
		if($config["ENABLE_ITEM_BROKER"]){
			$itemBrokerMySales = $getPanelInfo->itemBroker(0,$_SESSION["ICP_UserName"],null,"sales");
			if($tpl->exists("PANEL_ICP_TOTAL_SALES"))
				$tpl->PANEL_ICP_TOTAL_SALES = count($itemBrokerMySales);
			if(count($itemBrokerMySales) > 0){
				foreach($itemBrokerMySales as $rank){
					if($tpl->exists("PANEL_ITEM_BROKER_IMG"))
						$tpl->PANEL_ITEM_BROKER_IMG = $rank["itemImg"];
					if($tpl->exists("PANEL_ITEM_BROKER_NAME"))
						$tpl->PANEL_ITEM_BROKER_NAME = $rank["itemName"];
					if($tpl->exists("PANEL_ITEM_BROKER_BUTTON")){
						if($rank["itemType"] > 2){
							if(!empty($rank["itemAuctionPrice"])){
								if(strtotime($rank["itemAuctionTime"]) > time()){
									$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-60px;'>Auction with bids</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
								}else{
									$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-55px;'>Auction ended</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
								}
							}else{
								$btn_my_sales ="<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"itemBrokerId\" value=\"".$rank["itemId"]."\"><input type=\"submit\" class=\"btn btn-secondary btn-sm w-100\" name=\"itemBrokerReturn\" value=\"Return\"></form>";
							}
						}else{
							$btn_my_sales ="<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"itemBrokerId\" value=\"".$rank["itemId"]."\"><input type=\"submit\" class=\"btn btn-primary btn-sm w-100\" name=\"itemBrokerReturn\" value=\"Return\"></form>";
						}
						$tpl->PANEL_ITEM_BROKER_BUTTON = $btn_my_sales;
					}
					if($tpl->exists("PANEL_ITEM_BROKER_DETAILS"))
						$tpl->PANEL_ITEM_BROKER_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_MY_SALES");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_MY_SALES_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_MY_SALES_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ENABLE_CHAR_BROKER_MY_AUCTIONS",true)){
		if($config["ALLOW_AUCTION_CHARACTER_BROKER"]){
			if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_MY_AUCTIONS",true)){
				if($config["ENABLE_CHARACTER_BROKER"]){
					$itemBrokerMySales = $getPanelInfo->charBroker(0,$_SESSION["ICP_UserName"],null,"bids");
					if($tpl->exists("PANEL_ICP_TOTAL_SALES"))
						$tpl->PANEL_ICP_TOTAL_AUCTIONS = count($itemBrokerMySales);
					if(count($itemBrokerMySales) > 0){
						foreach($itemBrokerMySales as $rank){
							$charDetails = array();
							foreach($rank["charDetails"][0] as $key => $value)
								array_push($charDetails, $value);
							if($tpl->exists("PANEL_CHAR_BROKER_IMG"))
								$tpl->PANEL_CHAR_BROKER_IMG = $rank["charDetails"][0]["char_image"];
							if($tpl->exists("PANEL_CHAR_BROKER_NAME"))
								$tpl->PANEL_CHAR_BROKER_NAME = $rank["charDetails"][0]["char_name"];
							if($tpl->exists("PANEL_CHAR_BROKER_DETAILS"))
								$tpl->PANEL_CHAR_BROKER_DETAILS = "<div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center m-1 border-bottom' style='color:#fff;'><h5>Information</h5></div><table><tr><td style='padding:5px 10px; text-align:center;' valign='middle'><img src='images/races/".$charDetails[17]."' style='border-radius:5px; border:1px solid #aaa; width:100px; height:100px;'><br>".$charDetails[16]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Base class:</font> ".$charDetails[0]."<br><font color='#b09979'>Subclass:</font> ".$charDetails[1]."<br><font color='#b09979'>Nobless:</font> ".$charDetails[2]."<br><font color='#b09979'>Hero:</font> ".$charDetails[3]."<br><font color='#b09979'>Karma:</font> ".$charDetails[4]."<br><font color='#b09979'>Level:</font> ".$charDetails[5]."<br><font color='#b09979'>Sex:</font> ".$charDetails[6]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Online time:</font> ".$charDetails[7]."<br><font color='#b09979'>Last Access:</font> ".$charDetails[8]."<br><font color='#b09979'>Clan:</font> ".str_replace('"',"'",$charDetails[9]).$charDetails[10]."<br><font color='#b09979'>Ally:</font> ".$charDetails[11]."<br><font color='#b09979'>PvP's:</font> ".$charDetails[12]."<br><font color='#b09979'>Pk's:</font> ".$charDetails[13]."<br><font color='#b09979'>Location:</font> ".$charDetails[14]."</td></tr></table>";
							if($tpl->exists("PANEL_CHAR_BROKER_BUTTON")){
								if($rank["charType"] > 1){
									if(!empty($rank["charAuctionPrice"])){
										if(strtotime($rank["charAuctionTime"]) > time()){
											$btn_my_sales = "<a class=\"btn btn-secondary btn-sm w-100\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-175px;'>Auction ends in: ".$rank["charAuctionTime"]."</span>\" href=\"?icp=panel&show=character-broker-buy&id=".$rank["charId"]."\">Bid</a>";
										}else{
											if($getPanelInfo->ownerAuction($rank["charId"],$_SESSION["ICP_UserName"],true))
												$btn_my_sales = "<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\"><input type=\"hidden\" name=\"charBrokerId\" value=\"".$rank["charId"]."\"><input type=\"submit\"name=\"submitCharBroker\" class=\"btn btn-secondary btn-sm w-100\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-240px;'>The auction is ended and you were the big winner!!!</span>\" value=\"Receive\"></form>";
											else
												$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-202px;'>Auction ended and you were not the winner.</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
										}
									}else{
										$btn_my_sales ="<a class=\"btn btn-danger btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Error</a>";
									}
								}else{
									$btn_my_sales ="<a class=\"btn btn-danger btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Error</a>";
								}
								$tpl->PANEL_CHAR_BROKER_BUTTON = $btn_my_sales;
							}
							$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_MY_AUCTIONS");
						}
					}else{
						if($tpl->exists("BLOCK_ICP_PANEL_CHAR_BROKER_MY_AUCTIONS_NULL",true))
							$tpl->block("BLOCK_ICP_PANEL_CHAR_BROKER_MY_AUCTIONS_NULL");
					}
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_ENABLE_CHAR_BROKER_MY_AUCTIONS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ENABLE_ITEM_BROKER_MY_AUCTIONS",true)){
		if($config["ALLOW_AUCTION_ITEM_BROKER"]){
			if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_MY_AUCTIONS",true)){
				if($config["ENABLE_ITEM_BROKER"]){
					$itemBrokerMySales = $getPanelInfo->itemBroker(0,$_SESSION["ICP_UserName"],null,"bids");
					if($tpl->exists("PANEL_ICP_TOTAL_SALES"))
						$tpl->PANEL_ICP_TOTAL_AUCTIONS = count($itemBrokerMySales);
					if(count($itemBrokerMySales) > 0){
						foreach($itemBrokerMySales as $rank){
							if($tpl->exists("PANEL_ITEM_BROKER_IMG"))
								$tpl->PANEL_ITEM_BROKER_IMG = $rank["itemImg"];
							if($tpl->exists("PANEL_ITEM_BROKER_NAME"))
								$tpl->PANEL_ITEM_BROKER_NAME = $rank["itemName"];
							if($tpl->exists("PANEL_ITEM_BROKER_BUTTON")){
								if($rank["itemType"] > 2){
									if(!empty($rank["itemAuctionPrice"])){
										if(strtotime($rank["itemAuctionTime"]) > time()){
											$btn_my_sales = "<a class=\"btn btn-secondary btn-sm w-100\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-175px;'>Auction ends in: ".$rank["itemAuctionTime"]."</span>\" href=\"?icp=panel&show=item-broker-buy&id=".$rank["itemId"]."\">Bid</a>";
										}else{
											if($getPanelInfo->ownerAuction($rank["itemId"],$_SESSION["ICP_UserName"])){
												$dateForm = strtotime(date("Y-m-d H:i:s"));
												$charOptions = null;
												foreach($getPanelInfo->myCharList($_SESSION["ICP_UserName"]) as $rankChar)
													$charOptions .= "<option value='".$rankChar["charId"]."'".($rankChar["charOnline"] == 1 ? " disabled><em>Online -> </em" : "").">".$rankChar["charName"]."</option>";
												$btn_my_sales = "<a class=\"btn btn-secondary btn-sm modal-alert w-100\" data-toggle=\"modal\" data-target=\"#modal-alert\" description=\"<h2>Congratulations!!!</h2><p>The auction is ended and you were the big winner!!!</p><p>Choose the character to receive the item(s).</p><form action='' method='post' id='".$dateForm."' style='margin:0px; padding:0px;'><input type='hidden' name='itemBrokerId' value='".$rank["itemId"]."'><select class='form-select form-select-sm' name='charId' required>".$charOptions."</select></div></div></form>\" footer='<button name=\"submitItemBroker\" class=\"btn btn-primary\" type=\"submit\" form=\"".$dateForm."\">Receive</button>'>Receive</a>";
											}else
												$btn_my_sales = "<span data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"<span class='itemDetails' style='margin-left:-202px;'>Auction ended and you were not the winner.</span>\"><a class=\"btn btn-secondary btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Blocked</a></span>";
										}
									}else{
										$btn_my_sales ="<a class=\"btn btn-danger btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Error</a>";
									}
								}else{
									$btn_my_sales ="<a class=\"btn btn-danger btn-sm w-100 disabled\" tabindex=\"-1\" role=\"button\" aria-disabled=\"true\" href=\"#\">Error</a>";
								}
								$tpl->PANEL_ITEM_BROKER_BUTTON = $btn_my_sales;
							}
							if($tpl->exists("PANEL_ITEM_BROKER_DETAILS"))
								$tpl->PANEL_ITEM_BROKER_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
							$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_MY_AUCTIONS");
						}
					}else{
						if($tpl->exists("BLOCK_ICP_PANEL_ITEM_BROKER_MY_AUCTIONS_NULL",true))
							$tpl->block("BLOCK_ICP_PANEL_ITEM_BROKER_MY_AUCTIONS_NULL");
					}
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_ENABLE_ITEM_BROKER_MY_AUCTIONS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE",true)){
		if($config["ENABLE_SAFE_ENCHANT_SYSTEM"]){
			$allowedEnchantableGrades = explode(",",$config["allowEnchantItemsGrade"]);
			if(in_array("d",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>D-Grade: ".$config["PRICE_D_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("c",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>C-Grade: ".$config["PRICE_C_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("b",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>B-Grade: ".$config["PRICE_B_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("a",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>A-Grade: ".$config["PRICE_A_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("s",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>S-Grade: ".$config["PRICE_S_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("s80",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>S80-Grade: ".$config["PRICE_S80_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("s84",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>S84-Grade: ".$config["PRICE_S84_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("r",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>R-Grade: ".$config["PRICE_R_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("r95",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>R95-Grade: ".$config["PRICE_R95_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("r99",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>R99-Grade: ".$config["PRICE_R99_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
			if(in_array("r110",$allowedEnchantableGrades)){
				$tpl->ALLOWED_ENCHANT_ITEMS_LIST = "<li>R110-Grade: ".$config["PRICE_R110_GRADE_ITEMS"]." ".$config["DONATE_COIN_NAME"]."</li>";
				$tpl->block("BLOCK_ICP_PANEL_COST_ENCHANT_GRADE");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_SHOW_ENCHANTABLE_ITEMS",true)){
		if($config["ENABLE_SAFE_ENCHANT_SYSTEM"]){
			$id = $_GET["char_id"] ?? 0;
			if(!empty($id) && $id > 0)
				$tpl->block("BLOCK_ICP_PANEL_SHOW_ENCHANTABLE_ITEMS");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_SELECTED_CHARACTER_DETAILS",true)){
		if($page == "enchantment" && $config["ENABLE_SAFE_ENCHANT_SYSTEM"] || $page == "character-changes" && $config["enable_character_changes"]){
			$id = $_GET["char_id"] ?? 0;
			$charInfo = $getPanelInfo->charStatus($_SESSION["ICP_UserName"],empty($id) || $id <= 0 ? 999999999 : $id);
			if(count($charInfo) > 0){
				foreach($charInfo as $rankSelected){
					if($tpl->exists("PANEL_SELECTED_CHARACTER_IMG"))
						$tpl->PANEL_SELECTED_CHARACTER_IMG = $rankSelected["char_image"];
					if($tpl->exists("PANEL_SELECTED_CHARACTER_NAME"))
						$tpl->PANEL_SELECTED_CHARACTER_NAME = $rankSelected["char_name"];
					if($tpl->exists("PANEL_SELECTED_CHARACTER_DETAILS"))
						$tpl->PANEL_SELECTED_CHARACTER_DETAILS = "<div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center m-1 border-bottom' style='color:#fff;'><h5>Information</h5></div><table style='font-size:12px; color:#a3a0a3; min-width:328px;'><tr><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Base class:</font> ".$rankSelected["baseClass"]."<br><font color='#b09979'>Subclass:</font> ".$rankSelected["subClass"]."<br><font color='#b09979'>Nobless:</font> ".$rankSelected["nobles"]."<br><font color='#b09979'>Hero:</font> ".$rankSelected["hero"]."<br><font color='#b09979'>Karma:</font> ".$rankSelected["karma"]."<br><font color='#b09979'>Level:</font> ".$rankSelected["baseLevel"]."<br><font color='#b09979'>Sex:</font> ".$rankSelected["sex"]."</td><td style='padding:5px 10px;' valign='middle'><font color='#b09979'>Online time:</font> ".$rankSelected["onlineTime"]."<br><font color='#b09979'>Last Access:</font> ".$rankSelected["lastAccess"]."<br><font color='#b09979'>Clan:</font> ".str_replace('"',"'",$rankSelected["crest"]).$rankSelected["clan"]."<br><font color='#b09979'>Ally:</font> ".$rankSelected["allyance"]."<br><font color='#b09979'>PvP's:</font> ".$rankSelected["pvp"]."<br><font color='#b09979'>Pk's:</font> ".$rankSelected["pk"]."<br><font color='#b09979'>Location:</font> ".$rankSelected["loc"]."</td></tr></table>";
				}
				$tpl->block("BLOCK_ICP_PANEL_SELECTED_CHARACTER_DETAILS");
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_SELECTED_CHARACTER_DETAILS_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_SELECTED_CHARACTER_DETAILS_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_BASE_CHANGE",true)){
		if($config["ALLOW_CHARACTER_BASE_CLASS_CHANGE"]){
			if($tpl->exists("BLOCK_ICP_PANEL_BASE_CHANGE_OPTIONS",true)){
				for ($x=88; $x<119; $x++){
					if($tpl->exists("BASE_CHANGE_ID"))
						$tpl->BASE_CHANGE_ID = $x;
					if($tpl->exists("BASE_CHANGE_NAME"))
						$tpl->BASE_CHANGE_NAME = $getPanelInfo->classe_name($x);
					$tpl->block("BLOCK_ICP_PANEL_BASE_CHANGE_OPTIONS");
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_BASE_CHANGE");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_NICK_CHANGE",true)){
		if($config["ALLOW_CHARACTER_NICKNAME_CHANGE"])
			$tpl->block("BLOCK_ICP_PANEL_NICK_CHANGE");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_SEX_CHANGE",true)){
		if($config["ALLOW_CHARACTER_SEX_CHANGE"])
			$tpl->block("BLOCK_ICP_PANEL_SEX_CHANGE");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_ACCOUNT_CHANGE",true)){
		if($config["ALLOW_CHARACTER_ACCOUNT_CHANGE"])
			$tpl->block("BLOCK_ICP_PANEL_ACCOUNT_CHANGE");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM",true)){
		if($config["ENABLE_REWARD_SYSTEM"]){
			$reward = array();
			$rewardOnlinetime = null;
			if($config["ALLOW_REWARD_ONLINE_TIME"]){
				$rewardOnlinetime .= "Each ".number_format($config["REWARD_ONLINE_TIME_DAYS"],0,".",".")." day(s) online, you win: ";
				$onlineItems = explode(";", $config["REWARD_ONLINE_TIME_ITEMS"]);
				for($x=0;$x<(count($onlineItems)-1);$x++){
					$OI = explode(",", $onlineItems[$x]);
					$rewardOnlinetime .= $OI[1] > 999 ? $getPanelInfo->kkk($OI[1])." of " : $getPanelInfo->kkk($OI[1])." ";
					$rewardOnlinetime .= $OI[0] == 18000 ? $config["DONATE_COIN_NAME"] : $getPanelInfo->getItemName($OI[0]);
					if($x==(count($onlineItems)-2))
						$rewardOnlinetime .= ".";
					elseif($x==(count($onlineItems)-3))
						$rewardOnlinetime .= " and ";
					else
						$rewardOnlinetime .= ", ";
				}
				array_push($reward, "Online time");
			}
			$rewardPvP = null;
			if($config["ALLOW_REWARD_PVP"]){
				$rewardPvP .= "Each ".number_format($config["REWARD_PVP_COUNT"],0,".",".")." PvP(s), you win: ";
				$pvpItems = explode(";", $config["REWARD_PVP_ITEMS"]);
				for($z=0;$z<(count($pvpItems)-1);$z++){
					$PI = explode(",", $pvpItems[$z]);
					$rewardPvP .= $PI[1] > 999 ? $getPanelInfo->kkk($PI[1])." of " : $getPanelInfo->kkk($PI[1])." ";
					$rewardPvP .= $PI[0] == 18000 ? $config["DONATE_COIN_NAME"] : $getPanelInfo->getItemName($PI[0]);
					if($z==(count($pvpItems)-2))
						$rewardPvP .= ".";
					elseif($z==(count($pvpItems)-3))
						$rewardPvP .= " and ";
					else
						$rewardPvP .= ", ";
				}
				array_push($reward, "PvP(s)");
			}
			$rewardPk = null;
			if($config["ALLOW_REWARD_PK"]){
				$rewardPk .= "Each ".number_format($config["REWARD_PK_COUNT"],0,".",".")." Pk(s), you win: ";
				$pkItems = explode(";", $config["REWARD_PK_ITEMS"]);
				for($y=0;$y<(count($pkItems)-1);$y++){
					$PkI = explode(",", $pkItems[$y]);
					$rewardPk .= $PkI[1] > 999 ? $getPanelInfo->kkk($PkI[1])." of " : $getPanelInfo->kkk($PkI[1])." ";
					$rewardPk .= $PkI[0] == 18000 ? $config["DONATE_COIN_NAME"] : $getPanelInfo->getItemName($PkI[0]);
					if($y==(count($pkItems)-2))
						$rewardPk .= ".";
					elseif($y==(count($pkItems)-3))
						$rewardPk .= " and ";
					else
						$rewardPk .= ", ";
				}
				array_push($reward, "Pk(s)");
			}
			$rewardTitle = null;
			if(count($reward) > 0){
				$rewardTitle .= "Exchange your ";
				for($k=0;$k<count($reward);$k++){
					$rewardTitle .= $reward[$k];
					if($k==(count($reward)-1))
						$rewardTitle .= null;
					elseif($k==(count($reward)-2))
						$rewardTitle .= " or ";
					else
						$rewardTitle .= ", ";
				}
				$rewardTitle .= " for prizes!";
			}
			if($tpl->exists("ICP_PANEL_REWARD_TITLE"))
				$tpl->ICP_PANEL_REWARD_TITLE = $rewardTitle;
			if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_ONLINE_TIME_ITEMS",true)){
				if($tpl->exists("ICP_PANEL_REWARD_ONLINE_TIME"))
					$tpl->ICP_PANEL_REWARD_ONLINE_TIME = $rewardOnlinetime;
				$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_ONLINE_TIME_ITEMS");
			}
			if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_PVP_ITEMS",true)){
				if($tpl->exists("ICP_PANEL_REWARD_PVP"))
					$tpl->ICP_PANEL_REWARD_PVP = $rewardPvP;
				$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_PVP_ITEMS");
			}
			if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_PK_ITEMS",true)){
				if($tpl->exists("ICP_PANEL_REWARD_PK"))
					$tpl->ICP_PANEL_REWARD_PK = $rewardPk;
				$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_PK_ITEMS");
			}
			$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM");
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_DETAILS",true)){
		if($config["ENABLE_REWARD_SYSTEM"] && ($config["ALLOW_REWARD_ONLINE_TIME"] || $config["ALLOW_REWARD_PVP"] || $config["ALLOW_REWARD_PK"])){
			$rewards = explode(";", $getPanelInfo->reward($_SESSION["ICP_UserName"]));
			if($config["ALLOW_REWARD_ONLINE_TIME"]){
				if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_ONLINE_TIME",true)){
					if($tpl->exists("ICP_PANEL_ONLINE_TIME"))
						$tpl->ICP_PANEL_ONLINE_TIME = $getPanelInfo->remainingTime($rewards[0],true);
					if($tpl->exists("ICP_PANEL_ONLINE_TIME_REWARDS"))
						$tpl->ICP_PANEL_ONLINE_TIME_REWARDS = number_format(floor($rewards[0] / (86400 * $config["REWARD_ONLINE_TIME_DAYS"])),0,".",".");
					$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_ONLINE_TIME");
				}
			}
			if($config["ALLOW_REWARD_PVP"]){
				if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_PVP",true)){
					if($tpl->exists("ICP_PANEL_PVP"))
						$tpl->ICP_PANEL_PVP = number_format($rewards[1],0,".",".");
					if($tpl->exists("ICP_PANEL_PVP_REWARDS"))
						$tpl->ICP_PANEL_PVP_REWARDS = number_format(floor($rewards[1] / $config["REWARD_PVP_COUNT"]),0,".",".");
					$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_PVP");
				}
			}
			if($config["ALLOW_REWARD_PK"]){
				if($tpl->exists("BLOCK_ICP_PANEL_REWARD_SYSTEM_PK",true)){
					if($tpl->exists("ICP_PANEL_PK"))
						$tpl->ICP_PANEL_PK = number_format($rewards[2],0,".",".");
					if($tpl->exists("ICP_PANEL_PK_REWARDS"))
						$tpl->ICP_PANEL_PK_REWARDS = number_format(floor($rewards[2] / $config["REWARD_PK_COUNT"]),0,".",".");
					$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_PK");
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_REWARD_SYSTEM_DETAILS");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] == 10){
			if($tpl->exists("CONFIG_ICP_PANEL_CREATE_BY_EMAIL"))
				$tpl->CONFIG_ICP_PANEL_CREATE_BY_EMAIL = $config["CreateAccWithEmail"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_RECOVERY_BY_EMAIL"))
				$tpl->CONFIG_ICP_PANEL_RECOVERY_BY_EMAIL = $config["RecoveryAccWithEmail"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_DEPOSIT"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_DEPOSIT = $config["enable_deposit"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_MERCADOPAGO"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_MERCADOPAGO = $config["enable_mercadopago"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_PAGSEGURO"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_PAGSEGURO = $config["enable_pagseguro"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_PAYPAL"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_PAYPAL = $config["enable_paypal"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_MESSAGES"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_MESSAGES = $config["enable_messages"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_NEWS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_NEWS = $config["enable_news"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_SCREENSHOTS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_SCREENSHOTS = $config["enable_screenshots"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_VIDEOS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_VIDEOS = $config["enable_videos"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_BOSSES"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_BOSSES = $config["enable_bosses"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_CASTLES"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_CASTLES = $config["enable_castles"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_CLAN_HALLS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_CLAN_HALLS = $config["enable_clan_halls"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_PVP"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_PVP = $config["enable_top_pvp"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_CLASS_PVP"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_CLASS_PVP = $config["enable_top_class_pvp"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_PK"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_PK = $config["enable_top_pk"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_CLASS_PK"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_CLASS_PK = $config["enable_top_class_pk"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_ONLINE"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_ONLINE = $config["enable_top_online"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_ADENA"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_ADENA = $config["enable_top_adena"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_CLAN"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_CLAN = $config["enable_top_clan"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_CLAN_BY_PVP"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_CLAN_BY_PVP = $config["TOP_CLAN_BY_PVP"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_OLY"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_OLY = $config["enable_top_oly"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_HERO"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_HERO = $config["enable_top_hero"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_TOP_RAID"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_TOP_RAID = $config["enable_top_raid"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_REWARD"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_REWARD = $config["ENABLE_REWARD_SYSTEM"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_ONLINE_REWARD"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_ONLINE_REWARD = $config["ALLOW_REWARD_ONLINE_TIME"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_PVP_REWARD"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_PVP_REWARD = $config["ALLOW_REWARD_PVP"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_PK_REWARD"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_PK_REWARD = $config["ALLOW_REWARD_PK"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_PRIME_SHOP"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_PRIME_SHOP = $config["ENABLE_PRIME_SHOP"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_ITEM_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_ITEM_BROKER = $config["ENABLE_ITEM_BROKER"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_COMBO_ITEM_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_COMBO_ITEM_BROKER = $config["ALLOW_ITEM_BROKER_SALE_COMBO_ITEMS"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_PVP_ITEM_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_PVP_ITEM_BROKER = $config["ALLOW_ITEM_BROKER_SALE_PVP_ITEMS"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_AUG_ITEM_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_AUG_ITEM_BROKER = $config["ALLOW_ITEM_BROKER_SALE_AUGMENTED_ITEMS"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_AUCTION_ITEM_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_AUCTION_ITEM_BROKER = $config["ALLOW_AUCTION_ITEM_BROKER"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_CHARACTER_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_CHARACTER_BROKER = $config["ENABLE_CHARACTER_BROKER"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_AUCTION_CHARACTER_BROKER"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_AUCTION_CHARACTER_BROKER = $config["ALLOW_AUCTION_CHARACTER_BROKER"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_SAFE_ENCHANT"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_SAFE_ENCHANT = $config["ENABLE_SAFE_ENCHANT_SYSTEM"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_PVP_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_PVP_ITEMS = $config["ALLOW_ENCHANT_PVP_ITEMS"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_AUG_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_AUG_ITEMS = $config["ALLOW_ENCHANT_AUGMENTED_ITEMS"] ? " checked" : null;
			$sellItemsGrade = explode(",",$config["allowSellItemsGrade"]);
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_D_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_D_GRADE_ITEMS = in_array("d",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_C_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_C_GRADE_ITEMS = in_array("c",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_B_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_B_GRADE_ITEMS = in_array("b",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_A_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_A_GRADE_ITEMS = in_array("a",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_S_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_S_GRADE_ITEMS = in_array("s",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_S80_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_S80_GRADE_ITEMS = in_array("s80",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_S84_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_S84_GRADE_ITEMS = in_array("s84",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_R_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_R_GRADE_ITEMS = in_array("r",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_R95_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_R95_GRADE_ITEMS = in_array("r95",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_R99_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_R99_GRADE_ITEMS = in_array("r99",$sellItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SELL_R110_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SELL_R110_GRADE_ITEMS = in_array("r110",$sellItemsGrade) ? " checked" : null;
			$enchantItemsGrade = explode(",",$config["allowEnchantItemsGrade"]);
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_D_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_D_GRADE_ITEMS = in_array("d",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_C_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_C_GRADE_ITEMS = in_array("c",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_B_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_B_GRADE_ITEMS = in_array("b",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_A_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_A_GRADE_ITEMS = in_array("a",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_S_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_S_GRADE_ITEMS = in_array("s",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_S80_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_S80_GRADE_ITEMS = in_array("s80",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_S84_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_S84_GRADE_ITEMS = in_array("s84",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_R_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_R_GRADE_ITEMS = in_array("r",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_R95_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_R95_GRADE_ITEMS = in_array("r95",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_R99_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_R99_GRADE_ITEMS = in_array("r99",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ENCHANT_R110_GRADE_ITEMS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ENCHANT_R110_GRADE_ITEMS = in_array("r110",$enchantItemsGrade) ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_CHARACTER_CHANGES"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_CHARACTER_CHANGES = $config["enable_character_changes"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_BASE_CHANGE"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_BASE_CHANGE = $config["ALLOW_CHARACTER_BASE_CLASS_CHANGE"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SEX_CHANGE"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SEX_CHANGE = $config["ALLOW_CHARACTER_SEX_CHANGE"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_NICK_CHANGE"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_NICK_CHANGE = $config["ALLOW_CHARACTER_NICKNAME_CHANGE"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_ACC_CHANGE"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_ACC_CHANGE = $config["ALLOW_CHARACTER_ACCOUNT_CHANGE"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_TIME_ZONE"))
				$tpl->CONFIG_ICP_PANEL_TIME_ZONE = $getPanelInfo->select_Timezone($config["TIME_ZONE"]);
			if($tpl->exists("CONFIG_ICP_PANEL_PC_TEMPLATES"))
				$tpl->CONFIG_ICP_PANEL_PC_TEMPLATES = $getPanelInfo->showDir("templates/",$config["TEMPLATE"]);
			if($tpl->exists("CONFIG_ICP_PANEL_OLY_PERIOD"))
				$tpl->CONFIG_ICP_PANEL_OLY_PERIOD = $getPanelInfo->olympiadsPeriod($config["OLY_PERIOD_DAYS"]);
			if($tpl->exists("CONFIG_ICP_PANEL_PRIME_SHOP_LOC"))
				$tpl->CONFIG_ICP_PANEL_PRIME_SHOP_LOC = $getPanelInfo->depositLoc($config["PRIME_SHOP_LOC_PLACE"]);
			if($tpl->exists("CONFIG_ICP_PANEL_ITEM_BROKER_LOC"))
				$tpl->CONFIG_ICP_PANEL_ITEM_BROKER_LOC = $getPanelInfo->depositLoc($config["ITEM_BROKER_LOC_PLACE"]);
			if($tpl->exists("CONFIG_ICP_PANEL_REWARD_LOC"))
				$tpl->CONFIG_ICP_PANEL_REWARD_LOC = $getPanelInfo->depositLoc($config["REWARD_SYSTEM_LOC"]);
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_CHECK_SERVER_STATUS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_CHECK_SERVER_STATUS = $config["enable_servers_check"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_FORCE_LOGIN_ONLINE"))
				$tpl->CONFIG_ICP_PANEL_FORCE_LOGIN_ONLINE = $config["force_login_server"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_FORCE_GAME_ONLINE"))
				$tpl->CONFIG_ICP_PANEL_FORCE_GAME_ONLINE = $config["force_game_server"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ALLOW_SHOW_STATS"))
				$tpl->CONFIG_ICP_PANEL_ALLOW_SHOW_STATS = $config["allow_server_stats"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_ENABLE_FAKE_PLAYERS"))
				$tpl->CONFIG_ICP_PANEL_ENABLE_FAKE_PLAYERS = $config["enable_fake_players"] ? " checked" : null;
			if($tpl->exists("CONFIG_ICP_PANEL_FAKE_PLAYERS_NUM"))
				$tpl->CONFIG_ICP_PANEL_FAKE_PLAYERS_NUM = $getPanelInfo->percentageFakePlayers($config["fake_players_number"]);
			$tpl->block("CONFIG_ICP_PANEL");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_GIVE_PRIVILEGES",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] == 10){
			$tpl->block("CONFIG_ICP_PANEL_GIVE_PRIVILEGES");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_GIVE_PRIVILEGES_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_GIVE_PRIVILEGES_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_SEND_DONATIONS",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 9){
			$tpl->block("CONFIG_ICP_PANEL_SEND_DONATIONS");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_SEND_DONATIONS_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_SEND_DONATIONS_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_PRIME_SHOP",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 9){
			$tpl->block("CONFIG_ICP_PANEL_PRIME_SHOP");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_PRIME_SHOP_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_PRIME_SHOP_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_APPROVE_VIDEOS",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 7){
			if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS",true)){
				if($config["enable_videos"]){
					foreach($getPanelInfo->showVideos(0,"id DESC",0,false) as $rank){
						if($tpl->exists("INDEX_VIDEOS_ID"))
							$tpl->INDEX_VIDEOS_ID = $rank["videosId"];
						if($tpl->exists("INDEX_VIDEOS_IMG"))
							$tpl->INDEX_VIDEOS_IMG = $rank["videosImg"];
						if($tpl->exists("INDEX_VIDEOS_AUTHOR"))
							$tpl->INDEX_VIDEOS_AUTHOR = $rank["videosAuthor"];
						if($tpl->exists("INDEX_VIDEOS_LEGEND"))
							$tpl->INDEX_VIDEOS_LEGEND = $rank["videosLegend"];
						if($tpl->exists("INDEX_VIDEOS_DATE"))
							$tpl->INDEX_VIDEOS_DATE = $rank["videosDate"];
						if($tpl->exists("INDEX_VIDEOS_LINK"))
							$tpl->INDEX_VIDEOS_LINK = $rank["videosLink"];
						if($tpl->exists("INDEX_VIDEOS_URL"))
							$tpl->INDEX_VIDEOS_URL = $rank["videosUrl"];
						$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS");
					}
				}else{
					if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS_NULL",true))
						$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS_NULL");
				}
			}else{
				if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS_NULL",true))
					$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_VIDEOS_NULL");
			}
			$tpl->block("CONFIG_ICP_PANEL_APPROVE_VIDEOS");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_APPROVE_VIDEOS_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_APPROVE_VIDEOS_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_APPROVE_SCREENSHOTS",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 7){
			if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS",true)){
				if($config["enable_screenshots"]){
					foreach($getPanelInfo->showScreenshots(0,"id DESC",0,false) as $rank){
						if($tpl->exists("INDEX_SCREENSHOTS_ID"))
							$tpl->INDEX_SCREENSHOTS_ID = $rank["screenshotId"];
						if($tpl->exists("INDEX_SCREENSHOTS_IMG"))
							$tpl->INDEX_SCREENSHOTS_IMG = $rank["screenshotImg"];
						if($tpl->exists("INDEX_SCREENSHOTS_AUTHOR"))
							$tpl->INDEX_SCREENSHOTS_AUTHOR = $rank["screenshotAuthor"];
						if($tpl->exists("INDEX_SCREENSHOTS_LEGEND"))
							$tpl->INDEX_SCREENSHOTS_LEGEND = $rank["screenshotLegend"];
						if($tpl->exists("INDEX_SCREENSHOTS_DATE"))
							$tpl->INDEX_SCREENSHOTS_DATE = $rank["screenshotDate"];
						if($tpl->exists("INDEX_SCREENSHOTS_NUM"))
							$tpl->INDEX_SCREENSHOTS_NUM = $rank["screenshotNum"];
						$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS");
					}
				}else{
					if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS_NULL",true))
						$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS_NULL");
				}
			}else{
				if($tpl->exists("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS_NULL",true))
					$tpl->block("CONFIG_ICP_PANEL_TO_APPROVE_SCREENSHOTS_NULL");
			}
			$tpl->block("CONFIG_ICP_PANEL_APPROVE_SCREENSHOTS");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_APPROVE_SCREENSHOTS_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_APPROVE_SCREENSHOTS_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_NEWS",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 8){
			$tpl->block("CONFIG_ICP_PANEL_NEWS");
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_NEWS_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_NEWS_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_PROFILE",true)){
		if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 6){
			$staff = $getPanelInfo->showStaff($_SESSION["ICP_UserName"]);
			if($tpl->exists("CONFIG_GM_PROFILE_OFFICE"))
				$tpl->CONFIG_GM_PROFILE_OFFICE = $_SESSION["ICP_UserAccessLevel"] < 10 ? $_SESSION["ICP_UserAccessLevel"] > 7 ? '<div class="border-start border-end bg-warning text-dark" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Global</div>' : '<div class="border-start border-end bg-secondary text-light" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Helper</div>' : '<div class="border-start border-end bg-dark text-light" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span>Administrator</div>';
			if(count($staff) > 0){
				foreach($staff as $rank){
					if($tpl->exists("CONFIG_GM_PROFILE_NAME1"))
						$tpl->CONFIG_GM_PROFILE_NAME1 = !empty($rank["gmName"]) ? $rank["gmName"] : "GM Anonymous";
					if($tpl->exists("CONFIG_GM_PROFILE_NAME2"))
						$tpl->CONFIG_GM_PROFILE_NAME2 = $rank["gmName"] ?? "";
					if($tpl->exists("CONFIG_GM_PROFILE_EMAIL"))
						$tpl->CONFIG_GM_PROFILE_EMAIL = $rank["gmEmail"] ?? "";
					if($tpl->exists("CONFIG_GM_PROFILE_IMG"))
						$tpl->CONFIG_GM_PROFILE_IMG = !empty($rank["gmImg"]) ? $rank["gmImg"] : "noimage.jpg";
					$tpl->block("CONFIG_ICP_PANEL_PROFILE");
				}
			}else{
				if($tpl->exists("CONFIG_GM_PROFILE_NAME1"))
					$tpl->CONFIG_GM_PROFILE_NAME1 = "GM Anonymous";
				if($tpl->exists("CONFIG_GM_PROFILE_NAME2"))
					$tpl->CONFIG_GM_PROFILE_NAME2 = "";
				if($tpl->exists("CONFIG_GM_PROFILE_EMAIL"))
					$tpl->CONFIG_GM_PROFILE_EMAIL = "";
				if($tpl->exists("CONFIG_GM_PROFILE_IMG"))
					$tpl->CONFIG_GM_PROFILE_IMG = "noimage.jpg";
				$tpl->block("CONFIG_ICP_PANEL_PROFILE");
			}
		}else{
			if($tpl->exists("CONFIG_ICP_PANEL_PROFILE_NULL",true))
				$tpl->block("CONFIG_ICP_PANEL_PROFILE_NULL");
		}
	}
	if($tpl->exists("CONFIG_ICP_PANEL_MESSAGES",true)){
		if(!$config["enable_messages"]){
			if($tpl->exists("CONFIG_ICP_PANEL_MESSAGES_DISABLED",true))
				$tpl->block("CONFIG_ICP_PANEL_MESSAGES_DISABLED");
		}else{
			if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 6){
				$id = $_GET["id"] ?? 0;
				if($id > 0){
					if($tpl->exists("CONFIG_ICP_PANEL_READ_MESSAGES",true)){
						if($tpl->exists("CONFIG_ICP_PANEL_READ_MESSAGE_DETAILS",true)){
							if(!empty($_GET["delete"]) && $_GET["delete"] == "true"){
								echo $getPanelInfo->deleteMsg(empty($id) || $id <= 0 ? 999999999 : $id,$_SESSION["ICP_UserAccessLevel"]);
								exit;
							}
							if(!empty($_GET["delete_reply"]) && $_GET["delete_reply"] == "true" && !empty($_GET["reply_id"])){
								echo $getPanelInfo->deleteReplyMsg(empty($id) || $id <= 0 ? 999999999 : $id,$_GET["reply_id"],$_SESSION["ICP_UserAccessLevel"]);
								exit;
							}
							if(!empty($_GET["lock"]) && $_GET["lock"] == "true"){
								echo $getPanelInfo->lockMsg(empty($id) || $id <= 0 ? 999999999 : $id,$_SESSION["ICP_UserAccessLevel"]);
								exit;
							}
							$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
							$reg_inicial = $pag * 10;
							$quant_pag = ceil(count($getPanelInfo->showMsg(empty($id) || $id <= 0 ? 999999999 : $id))/10);
							if($tpl->exists("BLOCK_PAGINATION2",true)){
								if($tpl->exists("PAGINATION"))
									$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
								$tpl->block("BLOCK_PAGINATION2");
							}
							$msgId = $getPanelInfo->showMsg(empty($id) || $id <= 0 ? 999999999 : $id,$reg_inicial.", 10");
							if(count($msgId) > 0){
								foreach($msgId as $rank){
									if($tpl->exists("CONTACT_MSG_TITLE"))
										$tpl->CONTACT_MSG_TITLE = $rank["msgTitle"];
									if($tpl->exists("CONTACT_MSG_TEXT"))
										$tpl->CONTACT_MSG_TEXT = nl2br($rank["msgText"]);
									$attach = explode(".",$rank["msgAttachment"]);
									if($tpl->exists("CONTACT_MSG_ATTACH"))
										$tpl->CONTACT_MSG_ATTACH = !empty($rank["msgAttachment"]) ? "<div class=\"input-group mb-3\" style=\"position:absolute;bottom:0px;overflow:hidden;width:auto;\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">Attached: </span></div><a ".($attach[1] == "pdf" ? "target=\"_blank\" href=\"images/attachment/".$rank["msgAttachment"]."\"" : "pbsrc=\"images/attachment/".$rank["msgAttachment"]."\" pbCaption=\"\" onclick=\"Pop(this,50,'PopBoxImageLarge');\" href=\"javascript:void(0)\"")." class=\"form-control\">".$rank["msgAttachment"]."</a></div>" : null;
									$accLevel = $getPanelInfo->getAccessLevel($rank["msgAnswered"]);
									if($tpl->exists("CONTACT_MSG_ANSWERED"))
										$tpl->CONTACT_MSG_ANSWERED = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"] : $rank["msgAnswered"] : $rank["msgAnswered"];
									if($tpl->exists("CONTACT_MSG_DATE"))
										$tpl->CONTACT_MSG_DATE = $rank["msgDate"];
									if($tpl->exists("CONTACT_REPLY_ID"))
										$tpl->CONTACT_REPLY_ID = ltrim($rank["replyId"],0) ?? "";
									if($tpl->exists("CONTACT_MSG_ID"))
										$tpl->CONTACT_MSG_ID = ltrim($rank["msgId"],0) ?? "";
									if($tpl->exists("CONTACT_MSG_PROFILE_OFFICE"))
										$tpl->CONTACT_MSG_PROFILE_OFFICE = $accLevel < 10 ? $accLevel < 8 ? $accLevel == 0 ? '<div class="border-start border-end bg-light text-secondary text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> Player</div>' : '<div class="border-start border-end bg-secondary text-light text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Helper</div>' : '<div class="border-start border-end bg-warning text-dark text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Global</div>' : '<div class="border-start border-end bg-dark text-light text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span>Administrator</div>';
									if($tpl->exists("CONTACT_MSG_PROFILE_IMG"))
										$tpl->CONTACT_MSG_PROFILE_IMG = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"] : "noimage.jpg" : "noimage.jpg";
									$tpl->block("CONFIG_ICP_PANEL_READ_MESSAGE_DETAILS");
								}
								if($tpl->exists("CONTACT_BUTTON_LOCK")){
									if($rank["msgStatus"] == 1)
										$tpl->CONTACT_BUTTON_LOCK = "<span data-feather=\"lock\" style=\"position:relative;top:-2px;\"></span> Lock message";
									else
										$tpl->CONTACT_BUTTON_LOCK = "<span data-feather=\"unlock\" style=\"position:relative;top:-2px;\"></span> Unlock message";
								}
								if($tpl->exists("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY",true)){
									if($rank["msgStatus"] == 1)
										$tpl->block("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY");
									else
										if($tpl->exists("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY_NULL",true))
											$tpl->block("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY_NULL");
								}else{
									if($tpl->exists("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY_NULL",true))
										$tpl->block("CONFIG_ICP_PANEL_MSG_ALLOW_REPLY_NULL");
								}
								$tpl->block("CONFIG_ICP_PANEL_READ_MESSAGES");
							}else{
								if($tpl->exists("CONFIG_ICP_PANEL_READ_MESSAGES_NULL",true))
									$tpl->block("CONFIG_ICP_PANEL_READ_MESSAGES_NULL");
							}
						}else{
							if($tpl->exists("CONFIG_ICP_PANEL_READ_MESSAGES_NULL",true))
								$tpl->block("CONFIG_ICP_PANEL_READ_MESSAGES_NULL");
						}
					}else{
						if($tpl->exists("CONFIG_ICP_PANEL_READ_MESSAGES_NULL",true))
							$tpl->block("CONFIG_ICP_PANEL_READ_MESSAGES_NULL");
					}
				}else{
					if(!empty($_GET["new"] ?? "")){
						if($tpl->exists("CONFIG_ICP_PANEL_NEW_MESSAGE",true)){
							$tpl->block("CONFIG_ICP_PANEL_NEW_MESSAGE");
						}
					}else{
						if($tpl->exists("CONFIG_ICP_PANEL_UNREAD_MESSAGES",true)){
							if($tpl->exists("BLOCK_MSGS_TO_REPLY",true)){
								$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
								$reg_inicial = $pag * 10;
								$quant_pag = ceil(count($getPanelInfo->showMsgs())/10);
								if($tpl->exists("BLOCK_PAGINATION",true)){
									if($tpl->exists("PAGINATION"))
										$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
									$tpl->block("BLOCK_PAGINATION");
								}
								$messages = $getPanelInfo->showMsgs($reg_inicial.", 10");
								if(count($messages) > 0){
									$fixCss = 0;
									foreach($messages as $rank){
										if($tpl->exists("CONTACT_MSG_TITLE"))
											$tpl->CONTACT_MSG_TITLE = $rank["msgTitle"] ?? "";
										if($tpl->exists("CONTACT_MSG_DATE"))
											$tpl->CONTACT_MSG_DATE = $rank["msgDate"] ?? "";
										if($tpl->exists("CONTACT_MSG_ID"))
											$tpl->CONTACT_MSG_ID = ltrim($rank["msgId"],0) ?? "";
										if($tpl->exists("CONTACT_REPLY_COUNT"))
											$tpl->CONTACT_REPLY_COUNT = $rank["repliesCount"] ?? 0;
										if($tpl->exists("CONTACT_REPLY_ID"))
											$tpl->CONTACT_REPLY_ID = ltrim($rank["replyId"],0) ?? "";
										$accLevel1 = $getPanelInfo->getAccessLevel($rank["msgAuthor"]);
										if($tpl->exists("CONTACT_MSG_AUTHOR"))
											$tpl->CONTACT_MSG_AUTHOR = $accLevel1 > 0 ? !empty($getPanelInfo->showStaff($rank["msgAuthor"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAuthor"])[0]["gmName"] : $rank["msgAuthor"] : $rank["msgAuthor"];
										$accLevel = $getPanelInfo->getAccessLevel($rank["msgAnswered"]);
										if($tpl->exists("CONTACT_MSG_ANSWERED"))
											$tpl->CONTACT_MSG_ANSWERED = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"] : $rank["msgAnswered"] : $rank["msgAnswered"];
										if($tpl->exists("CONTACT_MSG_PROFILE_IMG"))
											$tpl->CONTACT_MSG_PROFILE_IMG = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"] : "noimage.jpg" : "noimage.jpg";
										if($tpl->exists("CONTACT_MSG_ACTIVE_DIV"))
											$tpl->CONTACT_MSG_ACTIVE_DIV = $rank["msgStatus"] == 1 ? $rank["msgAuthor"] == $rank["msgAnswered"] ? "bg-light" : "bg-white" : "bg-white";
										if($tpl->exists("CONTACT_MSG_ACTIVE_LINK"))
											$tpl->CONTACT_MSG_ACTIVE_LINK = $rank["msgStatus"] == 1 ? $rank["msgAuthor"] == $rank["msgAnswered"] ? " font-weight:bold;" : "" : "";
										if($tpl->exists("CONTACT_MSG_FIX_CSS_TOP"))
											$tpl->CONTACT_MSG_FIX_CSS_TOP = $fixCss == 0 ? " rounded-top" : "";
										if($tpl->exists("CONTACT_MSG_FIX_CSS_BOTTOM"))
											$tpl->CONTACT_MSG_FIX_CSS_BOTTOM = $fixCss == (count($messages)-1) ? " rounded-bottom" : "";
										$fixCss++;
										$tpl->block("BLOCK_MSGS_TO_REPLY");
									}
									$tpl->block("CONFIG_ICP_PANEL_UNREAD_MESSAGES");
								}else{
									if($tpl->exists("CONFIG_ICP_PANEL_UNREAD_MESSAGES_NULL",true))
										$tpl->block("CONFIG_ICP_PANEL_UNREAD_MESSAGES_NULL");
								}
							}else{
								if($tpl->exists("BLOCK_MSGS_TO_REPLY_NULL",true))
									$tpl->block("BLOCK_MSGS_TO_REPLY_NULL");
							}
						}else{
							if($tpl->exists("CONFIG_ICP_PANEL_UNREAD_MESSAGES_NULL",true))
								$tpl->block("CONFIG_ICP_PANEL_UNREAD_MESSAGES_NULL");
						}
					}
				}
				$tpl->block("CONFIG_ICP_PANEL_MESSAGES");
			}else{
				if($tpl->exists("CONFIG_ICP_PANEL_MESSAGES_NULL",true))
					$tpl->block("CONFIG_ICP_PANEL_MESSAGES_NULL");
			}
		}
	}
	if($tpl->exists("BLOCK_ICP_PANEL_MESSAGES",true)){
		if(!$config["enable_messages"]){
			if($tpl->exists("BLOCK_ICP_PANEL_MESSAGES_DISABLED",true))
				$tpl->block("BLOCK_ICP_PANEL_MESSAGES_DISABLED");
		}else{
			if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] >= 0){
				$id = $_GET["id"] ?? 0;
				if($id > 0){
					if($tpl->exists("BLOCK_ICP_PANEL_READ_MESSAGES",true)){
						if($tpl->exists("BLOCK_ICP_PANEL_READ_MESSAGE_DETAILS",true)){
							$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
							$reg_inicial = $pag * 10;
							$quant_pag = ceil(count($getPanelInfo->showMsg(empty($id) || $id <= 0 ? 999999999 : $id,0,$_SESSION["ICP_UserName"]))/10);
							if($tpl->exists("BLOCK_PAGINATION2",true)){
								if($tpl->exists("PAGINATION"))
									$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
								$tpl->block("BLOCK_PAGINATION2");
							}
							$msgId = $getPanelInfo->showMsg(empty($id) || $id <= 0 ? 999999999 : $id,$reg_inicial.", 10",$_SESSION["ICP_UserName"]);
							if(count($msgId) > 0){
								foreach($msgId as $rank){
									if($tpl->exists("CONTACT_MSG_TITLE"))
										$tpl->CONTACT_MSG_TITLE = $rank["msgTitle"];
									if($tpl->exists("CONTACT_MSG_TEXT"))
										$tpl->CONTACT_MSG_TEXT = nl2br($rank["msgText"]);
									$accLevel = $getPanelInfo->getAccessLevel($rank["msgAnswered"]);
									if($tpl->exists("CONTACT_MSG_ANSWERED"))
										$tpl->CONTACT_MSG_ANSWERED = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"] : $rank["msgAnswered"] : $rank["msgAnswered"];
									if($tpl->exists("CONTACT_MSG_DATE"))
										$tpl->CONTACT_MSG_DATE = $rank["msgDate"];
									if($tpl->exists("CONTACT_REPLY_ID"))
										$tpl->CONTACT_REPLY_ID = ltrim($rank["replyId"],0) ?? "";
									if($tpl->exists("CONTACT_MSG_ID"))
										$tpl->CONTACT_MSG_ID = ltrim($rank["msgId"],0) ?? "";
									if($tpl->exists("CONTACT_MSG_PROFILE_OFFICE"))
										$tpl->CONTACT_MSG_PROFILE_OFFICE = $accLevel < 10 ? $accLevel < 8 ? $accLevel == 0 ? '<div class="border-start border-end bg-light text-secondary text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> Player</div>' : '<div class="border-start border-end bg-secondary text-light text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Helper</div>' : '<div class="border-start border-end bg-warning text-dark text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span> GM Global</div>' : '<div class="border-start border-end bg-dark text-light text-center" style="width:100px;font-size:11px; padding:3px 0px; font-weight:bold;"><span data-feather="award" style="width:14px;"></span>Administrator</div>';
									if($tpl->exists("CONTACT_MSG_PROFILE_IMG"))
										$tpl->CONTACT_MSG_PROFILE_IMG = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"] : "noimage.jpg" : "noimage.jpg";
									$tpl->block("BLOCK_ICP_PANEL_READ_MESSAGE_DETAILS");
								}
								if($tpl->exists("CONTACT_BUTTON_LOCK")){
									if($rank["msgStatus"] == 1)
										$tpl->CONTACT_BUTTON_LOCK = "<span data-feather=\"lock\" style=\"position:relative;top:-2px;\"></span> Lock message";
									else
										$tpl->CONTACT_BUTTON_LOCK = "<span data-feather=\"unlock\" style=\"position:relative;top:-2px;\"></span> Unlock message";
								}
								if($tpl->exists("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY",true)){
									if($rank["msgStatus"] == 1)
										$tpl->block("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY");
									else
										if($tpl->exists("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY_NULL",true))
											$tpl->block("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY_NULL");
								}else{
									if($tpl->exists("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY_NULL",true))
										$tpl->block("BLOCK_ICP_PANEL_MSG_ALLOW_REPLY_NULL");
								}
								$tpl->block("BLOCK_ICP_PANEL_READ_MESSAGES");
							}else{
								if($tpl->exists("BLOCK_ICP_PANEL_READ_MESSAGES_NULL",true))
									$tpl->block("BLOCK_ICP_PANEL_READ_MESSAGES_NULL");
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_READ_MESSAGES_NULL",true))
								$tpl->block("BLOCK_ICP_PANEL_READ_MESSAGES_NULL");
						}
					}else{
						if($tpl->exists("BLOCK_ICP_PANEL_READ_MESSAGES_NULL",true))
							$tpl->block("BLOCK_ICP_PANEL_READ_MESSAGES_NULL");
					}
				}else{
					if(!empty($_GET["new"] ?? "")){
						if($tpl->exists("BLOCK_ICP_PANEL_NEW_MESSAGE",true)){
							$tpl->block("BLOCK_ICP_PANEL_NEW_MESSAGE");
						}
					}else{
						if($tpl->exists("BLOCK_ICP_PANEL_UNREAD_MESSAGES",true)){
							if($tpl->exists("BLOCK_MSGS_TO_REPLY",true)){
								$pag = empty($getPanelInfo->filter($_GET["page"] ?? "")) ? 0 : $getPanelInfo->filter($_GET["page"] ?? "");
								$reg_inicial = $pag * 10;
								$quant_pag = ceil(count($getPanelInfo->showMsgs(0,$_SESSION["ICP_UserName"]))/10);
								if($tpl->exists("BLOCK_PAGINATION",true)){
									if($tpl->exists("PAGINATION"))
										$tpl->PAGINATION = $getPanelInfo->paginationPanel($pag, $quant_pag);
									$tpl->block("BLOCK_PAGINATION");
								}
								$messages = $getPanelInfo->showMsgs($reg_inicial.", 10",$_SESSION["ICP_UserName"]);
								if(count($messages) > 0){
									$fixCss = 0;
									foreach($messages as $rank){
										if($tpl->exists("CONTACT_MSG_TITLE"))
											$tpl->CONTACT_MSG_TITLE = $rank["msgTitle"] ?? "";
										if($tpl->exists("CONTACT_MSG_DATE"))
											$tpl->CONTACT_MSG_DATE = $rank["msgDate"] ?? "";
										if($tpl->exists("CONTACT_MSG_ID"))
											$tpl->CONTACT_MSG_ID = ltrim($rank["msgId"],0) ?? "";
										if($tpl->exists("CONTACT_REPLY_COUNT"))
											$tpl->CONTACT_REPLY_COUNT = $rank["repliesCount"] ?? 0;
										if($tpl->exists("CONTACT_REPLY_ID"))
											$tpl->CONTACT_REPLY_ID = ltrim($rank["replyId"],0) ?? "";
										$accLevel1 = $getPanelInfo->getAccessLevel($rank["msgAuthor"]);
										if($tpl->exists("CONTACT_MSG_AUTHOR"))
											$tpl->CONTACT_MSG_AUTHOR = $accLevel1 > 0 ? !empty($getPanelInfo->showStaff($rank["msgAuthor"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAuthor"])[0]["gmName"] : $rank["msgAuthor"] : $rank["msgAuthor"];
										$accLevel = $getPanelInfo->getAccessLevel($rank["msgAnswered"]);
										if($tpl->exists("CONTACT_MSG_ANSWERED"))
											$tpl->CONTACT_MSG_ANSWERED = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmName"] : $rank["msgAnswered"] : $rank["msgAnswered"];
										if($tpl->exists("CONTACT_MSG_PROFILE_IMG"))
											$tpl->CONTACT_MSG_PROFILE_IMG = $accLevel > 0 ? !empty($getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"]) ? $getPanelInfo->showStaff($rank["msgAnswered"])[0]["gmImg"] : "noimage.jpg" : "noimage.jpg";
										if($tpl->exists("CONTACT_MSG_ACTIVE_DIV"))
											$tpl->CONTACT_MSG_ACTIVE_DIV = $rank["msgStatus"] == 1 ? $rank["msgAuthor"] == $rank["msgAnswered"] ? "bg-white" : "bg-light" : "bg-white";
										if($tpl->exists("CONTACT_MSG_ACTIVE_LINK"))
											$tpl->CONTACT_MSG_ACTIVE_LINK = $rank["msgStatus"] == 1 ? $rank["msgAuthor"] == $rank["msgAnswered"] ? "" : " font-weight:bold;" : "";
										if($tpl->exists("CONTACT_MSG_FIX_CSS_TOP"))
											$tpl->CONTACT_MSG_FIX_CSS_TOP = $fixCss == 0 ? " rounded-top" : "";
										if($tpl->exists("CONTACT_MSG_FIX_CSS_BOTTOM"))
											$tpl->CONTACT_MSG_FIX_CSS_BOTTOM = $fixCss == (count($messages)-1) ? " rounded-bottom" : "";
										$fixCss++;
										$tpl->block("BLOCK_MSGS_TO_REPLY");
									}
									$tpl->block("BLOCK_ICP_PANEL_UNREAD_MESSAGES");
								}else{
									if($tpl->exists("BLOCK_ICP_PANEL_UNREAD_MESSAGES_NULL",true))
										$tpl->block("BLOCK_ICP_PANEL_UNREAD_MESSAGES_NULL");
								}
							}else{
								if($tpl->exists("BLOCK_MSGS_TO_REPLY_NULL",true))
									$tpl->block("BLOCK_MSGS_TO_REPLY_NULL");
							}
						}else{
							if($tpl->exists("BLOCK_ICP_PANEL_UNREAD_MESSAGES_NULL",true))
								$tpl->block("BLOCK_ICP_PANEL_UNREAD_MESSAGES_NULL");
						}
					}
				}
				$tpl->block("BLOCK_ICP_PANEL_MESSAGES");
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_MESSAGES_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_MESSAGES_NULL");
			}
		}
	}
	if($tpl->exists("LINK_CHECKOUT_MP")){
		if($config["mp_currency"] == "BRL")
			$linkCheckoutMp = "https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js";
		elseif($config["mp_currency"] == "UYU")
			$linkCheckoutMp = "https://www.mercadopago.com.uy/integrations/v1/web-payment-checkout.js";
		elseif($config["mp_currency"] == "COP")
			$linkCheckoutMp = "https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js";
		elseif($config["mp_currency"] == "PEN")
			$linkCheckoutMp = "https://www.mercadopago.com.pe/integrations/v1/web-payment-checkout.js";
		elseif($config["mp_currency"] == "VES")
			$linkCheckoutMp = "https://www.mercadopago.com.ve/integrations/v1/web-payment-checkout.js";
		elseif($config["mp_currency"] == "MXN")
			$linkCheckoutMp = "https://www.mercadopago.com.mx/integrations/v1/web-payment-checkout.js";
		else
			$linkCheckoutMp = "https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js";
		$tpl->LINK_CHECKOUT_MP = $linkCheckoutMp;
	}
	if($tpl->exists("BLOCK_ICP_PANEL_DONATION",true)){
		if($config["enable_deposit"] || $config["enable_mercadopago"] || $config["enable_pagseguro"] || $config["enable_paypal"]){
			if($tpl->exists("BLOCK_ICP_PANEL_DONATION_METHODS",true)){
				$mult_donate = 10; // doações de 10 em 10 reais
				$donateMPOption = "<div class=\"card mb-3\"><div class=\"card-header text-center\">Donate in Reais with Mercado Pago</div><div class=\"card-body\"><center><img src=\"images/miscs/mercadopago.png\" class=\"w-75 mb-3\" style=\"max-width:350px; max-height:150px;\"></center><select class=\"form-select mb-3\" id=\"mercadopago\">";
				$donatePSOption = "<div class=\"card mb-3\"><div class=\"card-header text-center\">Donate in Reais with PagSeguro</div><div class=\"card-body\"><center><img src=\"images/miscs/pagseguro.png\" class=\"w-75 mb-3\" style=\"max-width:350px; max-height:150px;\"></center><select class=\"form-select mb-3\" id=\"pagseguro\">";
				$donatePPOption = "<div class=\"card mb-3\"><div class=\"card-header text-center\">Donate in Dollar with PayPal</div><div class=\"card-body\"><center><img src=\"images/miscs/paypal.png\" class=\"w-75 mb-3\" style=\"max-width:350px; max-height:150px;\"></center><select class=\"form-select mb-3\" id=\"paypal\">";
				for($x=1;$x<=25;$x++){
					$donateMPOption .= "<option value=\"".ceil($x*$mult_donate)."\">".($x*$mult_donate)." ".$config["DONATE_COIN_NAME"]." for ".$getPanelInfo->currency($config["mp_currency"]).ceil(($x*$mult_donate)/$config["mp_amount"]).".00 (".$config["mp_currency"].")</option>";
					$donatePSOption .= "<option value=\"".ceil($x*$mult_donate)."\">".($x*$mult_donate)." ".$config["DONATE_COIN_NAME"]." for ".$getPanelInfo->currency($config["ps_currency"]).ceil(($x*$mult_donate)/$config["ps_amount"]).".00 (".$config["ps_currency"].")</option>";
					$donatePPOption .= "<option value=\"".ceil($x*$mult_donate)."\">".($x*$mult_donate)." ".$config["DONATE_COIN_NAME"]." for ".$getPanelInfo->currency($config["pp_currency"]).ceil(($x*$mult_donate)/$config["pp_amount"]).".00 (".$config["pp_currency"].")</option>";
				}
				$donateMPOption .= "</select><div class=\"text-center\"><button class=\"btn btn-primary\" id=\"clickMP\">Donate now</button></div></div></div>";
				$donatePSOption .= "</select><div class=\"text-center\"><button class=\"btn btn-primary\" id=\"clickPS\">Donate now</button><form id=\"submitPagseguro\" action=\"https://pagseguro.uol.com.br/checkout/v2/payment.html\" method=\"post\" onsubmit=\"PagSeguroLightbox(this);return false;\" style=\"margin:0px;padding:0px;\"><input type=\"hidden\" name=\"code\" id=\"PScode\" value=\"\"></form></div></div></div>";
				$donatePPOption .= "</select><div class=\"text-center\"><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" style=\"margin:0px; padding:0px;\" target=\"_blank\"><input type=\"hidden\" name=\"cmd\" value=\"_xclick\"><input type=\"hidden\" name=\"business\" value=\"".$config["pp_email"]."\"><input type=\"hidden\" name=\"item_name\" value=\"".$config["DONATE_COIN_NAME"]."\"><input type=\"hidden\" name=\"quantity\" id=\"paypalQuantity\" value=\"".$mult_donate."\"><input type=\"hidden\" name=\"amount\" value=\"".number_format(1/$config["pp_amount"],2,".",".")."\"><input type=\"hidden\" name=\"tax\" value=\"0.00\"><input type=\"hidden\" name=\"no_note\" value=\"1\"><input type=\"hidden\" name=\"no_shipping\" value=\"1\"><input type=\"hidden\" name=\"charset\" value=\"utf-8\"><input type=\"hidden\" name=\"currency_code\" value=\"".$config["pp_currency"]."\"><input type=\"hidden\" name=\"custom\" value=\"".$_SESSION["ICP_UserName"]."\"><input type=\"submit\" class=\"btn btn-primary\" value=\"Donate now\"></form></div></div></div>";
				$donateMethod = array();
				if($config["enable_mercadopago"])
					array_push($donateMethod, $donateMPOption);
				if($config["enable_pagseguro"])
					array_push($donateMethod, $donatePSOption);
				if($config["enable_paypal"])
					array_push($donateMethod, $donatePPOption);
				if($config["enable_deposit"])
					array_push($donateMethod, "<div class=\"card mb-3\"><div class=\"card-header text-center\">Donate by bank transfer</div><div class=\"card-body\"><center><img src=\"images/miscs/bank.jpg\" class=\"w-75 mb-3\" style=\"max-width:180px; max-height:160px;\"><br>Bank name: ".$config["bank_name"]."<br>Bank branch: ".$config["bank_branch"]."<br>Bank account: ".$config["bank_account"]."<br>Bank account type: ".$config["bank_type"]."<br>Bank beneficiary: ".$config["bank_beneficiary"]."<br>Bank CPF: ".$config["bank_cpf"]."<br><br>After making the transfer, send the receipt to the email ".$config["email_donate_confirmation"]." and wait.</center></div></div>");
				for($y=0;$y<count($donateMethod);$y++){
					if($tpl->exists("DONATION_OPTIONS"))
						$tpl->DONATION_OPTIONS = "<div class=\"col-sm-".(12/count($donateMethod))."\">".$donateMethod[$y]."</div>";
					$tpl->block("BLOCK_ICP_PANEL_DONATION_METHODS");
				}
			}
			$tpl->block("BLOCK_ICP_PANEL_DONATION");
		}else{
			if($tpl->exists("BLOCK_ICP_PANEL_DONATION_NULL",true))
				$tpl->block("BLOCK_ICP_PANEL_DONATION_NULL");
		}
	}else{
		if($tpl->exists("BLOCK_ICP_PANEL_DONATION_NULL",true))
			$tpl->block("BLOCK_ICP_PANEL_DONATION_NULL");
	}
	if($tpl->exists("BLOCK_ICP_PANEL_INFORMER",true)){
		$informerType = $_GET["informer_type"] ?? null;
		$informerName = $_GET["informer_name"] ?? null;
		$droplist = $_GET["droplist"] ?? null;
		$spawn = $_GET["spawn"] ?? null;
		$drop = $_GET["drop"] ?? null;
		if(isset($informerName)){
			if($tpl->exists("ICP_PANEL_INFORMER_SEARCH"))
				$tpl->ICP_PANEL_INFORMER_SEARCH = !empty($informerName) ? "<h1>Searching for: ".(empty($informerType) ? $informerName : $informerType." - ".$informerName)."</h1>" : null;
			$informer = $getPanelInfo->informer($informerName,$informerType);
			if(count($informer) > 0){
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NPC",true) || $tpl->exists("BLOCK_ICP_PANEL_INFORMER_ITEM",true)){
					foreach($informer as $rank){
						if($rank["type"] == "NPC"){
							if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NPC_DETAILS",true)){
								if($tpl->exists("ICP_PANEL_INFORMER_NPC_ID"))
									$tpl->ICP_PANEL_INFORMER_NPC_ID = $rank["npcId"];
								if($tpl->exists("ICP_PANEL_INFORMER_NPC_NAME"))
									$tpl->ICP_PANEL_INFORMER_NPC_NAME = $rank["npcName"];
								if($tpl->exists("ICP_PANEL_INFORMER_NPC_DROPLIST"))
									$tpl->ICP_PANEL_INFORMER_NPC_DROPLIST = $rank["npcDroplist"] == "true" ? "<a href=\"?icp=panel&show=informer&droplist=".$rank["npcId"]."\" class=\"btn btn-sm btn-primary\">Show droplist</a>" : null;
								if($tpl->exists("ICP_PANEL_INFORMER_NPC_LOC"))
									$tpl->ICP_PANEL_INFORMER_NPC_LOC = $rank["npcSpawn"] == "true" ? "<a href=\"?icp=panel&show=informer&spawn=".$rank["npcId"]."\" class=\"btn btn-sm btn-primary\">Show on map</a>" : null;
								$tpl->block("BLOCK_ICP_PANEL_INFORMER_NPC_DETAILS");
							}
						}elseif($rank["type"] == "Item"){
							if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_ITEM_DETAILS",true)){
								if($tpl->exists("ICP_PANEL_INFORMER_ITEM_IMG"))
									$tpl->ICP_PANEL_INFORMER_ITEM_IMG = $rank["itemImg"];
								if($tpl->exists("ICP_PANEL_INFORMER_ITEM_IMG_DETAILS"))
									$tpl->ICP_PANEL_INFORMER_ITEM_IMG_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
								if($tpl->exists("ICP_PANEL_INFORMER_ITEM_NAME"))
									$tpl->ICP_PANEL_INFORMER_ITEM_NAME = $rank["itemName"];
								if($tpl->exists("ICP_PANEL_INFORMER_ITEM_DROPLIST"))
									$tpl->ICP_PANEL_INFORMER_ITEM_DROPLIST = $rank["itemDroplist"] == "true" ? "<a href=\"?icp=panel&show=informer&drop=".$rank["itemId"]."\" class=\"btn btn-sm btn-primary\">Show NPC(s)</a>" : null;
								$tpl->block("BLOCK_ICP_PANEL_INFORMER_ITEM_DETAILS");
							}
						}
					}
					if(empty($informerType) && in_array("NPC",array_column($informer, 'type')) || $informerType == "NPC")
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_NPC");
					if(empty($informerType) && in_array("Item",array_column($informer, 'type')) || $informerType == "Item")
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_ITEM");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
			}
		}elseif(isset($droplist)){
			$npcDetails = $getPanelInfo->informerNpcDetails($droplist);
			if(count($npcDetails) > 0){
				foreach($npcDetails as $rank2){
					if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_NPC_DETAILS"))
						$tpl->ICP_PANEL_INFORMER_DROPLIST_NPC_DETAILS = "<h2>".$rank2["npcName"]." droplist</h2>".($rank2["npcSpawn"] == "true" ? "<a href=\"?icp=panel&show=informer&spawn=".$rank2["npcId"]."\" class=\"btn btn-sm btn-primary\">Show on map</a>" : null);
				}
				$informer = $getPanelInfo->informerDroplist($droplist);
				if(count($informer) > 0){
					if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_DROPLIST",true)){
						foreach($informer as $rank){
							if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_DROPLIST_DETAILS",true)){
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_IMG"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_IMG = $rank["itemImg"];
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_IMG_DETAILS"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_IMG_DETAILS = "<div class=\"item-details".(strpos($rank["itemName"], '{{_}PvP}') !== false ? " pvp" : null)."\" data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"right\" title=\"".$rank["itemDetails"]."\"></div>";
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_NAME"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_NAME = $rank["itemName"];
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_COUNT"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_COUNT = $rank["itemCount"];
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_TYPE"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_TYPE = $rank["itemType"];
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_CHANCE"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_CHANCE = $rank["itemChance"];
								if($tpl->exists("ICP_PANEL_INFORMER_DROPLIST_ITEM_DROPLIST"))
									$tpl->ICP_PANEL_INFORMER_DROPLIST_ITEM_DROPLIST = $rank["itemDroplist"] == "true" ? "<a href=\"?icp=panel&show=informer&drop=".$rank["itemId"]."\" class=\"btn btn-sm btn-primary\">Show NPC(s)</a>" : null;
								$tpl->block("BLOCK_ICP_PANEL_INFORMER_DROPLIST_DETAILS");
							}
						}
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_DROPLIST");
					}
				}else{
					if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
			}
		}elseif(isset($drop)){
			$informer = $getPanelInfo->informer($drop,"NPC");
			if(count($informer) > 0){
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NPC",true)){
					foreach($informer as $rank){
						if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NPC_DETAILS",true)){
							if($tpl->exists("ICP_PANEL_INFORMER_NPC_ID"))
								$tpl->ICP_PANEL_INFORMER_NPC_ID = $rank["npcId"];
							if($tpl->exists("ICP_PANEL_INFORMER_NPC_NAME"))
								$tpl->ICP_PANEL_INFORMER_NPC_NAME = $rank["npcName"];
							if($tpl->exists("ICP_PANEL_INFORMER_NPC_DROPLIST"))
								$tpl->ICP_PANEL_INFORMER_NPC_DROPLIST = $rank["npcDroplist"] == "true" ? "<a href=\"?icp=panel&show=informer&droplist=".$rank["npcId"]."\" class=\"btn btn-sm btn-primary\">Show droplist</a>" : null;
							if($tpl->exists("ICP_PANEL_INFORMER_NPC_LOC"))
								$tpl->ICP_PANEL_INFORMER_NPC_LOC = $rank["npcSpawn"] == "true" ? "<a href=\"?icp=panel&show=informer&spawn=".$rank["npcId"]."\" class=\"btn btn-sm btn-primary\">Show on map</a>" : null;
							$tpl->block("BLOCK_ICP_PANEL_INFORMER_NPC_DETAILS");
						}
					}
					$tpl->block("BLOCK_ICP_PANEL_INFORMER_NPC");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
			}
		}elseif(isset($spawn)){
			$npcDetails = $getPanelInfo->informerSpawn($spawn);
			if(count($npcDetails) > 0){
				foreach($npcDetails as $rank2){
					if($tpl->exists("ICP_PANEL_INFORMER_SPAWN_NPC_DETAILS"))
						$tpl->ICP_PANEL_INFORMER_SPAWN_NPC_DETAILS = "<h2>".$rank2["npcName"]."</h2><a href=\"javascript:void(0)\" class=\"btn btn-sm btn-primary\" onClick=\"window.history.back()\">Go back</a>";
				}
				$informer = $getPanelInfo->informerSpawn($spawn);
				if(count($informer) > 0){
					if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_LOCATION",true)){
						foreach($informer as $rank){
							if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_LOCATION_DETAILS",true)){
								if($tpl->exists("ICP_PANEL_INFORMER_LOCATION_MAP"))
									$tpl->ICP_PANEL_INFORMER_LOCATION_MAP = '<div style="position:absolute;top:'.$rank["npcLocY"].'px;left:'.$rank["npcLocX"].'px;"><img src="images/miscs/marcador.gif" title="'.$rank["npcName"].' - Lv '.$rank["npcLevel"].'" /></div>';
								$tpl->block("BLOCK_ICP_PANEL_INFORMER_LOCATION_DETAILS");
							}
						}
						if($tpl->exists("INFORMER_POS_X"))
							$tpl->INFORMER_POS_X = $informer[0]["npcLocX"];
						if($tpl->exists("INFORMER_POS_Y"))
							$tpl->INFORMER_POS_Y = $informer[0]["npcLocY"];
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_LOCATION");
					}
				}else{
					if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
						$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
				}
			}else{
				if($tpl->exists("BLOCK_ICP_PANEL_INFORMER_NULL",true))
					$tpl->block("BLOCK_ICP_PANEL_INFORMER_NULL");
			}
		}
		$tpl->block("BLOCK_ICP_PANEL_INFORMER");
	}
}