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
if(strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'])){
	$allowedPosts = array(
						// buttom name => array(inputs name)
						"login" => array("username","password", "captcha"),
						"register" => array("username", "email", "password1", "password2", "accept_rules", "captcha"),
						"recovery" => array("username", "email", "captcha"),
						"passChange" => array("password", "password1", "password2", "captcha"),
						"addEmail" => array("username", "password", "email", "captcha"),
						"editEmail" => array("email1", "email2", "email3", "captcha"),
						"repair" => array("char_id"),
						"putCharForSale" => array("char_id", "saleValue", "saleType"),
						"putItemsForSale" => array("char_id", "items", "saleValue", "saleType"),
						"submitScreenshot" => array("legend", "charName", "screenshot"),
						"submitVideo" => array("legend", "charName", "video"),
						"submitPrimeShop" => array("primeShopId", "charId"),
						"submitItemBroker" => array("itemBrokerId", "charId", "bidValue"),
						"submitCharBroker" => array("charBrokerId", "bidValue"),
						"itemBrokerReturn" => array("itemBrokerId"),
						"charBrokerReturn" => array("charBrokerId"),
						"submitEnchantItem" => array("itemId"),
						"submitAccountChange" => array("newAccount"),
						"submitBaseChange" => array("classId"),
						"submitSexChange" => array("sexId"),
						"submitNickChange" => array("newName"),
						"submitRewards" => array("charId","getOnline","getPvP","getPk"),
						"submitMsg" => array("subject","message","msgId","recipient","file")
					);
	$arrCountValue = 0;
	for($arrCount=0;$arrCount<count($allowedPosts);$arrCount++){
		if($arrCountValue < count($allowedPosts[array_keys($allowedPosts)[$arrCount]]))
			$arrCountValue = count($allowedPosts[array_keys($allowedPosts)[$arrCount]]);
	}
	if(!isset($_SESSION["ICP_UserAccessLevel"]) || isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] == 0){
		if(count($_POST) > ($arrCountValue+1)){
			header('Location: ./');
			exit;
		}
	}
	$methodPost = null;
	foreach ($_POST as $key => $value){
		if(array_key_exists($key,$allowedPosts)){
			for($PostX=0;$PostX<count($allowedPosts[$key]);$PostX++){
				if(!empty($_POST[$allowedPosts[$key][$PostX]])){
					${$allowedPosts[$key][$PostX]} = $allowedPosts[$key][$PostX] == "items" ? $_POST[$allowedPosts[$key][$PostX]] : str_replace("\'", "&apos;", addslashes(trim($_POST[$allowedPosts[$key][$PostX]])));
					if(empty($methodPost))
						$methodPost = $key;
				}
			}
		}
	}
	if(!empty($methodPost)){
		if(in_array($methodPost, array(array_keys($allowedPosts)[0],array_keys($allowedPosts)[1],array_keys($allowedPosts)[2],array_keys($allowedPosts)[3],array_keys($allowedPosts)[4],array_keys($allowedPosts)[5]))){
			function captcha($code){
				$captcha = str_replace("\'", "&apos;", addslashes(trim($code)));
				require_once './engine/captcha/securimage.php';
				$securimage = new Securimage();
				if ($securimage->check($captcha) == false)
					return false;
				else
					return true;
			}
			require_once("engine/classes/LoginServer.php");
			$getLoginServer = new ICPNetworks\LoginServer($db_type,$loginServer,$config);
			if($methodPost == array_keys($allowedPosts)[0]){
				if(isset($_POST["{$allowedPosts[$methodPost][2]}"])){
					if(captcha(${$allowedPosts[$methodPost][2]} ?? "")){
						$result = $getLoginServer->login(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "");
					}else{
						$result = "Fill in the captcha correctly!";
					}
				}else{
					$result = $getLoginServer->login(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "");
				}
				if($result == "success"){
					header("Location: index.php?icp=panel");
					exit();
				}elseif($result == "account_inactivated"){
					$getLoginServer->resposta("Activate your account by email to login.","Oops...","error");
				}elseif($result == "pass_login_error"){
					$getLoginServer->resposta("Incorrect username or password<br>Try again.","Oops...","error");
				}elseif($result == "acc_banned"){
					$getLoginServer->resposta("You\'ve been banned!","Oooh no!","error");
				}elseif($result == "email_is_null"){
					if($config["CreateAccWithEmail"]){
						$getLoginServer->resposta("Your account does not have a registered email address.<br>Register an email, access your email and activate your account.","Oooh no!","error","?icp=panel&show=add-email");
					}else{
						$getLoginServer->resposta("Your account does not have a registered email address.<br>Register an email.","Oooh no!","error","?icp=panel&show=add-email");
					}
					exit();
				}else{
					$getLoginServer->resposta($result);
				}
			}
			if($methodPost == array_keys($allowedPosts)[1]){
				if(isset($_POST["{$allowedPosts[$methodPost][5]}"])){
					if(captcha(${$allowedPosts[$methodPost][5]} ?? ""))
						$getLoginServer->register(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",${$allowedPosts[$methodPost][3]} ?? "",${$allowedPosts[$methodPost][4]} ?? "");
					else
						$getLoginServer->resposta("Fill in the captcha correctly!");
				}else{
					$getLoginServer->register(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",${$allowedPosts[$methodPost][3]} ?? "",${$allowedPosts[$methodPost][4]} ?? "");
				}
			}
			if($methodPost == array_keys($allowedPosts)[2]){
				if(isset($_POST["{$allowedPosts[$methodPost][2]}"])){
					if(captcha(${$allowedPosts[$methodPost][2]} ?? ""))
						$getLoginServer->recovery(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "");
					else
						$getLoginServer->resposta("Fill in the captcha correctly!");
				}else{
					$getLoginServer->recovery(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "");
				}
			}
			if($methodPost == array_keys($allowedPosts)[3]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getLoginServer->resposta("You must be logged in to change the password!");
				}else{
					if(isset($_POST["{$allowedPosts[$methodPost][3]}"])){
						if(captcha(${$allowedPosts[$methodPost][3]} ?? ""))
							$getLoginServer->passChange(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
						else
							$getLoginServer->resposta("Fill in the captcha correctly!");
					}else{
						$getLoginServer->passChange(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
					}
				}
			}
			if($methodPost == array_keys($allowedPosts)[4]){
				if(isset($_POST["{$allowedPosts[$methodPost][3]}"])){
					if(captcha(${$allowedPosts[$methodPost][3]} ?? ""))
						$result = $getLoginServer->addEmail(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "");
					else
						$result = "Fill in the captcha correctly!";
				}else{
					$result = $getLoginServer->addEmail(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "");
				}
				if($result == "success"){
					if($config["CreateAccWithEmail"])
						$getLoginServer->resposta("Email registered successfully.<br>Activate your account by email and you can login.","Success!","success","?icp=panel");
					else
						$getLoginServer->resposta("Email registered successfully.<br>You can now login.","Success!","success","?icp=panel");
					exit();
				}elseif($result == "email_already_exists")
					$getLoginServer->resposta("This account already has an email.<br>Try again.","Oops...","error");
				elseif($result == "pass_login_error")
					$getLoginServer->resposta("Incorrect username or password<br>Try again.","Oops...","error");
				elseif($result == "error_sent_mail")
					$getLoginServer->resposta("Your account does not have a registered email address.<br>Please contact an Admin.","Oops...","error");
				else
					$getLoginServer->resposta($result);
			}
			if($methodPost == array_keys($allowedPosts)[5]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getLoginServer->resposta("You must be logged in to change your email!");
				}else{
					if(isset($_POST["{$allowedPosts[$methodPost][3]}"])){
						if(captcha(${$allowedPosts[$methodPost][3]} ?? ""))
							$getLoginServer->emailChange(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
						else
							$getLoginServer->resposta("Fill in the captcha correctly!");
					}else{
						$getLoginServer->emailChange(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
					}
				}
			}
		}else{
			require_once("engine/classes/GameServer.php");
			$getGameServer = new ICPNetworks\GameServer($db_type,$loginServer,$gameServer,$config,$db_conn);
			if($methodPost == array_keys($allowedPosts)[6]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to unlock the character.!");
				}else{
					$getGameServer->unlock(${$allowedPosts[$methodPost][0]} ?? "",$_SESSION["ICP_UserName"]);
				}
			}
			if($methodPost == array_keys($allowedPosts)[7]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to sell the character!");
				}else{
					if($config["ENABLE_CHARACTER_BROKER"])
						$getGameServer->putCharForSale(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Character Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[8]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to sell items!");
				}else{
					if($config["ENABLE_ITEM_BROKER"])
						$getGameServer->putItemsForSale(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? array(),${$allowedPosts[$methodPost][2]} ?? "",${$allowedPosts[$methodPost][3]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Item Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[9]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to upload screenshots!");
				}else{
					if($config["enable_screenshots"])
						$getGameServer->sendScreenshot(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",$_FILES["{$allowedPosts[$methodPost][2]}"],$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The ScreenShots system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[10]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to upload videos!");
				}else{
					if($config["enable_videos"])
						$getGameServer->sendVideo(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Videos system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[11]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to buy on the Prime Shop!");
				}else{
					if($config["ENABLE_PRIME_SHOP"])
						$getGameServer->buyItem(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",$_SESSION["ICP_UserName"],false);
					else
						$getGameServer->resposta("The Prime Shop system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[12]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to bid or buy on Item Broker!");
				}else{
					if($config["ENABLE_ITEM_BROKER"]){
						if(!empty(${$allowedPosts[$methodPost][2]} ?? ""))
							$getGameServer->bid(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_SESSION["ICP_UserName"],false);
						else
							$getGameServer->buyItem(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",$_SESSION["ICP_UserName"],true);
					}else
						$getGameServer->resposta("The Item Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[13]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to bid or buy on Character Broker!");
				}else{
					if($config["ENABLE_CHARACTER_BROKER"]){
						if(!empty(${$allowedPosts[$methodPost][1]} ?? ""))
							$getGameServer->bid(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",$_SESSION["ICP_UserName"],true);
						else
							$getGameServer->buyChar(${$allowedPosts[$methodPost][0]} ?? "",$_SESSION["ICP_UserName"],$_SESSION["ICP_UserId"] ?? "");
					}else
						$getGameServer->resposta("The Character Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[14]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to cancel a sale on Item Broker!");
				}else{
					if($config["ENABLE_ITEM_BROKER"])
						$getGameServer->cancelItemBroker(${$allowedPosts[$methodPost][0]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Item Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[15]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to cancel a sale on Character Broker!");
				}else{
					if($config["ENABLE_CHARACTER_BROKER"])
						$getGameServer->cancelCharacterBroker(${$allowedPosts[$methodPost][0]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Character Broker system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[16]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to enchant items!");
				}else{
					if($config["ENABLE_SAFE_ENCHANT_SYSTEM"])
						echo $getGameServer->enchantItem($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],${$allowedPosts[$methodPost][0]} ?? "");
					else
						$getGameServer->resposta("The Safe Enchant system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[17]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You need to be logged in to change account character!");
				}else{
					if($config["ALLOW_CHARACTER_ACCOUNT_CHANGE"])
						echo $getGameServer->accountChange($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],${$allowedPosts[$methodPost][0]} ?? "");
					else
						$getGameServer->resposta("The Account Change system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[18]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to change character base!");
				}else{
					if($config["ALLOW_CHARACTER_BASE_CLASS_CHANGE"])
						echo $getGameServer->classChange($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],${$allowedPosts[$methodPost][0]} ?? "");
					else
						$getGameServer->resposta("The Base Class Change system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[19]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to change the character\'s gender!");
				}else{
					if($config["ALLOW_CHARACTER_SEX_CHANGE"])
						echo $getGameServer->sexChange($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],${$allowedPosts[$methodPost][0]} ?? "");
					else
						$getGameServer->resposta("The Sex Change system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[20]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You need to be logged in to change the character\'s nickname!");
				}else{
					if($config["ALLOW_CHARACTER_NICKNAME_CHANGE"])
						echo $getGameServer->nickChange($_GET["char_id"] ?? "",$_SESSION["ICP_UserName"],${$allowedPosts[$methodPost][0]} ?? "");
					else
						$getGameServer->resposta("The Nickname Change system is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[21]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to receive rewards!");
				}else{
					if($config["ENABLE_REWARD_SYSTEM"])
						echo $getGameServer->getReward(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",${$allowedPosts[$methodPost][3]} ?? "",$_SESSION["ICP_UserName"]);
					else
						$getGameServer->resposta("The Reward System is disabled","Oops...","error");
				}
			}
			if($methodPost == array_keys($allowedPosts)[22]){
				if(!isset($_SESSION["ICP_UserName"])){
					$getGameServer->resposta("You must be logged in to send messages!");
				}else{
					echo $getGameServer->sendMsg(${$allowedPosts[$methodPost][0]} ?? "",${$allowedPosts[$methodPost][1]} ?? "",${$allowedPosts[$methodPost][2]} ?? "",$_FILES["{$allowedPosts[$methodPost][4]}"],${$allowedPosts[$methodPost][3]} ?? "",$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}
			}
		}
	}
	if(isset($_SESSION["ICP_UserAccessLevel"]) && $_SESSION["ICP_UserAccessLevel"] > 5){
		if(isset($_POST["submitPrivileges"])){
			require_once("engine/classes/LoginServer.php");
			$getLoginServer = new ICPNetworks\LoginServer($db_type,$loginServer,$config,$db_conn);
			if($_SESSION["ICP_UserAccessLevel"] == 10){
				echo $getLoginServer->setPrivilege($_POST["privilegeId"] ?? "",$_POST["username"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
			}else{
				$getLoginServer->resposta("You are not allowed to do this.","Oops...","error");
			}
		}else{
			require_once("engine/classes/GameServer.php");
			$getGameServer = new ICPNetworks\GameServer($db_type,$loginServer,$gameServer,$config,$db_conn);
			if(isset($_POST["submitDonateCoins"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 9){
					echo $getGameServer->sendDonate($_POST["username"] ?? "",$_POST["coins"] ?? 0,$_POST["donateType"] ?? 1,$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["addPrimeShop"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 9){
					echo $getGameServer->addPrimeShop($_POST["itemId"] ?? array(1),$_POST["itemCount"] ?? array(1),$_POST["itemEnchant"] ?? array(0),$_POST["itemFire"] ?? array(0),$_POST["itemWater"] ?? array(0),$_POST["itemWind"] ?? array(0),$_POST["itemEarth"] ?? array(0),$_POST["itemHoly"] ?? array(0),$_POST["itemDark"] ?? array(0),$_POST["itemPrice"] ?? 1,$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["removePrimeShop"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 9){
					echo $getGameServer->deletePrimeShop($_POST["removePrimeShop"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["approveScreenshot"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->approveScreenshot($_POST["approveScreenshot"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["disapproveScreenshot"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->approveScreenshot($_POST["disapproveScreenshot"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["delete1Screenshot"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->deleteScreenshot($_POST["delete1Screenshot"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["delete2Screenshot"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->deleteScreenshot($_POST["delete2Screenshot"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["approveVideo"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->approveVideo($_POST["approveVideo"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["disapproveVideo"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->approveVideo($_POST["disapproveVideo"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["delete1Video"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->deleteVideo($_POST["delete1Video"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["delete2Video"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 7){
					echo $getGameServer->deleteVideo($_POST["delete2Video"] ?? "",$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["addNews"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 8){
					echo $getGameServer->addNews($_POST["title"] ?? "",$_POST["news"] ?? "",empty($_POST["addNews"]) ? 0 : $_POST["addNews"],$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["editNews"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 8){
					$editNews = $getGameServer->editNews($_POST["editNews"] ?? 0,$_SESSION["ICP_UserAccessLevel"]);
					if(is_array($editNews)){
						if($tpl->exists("EDIT_NEWS_TITLE"))
							$tpl->EDIT_NEWS_TITLE = $editNews[0];
						if($tpl->exists("EDIT_NEWS_TXT"))
							$tpl->EDIT_NEWS_TXT = nl2br($editNews[1]);
						if($tpl->exists("EDIT_NEWS_ID"))
							$tpl->EDIT_NEWS_ID = $editNews[2];
					}
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["deleteNews"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 8){
					echo $getGameServer->deleteNews($_POST["deleteNews"] ?? 0,$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["editProfile"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 6){
					echo $getGameServer->editProfile($_POST["gmName"] ?? "",$_POST["gmEmail"] ?? "",$_FILES["profile"],$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["deleteProfile"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 6){
					echo $getGameServer->deleteProfile($_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["submitBlockMsg"])){
				if($_SESSION["ICP_UserAccessLevel"] >= 6){
					echo $getGameServer->banAccountMsgs($_POST["username"],$_POST["type"],$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
			if(isset($_POST["submitConfigs"])){
				if($_SESSION["ICP_UserAccessLevel"] == 10){
					echo $getGameServer->saveConfigs($_POST["serverName"] ?? "",$_POST["siteTile"] ?? "",$_POST["server"] ?? "",$_POST["safeEnchant"] ?? 0,$_POST["maxEnchant"] ?? 1,$_POST["xpRate"] ?? 1,$_POST["spRate"] ?? 1,$_POST["dropRate"] ?? 1,$_POST["spoilRate"] ?? 1,$_POST["template"] ?? "",$_POST["olyPeriod"] ?? 30,$_POST["timezone"] ?? "America/Sao_Paulo",$_POST["instagram"] ?? "",$_POST["youtube"] ?? "",$_POST["facebook"] ?? "",$_POST["discord"] ?? "",$_POST["maxRankings"] ?? 30,$_POST["maxIndexRankings"] ?? 5,$_POST["clientDownload"] ?? "#",$_POST["systemDownload"] ?? "#",$_POST["accCreateByEmail"] ?? 0,$_POST["accRecoveryByEmail"] ?? 0,$_POST["smtpHost"] ?? "",$_POST["smtpPort"] ?? 587,$_POST["smtpEmail"] ?? "",$_POST["smtpPass"] ?? "",$_POST["donateCoinName"] ?? "",$_POST["enableDeposit"] ?? 0,$_POST["depositBank"] ?? "",$_POST["depositBranch"] ?? "",$_POST["depositAccount"] ?? "",$_POST["depositType"] ?? "",$_POST["depositBeneficiary"] ?? "",$_POST["depositCpf"] ?? "",$_POST["donateEmail"] ?? "",$_POST["enableMercadopago"] ?? 0,$_POST["mpCurrency"] ?? "",$_POST["mpCoins"] ?? 1,$_POST["mpToken"] ?? "",$_POST["enablePagseguro"] ?? 0,$_POST["psCurrency"] ?? "",$_POST["psCoins"] ?? 1,$_POST["psEmail"] ?? "",$_POST["psToken"] ?? "",$_POST["enablePaypal"] ?? 0,$_POST["ppCurrency"] ?? "USD",$_POST["ppCoins"] ?? 1,$_POST["ppEmail"] ?? "",$_POST["enableMessages"] ?? 0,$_POST["enableNews"] ?? 0,$_POST["maxNews"] ?? 5,$_POST["enableScreenshotsGallery"] ?? 0,$_POST["maxScreenshots"] ?? 6,$_POST["enableVideosGallery"] ?? 0,$_POST["maxVideos"] ?? 6,$_POST["enableBosses"] ?? 0,$_POST["enableCastles"] ?? 0,$_POST["enableClanHalls"] ?? 0,$_POST["enableTopPvp"] ?? 0,$_POST["EnableTopClassPvp"] ?? 0,$_POST["maxClassPvp"] ?? 5,$_POST["enableTopPk"] ?? 0,$_POST["EnableTopClassPk"] ?? 0,$_POST["maxClassPk"] ?? 5,$_POST["enableTopOnline"] ?? 0,$_POST["enableTopAdena"] ?? 0,$_POST["goldbarValue"] ?? 500000000,$_POST["enableTopClan"] ?? 0,$_POST["enableTopClanPvp"] ?? 0,$_POST["enableTopOly"] ?? 0,$_POST["enableTopHero"] ?? 0,$_POST["enableTopRaid"] ?? 0,$_POST["enableRewardSystem"] ?? 0,$_POST["RewardLoc"] ?? "WAREHOUSE",$_POST["enableRewardOnline"] ?? 0,$_POST["rewardOnlineDays"] ?? 1,$_POST["rewardOnlineItems"] ?? "57,10000;",$_POST["enableRewardPvp"] ?? 0,$_POST["rewardPvpCount"] ?? 1,$_POST["rewardPvpItems"] ?? "3470,20;",$_POST["enableRewardPk"] ?? 0,$_POST["rewardPkCount"] ?? 1,$_POST["rewardPkItems"] ?? "57,15000;3470,10;",$_POST["enablePrimeShop"] ?? 0,$_POST["maxPrimeShop"] ?? 15,$_POST["primeShopLoc"] ?? "WAREHOUSE",$_POST["enableItemBroker"] ?? 0,implode(",",$_POST["itemBrokerGrade"] ?? ""),$_POST["allowComboItems"] ?? 0,$_POST["allowPvpItems"] ?? 0,$_POST["allowAugmentedItems"] ?? 0,$_POST["allowAuctionItems"] ?? 0,$_POST["auctionItemsDay"] ?? 7,$_POST["auctionRangeItems"] ?? 5,$_POST["maxItemBroker"] ?? 15,$_POST["itemBrokerLoc"] ?? "WAREHOUSE",$_POST["enableCharacterBroker"] ?? 0,$_POST["allowAuctionCharacters"] ?? 0,$_POST["auctionCharactersDay"] ?? 7,$_POST["auctionRangeCharacters"] ?? 5,$_POST["minCharacterBrokerLevel"] ?? 76,$_POST["maxCharacterBroker"] ?? 15,$_POST["enableSafeEnchant"] ?? 0,$_POST["allowPvpEnchant"] ?? 0,$_POST["allowAugmentedEnchant"] ?? 0,$_POST["enchantChance"] ?? 60,implode(",",$_POST["itemEnchantGrade"] ?? ""),$_POST["enchantDGrade"] ?? 1,$_POST["enchantCGrade"] ?? 1,$_POST["enchantBGrade"] ?? 1,$_POST["enchantAGrade"] ?? 1,$_POST["enchantSGrade"] ?? 1,$_POST["enchantS80Grade"] ?? 1,$_POST["enchantS84Grade"] ?? 1,$_POST["enchantRGrade"] ?? 1,$_POST["enchantR95Grade"] ?? 1,$_POST["enchantR99Grade"] ?? 1,$_POST["enchantR110Grade"] ?? 1,$_POST["enableCharacterChanges"] ?? 0,$_POST["enableBaseChange"] ?? 0,$_POST["baseChangePrice"] ?? 1,$_POST["enableSexChange"] ?? 0,$_POST["sexChangePrice"] ?? 1,$_POST["enableNickChange"] ?? 0,$_POST["nickChangePrice"] ?? 1,$_POST["enableAccChange"] ?? 0,$_POST["accChangePrice"] ?? 1,$_POST["enableCheckStatus"] ?? 0,$_POST["forceLoginOnline"] ?? 0,$_POST["forceGameOnline"] ?? 0,$_POST["allowServerStats"] ?? 0,$_POST["enableFakePlayers"] ?? 0,$_POST["fakePlayersNum"] ?? "1.05",$_SESSION["ICP_UserName"],$_SESSION["ICP_UserAccessLevel"]);
				}else{
					$getGameServer->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
		}
	}
}