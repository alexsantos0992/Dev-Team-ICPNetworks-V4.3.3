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
require_once("classes/Rankings.php");
$getRank = new ICPNetworks\Rankings($db_type, $loginServer, $gameServer, $config);
$allowedPvPPkRankings = array("BLOCK_INDEX_PVP","BLOCK_INDEX_PK","BLOCK_RANK_PVP","BLOCK_RANK_PK");
$allowedPvPPkByClassRankings = array("BLOCK_RANK_PVP_BY_CLASS","BLOCK_RANK_PK_BY_CLASS","BLOCK_MINI_RANK_PVP_BY_CLASS","BLOCK_MINI_RANK_PK_BY_CLASS");
$allowedScreenshots = array("BLOCK_INDEX_SCREENSHOTS","BLOCK_INDEX_SCREENSHOTS_6","BLOCK_GALLERY_SCREENSHOTS");
$allowedVideos = array("BLOCK_INDEX_VIDEOS","BLOCK_INDEX_VIDEOS_6","BLOCK_GALLERY_VIDEOS");
if(in_array($config["CHRONICLE_ID"],array(0,1,2,3,4,5,6))){
	$allowedClasses = array(88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118);
}elseif(in_array($config["CHRONICLE_ID"],array(7,8,9,10,11,12,13,14,15,16,17,18,19,20))){
	$allowedClasses = array(88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,123,124,125,126,127,128,129,130,131,132,133,134,135,136);
}elseif(in_array($config["CHRONICLE_ID"],array(21,22,23,24,25,26,27,28,29,30,31,32,33,34))){
	$allowedClasses = array(88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,123,124,125,126,127,128,129,130,131,132,133,134,135,136,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,186,187,188,189);
}elseif(in_array($config["CHRONICLE_ID"],array(35,36,37))){
	$allowedClasses = array(88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,123,124,125,126,127,128,129,130,131,132,133,134,135,136,194,195,198,199,202,203,206,207,210,211,219,220);
}else{
	$allowedClasses = array(88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118);
}
$model = $tpl->getBlocks();
if($tpl->exists("RANKING_BUTTONS")){
	$rankingBtn = null;
	if($config["enable_top_pvp"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-pvp\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-pvp\">Top PvP</a>";
	if($config["enable_top_class_pvp"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-class-pvp\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-class-pvp\">Top Class PvP</a>";
	if($config["enable_top_pk"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-pk\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-pk\">Top Pk</a>";
	if($config["enable_top_class_pk"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-class-pk\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-class-pk\">Top Class Pk</a>";
	if($config["enable_top_online"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-online\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-online\">Top Online</a>";
	if($config["enable_top_adena"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-adena\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-adena\">Top Adena</a>";
	if($config["enable_top_clan"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-clan\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-clan\">Top Clan</a>";
	if($config["enable_clan_halls"])
		$rankingBtn .= "<a href=\"?icp=panel&show=clan-halls\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-clan-halls\">Clan Halls</a>";
	if($config["enable_top_oly"])
		$rankingBtn .= "<a href=\"?icp=panel&show=olympiads\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-olympiads\">Olympiads</a>";
	if($config["enable_top_hero"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-heros\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-heros\">Top Heroes</a>";
	if($config["enable_top_raid"])
		$rankingBtn .= "<a href=\"?icp=panel&show=top-raid-points\" class=\"btn btn-sm btn-outline-secondary\" style=\"margin:5px;\" id=\"icp-panel-top-raid-points\">Top Raid Points</a>";
	$tpl->RANKING_BUTTONS = $rankingBtn;
}
for($x=0;$x<count($model);$x++){
	if(in_array($model[$x],$allowedPvPPkRankings)){
		if($config["enable_top_pvp"] && ($model[$x] == "BLOCK_INDEX_PVP" || $model[$x] == "BLOCK_RANK_PVP") || $config["enable_top_pk"] && ($model[$x] == "BLOCK_INDEX_PK" || $model[$x] == "BLOCK_RANK_PK")){
			foreach($getRank->PvP_Pk($model[$x] == "BLOCK_INDEX_PVP" || $model[$x] == "BLOCK_RANK_PVP" ? "pvpkills" : "pkkills",$model[$x] == "BLOCK_INDEX_PVP" || $model[$x] == "BLOCK_INDEX_PK" ? $config["MAX_INDEX_RANKINGS"] : $config["MAX_RANKINGS"]) as $rank){
				if($tpl->exists("RANK_PVPPK_POS"))
					$tpl->RANK_PVPPK_POS = $rank["playerPosition"] < 4 ? "<img src='images/miscs/".$rank["playerPosition"].".gif'>" : $rank["playerPosition"]."º";
				if($tpl->exists("RANK_PVPPK_NAME"))
					$tpl->RANK_PVPPK_NAME = $rank["playerName"];
				if($tpl->exists("RANK_PVPPK_CREST_CLAN"))
					$tpl->RANK_PVPPK_CREST_CLAN = $rank["playerCrestClan"];
				if($tpl->exists("RANK_PVPPK_CLAN"))
					$tpl->RANK_PVPPK_CLAN = $rank["playerClan"];
				if($tpl->exists("RANK_PVPPK_ALLY"))
					$tpl->RANK_PVPPK_ALLY = $rank["playerAlly"];
				if($tpl->exists("RANK_PVPPK_CLASS"))
					$tpl->RANK_PVPPK_CLASS = $rank["playerClass"];
				if($tpl->exists("RANK_PVPPK_COUNT"))
					$tpl->RANK_PVPPK_COUNT = $rank["playerCount"];
				if($tpl->exists("RANK_PVPPK_CSS_ADJUSTMENT"))
					$tpl->RANK_PVPPK_CSS_ADJUSTMENT = $rank["playerPosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if(in_array($model[$x],$allowedPvPPkByClassRankings)){
		if($config["enable_top_class_pvp"] && ($model[$x] == "BLOCK_RANK_PVP_BY_CLASS" || $model[$x] == "BLOCK_MINI_RANK_PVP_BY_CLASS") || $config["enable_top_class_pk"] && ($model[$x] == "BLOCK_RANK_PK_BY_CLASS" || $model[$x] == "BLOCK_MINI_RANK_PK_BY_CLASS")){
			for($y=0;$y<count($allowedClasses);$y++){
				foreach($getRank->PvP_Pk($model[$x] == "BLOCK_RANK_PVP_BY_CLASS" || $model[$x] == "BLOCK_MINI_RANK_PVP_BY_CLASS" ? "pvpkills" : "pkkills",$model[$x] == "BLOCK_RANK_PVP_BY_CLASS" || $model[$x] == "BLOCK_RANK_PK_BY_CLASS" ? $model[$x] == "BLOCK_RANK_PVP_BY_CLASS" ? $config["MAX_RANKING_PVP_BY_CLASSES"] : $config["MAX_RANKING_PK_BY_CLASSES"] : 1,$allowedClasses[$y]) as $rank){
					if($tpl->exists("RANK_PVPPK_POS"))
						$tpl->RANK_PVPPK_POS = $rank["playerPosition"] < 4 ? "<img src='images/miscs/".$rank["playerPosition"].".gif'>" : $rank["playerPosition"]."º";
					if($tpl->exists("RANK_PVPPK_NAME"))
						$tpl->RANK_PVPPK_NAME = $rank["playerName"];
					if($tpl->exists("RANK_PVPPK_CREST_CLAN"))
						$tpl->RANK_PVPPK_CREST_CLAN = $rank["playerCrestClan"];
					if($tpl->exists("RANK_PVPPK_CLAN"))
						$tpl->RANK_PVPPK_CLAN = $rank["playerClan"];
					if($tpl->exists("RANK_PVPPK_ALLY"))
						$tpl->RANK_PVPPK_ALLY = $rank["playerAlly"];
					if($tpl->exists("RANK_PVPPK_CLASS"))
						$tpl->RANK_PVPPK_CLASS = $getRank->classe_name($allowedClasses[$y]);
					if($tpl->exists("RANK_PVPPK_COUNT"))
						$tpl->RANK_PVPPK_COUNT = $rank["playerCount"];
					if($model[$x] == "BLOCK_RANK_PVP_BY_CLASS" || $model[$x] == "BLOCK_RANK_PK_BY_CLASS")
						if($tpl->exists("BLOCK_RANK_PVPPK_BY_CLASS", true))
							$tpl->block("BLOCK_RANK_PVPPK_BY_CLASS");
				}
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_CASTLES"){
		if($config["enable_castles"]){
			foreach($getRank->Castles() as $rank){
				if($tpl->exists("RANK_CASTLE_NAME"))
					$tpl->RANK_CASTLE_NAME = strtolower($rank["castleName"]);
				if($tpl->exists("RANK_CASTLE_OWNER"))
					$tpl->RANK_CASTLE_OWNER = $rank["castleOwner"];
				if($tpl->exists("RANK_CASTLE_CLAN"))
					$tpl->RANK_CASTLE_CLAN = $rank["castleClan"];
				if($tpl->exists("RANK_CASTLE_ALLY"))
					$tpl->RANK_CASTLE_ALLY = $rank["castleAlly"];
				if($tpl->exists("RANK_CASTLE_DATE"))
					$tpl->RANK_CASTLE_DATE = $rank["castleDate"];
				if($tpl->exists("RANK_CASTLE_TAX"))
					$tpl->RANK_CASTLE_TAX = $rank["castleTax"];
				if($tpl->exists("RANK_CASTLE_DEFENDERS"))
					$tpl->RANK_CASTLE_DEFENDERS = $rank["castleDefenders"];
				if($tpl->exists("RANK_CASTLE_ATTACKERS"))
					$tpl->RANK_CASTLE_ATTACKERS = $rank["castleAttackers"];
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_OLYMPIADS"){
		if($config["enable_top_oly"]){
			foreach($getRank->topOly() as $rank){
				if($tpl->exists("RANK_OLY_POS"))
					$tpl->RANK_OLY_POS = $rank["olyPosition"] < 4 ? "<img src='images/miscs/".$rank["olyPosition"].".gif'>" : $rank["olyPosition"]."º";
				if($tpl->exists("RANK_OLY_NAME"))
					$tpl->RANK_OLY_NAME = $rank["olyName"];
				if($tpl->exists("RANK_OLY_POINTS"))
					$tpl->RANK_OLY_POINTS = $rank["olyPoints"];
				if($tpl->exists("RANK_OLY_FIGHTS"))
					$tpl->RANK_OLY_FIGHTS = $rank["olyFights"];
				if($tpl->exists("RANK_OLY_CLASS"))
					$tpl->RANK_OLY_CLASS = $rank["olyClass"];
				if($tpl->exists("RANK_OLY_CREST_CLAN"))
					$tpl->RANK_OLY_CREST_CLAN = $rank["olyCrestClan"];
				if($tpl->exists("RANK_OLY_CLAN"))
					$tpl->RANK_OLY_CLAN = $rank["olyClan"];
				if($tpl->exists("RANK_OLY_CSS_ADJUSTMENT"))
					$tpl->RANK_OLY_CSS_ADJUSTMENT = $rank["olyCssPosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_OLYMPIAD_PERIOD"){
		foreach($getRank->olyPeriod($config["OLY_PERIOD_DAYS"]) as $rank){
			if($tpl->exists("OLY_PERIOD_CURRENT")){
				if($config["OLY_PERIOD_DAYS"] == 7)
					$olyPeriodType = ": 7 days (Weekly)";
				elseif($config["OLY_PERIOD_DAYS"] == 15)
					$olyPeriodType = ": 15 days (Biweekly)";
				elseif($config["OLY_PERIOD_DAYS"] == 30)
					$olyPeriodType = ": 30 days (Monthly)";
				else
					$olyPeriodType = null;
				if(!empty($olyPeriodType))
					$tpl->OLY_PERIOD_CURRENT = $olyPeriodType;
			}
			if($tpl->exists("OLY_PERIOD_PERCENT"))
				$tpl->OLY_PERIOD_PERCENT = $rank["olyPeriodPercent"];
			if($tpl->exists("OLY_PERIOD_DAYS"))
				$tpl->OLY_PERIOD_DAYS = $rank["olyPeriodDays"];
			if($tpl->exists($model[$x], true))
				$tpl->block($model[$x]);
		}
	}
	if($model[$x] == "BLOCK_RANK_TOP_HEROS" || $model[$x] == "BLOCK_RANK_HEROS"){
		if($config["enable_top_hero"]){
			foreach($getRank->topHero($model[$x] == "BLOCK_RANK_HEROS" ? 0 : $config["MAX_RANKINGS"]) as $rank){
				if($tpl->exists("RANK_HERO_POS"))
					$tpl->RANK_HERO_POS = $rank["heroPosition"] < 4 ? "<img src='images/miscs/".$rank["heroPosition"].".gif'>" : $rank["heroPosition"]."º";
				if($tpl->exists("RANK_HERO_NAME"))
					$tpl->RANK_HERO_NAME = $rank["heroName"];
				if($tpl->exists("RANK_HERO_CREST_CLAN"))
					$tpl->RANK_HERO_CREST_CLAN = $rank["heroCrestClan"];
				if($tpl->exists("RANK_HERO_CLAN"))
					$tpl->RANK_HERO_CLAN = $rank["heroClan"];
				if($tpl->exists("RANK_HERO_ALLYANCE"))
					$tpl->RANK_HERO_ALLYANCE = $rank["heroAlly"];
				if($tpl->exists("RANK_HERO_CLASS"))
					$tpl->RANK_HERO_CLASS = $rank["heroClass"];
				if($tpl->exists("RANK_HERO_COUNT"))
					$tpl->RANK_HERO_COUNT = $rank["heroCount"];
				if($tpl->exists("RANK_HERO_CSS_ADJUSTMENT"))
					$tpl->RANK_HERO_CSS_ADJUSTMENT = $rank["heroPosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_CLAN"){
		if($config["enable_top_clan"]){
			foreach($getRank->topClan($config["MAX_RANKINGS"],$config["TOP_CLAN_BY_PVP"]) as $rank){
				if($tpl->exists("RANK_CLAN_POS"))
					$tpl->RANK_CLAN_POS = $rank["clanPosition"] < 4 ? "<img src='images/miscs/".$rank["clanPosition"].".gif'>" : $rank["clanPosition"]."º";
				if($tpl->exists("RANK_CLAN_CREST"))
					$tpl->RANK_CLAN_CREST = $rank["clanCrest"];
				if($tpl->exists("RANK_CLAN_NAME"))
					$tpl->RANK_CLAN_NAME = $rank["clanName"];
				if($tpl->exists("RANK_CLAN_LEVEL"))
					$tpl->RANK_CLAN_LEVEL = $rank["clanLevel"];
				if($tpl->exists("RANK_CLAN_REPUTATION"))
					$tpl->RANK_CLAN_REPUTATION = $rank["clanReputation"];
				if($tpl->exists("RANK_CLAN_ALLYANCE"))
					$tpl->RANK_CLAN_ALLYANCE = $rank["clanAllyance"];
				if($tpl->exists("RANK_CLAN_LEADER"))
					$tpl->RANK_CLAN_LEADER = $rank["clanLeader"];
				if($tpl->exists("RANK_CLAN_PVPS"))
					$tpl->RANK_CLAN_PVPS = $rank["clanPvps"];
				if($tpl->exists("RANK_CLAN_CSS_ADJUSTMENT"))
					$tpl->RANK_CLAN_CSS_ADJUSTMENT = $rank["clanPosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_CLAN_HALL"){
		if($config["enable_clan_halls"]){
			foreach($getRank->rankClanHall() as $rank){
				if($tpl->exists("RANK_CLAN_HALL_NAME"))
					$tpl->RANK_CLAN_HALL_NAME = $rank["clanHallName"];
				if($tpl->exists("RANK_CLAN_HALL_LOC"))
					$tpl->RANK_CLAN_HALL_LOC = $rank["clanHallLoc"];
				if($tpl->exists("RANK_CLAN_HALL_OWNER_CREST_CLAN"))
					$tpl->RANK_CLAN_HALL_OWNER_CREST_CLAN = $rank["clanHallOwnerCrestClan"];
				if($tpl->exists("RANK_CLAN_HALL_OWNER_CLAN"))
					$tpl->RANK_CLAN_HALL_OWNER_CLAN = $rank["clanHallOwnerClan"];
				if($tpl->exists("RANK_CLAN_HALL_OWNER_ALLYANCE"))
					$tpl->RANK_CLAN_HALL_OWNER_ALLYANCE = $rank["clanHallOwnerAlly"];
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_ADENA"){
		if($config["enable_top_adena"]){
			foreach($getRank->topAdena($config["MAX_RANKINGS"]) as $rank){
				if($tpl->exists("RANK_ADENA_POS"))
					$tpl->RANK_ADENA_POS = $rank["adenaPosition"] < 4 ? "<img src='images/miscs/".$rank["adenaPosition"].".gif'>" : $rank["adenaPosition"]."º";
				if($tpl->exists("RANK_ADENA_NAME"))
					$tpl->RANK_ADENA_NAME = $rank["adenaName"];
				if($tpl->exists("RANK_ADENA_COUNT"))
					$tpl->RANK_ADENA_COUNT = $rank["adenaCount"];
				if($tpl->exists("RANK_ADENA_BGCOUNT"))
					$tpl->RANK_ADENA_BGCOUNT = $rank["adenaGoldbar"];
				if($tpl->exists("RANK_ADENA_TOTAL"))
					$tpl->RANK_ADENA_TOTAL = $rank["adenaTotal"];
				if($tpl->exists("RANK_ADENA_CSS_ADJUSTMENT"))
					$tpl->RANK_ADENA_CSS_ADJUSTMENT = $rank["adenaPosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_BOSSES" || $model[$x] == "BLOCK_RAID_BOSSES"){
		if($config["enable_bosses"]){
			$y=1;
			foreach($getRank->Bosses($model[$x] == "BLOCK_RANK_BOSSES" ? true : false) as $rank){
				if($tpl->exists("RANK_BOSS_NAME"))
					$tpl->RANK_BOSS_NAME = $rank["bossName"];
				if($tpl->exists("RANK_BOSS_LEVEL"))
					$tpl->RANK_BOSS_LEVEL = $rank["bossLevel"];
				if($tpl->exists("RANK_BOSS_STATUS"))
					$tpl->RANK_BOSS_STATUS = $rank["bossStatus"];
				if($tpl->exists("RANK_BOSS_RESPAWN"))
					$tpl->RANK_BOSS_RESPAWN = $rank["bossRespawn"];
				if($tpl->exists("RANK_BOSS_CSS_ADJUSTMENT"))
					$tpl->RANK_BOSS_CSS_ADJUSTMENT = $y % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
				$y++;
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_RAID_POINTS"){
		if($config["enable_top_raid"]){
			foreach($getRank->rankRaidPoints($config["MAX_RANKINGS"]) as $rank){
				if($tpl->exists("RANK_RAID_POINTS_POS"))
					$tpl->RANK_RAID_POINTS_POS = $rank["RPointsPosition"] < 4 ? "<img src='images/miscs/".$rank["RPointsPosition"].".gif'>" : $rank["RPointsPosition"]."º";
				if($tpl->exists("RANK_RAID_POINTS_NAME"))
					$tpl->RANK_RAID_POINTS_NAME = $rank["RPointsName"];
				if($tpl->exists("RANK_RAID_POINTS_CLASS"))
					$tpl->RANK_RAID_POINTS_CLASS = $rank["RPointsClass"];
				if($tpl->exists("RANK_RAID_POINTS_CREST_CLAN"))
					$tpl->RANK_RAID_POINTS_CREST_CLAN = $rank["RPointsCrestClan"];
				if($tpl->exists("RANK_RAID_POINTS_CLAN"))
					$tpl->RANK_RAID_POINTS_CLAN = $rank["RPointsClan"];
				if($tpl->exists("RANK_RAID_POINTS_COUNT"))
					$tpl->RANK_RAID_POINTS_COUNT = $rank["RPointsCount"];
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_RANK_ONLINE"){
		if($config["enable_top_online"]){
			foreach($getRank->topOnline($config["MAX_RANKINGS"]) as $rank){
				if($tpl->exists("RANK_ONLINE_POS"))
					$tpl->RANK_ONLINE_POS = $rank["onlinePosition"] < 4 ? "<img src='images/miscs/".$rank["onlinePosition"].".gif'>" : $rank["onlinePosition"]."º";
				if($tpl->exists("RANK_ONLINE_NAME"))
					$tpl->RANK_ONLINE_NAME = $rank["onlineName"];
				if($tpl->exists("RANK_ONLINE_CREST_CLAN"))
					$tpl->RANK_ONLINE_CREST_CLAN = $rank["onlineCrestClan"];
				if($tpl->exists("RANK_ONLINE_CLAN"))
					$tpl->RANK_ONLINE_CLAN = $rank["onlineClan"];
				if($tpl->exists("RANK_ONLINE_TIME"))
					$tpl->RANK_ONLINE_TIME = $rank["onlineTime"];
				if($tpl->exists("RANK_ONLINE_CSS_ADJUSTMENT"))
					$tpl->RANK_ONLINE_CSS_ADJUSTMENT = $rank["onlinePosition"] % 2 == 0 ? "one" : "two";
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_MINI_RANK"){
		foreach($getRank->miniRank() as $rank){
			if($tpl->exists("MINI_RANK_PVP"))
				$tpl->MINI_RANK_PVP = $rank["miniTopPvp"];
			if($tpl->exists("MINI_RANK_PK"))
				$tpl->MINI_RANK_PK = $rank["miniTopPk"];
			if($tpl->exists("MINI_RANK_ONLINE"))
				$tpl->MINI_RANK_ONLINE = $rank["miniTopOnline"];
			if($tpl->exists("MINI_RANK_CREST_CLAN"))
				$tpl->MINI_RANK_CREST_CLAN = $rank["miniTopCrestClan"];
			if($tpl->exists("MINI_RANK_CLAN"))
				$tpl->MINI_RANK_CLAN = $rank["miniTopClan"];
			if($tpl->exists($model[$x], true))
				$tpl->block($model[$x]);
		}
	}
	if($model[$x] == "BLOCK_MINI_BOSS_STATUS"){
		if($config["enable_bosses"]){
			foreach($rank = $getRank->miniBossStatus() as $rank){
				if($tpl->exists("MINI_BOSS_NAME"))
					$tpl->MINI_BOSS_NAME = $rank["miniBossName"];
				if($tpl->exists("MINI_BOSS_LEVEL"))
					$tpl->MINI_BOSS_LEVEL = $rank["miniBossLevel"];
				if($tpl->exists("MINI_BOSS_STATUS"))
					$tpl->MINI_BOSS_STATUS = $rank["miniBossStatus"];
				if($tpl->exists("MINI_BOSS_RESPAWN"))
					$tpl->MINI_BOSS_RESPAWN = $rank["miniBossRespawn"];
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if($model[$x] == "BLOCK_MINI_CASTLE"){
		if($config["enable_castles"]){
			foreach($rank = $getRank->miniCastle() as $rank){
				if($tpl->exists("MINI_CASTLE_NAME"))
					$tpl->MINI_CASTLE_NAME = strtolower($rank["miniCastleName"]);
				if($tpl->exists("MINI_CASTLE_OWNER"))
					$tpl->MINI_CASTLE_OWNER = $rank["miniCastleOwner"];
				if($tpl->exists("MINI_CASTLE_CLAN"))
					$tpl->MINI_CASTLE_CLAN = $rank["miniCastleClan"];
				if($tpl->exists("MINI_CASTLE_ALLY"))
					$tpl->MINI_CASTLE_ALLY = $rank["miniCastleAlly"];
				if($tpl->exists("MINI_CASTLE_DATE"))
					$tpl->MINI_CASTLE_DATE = $rank["miniCastleDate"];
				if($tpl->exists("MINI_CASTLE_TAX"))
					$tpl->MINI_CASTLE_TAX = $rank["miniCastleTax"];
				if($tpl->exists("MINI_CASTLE_DEFENDERS"))
					$tpl->MINI_CASTLE_DEFENDERS = $rank["miniCastleDefenders"];
				if($tpl->exists("MINI_CASTLE_ATTACKERS"))
					$tpl->MINI_CASTLE_ATTACKERS = $rank["miniCastleAttackers"];
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if(in_array($model[$x],$allowedScreenshots)){
		if($config["enable_screenshots"]){
			if($model[$x] == "BLOCK_GALLERY_SCREENSHOTS"){
				$pag = empty($getRank->filter($_GET["page"] ?? "")) ? 0 : $getRank->filter($_GET["page"] ?? "");
				$reg_inicial = $pag * $config["MAX_SCREENSHOTS_GALLERY"];
				$reg_atual = $reg_inicial + 1;
				$reg_final = $reg_inicial + $config["MAX_SCREENSHOTS_GALLERY"];
				$sql_qtd_reg = $getRank->showScreenshots(1,"id DESC",0,false);
				$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
				$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
				$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_SCREENSHOTS_GALLERY"]);
				if($tpl->exists("BLOCK_PAGINATION",true)){
					if($tpl->exists("PAGINATION"))
						$tpl->PAGINATION = $getRank->paginationPanel($pag, $quant_pag);
					$tpl->block("BLOCK_PAGINATION");
				}else{
					if($tpl->exists("PAGINATION"))
						$tpl->PAGINATION = $getRank->pagination($pag, $quant_pag);
				}
				if($tpl->exists("INDEX_SCREENSHOTS_TOTAL"))
					$tpl->INDEX_SCREENSHOTS_TOTAL = $qtd_total_reg;
				if($tpl->exists("INDEX_SCREENSHOTS_START"))
					$tpl->INDEX_SCREENSHOTS_START = empty($reg_final) ? 0 : $reg_atual;
				if($tpl->exists("INDEX_SCREENSHOTS_STOP"))
					$tpl->INDEX_SCREENSHOTS_STOP = $reg_final;
			}
			if($model[$x] == "BLOCK_INDEX_SCREENSHOTS")
				$maxScreenshot = 1;
			elseif($model[$x] == "BLOCK_INDEX_SCREENSHOTS_6")
				$maxScreenshot = 6;
			else
				$maxScreenshot = $reg_inicial.", ".$config["MAX_SCREENSHOTS_GALLERY"];
			foreach($getRank->showScreenshots(1,$model[$x] == "BLOCK_GALLERY_SCREENSHOTS" ? "id DESC" : "rand()",$maxScreenshot,$model[$x] == "BLOCK_GALLERY_SCREENSHOTS" ? false : true) as $rank){
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
				if($tpl->exists("GALLERY_PAIR_SEPARATOR"))
					$tpl->GALLERY_PAIR_SEPARATOR = $rank["screenshotNum"] % 2 == 0 ? "<div style='float:left; width:100%; heght:1px;'></div>" : null;
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
			}
		}
	}
	if(in_array($model[$x], $allowedVideos)){
		if($config["enable_videos"]){
			if($model[$x] == "BLOCK_GALLERY_VIDEOS"){
				$pag = empty($getRank->filter($_GET["page"] ?? "")) ? 0 : $getRank->filter($_GET["page"] ?? "");
				$reg_inicial = $pag * $config["MAX_VIDEOS_GALLERY"];
				$reg_atual = $reg_inicial + 1;
				$reg_final = $reg_inicial + $config["MAX_VIDEOS_GALLERY"];
				$sql_qtd_reg = $getRank->showVideos(1,"id DESC",0,false);
				$qtd_total_reg = count($sql_qtd_reg) == 0 ? 0 : count($sql_qtd_reg);
				$reg_final = $qtd_total_reg < $reg_final ? $qtd_total_reg : $reg_final;
				$quant_pag = ceil(count($sql_qtd_reg)/$config["MAX_VIDEOS_GALLERY"]);
				if($tpl->exists("BLOCK_PAGINATION",true)){
					if($tpl->exists("PAGINATION"))
						$tpl->PAGINATION = $getRank->paginationPanel($pag, $quant_pag);
					$tpl->block("BLOCK_PAGINATION");
				}else{
					if($tpl->exists("PAGINATION"))
						$tpl->PAGINATION = $getRank->pagination($pag, $quant_pag);
				}
				if($tpl->exists("INDEX_VIDEOS_TOTAL"))
					$tpl->INDEX_VIDEOS_TOTAL = $qtd_total_reg;
				if($tpl->exists("INDEX_VIDEOS_START"))
					$tpl->INDEX_VIDEOS_START = empty($reg_final) ? 0 : $reg_atual;
				if($tpl->exists("INDEX_VIDEOS_STOP"))
					$tpl->INDEX_VIDEOS_STOP = $reg_final;
			}
			if($model[$x] == "BLOCK_INDEX_VIDEOS")
				$maxVideos = 1;
			elseif($model[$x] == "BLOCK_INDEX_VIDEOS_6")
				$maxVideos = 6;
			else
				$maxVideos = $reg_inicial.", ".$config["MAX_VIDEOS_GALLERY"];
			$ps = 1;
			foreach($getRank->showVideos(1,$model[$x] == "BLOCK_GALLERY_VIDEOS" ? "id DESC" : "rand()",$maxVideos,$model[$x] == "BLOCK_GALLERY_VIDEOS" ? false : true) as $rank){
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
				if($tpl->exists("GALLERY_PAIR_SEPARATOR"))
					$tpl->GALLERY_PAIR_SEPARATOR = $ps % 2 == 0 ? "<div style='float:left; width:100%; heght:1px;'></div>" : null;
				if($tpl->exists($model[$x], true))
					$tpl->block($model[$x]);
				$ps++;
			}
		}
	}
}