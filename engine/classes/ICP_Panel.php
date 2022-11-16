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
namespace ICPNetworks {
	
	use \ICPNetworks\Miscs\Suport AS Suport;
	
	class ICP_Panel extends Suport {
		
		public function resposta($msg,$title=null,$type=null,$redirect=null){
			echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\" type=\"text/javascript\"></script><script src=\"//cdn.jsdelivr.net/npm/sweetalert2@10\"></script><script type=\"text/javascript\">$(document).ready(function(){Swal.fire({ title: '".$title."', html: '".$msg."', icon: '".$type."'".(!empty($redirect) ? ", confirmButtonText: 'Ok', preConfirm: () => { return [ window.location.href = '".$redirect."' ] } })" : "})")."})</script>";
		}
		
		public function activateAcc($hash){
			$acc_id = preg_replace("/(\D)/i" , "" , $hash);
			if($acc_id > 0){
				$records = $this->execute("SELECT login FROM icp_accounts WHERE acc_id = ? AND status = '0'",[$acc_id],"login");
				if(count($records) == 1){
					$up_icp = $this->execute("UPDATE icp_accounts SET status = '1' WHERE acc_id = ? AND status = '0'",[$acc_id],"login");
					$up_acc = $this->execute($this->QUERY_ACTIVATE_ACC,[$records[0]["login"]],"login");
					return true;
				}else{
					return false;
				}
			}
			return false;
		}
		
		public function accDetails($username){
			$acc = array();
			$table = $this->db_type ? "characters" : "user_data";
			$records = $this->execute("SELECT * FROM ".$table." WHERE account_name = ?",[$username]);
			if(count($records) > 0){
				$screenshots = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '1' AND account = ?",[$username]);
				$videos = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '1' AND account = ?",[$username]);
				array_push($acc, array("totalChars" => count($records), "totalScreenshots" => count($screenshots), "totalVideos" => count($videos)));
			}else{
				array_push($acc, array("totalChars" => 0, "totalScreenshots" => 0, "totalVideos" => 0));
			}
			return $acc;
		}
		
		public function donateDetails($username){
			$acc = array();
			if($this->db_type){
				$records = $this->execute("SELECT d.*, CASE WHEN (SELECT DISTINCT currency FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' || status = 'Aprovado') LIMIT 1) IS NULL THEN '$' ELSE (SELECT DISTINCT currency FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' || status = 'Aprovado') LIMIT 1) END AS currency, CASE WHEN (SELECT SUM(price) FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' || status = 'Aprovado')) > '0' THEN (SELECT SUM(price) FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' || status = 'Aprovado')) ELSE '0' END AS totalDonate FROM icp_donate AS d WHERE d.login = ?",[$username]);
			}else{
				$records = $this->execute("SELECT d.*, CASE WHEN (SELECT DISTINCT TOP 1 currency FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' OR status = 'Aprovado')) IS NULL THEN '$' ELSE (SELECT DISTINCT TOP 1 currency FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' OR status = 'Aprovado')) END AS currency, CASE WHEN (SELECT SUM(price) FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' OR status = 'Aprovado')) > '0' THEN (SELECT SUM(price) FROM icp_donate_history WHERE account = d.login AND (status = 'Completed' OR status = 'Aprovado')) ELSE '0' END AS totalDonate FROM icp_donate AS d WHERE d.login = ?",[$username]);
			}
			if(count($records) > 0){
				array_push($acc, array("currency" => $records[0]["currency"], "totalDonate" => number_format($records[0]["totalDonate"], 2), "totalCoins" => $records[0]["total"], "totalUsed" => $records[0]["used"], "totalBalance" => $records[0]["total"] - $records[0]["used"]));
			}else{
				array_push($acc, array("currency" => "$", "totalDonate" => "0.00", "totalCoins" => 0, "totalUsed" => 0, "totalBalance" => 0));
			}
			return $acc;
		}
		
		public function donateHistory($login){
			$result = array();
			$records =$this->execute("SELECT * FROM icp_donate_history WHERE account = ? ORDER BY date DESC",[$login]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($result, array("currency" => $records[$x]["currency"], "status" => $records[$x]["status"], "method" => $records[$x]["method"], "amount" => $records[$x]["amount"], "price" => number_format($records[$x]["price"], 2), "date" => $records[$x]["date"]));
				}
			}
			return $result;
		}
		
		public function donateLog($login){
			$result = array();
			$records = $this->execute("SELECT * FROM icp_donate_log WHERE account = ? ORDER BY date DESC",[$login]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($result, array("date" => $records[$x]["date"], "description" => $records[$x]["description"], "cost" => $records[$x]["cost"]));
				}
			}
			return $result;
		}
		
		public function donateBalance($login){
			$doacao = $this->execute('SELECT (total - used) AS credit FROM icp_donate WHERE login = ?',[$login]);
			if(count($doacao) == 1){
				return $doacao[0]["credit"];
			}
			return 0;
		}
		
		public function logIP($username){
			$ip = array();
			if($this->db_type){
				$records = $this->execute("SELECT * FROM icp_accounts_ip WHERE login = ? ORDER BY id DESC LIMIT 5",[$username],"login");
			}else{
				$records = $this->execute("SELECT TOP 5 * FROM icp_accounts_ip WHERE login = ? ORDER BY id DESC",[$username],"login");
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($ip, array("logIpDate" => $records[$x]["date"], "logIpNumber" => $records[$x]["ip"]));
				}
				for($y=0;$y<(5-count($records));$y++){
					array_push($ip, array("logIpDate" => "-", "logIpNumber" => "-"));
				}
			}else{
				for($x=0;$x<5;$x++){
					array_push($ip, array("logIpDate" => "-", "logIpNumber" => "-"));
				}
			}
			return $ip;
		}
		
		public function charStatus($login,$charId=0){
			$result = array();
			if(!empty($charId)){
				$records = $this->execute($this->QUERY_CHARACTER_STATUS_1,[$charId,$login]);
			}else{
				$records = $this->execute($this->QUERY_CHARACTER_STATUS_2,[$login]);
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					$clan_name = "n/a";
					$clan_ally = "n/a";
					$hero = 0;
					if(!empty($records[$x]["clanid"])){
						$this->showCrest($records[$x]["clanid"]);
						$records2 = $this->execute($this->QUERY_CHARACTER_STATUS_3,[$records[$x]["clanid"]]);
						if(count($records2) == 1){
							$clan_name = empty($records2[0]["clan_name"]) ? $clan_name : $records2[0]["clan_name"];
							$clan_ally = empty($records2[0]["ally_name"]) ? $clan_ally : $records2[0]["ally_name"];
						}
					}
					if($records[$x]["nobless"] == 1){
						$records3 = $this->execute($this->QUERY_CHARACTER_STATUS_4,[$records[$x]["char_id"]]);
						if(count($records3) == 1){
							$hero = 1;
						}
					}
					$subClassArr = explode(";",$this->showSubClasses($records[$x]["char_id"],$login));
					array_push($result, array("baseClass" => $this->classe_name($records[$x]["base_class"]), "subClass" => count($subClassArr) > 2 ? "Yes, ".(count($subClassArr)-2)."." : "None.", "nobles" => $records[$x]["nobless"] == 1 ? "Yes" : "No", "hero" => $hero == 1 ? "Yes" : "No", "karma" => $records[$x]["karma"], "baseLevel" => $records[$x]["level"], "sex" => $records[$x]["sex"] == 0 ? "Male" : "Female", "onlineTime" => $this->remainingTime($records[$x]["onlinetime"],true), "lastAccess" => $this->formatDate($records[$x]["lastAccess"]), "crest" => file_exists("images/crests/".$records[$x]["clanid"].".png") ? "<img src=\"images/crests/".$records[$x]["clanid"].".png\" /> " : null, "clan" => $clan_name, "allyance" => $clan_ally, "pvp" => $records[$x]["pvpkills"], "pk" => $records[$x]["pkkills"], "loc" => $this->charLoc($records[$x]["x"],$records[$x]["y"]), "char_id" => $records[$x]["char_id"], "char_name" => $records[$x]["char_name"], "char_image" => $this->showFace($this->getRace($records[$x]["base_class"]),$records[$x]["base_class"],$records[$x]["sex"]), "char_inStore" => $records[$x]["charBroker"]));
				}
			}
			return $result;
		}
		
		private function charLoc($x,$y){
			if($x > 75036 and $x < 91268 and $y > 141868 and $y < 154763){
				$loc = 'Giran Town';
			}elseif($x > 111704 and $x < 119254 and $y > 142606 and $y < 147705){
				$loc = 'Giran Castle';
			}elseif($x > 140294 and $x < 154452 and $y > 14829 and $y < 32579){
				$loc = 'Aden Town';
			}elseif($x > 141528 and $x < 153145 and $y > -28 and $y < 14829){
				$loc = 'Aden Castle';
			}elseif($x > 142547 and $x < 152714 and $y > -61724 and $y < -52009){
				$loc = 'Goddard Town';
			}elseif($x > 142666 and $x < 152714 and $y > -52097 and $y < -43995){
				$loc = 'Goddard Castle';
			}elseif($x > 32202 and $x < 47239 and $y > -53161 and $y < -41825){
				$loc = 'Rune Town';
			}elseif($x > 6822 and $x < 21361 and $y > -55812 and $y < -43506){
				$loc = 'Rune Castle';
			}elseif($x > 14631 and $x < 21917 and $y > 140991 and $y < 148056){
				$loc = 'Dion Town';
			}elseif($x > 18035 and $x < 25879 and $y > 155491 and $y < 164028){
				$loc = 'Dion Castle';
			}elseif($x > 75963 and $x < 86192 and $y > 47465 and $y < 61930){
				$loc = 'Oren Town';
			}elseif($x > 77723 and $x < 85693 and $y > 34672 and $y < 40106){
				$loc = 'Oren Castle';
			}elseif($x > -17167 and $x < -10729 and $y > 120243 and $y < 127434){
				$loc = 'Gludio Town';
			}elseif($x > -20782 and $x < -15353 and $y > 106447 and $y < 114258){
				$loc = 'Gludio Castle';
			}elseif($x > 80531 and $x < 93071 and $y > -148982 and $y < -135883){
				$loc = 'Schuttgart Town';
			}elseif($x > 72068 and $x < 84483 and $y > -155479 and $y < -146970){
				$loc = 'Schuttgart Castle';
			}elseif($x > 101509 and $x < 120955 and $y > 213891 and $y < 229923){
				$loc = 'Heine';
			}elseif($x > 113197 and $x < 118888 and $y > 244173 and $y < 252229){
				$loc = 'Innadril Castle';
			}elseif($x > -85665 and $x < -77001 and $y > 148085 and $y < 156642){
				$loc = 'Gludin Village';
			}elseif($x > 111612 and $x < 123698 and $y > 73431 and $y < 81450){
				$loc = 'Hunters Village';
			}elseif($x > -125454 and $x < -65999 and $y > 211377 and $y < 259892){
				$loc = 'Talking Island Village';
			}elseif($x > 110391 and $x < 126001 and $y > -190059 and $y < -176019){
				$loc = 'Dwarven Village';
			}elseif($x > -53946 and $x < -30142 and $y > -127853 and $y < -104236){
				$loc = 'Orc Village';
			}elseif($x > 607 and $x < 24570 and $y > 6203 and $y < 24532){
				$loc = 'Dark Elf Village';
			}elseif($x > 30366 and $x < 61592 and $y > 42314 and $y < 60601){
				$loc = 'Elven Village';
			}elseif($x > -127744 and $x < -102187 and $y > 30449 and $y < 54659){
				$loc = 'Kamael Village';
			}else{
				$loc = 'Out of town';
			}
			return $loc;
		}
		
		private function showBaseClasse($charid,$login){
			$result = null;
			$records = $this->execute($this->QUERY_GET_BASE_CLASS,[$charid,$login]);
			if(count($records) == 1){
				$result = $records[0]["base_class"];
			}
			return $result;
		}
		
		public function showSubClasses($charid,$login){
			$result = null;
			$result .= $this->showBaseClasse($charid,$login).";";
			$records = $this->execute($this->QUERY_GET_SUB_CLASSES,[$charid,$login]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					if($this->db_type){
						$result .= $records[$x]["class_id"].";";
					}else{
						$sub1 = $records[$x]["subjob1_class"] > 0 ? $records[$x]["subjob1_class"].";" : null;
						$sub2 = $records[$x]["subjob2_class"] > 0 ? $records[$x]["subjob2_class"].";" : null;
						$sub3 = $records[$x]["subjob3_class"] > 0 ? $records[$x]["subjob3_class"].";" : null;
						$result .= $sub1.$sub2.$sub3;
					}
				}
			}
			return $result;
		}
		
		protected function showFace($race,$class,$sex){
			$img = null;
			if($race == 0){
				$arrHumanFighter = [0,1,2,3,4,5,6,7,8,9,88,89,90,91,92,93,148,149,152,153,158,162];
				$arrHumanMage = [10,11,12,13,14,15,16,17,94,95,96,97,98,166,167,171,176,179];
				$arrDarkHuman = [196,197,198,199];
				if(in_array($class,$arrHumanFighter)){
					$img = $sex == 0 ? "human_fighter_male.png" : "human_fighter_female.png";
				}elseif(in_array($class,$arrHumanMage)){
					$img = $sex == 0 ? "human_mage_male.png" : "human_mage_female.png";
				}elseif(in_array($class,$arrDarkHuman)){
					$img = "dark_human_fighter_male.png";
				}
			}elseif($race == 1){
				$arrElfFighter = [18,19,20,21,22,23,24,99,100,101,102,150,159,163,172];
				$arrElfMage = [25,26,27,28,29,30,103,104,105,168,177,180];
				$arrDarkElf = [200,201,202,203];
				if(in_array($class,$arrElfFighter)){
					$img = $sex == 0 ? "elf_fighter_male.png" : "elf_fighter_female.png";
				}elseif(in_array($class,$arrElfMage)){
					$img = $sex == 0 ? "elf_mage_male.png" : "elf_mage_female.png";
				}elseif(in_array($class,$arrDarkElf)){
					$img = "dark_elf_fighter_male.png";
				}
			}elseif($race == 2){
				$arrDarkElfFighter = [31,32,33,34,35,36,37,108,109,151,160,164,173];
				$arrDarkElfMage = [38,39,40,41,42,43,110,111,112,169,178,181];
				$arrDarkDarkElf = [204,205,206,207];
				if(in_array($class,$arrDarkElfFighter)){
					$img = $sex == 0 ? "darkelf_fighter_male.png" : "darkelf_fighter_female.png";
				}elseif(in_array($class,$arrDarkElfMage)){
					$img = $sex == 0 ? "darkelf_mage_male.png" : "darkelf_mage_female.png";
				}elseif(in_array($class,$arrDarkDarkElf)){
					$img = "dark_darkelf_fighter_male.png";
				}
			}elseif($race == 3){
				$arrOrcFighter = [44,45,46,47,48,113,114,154,155];
				$arrOrcMage = [49,50,51,52,115,116,174,175];
				$arrDarkOrc = [217,218,219,220];
				if(in_array($class,$arrOrcFighter)){
					$img = $sex == 0 ? "orc_fighter_male.png" : "orc_fighter_female.png";
				}elseif(in_array($class,$arrOrcMage)){
					$img = $sex == 0 ? "orc_mage_male.png" : "orc_mage_female.png";
				}elseif(in_array($class,$arrDarkOrc)){
					$img = "dark_orc_fighter_male.png";
				}
			}elseif($race == 4){
				$arrDwarfFighter = [53,54,55,56,57,117,118,156,161];
				if(in_array($class,$arrDwarfFighter)){
					$img = $sex == 0 ? "dwarf_fighter_male.png" : "dwarf_fighter_female.png";
				}
			}elseif($race == 5){
				$arrKamaelFighter = [123,124,125,126,127,128,129,130,131,132,133,134,135,136,192,193,194,195];
				if(in_array($class,$arrKamaelFighter)){
					$img = $sex == 0 ? "kamael_fighter_male.png" : "kamael_fighter_female.png";
				}
			}elseif($race == 6){
				$arrErtheiaFighter = [182,184,186,188];
				$arrErtheiaMage = [183,185,187,189];
				if(in_array($class,$arrErtheiaFighter)){
					$img = $sex == 0 ? "ertheia_fighter_male.png" : "ertheia_fighter_female.png";
				}elseif(in_array($class,$arrErtheiaMage)){
					$img = $sex == 0 ? "ertheia_mage_male.png" : "ertheia_mage_female.png";
				}
			}elseif($race == 30){
				$arrSylphFighter = [208,209,210,211];
				if(in_array($class,$arrSylphFighter)){
					$img = $sex == 0 ? "sylph_fighter_male.png" : "sylph_fighter_female.png";
				}
			}
			return empty($img) ? "noimage.jpg" : $img;
		}
		
		public function showCharQuests($charid,$login){
			$result = array();
			$records = $this->execute($this->QUERY_GET_CHARACTER_QUESTS,[$charid,$login]);
			if(count($records) > 0){
				for($x=0;$x<($this->db_type ? count($records) : 27);$x++){
					$questId = 0;
					if(isset($records[$x]["id"])){
						$questId = ltrim(preg_replace("/(\D)/i","",$records[$x]["id"]),"0");
					}elseif(isset($records[$x]["name"])){
						$questSeparator = explode("_",$records[$x]["name"]);
						$questId = is_array($questSeparator) && count($questSeparator) > 1 ? ltrim(preg_replace("/(\D)/i","",$questSeparator[0])) : $questId;
					}elseif(isset($records[0]["q".$x])){
						$questId = ltrim(preg_replace("/(\D)/i","",$records[0]["q".$x]));
					}
					if(!empty($questId) || empty($questId) && isset($records[$x]["name"])){
						$questName = $this->quest_name($questId);
						if($questName == "_quest_name_error" && isset($records[$x]["name"])){
							$questName = $records[$x]["name"];
						}
						$pieces = explode("_",$questName);
						$name = null;
						if(is_array($pieces) && count($pieces) > 1){
							for($y=1;$y<count($pieces);$y++){
								$name .= ucfirst(preg_replace('/(?<!^)([A-Z])/',' \\1',$pieces[$y]));
								$name .= $y != (count($pieces)-1) ? " " : null;
							}
						}else{
							$name = ucfirst(preg_replace('/(?<!^)([A-Z])/',' \\1',$questName));
						}
						if(!empty($name) && $name != "_quest_name_error"){
							if($this->db_type){
								array_push($result, array("questName" => $name, "questValue" => $records[$x]["value"], "questOwner" => $records[$x]["char_name"]));
							}else{
								array_push($result, array("questName" => $records[0]["q".$x] > 0 ? $name : null, "questValue" => $records[0]["q".$x] > 0 ? "Started" : null, "questOwner" => $records[0]["char_name"]));
							}
						}
					}
				}
			}
			return $result;
		}

		public function showCharSkills($class,$charid,$login){
			$result = array();
			$records = $this->execute($this->QUERY_GET_CHARACTER_SKILLS,[$charid,$login,$class]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					$img = "<img src=\"images/icons/".(file_exists("images/icons/".str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon..","",str_replace("icon.","",strtolower($records[$x]["icon"])))))))).".png") ? str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon..","",str_replace("icon.","",strtolower($records[$x]["icon"]))))))) : "404").".png\" style=\"width:32px; height:32px;\">";
					$skill_enchant = $records[$x]["skill_level"] > 100 ? ceil(preg_replace("/(\D)/i" , "" , substr($records[$x]["skill_level"],-2))) : null;
					$skill_level = $records[$x]["skill_level"] > 100 ? $records[$x]["level"] : $records[$x]["skill_level"];
					$skillNameLevel = "<strong>".$records[$x]["name"]."</strong> <span style='color:#b09979;'>Lv ".$skill_level."</span>";
					$skillEnchanted = !empty($skill_enchant) ? "<br>Enchanted <span style='color:#ffd969;'>+".$skill_enchant."</span>" : null;
					array_push($result, array("skillImg" => $img, "skillDetails" => $skillNameLevel.$skillEnchanted, "skillOwner" => $records[$x]["char_name"]));
				}
			}
			return $result;
		}
		
		private function getAugElem($itemid){
			$augment = 0;
			$attrubutes = null;
			$fire = null;
			$water = null;
			$wind = null;
			$earth = null;
			$holy = null;
			$unholy = null;
			if(isset($this->QUERY_SELECT_ITEM_STATS_ITEM_VARIATIONS) && !empty($this->QUERY_SELECT_ITEM_STATS_ITEM_VARIATIONS)){
				$item_variations = $this->execute($this->QUERY_SELECT_ITEM_STATS_ITEM_VARIATIONS,[$itemid]);
				if(count($item_variations) > 0){
					$augment = 1;
				}
			}
			if(isset($this->QUERY_SELECT_ITEM_STATS_ITEM_ELEMENTALS) && !empty($this->QUERY_SELECT_ITEM_STATS_ITEM_ELEMENTALS)){
				$item_elementals = $this->execute($this->QUERY_SELECT_ITEM_STATS_ITEM_ELEMENTALS,[$itemid]);
				if(count($item_elementals) > 0){
					$el = explode(",", $item_elementals[0]["elements"]);
					for($x=0;$x<count($el);$x++){
						$element = explode(";", $el[$x]);
						$fire .= $element[0] == 0 ? $element[1] ?? "" : null;
						$water .= $element[0] == 1 ? $element[1] ?? "" : null;
						$wind .= $element[0] == 2 ? $element[1] ?? "" : null;
						$earth .= $element[0] == 3 ? $element[1] ?? "" : null;
						$holy .= $element[0] == 4 ? $element[1] ?? "" : null;
						$unholy .= $element[0] == 5 ? $element[1] ?? "" : null;
					}
					$attrubutes = $fire.",".$water.",".$wind.",".$earth.",".$holy.",".$unholy.",";
				}
			}
			if(isset($this->QUERY_SELECT_ITEM_STATS_ITEM_ATTRIBUTES) && !empty($this->QUERY_SELECT_ITEM_STATS_ITEM_ATTRIBUTES)){
				$item_attributes = $this->execute($this->QUERY_SELECT_ITEM_STATS_ITEM_ATTRIBUTES,[$itemid]);
				if(count($item_attributes) > 0){
					if(isset($item_attributes[0]["elemType"])){
						$fire .= $item_attributes[0]["elemType"] == 0 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$water .= $item_attributes[0]["elemType"] == 1 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$wind .= $item_attributes[0]["elemType"] == 2 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$earth .= $item_attributes[0]["elemType"] == 3 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$holy .= $item_attributes[0]["elemType"] == 4 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$unholy .= $item_attributes[0]["elemType"] == 5 ? $item_attributes[0]["elemValue"] ?? "" : null;
						$attrubutes = $fire.",".$water.",".$wind.",".$earth.",".$holy.",".$unholy.",";
					}
					$augment = $item_attributes[0]["augAttributes"] > 0 ? 1 : $augment;
				}
			}
			if(isset($this->QUERY_SELECT_ITEM_STATS_AUGMENTATIONS) && !empty($this->QUERY_SELECT_ITEM_STATS_AUGMENTATIONS)){
				$item_augmentations = $this->execute($this->QUERY_SELECT_ITEM_STATS_AUGMENTATIONS,[$itemid]);
				if(count($item_augmentations) > 0){
					$augment = 1;
				}
			}
			$attrubutes = empty($attrubutes) ? ",,,,,," : $attrubutes;
			return $attrubutes.$augment.",";
		}
		
		public function showCharacterItems($loc,$charid,$login,$enchant=false,$pvpItems=false){
			if($enchant && !$this->ENABLE_SAFE_ENCHANT_SYSTEM){
				return $this->resposta("The Safe Enchant system is disabled","Oops...","error");
			}
			$result = array();
			$noPvpItems = !$this->db_type ? str_replace("i.item_id","i.item_type",$this->noPvpItems) : $this->noPvpItems;
			$wherePvP = !$pvpItems ? $noPvpItems : null;
			if($enchant){
				if($this->db_type){
					$records = $this->execute(str_replace("{LOC}",$loc,str_replace("{WHERE_PVP}",$wherePvP,$this->QUERY_GET_CHARACTER_ITEMS_1)),[$charid,$login]);
				}else{
					$inStore = null;
					$records_store = $this->execute($this->QUERY_GET_STORE_ITEMS,[$charid]);
					if(count($records_store) > 0){
						for($x=0;$x<count($records_store);$x++){
							$itemStoreId = explode(";",$records_store[$x]["item_id"]);
							for($y=0;$y<(count($itemStoreId)-1);$y++){
								$inStoreSeparator = $y < (count($itemStoreId)-2) ? "," : null;
								$inStore .= "'".$itemStoreId[$y]."'".$inStoreSeparator;
							}
						}
					}
					$inStore = empty($inStore) ? "''" : $inStore;
					if($loc == "WAREHOUSE"){
						$records = $this->execute(str_replace("{STORE}",$inStore,str_replace("{WHERE_PVP}",$wherePvP,$this->QUERY_GET_CHARACTER_ITEMS_1)),[$charid,$login]);
					}else{
						$records = $this->execute(str_replace("{STORE}",$inStore,str_replace("{WHERE_PVP}",$wherePvP,$this->QUERY_GET_CHARACTER_ITEMS_2)),[$charid,$login,$loc]);
					}
				}
			}else{
				if($this->db_type){
					$records = $this->execute(str_replace("{LOC}",$loc,str_replace("{WHERE_PVP}",$wherePvP,$this->QUERY_GET_CHARACTER_ITEMS_2)),[$charid,$login]);
				}else{
					$inStore = null;
					$records_store = $this->execute($this->QUERY_GET_STORE_ITEMS,[$charid]);
					if(count($records_store) > 0){
						for($x=0;$x<count($records_store);$x++){
							$itemStoreId = explode(";",$records_store[$x]["item_id"]);
							for($x=0;$x<(count($itemStoreId)-1);$x++){
								$inStoreSeparator = $x < (count($itemStoreId)-2) ? "," : null;
								$inStore .= "'".$itemStoreId[$x]."'".$inStoreSeparator;
							}
						}
					}
					$inStore = empty($inStore) ? "''" : $inStore;
					if($loc == "WAREHOUSE"){
						$records = $this->execute(str_replace("{STORE}",$inStore,$this->QUERY_GET_CHARACTER_ITEMS_3),[$charid,$login]);
					}else{
						$records = $this->execute(str_replace("{STORE}",$inStore,$this->QUERY_GET_CHARACTER_ITEMS_4),[$charid,$login,$loc]);
					}
				}
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					$augment = null;
					$fire = null;
					$water = null;
					$wind = null;
					$earth = null;
					$holy = null;
					$unholy = null;
					if($this->db_type){
						if(isset($records[$x]["attribute_fire"]) || isset($records[$x]["attribute_water"]) || isset($records[$x]["attribute_wind"]) || isset($records[$x]["attribute_earth"]) || isset($records[$x]["attribute_holy"]) || isset($records[$x]["attribute_unholy"]) || isset($records[$x]["augmentation_id"])){
							$fire = empty($records[$x]["attribute_fire"]) ? $fire : $records[$x]["attribute_fire"];
							$water = empty($records[$x]["attribute_water"]) ? $water : $records[$x]["attribute_water"];
							$wind = empty($records[$x]["attribute_wind"]) ? $wind : $records[$x]["attribute_wind"];
							$earth = empty($records[$x]["attribute_earth"]) ? $earth : $records[$x]["attribute_earth"];
							$holy = empty($records[$x]["attribute_holy"]) ? $holy : $records[$x]["attribute_holy"];
							$unholy = empty($records[$x]["attribute_unholy"]) ? $unholy : $records[$x]["attribute_unholy"];
							$augment = $records[$x]["augmentation_id"] > 0 ? "Augmented " : $augment;
						}else{
							$augElem = explode(",", $this->getAugElem($records[$x]["object_id"]));
							$fire = empty($augElem[0]) ? $fire : $augElem[0];
							$water = empty($augElem[1]) ? $water : $augElem[1];
							$wind = empty($augElem[2]) ? $wind : $augElem[2];
							$earth = empty($augElem[3]) ? $earth : $augElem[3];
							$holy = empty($augElem[4]) ? $holy : $augElem[4];
							$unholy = empty($augElem[5]) ? $unholy : $augElem[5];
							$augment = empty($augElem[6]) ? $augment : "Augmented ";
						}
					}else{
						$augment = $records[$x]["augmentation"] > 0 ? "Augmented " : $augment;
					}
					$attrubutes = array($fire,$water,$wind,$earth,$holy,$unholy);
					$allowedEnchantableGrades = explode(",",$this->allowEnchantItemsGrade);
					$allowedSellableGrades = explode(",",$this->allowSellItemsGrade);
					if($this->ENABLE_ITEM_BROKER){
						$buttonSell = $records[$x]["itemType"] == "Armor" && $records[$x]["enchant_level"] != "" && $records[$x]["itemGrade"] != "" && in_array($records[$x]["itemGrade"],$allowedSellableGrades) || $records[$x]["itemType"] == "Weapon" && $records[$x]["enchant_level"] != "" && $records[$x]["itemGrade"] != "" && in_array($records[$x]["itemGrade"],$allowedSellableGrades) ? "<input type=\"checkbox\" form=\"itemSale\" class=\"form-check-input shadow\" name=\"items[]\" value=\"".$records[$x]["object_id"]."\">" : "<input type=\"checkbox\" class=\"form-check-input\" style=\"opacity:0.3;\" disabled>";
					}else{
						$buttonSell = null;
					}
					$buttonEnchant = $records[$x]["itemType"] == "Armor" && $records[$x]["itemGrade"] != "" && in_array($records[$x]["itemGrade"],$allowedEnchantableGrades) && $records[$x]["enchant_level"] != "" && $records[$x]["enchant_level"] < $this->MAX_ENCHANT && $records[$x]["enchant_level"] >= 0 || $records[$x]["itemType"] == "Weapon" && $records[$x]["itemGrade"] != "" && in_array($records[$x]["itemGrade"],$allowedEnchantableGrades) && $records[$x]["enchant_level"] != "" && $records[$x]["enchant_level"] < $this->MAX_ENCHANT && $records[$x]["enchant_level"] >= 0 ? "<form action=\"\" method=\"post\" style=\"margin:0px; padding:0px;\" id=\"".$records[$x]["object_id"]."\"><input type=\"hidden\" name=\"itemId\" value=\"".$records[$x]["object_id"]."\"><input type=\"hidden\" name=\"submitEnchantItem\" value=\"enchant\"></form><button class=\"btn btn-primary btn-sm w-100\" onclick=\"Swal.fire({ text: 'Are you sure you want to enchant this item?', showDenyButton: true, showCancelButton: false, confirmButtonText: `Yes, do it!`, denyButtonText: `Cancel`, allowOutsideClick: false }).then((result) => { if(result.isConfirmed){ Swal.fire({ title: 'Processing', text: 'Please wait...', allowOutsideClick: false, showDenyButton: false, showCloseButton: false, showCancelButton: false, showConfirmButton: false}); document.getElementById('".$records[$x]["object_id"]."').submit(); } else if (result.isDenied){ Swal.fire('Canceled', '', 'info'); } });\">+".($records[$x]["enchant_level"] + 1)."</button>" : null;
					$item = array($records[$x]["count"],$records[$x]["enchant_level"],...$attrubutes,$records[$x]["itemId"],$augment.str_replace("{","{{_}",$records[$x]["itemName"]),$records[$x]["itemType"],$records[$x]["itemTypeName"],$records[$x]["itemWeight"],$records[$x]["itemGrade"],$records[$x]["itemBodyPart"],$records[$x]["itemPAD"],$records[$x]["itemMAD"],$records[$x]["itemSS"],$records[$x]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))));
					array_push($result, array("itemImg" => file_exists("images/icons/".str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))).".png") ? str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))) : 404, "itemName" => $augment.str_replace("{","{{_}",$records[$x]["itemName"]), "itemEnchant" => $records[$x]["enchant_level"], "itemDetails" => $this->showItemDetails($item), "buttonSell" => $buttonSell, "buttonEnchant" => $buttonEnchant, "itemOwnerId" => $charid, "itemOwnerName" => $records[$x]["char_name"]));
				}
			}
			return $result;
		}
		
		private function showItemDetails($item, $combo = false){
			$html = null;
			$grade = !empty($item[13]) ? "<img src='images/miscs/".$item[13]."-grade.png' border='0' height='10'>" : null;
			switch ($item[14]){
				case "head":
					$bodypart = "Headgear"; break;
				case "chest":
					$bodypart = "Upper Body"; break;
				case "onepiece":
					$bodypart = "Upper and Lower Body"; break;
				case "legs":
					$bodypart = "Lower Body"; break;
				case "gloves":
					$bodypart = "Gloves"; break;
				case "feet":
					$bodypart = "Boots"; break;
				case "back":
					$bodypart = "Cloak"; break;
				case "underwear":
					$bodypart = "Underwear"; break;
				case "waist":
					$bodypart = "Belt"; break;
				case "rhand":
					$bodypart = "One Handed"; break;
				case "lhand":
					$bodypart = "One Handed"; break;
				case "lrhand":
					$bodypart = "Two Handed"; break;
				case "rfinger;lfinger":
					$bodypart = "Ring"; break;
				case "neck":
					$bodypart = "Necklace"; break;
				case "rear;lear":
					$bodypart = "Earring"; break;
				case "rbracelet":
					$bodypart = "Bracelet"; break;
				case "lbracelet":
					$bodypart = "Bracelet"; break;
				default:
					$bodypart = null; break;
			}
			if($item[10] == 'Armor' && !empty($bodypart)){
				$item[1] = empty($item[1]) ? 0 : $item[1];
				if($bodypart == 'Ring' or $bodypart == 'Earring' or $bodypart == 'Necklace'){
					$description = "<span class='specification'>[ Jewelry Specification ]</span>";
					$legend = "<span class='specification_legend'>".$bodypart."</span>";
				}else{
					$description = "<span class='specification'>[ Armor Specification ]</span>";
					$legend = "<span class='specification_legend'>".$bodypart;
					$legend .= !empty($item[11]) ? " / ".ucfirst($item[11]) : null;
					$legend .= "</span>";
				}
				if($item[15] > 0){
					if($item[1] > 0 and $item[1] < 4){
						$padt = (1 * $item[1]) + $item[15];
					}elseif($item[1] > 3){
						$padt = ((3 * 1) + (3 * ($item[1] - 3))) + $item[15];
					}else{
						$padt = $item[15];
					}
					$pad = "P. Def. : <span class='attribute'>".$padt."</span><br />";
				}else{
					$pad = null;
				}
				if($item[16] > 0){
					if($item[1] > 0 and $item[1] < 4){
						$madt = (1 * $item[1]) + $item[16];
					}elseif($item[1]> 3){
						$madt = ((3 * 1) + (3 * ($item[1] - 3))) + $item[16];
					}else{
						$madt = $item[16];
					}
					$mad = "M. Def. : <span class='attribute'>".$madt."</span><br />";
				}else{
					$mad = null;
				}
			}elseif($item[10] == 'Weapon' && !empty($bodypart)){
				$item[1] = empty($item[1]) ? 0 : $item[1];
				$description = "<span class='specification'>[ Weapon Specification ]</span>";
				$legend = "<span class='specification_legend'>".ucfirst($item[11])." / ".$bodypart."</span>";
				if($item[15] > 0){
					if($item[1] > 0 and $item[1] < 4){
						$padt = (4 * $item[1]) + $item[15];
					}elseif($item[1] > 3){
						$padt = ((3 * 4) + (8 * ($item[1] - 3))) + $item[15];
					}else{
						$padt = $item[15];
					}
					$pad = "P. Atk. : <span class='attribute'>".$padt."</span><br />";
				}else{
					$pad = null;
				}
				if($item[16] > 0){
					if($item[1] > 0 and $item[1] < 4){
						$madt = (3 * $item[1]) + $item[16];
					}elseif($item[1] > 3){
						$madt = ((3 * 3) + (6 * ($item[1] - 3))) + $item[16];
					}else{
						$madt = $item[16];
					}
					$mad = "M. Atk. : <span class='attribute'>".$madt."</span><br />";
				}else{
					$mad = null;
				}
			}else{
				$mad = null;
				$pad = null;
				$legend = null;
				$description = ($combo ? "<br>" : null)."<span class='specification'>[ Item Specification ]</span>";
			}
			$legend = !empty($legend) ? $legend."<br />" : null;
			$description = !empty($description) ? $description."<br />" : null;
			if($item[17] > 0){
				$ss = "<br />Soulshot Used : <span class='attribute'>X ".$item[17]."</span>";
			}else{
				$ss = null;
			}
			if($item[18] > 0){
				$bss = "<br />Spiritshot Used : <span class='attribute'>X ".$item[18]."</span>";
			}else{
				$bss = null;
			}
			if($combo){
				$foto = file_exists("images/icons/".$item[19].".png") ? $item[19] : 404;
				$img = "<div class='item-details".(strpos($item[9], '{{_}PvP}') !== false ? " pvp" : null)."'></div><img src='images/icons/".$foto.".png' style='border:1px solid #666; width:32px; height:32px; margin:2px 5px 0px 0px; float:left;' align='top'>";
			}else{
				$img = null;
			}
			$enchant = $item[10] != 'EtcItem' ?  !empty($bodypart) ? "<span class='attribute' style='margin-right:3px;'>+".$item[1]."</span>" : null : null;
			$itemname = explode(" - ", $item[9]);
			$itemname2 = null;
			if(($item[8] >= 10870 && $item[8] <= 11604) || ($item[8] >= 12852 && $item[8] <= 13001) || ($item[8] >= 14412 && $item[8] <= 14460) || ($item[8] >= 14526 && $item[8] <= 14529) || ($item[8] >= 16042 && $item[8] <= 16097) || ($item[8] >= 16134 && $item[8] <= 16159) || ($item[8] >= 16168 && $item[8] <= 16176) || ($item[8] >= 16179 && $item[8] <= 16220) || ($item[8] >= 16289 && $item[8] <= 16356) || ($item[8] >= 16369 && $item[8] <= 16380)){
				for($c=0;$c<count($itemname);$c++){
					$itemDivisor = $c == (count($itemname) - 1) ? null : " - ";
					$itemname2 .= $c == (count($itemname) - 1) && $c != 0 ? "<font color='#ffd969'>".$itemname[$c]."</font>" : "<span style='color:yellow;'>".$itemname[$c].$itemDivisor."</span>";
				}
			}else{
				if(count($itemname) == 1){
					$itemname2 = $item[9];
				}else{
					for($c=0;$c<count($itemname);$c++){
						$itemDivisor = $c == (count($itemname) - 1) ? null : " - ";
						$itemname2 .= $c == (count($itemname) - 1) && $c != 0 ? "<font color='#ffd969'>".$itemname[$c]."</font>" : $itemname[$c].$itemDivisor;
					}
				}
			}
			$itemname = $item[10] == 'EtcItem' || $itemname[0] == "Common Item" ? $item[9] : $itemname2;
			$html .= "<span style='text-shadow:1px 1px #444;'>".$itemname."</span><span> ".$grade;
			$html .= $item[10] == 'EtcItem' ? " [".number_format($item[0],0,'.','.')."]" : null;
			$html .= "</span><br />".$legend."<br />".$description.$pad.$mad."Weight : <span class='attribute'>";
			$html .= !empty($item[12]) ? $item[12] : 0;
			$html .= "</span>".$ss.$bss;
			$att = 0;
			if($item[10] == 'Weapon'){
				$attItem = $this->attrWeapons($item[2],"fire").$this->attrWeapons($item[3],"water").$this->attrWeapons($item[4],"wind").$this->attrWeapons($item[5],"earth").$this->attrWeapons($item[6],"holy").$this->attrWeapons($item[7],"dark");
				$attItem = explode("|", $attItem);
				for($k=0;$k<count($attItem);$k++){
					$attSubItem = explode(";", $attItem[$k]);
					if(!empty($attSubItem[0])){
						$att++;
						if($att == 1){
							$html .= "<br /><br /><span class='specification'>[ Element Specification ]</span><br />";
						}
						$html .= "<span class=attribute>".ucfirst($attSubItem[4])."</span> Lv <span class='attribute'>".$attSubItem[1]."</span> (<span class=attribute>".ucfirst($attSubItem[4])."</span> P. Atk. <span class='attribute'>".$attSubItem[0]."</span>)<br /><span style='background:url(images/miscs/bar_".$attSubItem[4]."_1.png) no-repeat;width:140px;height:7px;display:block;'><img src='images/miscs/bar_".$attSubItem[4]."_2.png' border='0' width='".@((($attSubItem[0] - $attSubItem[2]) / ($attSubItem[3] - $attSubItem[2])) * 100)."%' height='7'></span>";
					}
				}
			}elseif($item[10] == 'Armor'){
				$attItem = $this->attrArmors($item[2],"fire").$this->attrArmors($item[3],"water").$this->attrArmors($item[4],"wind").$this->attrArmors($item[5],"earth").$this->attrArmors($item[6],"holy").$this->attrArmors($item[7],"dark");
				$attItem = explode("|", $attItem);
				for($l=0;$l<count($attItem);$l++){
					$attSubItem = explode(";", $attItem[$l]);
					if(!empty($attSubItem[0])){
						$att++;
						if($att == 1){
							$html .= "<br /><br /><span class='specification'>[ Element Specification ]</span><br />";
						}
						if($attSubItem[4] == 'fire'){
							$elemento = 'Water';
						}elseif($attSubItem[4] == 'water'){
							$elemento = 'Fire';
						}elseif($attSubItem[4] == 'wind'){
							$elemento = 'Earth';
						}elseif($attSubItem[4] == 'earth'){
							$elemento = 'Wind';
						}elseif($attSubItem[4] == 'holy'){
							$elemento = 'Dark';
						}elseif($attSubItem[4] == 'dark'){
							$elemento = 'Holy';
						}else{
							$elemento = null;
						}
						$html .= "<span class=attribute>".$elemento."</span> Lv <span class='attribute'>".$attSubItem[1]."</span> (<span class=attribute>".ucfirst($attSubItem[4])."</span> P. Def. <span class='attribute'>".$attSubItem[0]."</span>)<br /><span style='background:url(images/miscs/bar_".$attSubItem[4]."_1.png) no-repeat;width:140px;height:7px;display:block;'><img src='images/miscs/bar_".$attSubItem[4]."_2.png' border='0' width='".@((($attSubItem[0] - $attSubItem[2]) / ($attSubItem[3] - $attSubItem[2])) * 100)."%' height='7'></span>";
					}
				}
			}
			return "<div class='itemDetails'>".$img.$enchant.$html."</div>";
		}
		
		private function attrWeapons($att,$color){
			$level = 0;
			$level = $att > 0 && $att <= 24 ? 1 : $level;
			$level = $att > 24  && $att <= 79 ? 2 : $level;
			$level = $att > 79 && $att <= 149 ? 3 : $level;
			$level = $att > 149 && $att <= 174 ? 4 : $level;
			$level = $att > 174 && $att <= 224 ? 5 : $level;
			$level = $att > 224 && $att <= 299 ? 6 : $level;
			$level = $att >= 300 ? 7 : $level;
			$bar_min = 0;
			$bar_min = $level == 1 ? 0 : $bar_min;
			$bar_min = $level == 2 ? 25 : $bar_min;
			$bar_min = $level == 3 ? 80 : $bar_min;
			$bar_min = $level == 4 ? 150 : $bar_min;
			$bar_min = $level == 5 ? 175 : $bar_min;
			$bar_min = $level == 6 ? 225 : $bar_min;
			$bar_min = $level == 7 ? 300 : $bar_min;
			$bar_max = 0;
			$bar_max = $level == 1 ? 24 : $bar_max;
			$bar_max = $level == 2 ? 79 : $bar_max;
			$bar_max = $level == 3 ? 149 : $bar_max;
			$bar_max = $level == 4 ? 174 : $bar_max;
			$bar_max = $level == 5 ? 224 : $bar_max;
			$bar_max = $level == 6 ? 299 : $bar_max;
			$bar_max = $level == 7 ? 300 : $bar_max;
			return $att.";".$level.";".$bar_min.";".$bar_max.";".$color."|";
		}
		
		private function attrArmors($att,$color){
			$level = 0;
			$level = $att > 0 && $att <= 11 ? 1 : $level;
			$level = $att > 11  && $att <= 29 ? 2 : $level;
			$level = $att > 29 && $att <= 59 ? 3 : $level;
			$level = $att > 59 && $att <= 71 ? 4 : $level;
			$level = $att > 71 && $att <= 89 ? 5 : $level;
			$level = $att > 89 && $att <= 119 ? 6 : $level;
			$level = $att >= 120 ? 7 : $level;
			$bar_min = 0;
			$bar_min = $level == 1 ? 0 : $bar_min;
			$bar_min = $level == 2 ? 12 : $bar_min;
			$bar_min = $level == 3 ? 30 : $bar_min;
			$bar_min = $level == 4 ? 60 : $bar_min;
			$bar_min = $level == 5 ? 72 : $bar_min;
			$bar_min = $level == 6 ? 90 : $bar_min;
			$bar_min = $level == 7 ? 120 : $bar_min;
			$bar_max = 0;
			$bar_max = $level == 1 ? 11 : $bar_max;
			$bar_max = $level == 2 ? 29 : $bar_max;
			$bar_max = $level == 3 ? 59 : $bar_max;
			$bar_max = $level == 4 ? 71 : $bar_max;
			$bar_max = $level == 5 ? 89 : $bar_max;
			$bar_max = $level == 6 ? 119 : $bar_max;
			$bar_max = $level == 7 ? 120 : $bar_max;
			return $att.";".$level.";".$bar_min.";".$bar_max.";".$color."|";
		}
		
		public function myCharList($login,$store=false){
			$result = array();
			$records = $this->execute($this->QUERY_LIST_MY_CHARACTERS,[$login]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					if(!$this->db_type && $store){
						$checkStore = $this->execute("SELECT * FROM icp_shop_chars WHERE owner_id = ?",[$records[$x]["char_id"]]);
						if(count($checkStore) == 0){
							array_push($result, array("charName" => $records[$x]["char_name"], "charId" => $records[$x]["char_id"], "charOnline" => $records[$x]["online"]));
						}
					}else{
						array_push($result, array("charName" => $records[$x]["char_name"], "charId" => $records[$x]["char_id"], "charOnline" => $records[$x]["online"]));
					}
				}
			}
			return $result;
		}
		
		public function showScreenshots($status, $sort, $limit, $index = true){
			$images = array();
			if($this->enable_screenshots){
				if($this->db_type){
					if(!empty($limit)){
						$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort." LIMIT ".$limit);
					}else{
						$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort);
					}
				}else{
					if(!empty($limit)){
						$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
					}else{
						$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort);
					}
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						array_push($images, array("screenshotId" => $records[$y]["id"], "screenshotAuthor" => $records[$y]["author"], "screenshotLegend" => $records[$y]["legend"], "screenshotDate" => $records[$y]["date"], "screenshotImg" => $records[$y]["screenshot"], "screenshotNum" => $y+1));
					}
					if(count($images) < $limit && $index){
						for($x=count($images);$x<$limit;$x++){
							array_push($images, array("screenshotId" => "-", "screenshotAuthor" => "-", "screenshotLegend" => "-", "screenshotDate" => date("d/m/Y"), "screenshotImg" => "noimage.jpg", "screenshotNum" => $x+1));
						}
					}
				}else{
					if($index){
						for($x=0;$x<$limit;$x++){
							array_push($images, array("screenshotId" => "-", "screenshotAuthor" => "-", "screenshotLegend" => "-", "screenshotDate" => date("d/m/Y"), "screenshotImg" => "noimage.jpg", "screenshotNum" => $x+1));
						}
					}
				}
			}else{
				if($index){
					for($x=0;$x<$limit;$x++){
						array_push($images, array("screenshotId" => "-", "screenshotAuthor" => "-", "screenshotLegend" => "-", "screenshotDate" => date("d/m/Y"), "screenshotImg" => "noimage.jpg", "screenshotNum" => $x+1));
					}
				}
			}
			return $images;
		}
		
		public function showMyScreenshots($status, $sort, $limit, $login){
			if(!$this->enable_screenshots){
				return $this->resposta("The ScreenShots system is disabled","Oops...","error");
			}
			$images = array();
			if($this->db_type){
				if(!empty($limit)){
					$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = ? AND account = ? ORDER BY ".$sort." LIMIT ".$limit,[$status,$login]);
				}else{
					$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = ? AND account = ?",[$status,$login]);
				}
			}else{
				if(!empty($limit)){
					$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = ? AND account = ? ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY",[$status,$login]);
				}else{
					$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = ? AND account = ?",[$status,$login]);
				}
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($images, array("screenshotId" => $records[$x]["id"], "screenshotAuthor" => $records[$x]["author"], "screenshotLegend" => $records[$x]["legend"], "screenshotDate" => $records[$x]["date"], "screenshotImg" => $records[$x]["screenshot"]));
				}
			}
			return $images;
		}
		
		public function showVideos($status, $sort, $limit, $index = true){
			$videos = array();
			if($this->enable_videos){
				if($this->db_type){
					if(!empty($limit)){
						$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort." LIMIT ".$limit);
					}else{
						$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort);
					}
				}else{
					if(!empty($limit)){
						$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
					}else{
						$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort);
					}
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						array_push($videos, array("videosId" => $records[$y]["id"], "videosAuthor" => $records[$y]["author"], "videosLegend" => $records[$y]["legend"], "videosDate" => $records[$y]["date"], "videosLink" => $records[$y]["link"], "videosImg" => $records[$y]["photo"], "videosNum" => $y+1, "videosUrl" => $records[$y]["url"]));
						$y++;
					}
					if(count($records) < $limit && $index){
						for($x=0;$x<($limit - count($records));$x++){
							array_push($videos, array("videosId" => "-", "videosAuthor" => "-", "videosLegend" => "-", "videosDate" => date("d/m/Y"), "videosLink" => "<iframe width='560' height='315' src='https://www.youtube.com/embed/qDeMdjTmKck' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>", "videosImg" => "http://i1.ytimg.com/vi/qDeMdjTmKck/default.jpg;;https://www.youtube.com/watch?v=qDeMdjTmKck", "videosNum" => $x+1, "videosUrl" => "https://www.youtube.com/embed/qDeMdjTmKck"));
						}
					}
				}else{
					if($index){
						for($x=0;$x<$limit;$x++){
							array_push($videos, array("videosId" => "-", "videosAuthor" => "-", "videosLegend" => "-", "videosDate" => date("d/m/Y"), "videosLink" => "<iframe width='560' height='315' src='https://www.youtube.com/embed/qDeMdjTmKck' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>", "videosImg" => "http://i1.ytimg.com/vi/qDeMdjTmKck/default.jpg;;https://www.youtube.com/watch?v=qDeMdjTmKck", "videosNum" => $x+1, "videosUrl" => "https://www.youtube.com/embed/qDeMdjTmKck"));
						}
					}
				}
			}else{
				if($index){
					for($x=0;$x<$limit;$x++){
						array_push($videos, array("videosId" => "-", "videosAuthor" => "-", "videosLegend" => "-", "videosDate" => date("d/m/Y"), "videosLink" => "<iframe width='560' height='315' src='https://www.youtube.com/embed/qDeMdjTmKck' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>", "videosImg" => "http://i1.ytimg.com/vi/qDeMdjTmKck/default.jpg;;https://www.youtube.com/watch?v=qDeMdjTmKck", "videosNum" => $x+1, "videosUrl" => "https://www.youtube.com/embed/qDeMdjTmKck"));
					}
				}
			}
			return $videos;
		}
		
		public function showMyVideos($status, $sort, $limit, $login){
			if(!$this->enable_videos){
				return $this->resposta("The Video system is disabled","Oops...","error");
			}
			$videos = array();
			if($this->db_type){
				if(!empty($limit)){
					$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = ? AND account = ? ORDER BY ".$sort." LIMIT ".$limit,[$status,$login]);
				}else{
					$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = ? AND account = ?",[$status,$login]);
				}
			}else{
				if(!empty($limit)){
					$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = ? AND account = ? ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY",[$status,$login]);
				}else{
					$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = ? AND account = ?",[$status,$login]);
				}
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($videos, array("videoId" => $records[$x]["id"], "videoAuthor" => $records[$x]["author"], "videoLegend" => $records[$x]["legend"], "videoDate" => $records[$x]["date"], "videoImg" => $records[$x]["photo"], "videosUrl" => $records[$x]["url"]));
				}
			}
			return $videos;
		}
		
		function primeShop($id=0,$limit=0){
			if(!$this->ENABLE_PRIME_SHOP){
				return $this->resposta("Prime shop is disabled","Oops...","error");
			}
			$result = array();
			if(!empty($limit)){
				if($this->db_type){
					$records = $this->execute("SELECT * FROM icp_prime_shop ORDER BY id DESC LIMIT ".$limit);
				}else{
					$records = $this->execute("SELECT * FROM icp_prime_shop ORDER BY id DESC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
				}
			}else{
				$where = !empty($id) ? " WHERE id = '".preg_replace("/(\D)/i" , "" , $id)."'" : null;
				$records = $this->execute("SELECT * FROM icp_prime_shop".$where." ORDER BY id DESC");
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					$item = explode(",", $records[$x]["item_id"]);
					$count = explode(",", $records[$x]["count"]);
					$enchant = explode(",", $records[$x]["enchant"]);
					$attribute_fire = explode(",", $records[$x]["attribute_fire"]);
					$attribute_water = explode(",", $records[$x]["attribute_water"]);
					$attribute_wind = explode(",", $records[$x]["attribute_wind"]);
					$attribute_earth = explode(",", $records[$x]["attribute_earth"]);
					$attribute_holy = explode(",", $records[$x]["attribute_holy"]);
					$attribute_unholy = explode(",", $records[$x]["attribute_unholy"]);
					$itemDetails = null;
					$itemName = null;
					$itemIcon = null;
					for($z=0;$z<(count($count)-1);$z++){
						$records2 = $this->execute("SELECT * FROM ".$this->chronicleTables("icp_icons")." WHERE itemId = ?",[$item[$z]]);
						if(count($records2) == 1){
							if(empty($id)){
								$itemDetails .= $this->showItemDetails(array($count[$z],$enchant[$z],$attribute_fire[$z],$attribute_water[$z],$attribute_wind[$z],$attribute_earth[$z],$attribute_holy[$z],$attribute_unholy[$z],$records2[0]["itemId"],str_replace("{","{{_}",$records2[0]["itemName"]),$records2[0]["itemType"],$records2[0]["itemTypeName"],$records2[0]["itemWeight"],$records2[0]["itemGrade"],$records2[0]["itemBodyPart"],$records2[0]["itemPAD"],$records2[0]["itemMAD"],$records2[0]["itemSS"],$records2[0]["itemBSS"],str_replace("icon.","",str_replace("icon..","",strtolower($records2[0]["itemIcon"])))), count($count) > 2 ? true : false);
							}else{
								$itemDetails = $this->showItemDetails(array($count[$z],$enchant[$z],$attribute_fire[$z],$attribute_water[$z],$attribute_wind[$z],$attribute_earth[$z],$attribute_holy[$z],$attribute_unholy[$z],$records2[0]["itemId"],str_replace("{","{{_}",$records2[0]["itemName"]),$records2[0]["itemType"],$records2[0]["itemTypeName"],$records2[0]["itemWeight"],$records2[0]["itemGrade"],$records2[0]["itemBodyPart"],$records2[0]["itemPAD"],$records2[0]["itemMAD"],$records2[0]["itemSS"],$records2[0]["itemBSS"],str_replace("icon.","",str_replace("icon..","",strtolower($records2[0]["itemIcon"])))));
							}
							if($z == 0){
								$itemName = str_replace("{","{{_}",$records2[0]["itemName"]);
								$itemIcon = str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records2[0]["itemIcon"]))))))));
							}
							if(!empty($id)){
								array_push($result, array("itemAmount" => $count[$z], "itemEnchant" => $enchant[$z], "itemName" => str_replace("{","{{_}",$records2[0]["itemName"]), "itemImg" => str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records2[0]["itemIcon"])))))))), "itemPrice" => $records[$x]["price"], "itemDetails" => $itemDetails, "itemId" => ltrim($records[$x]["id"], "0")));
							}
						}
					}
					if(empty($id)){
						array_push($result, array("itemAmount" => $count[0], "itemEnchant" => $enchant[0], "itemName" => $itemName, "itemImg" => str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",$itemIcon))))), "itemPrice" => $records[$x]["price"], "itemDetails" => $itemDetails, "itemId" => ltrim($records[$x]["id"], "0")));
					}
				}
			}
			return $result;
		}
		
		public function charBroker($id=0,$login=null,$type=null,$my=null,$limit=null){
			if(!$this->ENABLE_CHARACTER_BROKER){
				return $this->resposta("Character Broker is disabled.","Oops...","error");
			}
			$result = array();
			$timeAuction = $this->AUCTION_CHARACTER_BROKER_DAYS * 86400;
			if($this->db_type){
				if($my == "sales"){
					$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE s.status = '1' AND s.account = '".$login."' ORDER BY s.id DESC");
				}elseif($my == "bids"){
					$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) AS price_auction FROM icp_shop_chars AS s WHERE s.type = '2' AND s.status = '1' AND (SELECT COUNT(*) FROM icp_shop_chars_auction WHERE bidId = s.id AND account = '".$login."') > 0 ORDER BY s.id DESC");
				}else{
					$where = !empty($id) ? " AND s.id = '".ltrim(preg_replace("/(\D)/i" , "" , $id), "0")."'" : null;
					$where = !empty($type) ? " AND s.type = '".ltrim(preg_replace("/(\D)/i" , "" , $type), "0")."'" : $where;
					if(empty($limit)){
						$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE IF(s.type = '2', (UNIX_TIMESTAMP(s.date) + '".$timeAuction."') > '".time()."', '1'='1') AND s.status = '1'".$where." ORDER BY s.id DESC");
					}else{
						$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE IF(s.type = '2', (UNIX_TIMESTAMP(s.date) + '".$timeAuction."') > '".time()."', '1'='1') AND s.status = '1'".$where." ORDER BY s.id DESC LIMIT ".$limit);
					}
				}
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						$price = !empty($records[$x]["price_auction"]) ? $records[$x]["price_auction"] : $records[$x]["price"];
						array_push($result, array("charId" => ltrim($records[$x]["id"], "0"), "charPrice" => $price, "charInitialPrice" => $records[$x]["price"], "charType" => $records[$x]["type"], "charAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$x]["date"]) + $timeAuction)), "charAuctionPrice" => $records[$x]["price_auction"], "charDetails" => $this->charStatus($records[$x]["has_account"],$records[$x]["owner_id"]), "charAccount" => $records[$x]["has_account"]));
					}
				}
			}else{
				if($my == "sales"){
					$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE s.status = '1' AND s.account = '".$login."' ORDER BY s.id DESC");
				}elseif($my == "bids"){
					$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) AS price_auction FROM icp_shop_chars AS s WHERE s.type = '2' AND s.status = '1' AND (SELECT COUNT(*) FROM icp_shop_chars_auction WHERE bidId = s.id AND account = '".$login."') > 0 ORDER BY s.id DESC");
				}else{
					$where = !empty($id) ? " AND s.id = '".ltrim(preg_replace("/(\D)/i" , "" , $id), "0")."'" : null;
					$where = !empty($type) ? " AND s.type = '".ltrim(preg_replace("/(\D)/i" , "" , $type), "0")."'" : $where;
					if(empty($limit)){
						$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE CASE WHEN s.type = '2' THEN CASE WHEN DATEADD(DAY,".$this->AUCTION_CHARACTER_BROKER_DAYS.",s.date) > '".date("Y-m-d H:i:s")."' THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.status = '1'".$where." ORDER BY s.id DESC");
					}else{
						$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type = '2' THEN (SELECT MAX(value) FROM icp_shop_chars_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_chars AS s WHERE CASE WHEN s.type = '2' THEN CASE WHEN DATEADD(DAY,".$this->AUCTION_CHARACTER_BROKER_DAYS.",s.date) > '".date("Y-m-d H:i:s")."' THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.status = '1'".$where." ORDER BY s.id DESC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
					}
				}
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						$price = !empty($records[$x]["price_auction"]) ? $records[$x]["price_auction"] : $records[$x]["price"];
						array_push($result, array("charId" => ltrim($records[$x]["id"], "0"), "charPrice" => $price, "charInitialPrice" => $records[$x]["price"], "charType" => $records[$x]["type"], "charAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$x]["date"]) + $timeAuction)), "charAuctionPrice" => $records[$x]["price_auction"], "charDetails" => $this->charStatus($records[$x]["account"],$records[$x]["owner_id"]), "charAccount" => $records[$x]["account"]));
					}
				}
			}
			return $result;
		}
		
		function itemBroker($id=0,$login=null,$type=null,$my=null,$limit=null){
			if(!$this->ENABLE_ITEM_BROKER){
				return $this->resposta("Item Broker is disabled.","Oops...","error");
			}
			$result = array();
			$timeAuction = $this->AUCTION_ITEM_BROKER_DAYS * 86400;
			if($this->db_type){
				if($my == "sales"){
					$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE s.status = '1' AND (".str_replace("?","s.owner_id",$this->QUERY_SELECT_CHARACTER_ACC).") = '".$login."' ORDER BY s.id DESC");
				}elseif($my == "bids"){
					$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) AS price_auction FROM icp_shop_items AS s WHERE s.type > '2' AND s.status = '1' AND (SELECT COUNT(*) FROM icp_shop_items_auction WHERE bidId = s.id AND account = '".$login."') > 0 ORDER BY s.id DESC");
				}else{
					$where = !empty($id) ? " AND s.id = '".ltrim(preg_replace("/(\D)/i" , "" , $id), "0")."'" : null;
					$where = !empty($type) ? " AND s.type = '".ltrim(preg_replace("/(\D)/i" , "" , $type), "0")."'" : $where;
					if(empty($limit)){
						$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE IF(s.type > '2', (UNIX_TIMESTAMP(s.date) + '".$timeAuction."') > '".time()."', '1'='1') AND s.status = '1'".$where." ORDER BY s.id DESC");
					}else{
						$records = $this->execute("SELECT s.*, (".$this->QUERY_SELECT_CHARACTER_NAME_2."s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE IF(s.type > '2', (UNIX_TIMESTAMP(s.date) + '".$timeAuction."') > '".time()."', '1'='1') AND s.status = '1'".$where." ORDER BY s.id DESC LIMIT ".$limit);
					}
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						$price = !empty($records[$y]["price_auction"]) ? $records[$y]["price_auction"] : $records[$y]["price"];
						$itemid = explode(";", $records[$y]["item_id"]);
						$count = explode(";", $records[$y]["count"]);
						$enchant = explode(";", $records[$y]["enchant"]);
						$augment = explode(";", $records[$y]["augmented"]);
						$attribute_fire = explode(";", $records[$y]["fire"]);
						$attribute_water = explode(";", $records[$y]["water"]);
						$attribute_wind = explode(";", $records[$y]["wind"]);
						$attribute_earth = explode(";", $records[$y]["earth"]);
						$attribute_holy = explode(";", $records[$y]["holy"]);
						$attribute_unholy = explode(";", $records[$y]["unholy"]);
						$itemDetails = null;
						$itemName = null;
						$itemIcon = null;
						for($x=0;$x<(count($count)-1);$x++){
							$items_info = $this->execute("SELECT * FROM ".$this->chronicleTables("icp_icons")." WHERE itemId = '".$itemid[$x]."'");
							if(count($items_info) > 0){
								$augmented = $augment[$x] > 0 ? "Augmented " : null;
								if(empty($id)){
									$itemDetails .= $this->showItemDetails(array($count[$x],$enchant[$x],$attribute_fire[$x],$attribute_water[$x],$attribute_wind[$x],$attribute_earth[$x],$attribute_holy[$x],$attribute_unholy[$x],$items_info[0]["itemId"],$augmented.str_replace("{","{{_}",$items_info[0]["itemName"]),$items_info[0]["itemType"],$items_info[0]["itemTypeName"],$items_info[0]["itemWeight"],$items_info[0]["itemGrade"],$items_info[0]["itemBodyPart"],$items_info[0]["itemPAD"],$items_info[0]["itemMAD"],$items_info[0]["itemSS"],$items_info[0]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))))), count($count) > 2 ? true : false);
								}else{
									$itemDetails = $this->showItemDetails(array($count[$x],$enchant[$x],$attribute_fire[$x],$attribute_water[$x],$attribute_wind[$x],$attribute_earth[$x],$attribute_holy[$x],$attribute_unholy[$x],$items_info[0]["itemId"],$augmented.str_replace("{","{{_}",$items_info[0]["itemName"]),$items_info[0]["itemType"],$items_info[0]["itemTypeName"],$items_info[0]["itemWeight"],$items_info[0]["itemGrade"],$items_info[0]["itemBodyPart"],$items_info[0]["itemPAD"],$items_info[0]["itemMAD"],$items_info[0]["itemSS"],$items_info[0]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))))));
								}
								if($x == 0){
									$itemName = $augmented.str_replace("{","{{_}",$items_info[0]["itemName"]);
									$itemIcon = str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))));
								}
								if(!empty($id)){
									array_push($result, array("itemAmount" => $count[$x], "itemEnchant" => $enchant[$x], "itemName" => $augmented.str_replace("{","{{_}",$items_info[0]["itemName"]), "itemImg" => str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"])))))))), "itemPrice" => $price, "itemInitialPrice" => $records[$y]["price"], "itemDetails" => $itemDetails, "itemId" => ltrim($records[$y]["id"], "0"), "itemCharName" => $records[$y]["char_name"], "itemType" => $records[$y]["type"], "itemAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$y]["date"]) + $timeAuction)), "itemAuctionPrice" => $records[$y]["price_auction"]));
								}
							}
						}
						if(empty($id)){
							array_push($result, array("itemAmount" => $count[0], "itemEnchant" => $enchant[0], "itemName" => $itemName, "itemImg" => $itemIcon, "itemId" => ltrim($records[$y]["id"], "0"), "itemCharName" => $records[$y]["char_name"], "itemType" => $records[$y]["type"], "itemAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$y]["date"]) + $timeAuction)), "itemPrice" => $price, "itemInitialPrice" => $records[$y]["price"], "itemDetails" => $itemDetails, "itemAuctionPrice" => $records[$y]["price_auction"]));
						}
					}
				}
			}else{
				if($my == "sales"){
					$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE s.status = '1' AND (SELECT account_name FROM user_data WHERE char_id = s.owner_id) = '".$login."' ORDER BY s.id DESC");
				}elseif($my == "bids"){
					$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) AS price_auction FROM icp_shop_items AS s WHERE s.type > '2' AND s.status = '1' AND (SELECT COUNT(*) FROM icp_shop_items_auction WHERE bidId = s.id AND account = '".$login."') > 0 ORDER BY s.id DESC");
				}else{
					$where = !empty($id) ? " AND s.id = '".ltrim(preg_replace("/(\D)/i" , "" , $id), "0")."'" : null;
					$where = !empty($type) ? " AND s.type = '".ltrim(preg_replace("/(\D)/i" , "" , $type), "0")."'" : $where;
					if(empty($limit)){
						$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE CASE WHEN s.type > '2' THEN CASE WHEN DATEADD(DAY,".$this->AUCTION_ITEM_BROKER_DAYS.",s.date) > '".date("Y-m-d H:i:s")."' THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.status = '1'".$where." ORDER BY s.id DESC");
					}else{
						$records = $this->execute("SELECT s.*, (SELECT char_name FROM user_data WHERE char_id = s.owner_id) AS char_name, CASE WHEN s.type > '2' THEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) END AS price_auction FROM icp_shop_items AS s WHERE CASE WHEN s.type > '2' THEN CASE WHEN DATEADD(DAY,".$this->AUCTION_ITEM_BROKER_DAYS.",s.date) > '".date("Y-m-d H:i:s")."' THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.status = '1'".$where." ORDER BY s.id DESC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
					}
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						$price = !empty($records[$y]["price_auction"]) ? $records[$y]["price_auction"] : $records[$y]["price"];
						$itemid = explode(";", $records[$y]["item_id"]);
						$count = count($itemid);
						$itemDetails = null;
						$itemIcon = null;
						for($x=0;$x<($count-1);$x++){
							$items_info = $this->execute("SELECT * FROM ".$this->chronicleTables("icp_icons")." AS c, user_item AS i WHERE c.itemId = i.item_type AND i.item_id = ?",[$itemid[$x]]);
							if(count($items_info) > 0){
								$augmented = $items_info[0]["augmentation"] > 0 ? "Augmented " : null;
								$attributes = array("","","","","","");
								if(empty($id)){
									$itemDetails .= $this->showItemDetails(array($items_info[0]["amount"],$items_info[0]["enchant"],...$attributes,$items_info[0]["itemId"],$augmented.str_replace("{","{{_}",$items_info[0]["itemName"]),$items_info[0]["itemType"],$items_info[0]["itemTypeName"],$items_info[0]["itemWeight"],$items_info[0]["itemGrade"],$items_info[0]["itemBodyPart"],$items_info[0]["itemPAD"],$items_info[0]["itemMAD"],$items_info[0]["itemSS"],$items_info[0]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))))), $count > 2 ? true : false);
								}else{
									$itemDetails = $this->showItemDetails(array($items_info[0]["amount"],$items_info[0]["enchant"],...$attributes,$items_info[0]["itemId"],$augmented.str_replace("{","{{_}",$items_info[0]["itemName"]),$items_info[0]["itemType"],$items_info[0]["itemTypeName"],$items_info[0]["itemWeight"],$items_info[0]["itemGrade"],$items_info[0]["itemBodyPart"],$items_info[0]["itemPAD"],$items_info[0]["itemMAD"],$items_info[0]["itemSS"],$items_info[0]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))))));
								}
								if($x == 0){
									$itemName = $augmented.str_replace("{","{{_}",$items_info[0]["itemName"]);
									$itemIcon = str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"]))))))));
									$itemCount = strtolower($items_info[0]["amount"]);
									$itemEnchant = strtolower($items_info[0]["enchant"]);
								}
								if(!empty($id)){
									array_push($result, array("itemAmount" => $items_info[0]["amount"], "itemEnchant" => $items_info[0]["enchant"], "itemName" => $augmented.str_replace("{","{{_}",$items_info[0]["itemName"]), "itemImg" => str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($items_info[0]["itemIcon"])))))))), "itemPrice" => $price, "itemInitialPrice" => $records[$y]["price"], "itemDetails" => $itemDetails, "itemId" => ltrim($records[$y]["id"], "0"), "itemCharName" => $records[$y]["char_name"], "itemType" => $records[$y]["type"], "itemAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$y]["date"]) + $timeAuction)), "itemAuctionPrice" => $records[$y]["price_auction"]));
								}
							}
						}
						if(empty($id)){
							array_push($result, array("itemAmount" => $itemCount, "itemEnchant" => $itemEnchant, "itemName" => $itemName, "itemImg" => $itemIcon, "itemId" => ltrim($records[$y]["id"], "0"), "itemCharName" => $records[$y]["char_name"], "itemType" => $records[$y]["type"], "itemAuctionTime" => date("Y-m-d H:i:s", (strtotime($records[$y]["date"]) + $timeAuction)), "itemPrice" => $price, "itemInitialPrice" => $records[$y]["price"], "itemDetails" => $itemDetails, "itemAuctionPrice" => $records[$y]["price_auction"]));
						}
					}
				}
			}
			return $result;
		}
		
		public function ownerAuction($auctionId,$login,$itemBroker=false){
			$table = !$itemBroker ? "icp_shop_items_auction" : "icp_shop_chars_auction";
			if($this->db_type){
				$records = $this->execute("SELECT account FROM ".$table." WHERE bidId = ? ORDER BY id DESC LIMIT 1",[$auctionId]);
			}else{
				$records = $this->execute("SELECT TOP 1 account FROM ".$table." WHERE bidId = ? ORDER BY id DESC",[$auctionId]);
			}
			if(count($records) == 1){
				if($records[0]["account"] == $login){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function itemBidHistory($id,$itemBroker=true){
			if($itemBroker && !$this->ALLOW_AUCTION_ITEM_BROKER || !$itemBroker && !$this->ALLOW_AUCTION_CHARACTER_BROKER){
				return $this->resposta("Auctions is disabled.","Oops...","error");
			}
			$result = array();
			$table = $itemBroker ? "icp_shop_items_auction" : "icp_shop_chars_auction";
			$records = $this->execute("SELECT * FROM ".$table." WHERE bidId = ? ORDER BY id DESC",[$id]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($result, array("bidDate" => $records[$x]["date"], "bidAccount" => $records[$x]["account"], "bidValue" => $records[$x]["value"]));
				}
			}
			return $result;
		}
		
		public function reward($login){
			if(!$this->ENABLE_REWARD_SYSTEM){
				return "0;0;0";
			}
			if($this->db_type){
				$reward = $this->execute("SELECT SUM(c.onlinetime) AS online_time, SUM(c.pvpkills) AS pvp, SUM(c.pkkills) AS pk, IF((SELECT COUNT(*) FROM icp_rewards WHERE account = c.account_name) > 0, (SELECT CONCAT(onlinetime, ';', pvpkills, ';', pkkills) FROM icp_rewards WHERE account = c.account_name), '0;0;0') AS reward_records FROM characters AS c WHERE c.account_name = ?",[$login]);
				if(count($reward) == 1){
					$reward_records = explode(";", $reward[0]["reward_records"]);
					return ($reward[0]["online_time"] - $reward_records[0] ?? 0).";".($reward[0]["pvp"] - $reward_records[1] ?? 0).";".($reward[0]["pk"] - $reward_records[2] ?? 0);
				}else{
					return "0;0;0";
				}
			}else{
				$reward = $this->execute("SELECT SUM(c.use_time) AS online_time, SUM(c.Duel) AS pvp, SUM(c.PK) AS pk FROM user_data AS c WHERE c.account_name = ?",[$login]);
				if(count($reward) == 1){
					$results = $this->execute("SELECT CONCAT(onlinetime, ';', pvpkills, ';', pkkills) AS reward_records FROM icp_rewards WHERE account = ?",[$login]);
					$reward_records = explode(";", count($results) > 0 ? $results[0]["reward_records"] : "0;0;0");
					return ($reward[0]["online_time"] - $reward_records[0] ?? 0).";".($reward[0]["pvp"] - $reward_records[1] ?? 0).";".($reward[0]["pk"] - $reward_records[2] ?? 0);
				}else{
					return "0;0;0";
				}
			}
		}
		
		public function getItemName($itemId){
			$item = $this->execute("SELECT itemName FROM ".$this->chronicleTables("icp_icons")." WHERE itemId = ?",[$itemId]);
			if(count($item) == 1){
				return $item[0]["itemName"];
			}else{
				return "No_name";
			}
		}
		
		public function showStaff($login=null){
			$result = array();
			if(!empty($login)){
				$records = $this->execute("SELECT * FROM icp_staff WHERE login = ? ORDER BY id ASC",[$login]);
			}else{
				$records = $this->execute("SELECT * FROM icp_staff ORDER BY id ASC");
			}
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($result, array("gmName" => $records[$x]["name"], "gmEmail" => $records[$x]["email"], "gmImg" => $records[$x]["img"]));
				}
			}
			return $result;
		}
		
		public function getAccessLevel($login){
			$access = $this->execute("SELECT accessLevel FROM icp_accounts WHERE login = ?",[$login],"login");
			if(count($access) == 1){
				 return $access[0]["accessLevel"];
			}else{
				return 0;
			}
		}
		
		public function getNumMessages($login=null){
			if($this->enable_messages){
				if(empty($login)){
					if($this->db_type){
						$msg = $this->execute("SELECT t.* FROM icp_tickets AS t WHERE t.status = '1' AND (SELECT answered FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) = t.sender ORDER BY t.id DESC");
					}else{
						$msg = $this->execute("SELECT t.* FROM icp_tickets AS t WHERE t.status = '1' AND (SELECT TOP 1 answered FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) = t.sender ORDER BY t.id DESC");
					}
				}else{
					if($this->db_type){
						$msg = $this->execute("SELECT t.* FROM icp_tickets AS t WHERE t.status = '1' AND (SELECT answered FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) != t.sender AND t.sender = ? ORDER BY t.id DESC",[$login]);
					}else{
						$msg = $this->execute("SELECT t.* FROM icp_tickets AS t WHERE t.status = '1' AND (SELECT TOP 1 answered FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) != t.sender AND t.sender = ? ORDER BY t.id DESC",[$login]);
					}
				}
				return count($msg);
			}
		}
		
		public function showMsgs($limit=0,$login=null){
			$result = array();
			if($this->enable_messages){
				if(empty($login)){
					if(empty($limit)){
						if($this->db_type){
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) ORDER BY m.date DESC");
						}else{
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT TOP 1 id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) ORDER BY m.date DESC");
						}
					}else{
						if($this->db_type){
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) ORDER BY m.date DESC LIMIT ".$limit);
						}else{
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT TOP 1 id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) ORDER BY m.date DESC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
						}
					}
				}else{
					if(empty($limit)){
						if($this->db_type){
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) AND t.sender = ? ORDER BY m.date DESC",[$login]);
						}else{
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT TOP 1 id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) AND t.sender = ? ORDER BY m.date DESC",[$login]);
						}
					}else{
						if($this->db_type){
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC LIMIT 1) AND t.sender = ? ORDER BY m.date DESC LIMIT ".$limit,[$login]);
						}else{
							$msg = $this->execute("SELECT t.title, t.sender, t.id, t.status, m.answered, m.date, (SELECT COUNT(*) FROM icp_tickets_msgs WHERE status = '1' AND msg_id = t.id) AS repliesCount, (m.id) AS replyId FROM icp_tickets AS t, icp_tickets_msgs AS m WHERE t.status < '2' AND m.id = (SELECT TOP 1 id FROM icp_tickets_msgs WHERE msg_id = t.id AND status = '1' ORDER BY id DESC) AND t.sender = ? ORDER BY m.date DESC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY",[$login]);
						}
					}
				}
				if(count($msg) > 0){
					for($x=0;$x<count($msg);$x++){
						array_push($result, array("msgTitle" => $msg[$x]["title"], "msgAuthor" => $msg[$x]["sender"], "msgAnswered" => $msg[$x]["answered"], "msgDate" => $msg[$x]["date"], "msgId" => $msg[$x]["id"], "repliesCount" => ($msg[$x]["repliesCount"]-1), "replyId" => $msg[$x]["replyId"], "msgStatus" => $msg[$x]["status"]));
					}
				}
			}
			return $result;
		}
		
		public function showMsg($id=0,$limit=null,$login=null){
			$result = array();
			if($this->enable_messages){
				if(empty($login)){
					$msg = $this->execute("SELECT * FROM icp_tickets WHERE status < '2' AND id = ?",[$id]);
				}else{
					$msg = $this->execute("SELECT * FROM icp_tickets WHERE status < '2' AND id = ? AND sender = ?",[$id,$login]);
				}
				if(count($msg) == 1){
					if(empty($limit)){
						$msgs = $this->execute("SELECT * FROM icp_tickets_msgs WHERE msg_id = ? AND status = '1' ORDER BY id ASC",[$msg[0]["id"]]);
					}else{
						if($this->db_type){
							$msgs = $this->execute("SELECT * FROM icp_tickets_msgs WHERE msg_id = ? AND status = '1' ORDER BY id ASC LIMIT ".$limit,[$msg[0]["id"]]);
						}else{
							$msgs = $this->execute("SELECT * FROM icp_tickets_msgs WHERE msg_id = ? AND status = '1' ORDER BY id ASC OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY",[$msg[0]["id"]]);
						}
					}
					if(count($msgs) > 0){
						for($x=0;$x<count($msgs);$x++){
							array_push($result, array("msgTitle" => $msg[0]["title"], "msgText" => $msgs[$x]["message"], "msgAuthor" => $msg[0]["sender"], "msgAnswered" => $msgs[$x]["answered"], "msgDate" => $msgs[$x]["date"], "replyId" => $msgs[$x]["id"], "msgId" => $msg[0]["id"], "msgStatus" => $msg[0]["status"], "msgAttachment" => $msgs[$x]["attach"]));
						}
					}
				}
			}
			return $result;
		}
		
		public function deleteReplyMsg($id=0,$reply=0,$senderPrivId){
			if($this->enable_messages){
				if($senderPrivId > 5){
					$msg = $this->execute("UPDATE icp_tickets_msgs SET status = '2' WHERE msg_id = ? AND id = ?",[$id,$reply]);
					if($msg){
						return $this->resposta("Message successfully deleted.","Success!","success","?icp=panel&show=adm-messages&id=".$id);
					}else{
						return $this->resposta("An error occurred for trying to delete the message.","Oops...","error","?icp=panel&show=adm-messages&id=".$id);
					}
				}else{
					return $this->resposta("You are not allowed to do this.","Oops...","error","?icp=panel&show=adm-messages");
				}
			}
		}
		
		public function deleteMsg($id=0,$senderPrivId){
			if($this->enable_messages){
				if($senderPrivId > 5){
					$msg = $this->execute("UPDATE icp_tickets SET status = '2' WHERE id = ?",[$id]);
					if($msg){
						return $this->resposta("Message successfully deleted.","Success!","success","?icp=panel&show=adm-messages");
					}else{
						return $this->resposta("An error occurred for trying to delete the message.","Oops...","error","?icp=panel&show=adm-messages&id=".$id);
					}
				}else{
					return $this->resposta("You are not allowed to do this.","Oops...","error","?icp=panel&show=adm-messages");
				}
			}
		}
		
		public function lockMsg($id=0,$senderPrivId){
			if($this->enable_messages){
				if($senderPrivId > 5){
					$msg = $this->execute("UPDATE icp_tickets SET status =  CASE WHEN status = '1' THEN '0' ELSE '1' END WHERE status = '0' OR status = '1' AND id = ?",[$id]);
					if($msg){
						return $this->resposta("Message successfully locked/unlocked.","Success!","success","?icp=panel&show=adm-messages&id=".$id);
					}else{
						return $this->resposta("An error occurred for trying to lock/unlock the message.","Oops...","error","?icp=panel&show=adm-messages&id=".$id);
					}
				}else{
					return $this->resposta("You are not allowed to do this.","Oops...","error","?icp=panel&show=adm-messages");
				}
			}
		}
		
		public function informer($name,$type=null){
			$result = array();
			if($type == "NPC" || empty($type)){
				if(!empty($name)){
					if(is_numeric($name)){
						$records = $this->execute("SELECT i.name, i.id, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_droplist")." WHERE mobId=i.id) > '0' THEN 'true' ELSE 'false' END AS droplist, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_spawnlist")." WHERE npc_id=i.id) > '0' THEN 'true' ELSE 'false' END AS spawn FROM ".$this->chronicleTables("icp_npc")." AS i WHERE (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_droplist")." WHERE mobId=i.id AND itemId = ? AND itemId != '57') > '0' ORDER BY i.name ASC",[$name]);
					}else{
						$records = $this->execute("SELECT i.name, i.id, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_droplist")." WHERE mobId=i.id) > '0' THEN 'true' ELSE 'false' END AS droplist, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_spawnlist")." WHERE npc_id=i.id) > '0' THEN 'true' ELSE 'false' END AS spawn FROM ".$this->chronicleTables("icp_npc")." AS i WHERE i.name LIKE CONCAT('%',?,'%') ORDER BY i.name ASC",[$name]);
					}
					if(count($records) > 0){
						for($x=0;$x<count($records);$x++){
							if($records[$x]["droplist"] == 'true' || $records[$x]["spawn"] == 'true'){
								array_push($result, array("npcName" => $records[$x]["name"], "npcId" => $records[$x]["id"], "npcDroplist" => $records[$x]["droplist"], "npcSpawn" => $records[$x]["spawn"], "type" => "NPC"));
							}
						}
					}
				}
			}
			if($type == "Item" || empty($type)){
				if(!empty($name)){
					$records = $this->execute("SELECT i.*, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_droplist")." WHERE itemId=i.itemId) > 0 THEN 'true' ELSE 'false' END AS droplist FROM ".$this->chronicleTables("icp_icons")." AS i WHERE i.itemName LIKE CONCAT('%',?,'%') ORDER BY i.itemName ASC",[$name]);
					if(count($records) > 0){
						for($x=0;$x<count($records);$x++){
							if($records[$x]["droplist"] == 'true'){
								$item = array(1,0,0,0,0,0,0,0,$records[$x]["itemId"],str_replace("{","{{_}",$records[$x]["itemName"]),$records[$x]["itemType"],$records[$x]["itemTypeName"],$records[$x]["itemWeight"],$records[$x]["itemGrade"],$records[$x]["itemBodyPart"],$records[$x]["itemPAD"],$records[$x]["itemMAD"],$records[$x]["itemSS"],$records[$x]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))));
								array_push($result, array("itemImg" => file_exists("images/icons/".str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))).".png") ? str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))) : 404, "itemName" => str_replace("{","{{_}",$records[$x]["itemName"]), "itemDetails" => $this->showItemDetails($item), "itemDroplist" => strtolower($name) == "adena" ? "false" : $records[$x]["droplist"], "type" => "Item", "itemId" => $records[$x]["itemId"]));
							}
						}
					}
				}
			}
			return $result;
		}
		
		public function informerNpcDetails($npc_id){
			$result = array();
			$records = $this->execute("SELECT n.name, n.id, CASE WHEN (SELECT COUNT(*) FROM ".$this->chronicleTables("icp_spawnlist")." WHERE npc_id=n.id) > 0 THEN 'true' END AS spawn FROM ".$this->chronicleTables("icp_npc")." AS n WHERE n.id = ?",[$npc_id]);
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($result, array("npcName" => $records[$x]["name"], "npcSpawn" => $records[$x]["spawn"], "npcId" => $records[$x]["id"]));
				}
			}
			return $result;
		}
		
		public function informerDroplist($npc_id){
			$result = array();
			if(!empty($npc_id)){
				$records = $this->execute("SELECT * FROM ".$this->chronicleTables("icp_droplist")." AS d, ".$this->chronicleTables("icp_icons")." AS i WHERE d.itemId = i.itemId AND mobId = ? ORDER BY d.sweep ASC, d.chance DESC",[$npc_id]);
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						$item = array(1,0,0,0,0,0,0,0,$records[$x]["itemId"],str_replace("{","{{_}",$records[$x]["itemName"]),$records[$x]["itemType"],$records[$x]["itemTypeName"],$records[$x]["itemWeight"],$records[$x]["itemGrade"],$records[$x]["itemBodyPart"],$records[$x]["itemPAD"],$records[$x]["itemMAD"],$records[$x]["itemSS"],$records[$x]["itemBSS"],str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))));
						$dropType = empty($records[$x]["sweep"]) ? "Drop" : "Spoil";
						array_push($result, array("itemImg" => file_exists("images/icons/".str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))).".png") ? str_replace("branchicon.","",str_replace("branchsys.","",str_replace("branchsys2.","",str_replace("branchsys3.","",str_replace("br_cashtex.","",str_replace("icon.","",str_replace("icon..","",strtolower($records[$x]["itemIcon"])))))))) : 404, "itemName" => str_replace("{","{{_}",$records[$x]["itemName"]), "itemDetails" => $this->showItemDetails($item), "itemCount" => $records[$x]["min"] == $records[$x]["max"] ? $records[$x]["max"] : $this->kkk($records[$x]["min"])." / ".$this->kkk($records[$x]["max"]), "itemChance" => @number_format($records[$x]["chance"] > 100 ? (($records[$x]["chance"]/1000000)*100)."%" : $records[$x]["chance"],4,".",",")."%", "itemType" => $dropType, "itemId" => $records[$x]["itemId"], "itemDroplist" => strtolower($records[$x]["itemName"]) == "adena" ? "false" : "true"));
					}
				}
			}
			return $result;
		}
		
		public function informerSpawn($npc_id){
			$result = array();
			if(!empty($npc_id)){
				if($this->db_type){
					$records = $this->execute("SELECT i.name, i.level, (SELECT GROUP_CONCAT(x,';',y) FROM ".$this->chronicleTables("icp_spawnlist")." WHERE npc_id = i.id) AS loc FROM ".$this->chronicleTables("icp_npc")." AS i WHERE i.id = ?",[$npc_id]);
				}else{
					$records = $this->execute("SELECT i.name, i.level, STUFF((SELECT ',' + CONVERT(VARCHAR, x) + ';' + CONVERT(VARCHAR, y) FROM ".$this->chronicleTables("icp_spawnlist")." WHERE npc_id = i.id FOR XML PATH('')),1,1,'') AS loc FROM ".$this->chronicleTables("icp_npc")." AS i WHERE i.id = ?",[$npc_id]);
				}
				if(count($records) > 0){
					$xy = explode(",",$records[0]["loc"]);
					$name = $records[0]["name"];
					$level = $records[0]["level"];
					for($z=0;$z<count($xy);$z++){
						$xyz = explode(";",$xy[$z]);
						$x = (116  + ($xyz[0] + 107823) / 200);
						$y = (2580 + ($xyz[1] - 255420) / 200);
						array_push($result, array("npcName" => $records[0]["name"], "npcLevel" => $records[0]["level"], "npcLocX" => $x, "npcLocY" => $y));
					}
				}
			}
			return $result;
		}
		
	}
	
}