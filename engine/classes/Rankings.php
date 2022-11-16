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
	
	class Rankings extends Suport {
		
		public function PvP_Pk($type,$limit,$classId=null){
			$rank = array();
			if(!empty($limit)){
				if($this->enable_top_pvp && $type == "pvpkills" && empty($classId) || $this->enable_top_pk && $type == "pkkills" && empty($classId) || $this->enable_top_class_pvp && $type == "pvpkills" && !empty($classId) || $this->enable_top_class_pk && $type == "pkkills" && !empty($classId)){
					if(empty($classId)){
						if($type == "pvpkills"){
							$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_TOP_PVP_1));
						}else{
							$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_TOP_PK_1));
						}
					}else{
						if($type == "pvpkills"){
							$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_TOP_PVP_2),[$classId]);
						}else{
							$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_TOP_PK_2),[$classId]);
						}
					}
					$count_pvp = count($records);
					if($count_pvp > 0){
						for($x=0;$x<$count_pvp;$x++){
							$this->showCrest($records[$x]["clanid"]);
							array_push($rank, array("playerPosition" => $x+1, "playerName" => $records[$x]["char_name"], "playerCrestClan" => file_exists("images/crests/".$records[$x]["clanid"].".png") ? "<img src=\"images/crests/".$records[$x]["clanid"].".png\" /> " : null, "playerClan" => $records[$x]["clan"], "playerAlly" => $records[$x]["ally"], "playerClass" => $this->classe_name($records[$x]["base_class"]), "playerCount" => number_format($records[$x]["{$type}"],0,".",".")));
						}
						if($count_pvp < $limit){
							for($y=1;$y<=($limit - $count_pvp);$y++){
								array_push($rank, array("playerPosition" => $count_pvp+$y, "playerName" => "-", "playerCrestClan" => "", "playerClan" => "-", "playerAlly" => "-", "playerClass" => "-", "playerCount" => "-"));
							}
						}
					}else{
						for($x=1;$x<=$limit;$x++){
							array_push($rank, array("playerPosition" => $x, "playerName" => "-", "playerCrestClan" => "", "playerClan" => "-", "playerAlly" => "-", "playerClass" => "-", "playerCount" => "-"));
						}
					}
				}else{
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("playerPosition" => $x, "playerName" => "-", "playerCrestClan" => "", "playerClan" => "-", "playerAlly" => "-", "playerClass" => "-", "playerCount" => "-"));
					}
				}
			}
			return $rank;
		}
		
		private function getCastleAttDef($type,$casteloID,$dono){
			$AttDef = null;
			$records = $this->execute($this->QUERY_RANKING_CASTLES_1,[$casteloID,$type]);
			for($x=0;$x<count($records);$x++){
				$this->showCrest($records[$x]["clanid"]);
				$AttDef .= (file_exists("images/crests/".$records[$x]["clanid"].".png") ? "<img src=\"images/crests/".$records[$x]["clanid"].".png\" /> " : null).$records[$x]["clan"];
				$AttDef .= $x == count($records) ? "." : ", ";
			}
			if($type == 1){
				if(empty($AttDef)){
					$retorno = "None.";
				}else{
					$retorno = $AttDef;
				}
			}else{
				if(empty($AttDef)){
					if($dono == "Unowned"){
						$retorno = "NPC's.";
					}else{
						$retorno = $dono.".";
					}
				}else{
					$retorno = $dono.", ".$AttDef;
				}
			}
			return empty($retorno) ? null : $retorno;
		}
		
		function Castles(){
			$rank = array();
			if($this->enable_castles){
				$records = $this->execute($this->QUERY_RANKING_CASTLES_2);
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						$siegeDate = $this->formatDate($records[$x]["siegeDate"]);
						$siegeDate = strtotime($siegeDate) > time() ? $siegeDate : "?";
						$dono = explode(";",$this->getCastleOwner($records[$x]["id"]));
						array_push($rank, array("castleName" => str_replace("_castle","",$records[$x]["name"]), "castleOwner" => $this->getCastleLeader($records[$x]["id"]), "castleClan" => $dono[0], "castleAlly" => $dono[1], "castleDate" => $siegeDate, "castleTax" => $records[$x]["taxPercent"], "castleDefenders" => $this->getCastleAttDef(2,$records[$x]["id"],$dono[0]), "castleAttackers" => $this->getCastleAttDef(1,$records[$x]["id"],$dono[0])));
					}
				}
			}
			return $rank;
		}
		
		private function getCastleOwner($casteloID){
			$ClanDono = null;
			$AllyDono = null;
			$records = $this->execute($this->QUERY_RANKING_CASTLES_3,[$casteloID]);
			if(count($records) == 1){
				$this->showCrest($records[0]["clanid"]);
				$ClanDono = (file_exists("images/crests/".$records[0]["clanid"].".png") ? "<img src=\"images/crests/".$records[0]["clanid"].".png\" /> " : null).$records[0]["clan"];
				$AllyDono = $records[0]["ally"];
			}
			$ClanDono = empty($ClanDono) ? "Unowned" : $ClanDono;
			$AllyDono = empty($AllyDono) ? "n/a" : $AllyDono;
			return $ClanDono.";".$AllyDono;
		}
		
		private function getCastleLeader($castleID){
			$char = $this->execute($this->QUERY_RANKING_CASTLES_4,[$castleID]);
			if(count($char) == 1){
				return $char[0]["leader"];
			}else{
				return "Unknown";
			}
		}
		
		public function Bosses($type = true){
			$rank = array();
			if($this->enable_bosses){
				if($type){
					$Bosses = $this->execute($this->QUERY_RANKING_GRANDBOSSES_1);
				}else{
					$Bosses = $this->execute($this->QUERY_RANKING_RAIDBOSSES_1);
				}
				for($x=0;$x<count($Bosses);$x++){
					$morto = [];
					$Bosses[$x]["boss_id"] = ltrim($Bosses[$x]["boss_id"], "0");
					if($type){
						$Bosses2 = $this->execute($this->QUERY_RANKING_GRANDBOSSES_2,[$Bosses[$x]["boss_id"]]);
					}else{
						$Bosses2 = $this->execute($this->QUERY_RANKING_RAIDBOSSES_2,[$Bosses[$x]["boss_id"]]);
					}
					if(count($Bosses2) == 1){
						$respawn_time = $this->formatDate($Bosses2[0]["respawn_time"]);
						if(($Bosses[$x]["boss_id"] == 29019 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 29045 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 29180 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 29136 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 29157 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25787 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25333 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 18710 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25690 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25651 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25659 && strtotime($respawn_time) < time()) || ($Bosses[$x]["boss_id"] == 25609 && strtotime($respawn_time) < time())){
							$bossId = array(29019 => array(29066,29067,29068), 29045 => array(29046,29047), 29180 => array(29177,29178,29179), 29136 => array(29137,29138,29139), 25787 => array(25788,25789,25790,25791,25792), 29157 => array(29158,29159,29160,29161,29162,29163,29164,29165,29166,29167,29168), 25333 => array(25334,25335,25336,25337,25338), 18710 => array(18711,18712,18713,18714,18715,18716,18717,18718), 25690 => array(25691,25692,25693,25694,25695), 25651 => array(25652), 25659 => array(25660,25661), 25609 => array(25610,25611,25612));
							for($y=0;$y<count($bossId[$Bosses[$x]["boss_id"]]);$y++){
								if($type){
									$Bosses3 = $this->execute($this->QUERY_RANKING_GRANDBOSSES_2,[$bossId[$Bosses[$x]["boss_id"]][$y]]);
								}else{
									$Bosses3 = $this->execute($this->QUERY_RANKING_RAIDBOSSES_2,[$bossId[$Bosses[$x]["boss_id"]][$y]]);
								}
								if(count($Bosses3) == 1){
									if(!empty($Bosses3[0]["respawn_time"]) && strtotime($this->formatDate($Bosses3[0]["respawn_time"])) > time()){
										array_push($morto, $Bosses3[0]["respawn_time"]);
									}
								}
							}
						}
						$respawn_time = count($morto) > 0 ? $morto[0] : $respawn_time;
						if(strtotime($respawn_time) > time()){
							$status = "<span style='color:#F00;font-weight:bold;'>Dead</span>";
							$respawn = "<span style='font-size:11px;'>".$respawn_time."</span>";
						}else{
							$respawn = "Available";
							$status = "<span style='color:#3CB371;font-weight:bold;'>Alive</span>";
						}
					}else{
						$respawn = "Available";
						$status = "<span style='color:#3CB371;font-weight:bold;'>Alive</span>";
					}
					array_push($rank, array("bossName" => ucwords(str_replace("_"," ",$Bosses[$x]["name"])), "bossLevel" => ltrim($Bosses[$x]["level"], "0"), "bossStatus" => $status, "bossRespawn" => $respawn));
				}
			}
			return $rank;
		}
		
		function rankRaidPoints($limit){
			$rank = array();
			if($this->enable_top_raid && isset($this->QUERY_RANKING_RAIDPOINTS)){
				if($limit > 0){
					$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_RAIDPOINTS));
					if(count($records) > 0){
						for($x=0;$x<count($records);$x++){
							$this->showCrest($records[$x]["clanid"]);
							array_push($rank, array("RPointsPosition" => $x+1, "RPointsName" => $records[$x]["char_name"], "RPointsClass" => $this->classe_name($records[$x]["base_class"]), "RPointsCrestClan" => file_exists("images/crests/".$records[$x]["clanid"].".png") ? "<img src=\"images/crests/".$records[$x]["clanid"].".png\" /> " : null, "RPointsClan" => $records[$x]["clan"], "RPointsCount" => number_format($records[$x]["raid_points"],0,".",".")));
						}
					}
					if($limit > 0){
						if(count($records) < $limit){
							for($y=1;$y<=($limit - count($records));$y++){
								array_push($rank, array("RPointsPosition" => count($records)+$y, "RPointsName" => "-", "RPointsClass" => "-", "RPointsCrestClan" => "", "RPointsClan" => "-", "RPointsCount" => "-"));
							}
						}
					}
				}else{
					if($limit > 0){
						for($x=1;$x<=$limit;$x++){
							array_push($rank, array("RPointsPosition" => $x, "RPointsName" => "-", "RPointsClass" => "-", "RPointsCrestClan" => "", "RPointsClan" => "-", "RPointsCount" => "-"));
						}
					}
				}
			}else{
				if($limit > 0){
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("RPointsPosition" => $x, "RPointsName" => "-", "RPointsClass" => "-", "RPointsCrestClan" => "", "RPointsClan" => "-", "RPointsCount" => "-"));
					}
				}
			}
			return $rank;
		}
		
		public function topOly(){
			$rank = array();
			if($this->enable_top_oly){
				$records = $this->execute($this->QUERY_RANKING_OLYMPIADS);
				if(count($records) > 0){
					$y = 1;
					$classId = 1;
					for($x=0;$x<count($records);$x++){
						$this->showCrest($records[$x]["clanid"]);
						$y = $classId == $records[$x]["class_id"] ? $y : 1;
						array_push($rank, array("olyCssPosition" => $x+1, "olyPosition" => $y, "olyName" => $records[$x]["char_name"], "olyCrestClan" => file_exists("images/crests/".$records[$x]["clanid"].".png") ? "<img src=\"images/crests/".$records[$x]["clanid"].".png\" /> " : null, "olyClan" => $records[$x]["clan"], "olyPoints" => number_format($records[$x]["olympiad_points"],0,".","."), "olyFights" => number_format($records[$x]["competitions_done"],0,".","."), "olyClass" => $this->classe_name($records[$x]["class_id"])));
						$classId = $records[$x]["class_id"];
						$y++;
					}
				}
			}
			return $rank;
		}
		
		public function topHero($limit){
			$rank = array();
			if($this->enable_top_hero){
				if($limit > 0){
					$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_HEROES_1));
				}else{
					$records = $this->execute($this->QUERY_RANKING_HEROES_2);
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						$this->showCrest($records[$y]["clanid"]);
						array_push($rank, array("heroPosition" => $y+1, "heroName" => $records[$y]["char_name"], "heroCrestClan" => file_exists("images/crests/".$records[$y]["clanid"].".png") ? "<img src=\"images/crests/".$records[$y]["clanid"].".png\" /> " : null, "heroClan" => $records[$y]["clan"], "heroAlly" => $records[$y]["ally"], "heroClass" => $this->classe_name($records[$y]["base"]), "heroCount" => number_format($records[$y]["count"],0,".",".")));
					}
					if($limit > 0){
						if(count($records) < $limit){
							for($x=1;$x<=($limit - count($records));$x++){
								array_push($rank, array("heroPosition" => count($records)+$x, "heroName" => "-", "heroCrestClan" => "", "heroClan" => "-", "heroAlly" => "-", "heroClass" => "-", "heroCount" => "-"));
							}
						}
					}
				}else{
					if($limit > 0){
						for($x=1;$x<=$limit;$x++){
							array_push($rank, array("heroPosition" => $x, "heroName" => "-", "heroCrestClan" => "", "heroClan" => "-", "heroAlly" => "-", "heroClass" => "-", "heroCount" => "-"));
						}
					}
				}
			}else{
				if($limit > 0){
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("heroPosition" => $x, "heroName" => "-", "heroCrestClan" => "", "heroClan" => "-", "heroAlly" => "-", "heroClass" => "-", "heroCount" => "-"));
					}
				}
			}
			return $rank;
		}
		
		public function topClan($limit,$topPvp=false){
			$rank = array();
			if($limit > 0){
				if($this->enable_top_clan){
					if($this->db_type){
						$order = $topPvp ? "toppvp DESC, c.clan_level DESC, clan_name ASC" : "c.clan_level DESC, c.reputation_score DESC, clan_name ASC";
					}else{
						$order = $topPvp ? "toppvp DESC, c.skill_level DESC, c.name ASC" : "c.skill_level DESC, reputation_score DESC, c.name ASC";
					}
					$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_CLANS),[$order]);
					if(count($records) > 0){
						for($y=0;$y<count($records);$y++){
							$this->showCrest($records[$y]["clanid"]);
							array_push($rank, array("clanPosition" => $y+1, "clanCrest" => file_exists("images/crests/".$records[$y]["clanid"].".png") ? "<img src=\"images/crests/".$records[$y]["clanid"].".png\" /> " : null, "clanName" => $records[$y]["clan_name"], "clanLevel" => $records[$y]["clan_level"], "clanReputation" => number_format($records[$y]["reputation_score"],0,'.','.'), "clanAllyance" => $records[$y]["ally_name"], "clanLeader" => $records[$y]["leader"], "clanPvps" => $records[$y]["toppvp"]));
						}
						if(count($records) < $limit){
							for($x=1;$x<=($limit - count($records));$x++){
								array_push($rank, array("clanPosition" => count($records)+$x, "clanCrest" => "", "clanName" => "-", "clanLevel" => "-", "clanReputation" => "-", "clanAllyance" => "-", "clanLeader" => "-", "clanPvps" => "-"));
							}
						}
					}else{
						for($x=1;$x<=$limit;$x++){
							array_push($rank, array("clanPosition" => $x, "clanCrest" => "", "clanName" => "-", "clanLevel" => "-", "clanReputation" => "-", "clanAllyance" => "-", "clanLeader" => "-", "clanPvps" => "-"));
						}
					}
				}else{
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("clanPosition" => $x, "clanCrest" => "", "clanName" => "-", "clanLevel" => "-", "clanReputation" => "-", "clanAllyance" => "-", "clanLeader" => "-", "clanPvps" => "-"));
					}
				}
			}
			return $rank;
		}
		
		public function topOnline($limit){
			$rank = array();
			if($limit > 0){
				if($this->enable_top_online){
					$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_ONLINE));
					if(count($records) > 0){
						for($y=0;$y<count($records);$y++){
							$this->showCrest($records[$y]["clanid"]);
							array_push($rank, array("onlinePosition" => $y+1, "onlineName" => $records[$y]["char_name"], "onlineCrestClan" => file_exists("images/crests/".$records[$y]["clanid"].".png") ? "<img src=\"images/crests/".$records[$y]["clanid"].".png\" /> " : null, "onlineClan" => $records[$y]["clan"], "onlineTime" => $this->remainingTime($records[$y]["onlinetime"],true)));
						}
						if(count($records) < $limit){
							for($x=1;$x<=($limit - count($records));$x++){
								array_push($rank, array("onlinePosition" => count($records)+$x, "onlineName" => "-", "onlineCrestClan" => "", "onlineClan" => "-", "onlineTime" => "-"));
							}
						}
					}else{
						for($x=1;$x<=$limit;$x++){
							array_push($rank, array("onlinePosition" => $x, "onlineName" => "-", "onlineCrestClan" => "", "onlineClan" => "-", "onlineTime" => "-"));
						}
					}
				}else{
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("onlinePosition" => $x, "onlineName" => "-", "onlineCrestClan" => "", "onlineClan" => "-", "onlineTime" => "-"));
					}
				}
			}
			return $rank;
		}
		
		public function topAdena($limit){
			$rank = array();
			if($limit > 0){
				if($this->enable_top_adena){
					ini_set('max_execution_time', 0);
					$topAdena = array();
					$records = $this->execute(str_replace("{MAX_LIMIT}",$limit,$this->QUERY_RANKING_ADENA));
					if(count($records) > 0){
						for($z=0;$z<count($records);$z++){
							$adenas = $records[$z]["adena_inv"] + $records[$z]["adena_war"];
							$adenaTotal = ($records[$z]["gold_bar"] * $this->GOLDBAR_VALUE) + $adenas;
							array_push($topAdena, array('name' => $records[$z]["char_name"], 'adena' => $adenas, 'gb' => $records[$z]["gold_bar"], 'totaladena' => $adenaTotal));
						}
					}
					foreach($topAdena as $key => $rows){
						$name[$key]  = $rows['name'];
						$adena[$key] = $rows['adena'];
						$gb[$key] = $rows['gb'];
						$totaladena[$key] = $rows['totaladena'];
					}
					@array_multisort($totaladena, SORT_DESC, $name, SORT_ASC, $topAdena);
					for($y=0;$y<count($topAdena);$y++){
						array_push($rank, array("adenaPosition" => $y+1, "adenaName" => $topAdena[$y]["name"], "adenaCount" => number_format($topAdena[$y]["adena"],0,".","."), "adenaGoldbar" => number_format($topAdena[$y]["gb"],0,".","."), "adenaTotal" => number_format($topAdena[$y]["totaladena"],0,".",".")));
					}
					if(count($records) < $limit){
						for($x=1;$x<=($limit - count($records));$x++){
							array_push($rank, array("adenaPosition" => count($records)+$x, "adenaName" => "-", "adenaCount" => "-", "adenaGoldbar" => "-", "adenaTotal" => "-"));
						}
					}
				}else{
					for($x=1;$x<=$limit;$x++){
						array_push($rank, array("adenaPosition" => $x, "adenaName" => "-", "adenaCount" => "-", "adenaGoldbar" => "-", "adenaTotal" => "-"));
					}
				}
			}
			return $rank;
		}
		
		function rankClanHall(){
			$rank = array();
			if($this->enable_clan_halls){
				for($x=0;$x<count($this->QUERY_RANKING_CLANHALL_IDS);$x++){
					$records = $this->execute($this->QUERY_RANKING_CLANHALL,[$this->QUERY_RANKING_CLANHALL_IDS[$x]]);
					if(count($records) == 1){
						$this->showCrest($records[0]["clanid"]);
						array_push($rank, array("clanHallName" => $this->clanHallName($this->QUERY_RANKING_CLANHALL_IDS[$x]), "clanHallLoc" => $this->clanHallLoc($this->QUERY_RANKING_CLANHALL_IDS[$x]), "clanHallOwnerCrestClan" => file_exists("images/crests/".$records[0]["clanid"].".png") ? "<img src=\"images/crests/".$records[0]["clanid"].".png\" /> " : null, "clanHallOwnerClan" => $records[0]["clan_name"], "clanHallOwnerAlly" => $records[0]["ally_name"]));
					}else{
						array_push($rank, array("clanHallName" => $this->clanHallName($this->QUERY_RANKING_CLANHALL_IDS[$x]), "clanHallLoc" => $this->clanHallLoc($this->QUERY_RANKING_CLANHALL_IDS[$x]), "clanHallOwnerCrestClan" => "", "clanHallOwnerClan" => "-", "clanHallOwnerAlly" => "-"));
					}
				}
			}
			return $rank;
		}
		
		public function miniRank(){
			$rank = array();
			$topPvp = $this->PvP_Pk("pvpkills",1);
			$topPk = $this->PvP_Pk("pkkills",1);
			$topOnline = $this->topOnline(1);
			$topClan = $this->topClan(1);
			array_push($rank, array("miniTopPvp" => $topPvp[0]["playerName"], "miniTopPk" => $topPk[0]["playerName"], "miniTopOnline" => $topOnline[0]["onlineName"], "miniTopCrestClan" => $topClan[0]["clanCrest"], "miniTopClan" => $topClan[0]["clanName"]));
			return $rank;
		}
		
		public function miniBossStatus(){
			$rank = array();
			$bigBosses = $this->Bosses(true);
			if(count($bigBosses) > 0){
				$x = rand(0,(count($bigBosses)-1));
				array_push($rank, array("miniBossName" => $bigBosses[$x]["bossName"], "miniBossLevel" => $bigBosses[$x]["bossLevel"], "miniBossStatus" => $bigBosses[$x]["bossStatus"], "miniBossRespawn" => $bigBosses[$x]["bossRespawn"]));
			}
			return count($rank) == 0 ? array(array("miniBossName" => "-", "miniBossLevel" => "-", "miniBossStatus" => "-", "miniBossRespawn" => "-")) : $rank;
		}
		
		public function miniCastle(){
			$rank = array();
			$castles = $this->Castles();
			if(count($castles) > 0){
				$x = rand(0,(count($castles)-1));
				array_push($rank, array("miniCastleName" => $castles[$x]["castleName"], "miniCastleOwner" => $castles[$x]["castleOwner"], "miniCastleClan" => $castles[$x]["castleClan"], "miniCastleAlly" => $castles[$x]["castleAlly"], "miniCastleDate" => $castles[$x]["castleDate"], "miniCastleTax" => $castles[$x]["castleTax"], "miniCastleDefenders" => $castles[$x]["castleDefenders"], "miniCastleAttackers" => $castles[$x]["castleAttackers"]));
			}
			return count($rank) == 0 ? array(array("miniCastleName" => "-", "miniCastleOwner" => "-", "miniCastleClan" => "-", "miniCastleAlly" => "-", "miniCastleDate" => "-", "miniCastleTax" => "-", "miniCastleDefenders" => "-", "miniCastleAttackers" => "-")) : $rank;
		}
		
		public function olyPeriod($period){
			if($period == 30){
				$olyDay = date("t") - date("d");
				$olyPeriod = $olyDay == 0 ? 100 : intval(((date("t") - $olyDay) * 100) / date("t"));
			}elseif($period == 15){
				$olyDay = date("d") < 16 ? 15 - date("d") : 15 - (date("t") - date("d"));
				$olyPeriod = $olyDay == 0 ? 100 : intval(((15 - $olyDay) * 100) / 15);
			}elseif($period == 7){
				if(date("d") < 8){
					$olyDay = 7 - date("d");
					$olyDays = 7 - $olyDay;
				}elseif(date("d") > 7 && date("d") < 16){
					$olyDay = 15 - date("d");
					$olyDays = 8 - $olyDay;
				}elseif(date("d") > 15 && date("d") < 23){
					$olyDay = 22 - date("d");
					$olyDays = 7 - $olyDay;
				}elseif(date("d") > 22 && date("d") < date("t")){
					$olyDay = date("t") - date("d");
					$olyDays = (date("t") - 22) - $olyDay;
				}
				$olyPeriod = $olyDays == 0 ? 100 : intval(($olyDays * 100) / 7);
			}else{
				$olyDay = 0;
				$olyPeriod = 0;
			}
			return array(array("olyPeriodPercent" => $olyPeriod, "olyPeriodDays" => $olyDay));
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
						if(is_numeric($limit)){
							$records = $this->execute("SELECT TOP ".$limit." * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort);
						}else{
							$records = $this->execute("SELECT * FROM icp_gallery_screenshots WHERE status = '".$status."' ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
						}
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
						if(is_numeric($limit)){
							$records = $this->execute("SELECT TOP ".$limit." * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort);
						}else{
							$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort." OFFSET ".str_replace(","," ROWS FETCH NEXT", $limit)." ROWS ONLY");
						}
					}else{
						$records = $this->execute("SELECT * FROM icp_gallery_videos WHERE status = '".$status."' ORDER BY ".$sort);
					}
				}
				if(count($records) > 0){
					for($y=0;$y<count($records);$y++){
						array_push($videos, array("videosId" => $records[$y]["id"], "videosAuthor" => $records[$y]["author"], "videosLegend" => $records[$y]["legend"], "videosDate" => $records[$y]["date"], "videosLink" => $records[$y]["link"], "videosImg" => $records[$y]["photo"], "videosNum" => $y+1, "videosUrl" => $records[$y]["url"]));
					}
					if(count($videos) < $limit && $index){
						for($x=count($videos);$x<$limit;$x++){
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
		
	}
	
}