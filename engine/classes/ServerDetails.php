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
	
	class ServerInfo extends Suport {
		
		public function serverDetails(){
			$rank = array();
			if($this->allow_server_stats){
				$records2 = $this->execute($this->QUERY_SELECT_SERVER_STATISTICS_1,[],"login");
				$records = $this->execute($this->QUERY_SELECT_SERVER_STATISTICS_2);
				if(count($records) == 1){
					if(count($records2) == 1){
						$accounts = $records2[0]["accounts"];
					}else{
						$accounts = 0;
					}
					if($this->enable_fake_players){
						$accounts = ceil($accounts * $this->fake_players_number);
						$chars = ceil($records[0]["chars"] * $this->fake_players_number);
						$playersOnline = ceil($records[0]["players_on"] * $this->fake_players_number);
						$clans = ceil($records[0]["clans"] * $this->fake_players_number);
					}else{
						$chars = $records[0]["chars"];
						$playersOnline = $records[0]["players_on"];
						$clans = $records[0]["clans"];
					}
					array_push($rank, array("totalAccounts" => $accounts, "totalCharacters" => $chars, "totalCharactersOnline" => $playersOnline, "totalClans" => $clans));
				}else{
					array_push($rank, array("totalAccounts" => 0, "totalCharacters" => 0, "totalCharactersOnline" => 0, "totalClans" => 0));
				}
			}
			return $rank;
		}
		
		function showStaff(){
			$staff = array();
			$records = $this->execute("SELECT * FROM icp_staff ORDER BY id ASC");
			if(count($records) > 0){
				for($x=0;$x<count($records);$x++){
					array_push($staff, array("staffImg" => $records[$x]["img"], "staffName" => $records[$x]["name"], "staffEmail" => $records[$x]["email"]));
				}
			}
			return $staff;
		}
		
		public function showNews($newsId,$limit){
			$news = array();
			if($this->enable_news > 0){
				if(!empty($limit)){
					$where = $newsId > 0 ? " WHERE n.id = '".$newsId."'" : null;
					if($this->db_type){
						$records = $this->execute("SELECT n.*, CASE WHEN n.author != '' THEN CASE WHEN (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) IS NULL THEN 'noimage.jpg;GM Anonymous' ELSE (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) END ELSE 'noimage.jpg;GM Anonymous' END AS staff FROM icp_news AS n".$where." ORDER BY n.date DESC LIMIT ".$limit);
					}else{
						$records = $this->execute("SELECT n.*, CASE WHEN n.author != '' THEN CASE WHEN (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) IS NULL THEN 'noimage.jpg;GM Anonymous' ELSE (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) END ELSE 'noimage.jpg;GM Anonymous' END AS staff FROM icp_news AS n".$where." ORDER BY n.date DESC OFFSET ".str_replace(","," ROWS FETCH NEXT",$limit)." ROWS ONLY");
					}
				}else{
					$records = $this->execute("SELECT n.*, CASE WHEN n.author != '' THEN CASE WHEN (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) IS NULL THEN 'noimage.jpg;GM Anonymous' ELSE (SELECT CONCAT(img,';',name) FROM icp_staff WHERE login = n.author) END ELSE 'noimage.jpg;GM Anonymous' END AS staff FROM icp_news AS n ORDER BY n.date DESC");
				}
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						$staff = explode(";",$records[$x]["staff"]);
						array_push($news, array("newsId" => $records[$x]["id"], "newsText" => $records[$x]["news"], "newsTitle" => $records[$x]["title"], "newsDate" => $records[$x]["date"], "newsImage" => $staff[0], "newsAuthor" => $staff[1]));
					}
				}
			}
			return $news;
		}
		
	}
	
}