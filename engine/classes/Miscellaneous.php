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
namespace ICPNetworks\Miscs {
	
	class Suport {
		
		public function __construct($db_type, $loginServer, $gameServer, $config) {
			$this->db_type = $db_type;
			$this->loginServer = $loginServer;
			$this->gameServer = $gameServer;
			foreach($config AS $key => $val){
				$this->{$key} = $val;
			}
		}
		
		public function select_Timezone($selected = '') {
			$OptionsArray = timezone_identifiers_list();
			$select= '<select class="form-select form-select-sm" form="configs" id="serverTimezone" name="timezone">';
			foreach($OptionsArray as $key => $val){
				$select .='<option value="'.$val.'"';
				$select .= ($val == $selected ? ' selected' : null);
				$select .= '>'.$val.'</option>';
			}
			$select.='</select>';
			return $select;
		}
		
		public function showDir($dir, $selected = ''){
			if(!is_dir($dir))
				return null;
			$scan = scandir($dir);
			$select = null;
			foreach($scan as $key => $val){
				if($val[0] == "."){ continue; }
				$select .= '<option value="'.$val.'"';
				$select .= ($val == $selected ? ' selected' : null);
				$select .= '>'.$val.'</option>';
			}
			return $select;
		}
		
		protected function chronicleTables($table){
			switch ($table){
				case "icp_spawnlist":
					if(in_array($this->CHRONICLE_ID,array(0,1,2,3,4,5))){
						$name = "icp_spawnlist_c4";
					}elseif(in_array($this->CHRONICLE_ID,array(6,7))){
						$name = "icp_spawnlist_interlude";
					}elseif(in_array($this->CHRONICLE_ID,array(8,9,10,11,12))){
						$name = "icp_spawnlist_gracia";
					}elseif($this->CHRONICLE_ID == 13){
						$name = "icp_spawnlist_freya";
					}elseif($this->CHRONICLE_ID == 14){
						$name = "icp_spawnlist_high_five";
					}elseif(in_array($this->CHRONICLE_ID,array(15,16,17,18,19,20,21,22,23,24,25,26,27,28,29))){
						$name = "icp_spawnlist_god";
					}elseif(in_array($this->CHRONICLE_ID,array(30,31,32,33,34))){
						$name = "icp_spawnlist_classic";
					}elseif(in_array($this->CHRONICLE_ID,array(35,36,37))){
						$name = "icp_spawnlist_essence";
					}else{
						$name = "icp_spawnlist_interlude";
					}
					break;
				case "icp_droplist":
					if(in_array($this->CHRONICLE_ID,array(0,1,2,3,4,5))){
						$name = "icp_droplist_c4";
					}elseif(in_array($this->CHRONICLE_ID,array(6,7))){
						$name = "icp_droplist_interlude";
					}elseif(in_array($this->CHRONICLE_ID,array(8,9,10,11,12))){
						$name = "icp_droplist_gracia";
					}elseif($this->CHRONICLE_ID == 13){
						$name = "icp_droplist_freya";
					}elseif($this->CHRONICLE_ID == 14){
						$name = "icp_droplist_high_five";
					}elseif(in_array($this->CHRONICLE_ID,array(15,16,17,18,19,20,21,22,23,24,25,26,27,28,29))){
						$name = "icp_droplist_god";
					}elseif(in_array($this->CHRONICLE_ID,array(30,31,32,33,34))){
						$name = "icp_droplist_classic";
					}elseif(in_array($this->CHRONICLE_ID,array(35,36,37))){
						$name = "icp_droplist_essence";
					}else{
						$name = "icp_droplist_interlude";
					}
					break;
				case "icp_icons":
					if(in_array($this->CHRONICLE_ID,array(0,1,2,3,4,5))){
						$name = "icp_icons_c4";
					}elseif(in_array($this->CHRONICLE_ID,array(6,7))){
						$name = "icp_icons_interlude";
					}elseif(in_array($this->CHRONICLE_ID,array(8,9,10,11,12))){
						$name = "icp_icons_gracia";
					}elseif($this->CHRONICLE_ID == 13){
						$name = "icp_icons_freya";
					}elseif($this->CHRONICLE_ID == 14){
						$name = "icp_icons_high_five";
					}elseif(in_array($this->CHRONICLE_ID,array(15,16,17,18,19,20,21,22,23,24,25,26,27,28,29))){
						$name = "icp_icons_god";
					}elseif(in_array($this->CHRONICLE_ID,array(30,31,32,33,34))){
						$name = "icp_icons_classic";
					}elseif(in_array($this->CHRONICLE_ID,array(35,36,37))){
						$name = "icp_icons_essence";
					}else{
						$name = "icp_icons_interlude";
					}
					break;
				case "icp_npc":
					if(in_array($this->CHRONICLE_ID,array(0,1,2,3,4,5))){
						$name = "icp_npc_c4";
					}elseif(in_array($this->CHRONICLE_ID,array(6,7))){
						$name = "icp_npc_interlude";
					}elseif(in_array($this->CHRONICLE_ID,array(8,9,10,11,12))){
						$name = "icp_npc_gracia";
					}elseif($this->CHRONICLE_ID == 13){
						$name = "icp_npc_freya";
					}elseif($this->CHRONICLE_ID == 14){
						$name = "icp_npc_high_five";
					}elseif(in_array($this->CHRONICLE_ID,array(15,16,17,18,19,20,21,22,23,24,25,26,27,28,29))){
						$name = "icp_npc_god";
					}elseif(in_array($this->CHRONICLE_ID,array(30,31,32,33,34))){
						$name = "icp_npc_classic";
					}elseif(in_array($this->CHRONICLE_ID,array(35,36,37))){
						$name = "icp_npc_essence";
					}else{
						$name = "icp_npc_interlude";
					}
					break;
				default:
					$name = "chronicleTables ERROR"; break;
			}
			return $name;
		}
		
		public function percentageFakePlayers($selected = ''){
			$fakeNum = array("5" => 1.05, "10" => 1.1, "15" => 1.15, "20" => 1.2, "25" => 1.25, "30" => 1.3, "35" => 1.35, "40" => 1.4, "45" => 1.45, "50" => 1.5, "55" => 1.55, "60" => 1.6, "65" => 1.65, "70" => 1.7, "75" => 1.75, "80" => 1.8, "85" => 1.85, "90" => 1.9, "95" => 1.95, "100" => 2);
			$select = null;
			foreach($fakeNum as $key => $val){
				$select .= '<option value="'.$val.'"';
				$select .= ($val == $selected ? ' selected' : null);
				$select .= '>'.$key.'%</option>';
			}
			return $select;
		}
		
		public function depositLoc($selected = ''){
			$deposit = array("INVENTORY","WAREHOUSE");
			$select = null;
			foreach($deposit as $key => $val){
				$select .= '<option value="'.$val.'"';
				$select .= ($val == $selected ? ' selected' : null);
				$select .= '>'.$val.'</option>';
			}
			return $select;
		}
		
		public function olympiadsPeriod($selected = ''){
			$periods = array(7,15,30);
			$select = null;
			foreach($periods as $key => $val){
				$select .= '<option value="'.$val.'"';
				$select .= ($val == $selected ? ' selected' : null);
				$select .= '>'.$val.' days</option>';
			}
			return $select;
		}
		
		public function currency($id = ''){
			if($id == "BRL")
				$currency_id = "R$";
			elseif($id == "EUR")
				$currency_id = "€";
			elseif($id == "VES")
				$currency_id = "Bs.";
			elseif($id == "PEN")
				$currency_id = "S/";
			else
				$currency_id = "$";
			return $currency_id;
		}
		
		protected $noPvpItems = " AND i.item_id != '21923' AND i.item_id != '21924' AND i.item_id != '21925' AND i.item_id != '21926' AND i.item_id != '21931' AND i.item_id != '21932' AND i.item_id != '21933' AND i.item_id != '21934' AND i.item_id != '21936' AND i.item_id != '21938' AND i.item_id != '21943' AND i.item_id != '21944' AND i.item_id != '21945' AND i.item_id != '21946' AND i.item_id != '21951' AND i.item_id != '21952' AND i.item_id != '21953' AND i.item_id != '21954' AND i.item_id != '21956' AND i.item_id != '21958' AND i.item_id != '21963' AND i.item_id != '21964' AND i.item_id != '21965' AND i.item_id != '21970' AND i.item_id != '21971' AND i.item_id != '21972' AND i.item_id != '10752' AND i.item_id != '10753' AND i.item_id != '10754' AND i.item_id != '10755' AND i.item_id != '10756' AND i.item_id != '10757' AND i.item_id != '10758' AND i.item_id != '16134' AND i.item_id != '10759' AND i.item_id != '16135' AND i.item_id != '10760' AND i.item_id != '16136' AND i.item_id != '10761' AND i.item_id != '16137' AND i.item_id != '10762' AND i.item_id != '16138' AND i.item_id != '10763' AND i.item_id != '16139' AND i.item_id != '10764' AND i.item_id != '16140' AND i.item_id != '10765' AND i.item_id != '16141' AND i.item_id != '10766' AND i.item_id != '16142' AND i.item_id != '10767' AND i.item_id != '16143' AND i.item_id != '10768' AND i.item_id != '16144' AND i.item_id != '10769' AND i.item_id != '16145' AND i.item_id != '10770' AND i.item_id != '16146' AND i.item_id != '10771' AND i.item_id != '16147' AND i.item_id != '10772' AND i.item_id != '10773' AND i.item_id != '16149' AND i.item_id != '10774' AND i.item_id != '10775' AND i.item_id != '16151' AND i.item_id != '10776' AND i.item_id != '10777' AND i.item_id != '16153' AND i.item_id != '10778' AND i.item_id != '10779' AND i.item_id != '14363' AND i.item_id != '16155' AND i.item_id != '10780' AND i.item_id != '14364' AND i.item_id != '10781' AND i.item_id != '14365' AND i.item_id != '16157' AND i.item_id != '10782' AND i.item_id != '14366' AND i.item_id != '10783' AND i.item_id != '14367' AND i.item_id != '16159' AND i.item_id != '10784' AND i.item_id != '14368' AND i.item_id != '10785' AND i.item_id != '14369' AND i.item_id != '10786' AND i.item_id != '14370' AND i.item_id != '10787' AND i.item_id != '14371' AND i.item_id != '10788' AND i.item_id != '14372' AND i.item_id != '10789' AND i.item_id != '14373' AND i.item_id != '10790' AND i.item_id != '14374' AND i.item_id != '10791' AND i.item_id != '14375' AND i.item_id != '10792' AND i.item_id != '14376' AND i.item_id != '16168' AND i.item_id != '10793' AND i.item_id != '14377' AND i.item_id != '15913' AND i.item_id != '16169' AND i.item_id != '10794' AND i.item_id != '14378' AND i.item_id != '15914' AND i.item_id != '16170' AND i.item_id != '10795' AND i.item_id != '14379' AND i.item_id != '15915' AND i.item_id != '16171' AND i.item_id != '10796' AND i.item_id != '14380' AND i.item_id != '15916' AND i.item_id != '16172' AND i.item_id != '10797' AND i.item_id != '14381' AND i.item_id != '15917' AND i.item_id != '16173' AND i.item_id != '10798' AND i.item_id != '14382' AND i.item_id != '15918' AND i.item_id != '16174' AND i.item_id != '10799' AND i.item_id != '14383' AND i.item_id != '15919' AND i.item_id != '16175' AND i.item_id != '10800' AND i.item_id != '14384' AND i.item_id != '15920' AND i.item_id != '16176' AND i.item_id != '10801' AND i.item_id != '14385' AND i.item_id != '15921' AND i.item_id != '10802' AND i.item_id != '14386' AND i.item_id != '15922' AND i.item_id != '10803' AND i.item_id != '14387' AND i.item_id != '15923' AND i.item_id != '16179' AND i.item_id != '10804' AND i.item_id != '12852' AND i.item_id != '14388' AND i.item_id != '15924' AND i.item_id != '16180' AND i.item_id != '10805' AND i.item_id != '12853' AND i.item_id != '14389' AND i.item_id != '15925' AND i.item_id != '16181' AND i.item_id != '10806' AND i.item_id != '12854' AND i.item_id != '14390' AND i.item_id != '15926' AND i.item_id != '16182' AND i.item_id != '10807' AND i.item_id != '12855' AND i.item_id != '14391' AND i.item_id != '15927' AND i.item_id != '16183' AND i.item_id != '10808' AND i.item_id != '12856' AND i.item_id != '14392' AND i.item_id != '15928' AND i.item_id != '16184' AND i.item_id != '10809' AND i.item_id != '12857' AND i.item_id != '14393' AND i.item_id != '15929' AND i.item_id != '16185' AND i.item_id != '10810' AND i.item_id != '12858' AND i.item_id != '14394' AND i.item_id != '15930' AND i.item_id != '16186' AND i.item_id != '10811' AND i.item_id != '12859' AND i.item_id != '14395' AND i.item_id != '15931' AND i.item_id != '16187' AND i.item_id != '10812' AND i.item_id != '12860' AND i.item_id != '14396' AND i.item_id != '15932' AND i.item_id != '16188' AND i.item_id != '10813' AND i.item_id != '12861' AND i.item_id != '14397' AND i.item_id != '15933' AND i.item_id != '16189' AND i.item_id != '10814' AND i.item_id != '12862' AND i.item_id != '14398' AND i.item_id != '15934' AND i.item_id != '16190' AND i.item_id != '10815' AND i.item_id != '12863' AND i.item_id != '14399' AND i.item_id != '15935' AND i.item_id != '16191' AND i.item_id != '10816' AND i.item_id != '12864' AND i.item_id != '14400' AND i.item_id != '15936' AND i.item_id != '16192' AND i.item_id != '10817' AND i.item_id != '12865' AND i.item_id != '14401' AND i.item_id != '15937' AND i.item_id != '16193' AND i.item_id != '10818' AND i.item_id != '12866' AND i.item_id != '14402' AND i.item_id != '15938' AND i.item_id != '16194' AND i.item_id != '10819' AND i.item_id != '12867' AND i.item_id != '14403' AND i.item_id != '15939' AND i.item_id != '16195' AND i.item_id != '10820' AND i.item_id != '12868' AND i.item_id != '14404' AND i.item_id != '15940' AND i.item_id != '16196' AND i.item_id != '10821' AND i.item_id != '12869' AND i.item_id != '14405' AND i.item_id != '15941' AND i.item_id != '16197' AND i.item_id != '10822' AND i.item_id != '12870' AND i.item_id != '14406' AND i.item_id != '15942' AND i.item_id != '16198' AND i.item_id != '10823' AND i.item_id != '12871' AND i.item_id != '14407' AND i.item_id != '15943' AND i.item_id != '16199' AND i.item_id != '10824' AND i.item_id != '12872' AND i.item_id != '14408' AND i.item_id != '15944' AND i.item_id != '16200' AND i.item_id != '10825' AND i.item_id != '12873' AND i.item_id != '14409' AND i.item_id != '15945' AND i.item_id != '16201' AND i.item_id != '10826' AND i.item_id != '12874' AND i.item_id != '14410' AND i.item_id != '15946' AND i.item_id != '16202' AND i.item_id != '10827' AND i.item_id != '12875' AND i.item_id != '14411' AND i.item_id != '15947' AND i.item_id != '16203' AND i.item_id != '10828' AND i.item_id != '12876' AND i.item_id != '14412' AND i.item_id != '15948' AND i.item_id != '16204' AND i.item_id != '10829' AND i.item_id != '12877' AND i.item_id != '14413' AND i.item_id != '15949' AND i.item_id != '16205' AND i.item_id != '10830' AND i.item_id != '12878' AND i.item_id != '14414' AND i.item_id != '15950' AND i.item_id != '16206' AND i.item_id != '10831' AND i.item_id != '12879' AND i.item_id != '14415' AND i.item_id != '15951' AND i.item_id != '16207' AND i.item_id != '10832' AND i.item_id != '12880' AND i.item_id != '14416' AND i.item_id != '15952' AND i.item_id != '16208' AND i.item_id != '10833' AND i.item_id != '12881' AND i.item_id != '14417' AND i.item_id != '15953' AND i.item_id != '16209' AND i.item_id != '10834' AND i.item_id != '12882' AND i.item_id != '14418' AND i.item_id != '15954' AND i.item_id != '16210' AND i.item_id != '10835' AND i.item_id != '12883' AND i.item_id != '14419' AND i.item_id != '15955' AND i.item_id != '16211' AND i.item_id != '12884' AND i.item_id != '14420' AND i.item_id != '15956' AND i.item_id != '16212' AND i.item_id != '12885' AND i.item_id != '14421' AND i.item_id != '15957' AND i.item_id != '16213' AND i.item_id != '12886' AND i.item_id != '14422' AND i.item_id != '15958' AND i.item_id != '16214' AND i.item_id != '12887' AND i.item_id != '14423' AND i.item_id != '15959' AND i.item_id != '16215' AND i.item_id != '12888' AND i.item_id != '14424' AND i.item_id != '15960' AND i.item_id != '16216' AND i.item_id != '12889' AND i.item_id != '14425' AND i.item_id != '15961' AND i.item_id != '16217' AND i.item_id != '12890' AND i.item_id != '14426' AND i.item_id != '15962' AND i.item_id != '16218' AND i.item_id != '12891' AND i.item_id != '14427' AND i.item_id != '15963' AND i.item_id != '16219' AND i.item_id != '12892' AND i.item_id != '14428' AND i.item_id != '15964' AND i.item_id != '16220' AND i.item_id != '12893' AND i.item_id != '14429' AND i.item_id != '15965' AND i.item_id != '12894' AND i.item_id != '14430' AND i.item_id != '15966' AND i.item_id != '12895' AND i.item_id != '14431' AND i.item_id != '15967' AND i.item_id != '12896' AND i.item_id != '14432' AND i.item_id != '15968' AND i.item_id != '12897' AND i.item_id != '14433' AND i.item_id != '15969' AND i.item_id != '12898' AND i.item_id != '14434' AND i.item_id != '15970' AND i.item_id != '12899' AND i.item_id != '14435' AND i.item_id != '15971' AND i.item_id != '12900' AND i.item_id != '14436' AND i.item_id != '15972' AND i.item_id != '12901' AND i.item_id != '14437' AND i.item_id != '15973' AND i.item_id != '12902' AND i.item_id != '14438' AND i.item_id != '15974' AND i.item_id != '12903' AND i.item_id != '14439' AND i.item_id != '15975' AND i.item_id != '12904' AND i.item_id != '14440' AND i.item_id != '15976' AND i.item_id != '12905' AND i.item_id != '14441' AND i.item_id != '15977' AND i.item_id != '12906' AND i.item_id != '14442' AND i.item_id != '15978' AND i.item_id != '12907' AND i.item_id != '14443' AND i.item_id != '15979' AND i.item_id != '12908' AND i.item_id != '14444' AND i.item_id != '15980' AND i.item_id != '12909' AND i.item_id != '14445' AND i.item_id != '15981' AND i.item_id != '12910' AND i.item_id != '14446' AND i.item_id != '15982' AND i.item_id != '12911' AND i.item_id != '14447' AND i.item_id != '15983' AND i.item_id != '12912' AND i.item_id != '14448' AND i.item_id != '15984' AND i.item_id != '12913' AND i.item_id != '14449' AND i.item_id != '15985' AND i.item_id != '12914' AND i.item_id != '14450' AND i.item_id != '15986' AND i.item_id != '12915' AND i.item_id != '14451' AND i.item_id != '15987' AND i.item_id != '12916' AND i.item_id != '14452' AND i.item_id != '15988' AND i.item_id != '12917' AND i.item_id != '14453' AND i.item_id != '15989' AND i.item_id != '12918' AND i.item_id != '14454' AND i.item_id != '15990' AND i.item_id != '12919' AND i.item_id != '14455' AND i.item_id != '15991' AND i.item_id != '12920' AND i.item_id != '14456' AND i.item_id != '15992' AND i.item_id != '12921' AND i.item_id != '14457' AND i.item_id != '15993' AND i.item_id != '12922' AND i.item_id != '14458' AND i.item_id != '15994' AND i.item_id != '12923' AND i.item_id != '14459' AND i.item_id != '15995' AND i.item_id != '12924' AND i.item_id != '14460' AND i.item_id != '15996' AND i.item_id != '12925' AND i.item_id != '14461' AND i.item_id != '15997' AND i.item_id != '12926' AND i.item_id != '14462' AND i.item_id != '15998' AND i.item_id != '12927' AND i.item_id != '14463' AND i.item_id != '15999' AND i.item_id != '12928' AND i.item_id != '14464' AND i.item_id != '16000' AND i.item_id != '12929' AND i.item_id != '14465' AND i.item_id != '16001' AND i.item_id != '12930' AND i.item_id != '14466' AND i.item_id != '16002' AND i.item_id != '12931' AND i.item_id != '14467' AND i.item_id != '16003' AND i.item_id != '12932' AND i.item_id != '14468' AND i.item_id != '16004' AND i.item_id != '12933' AND i.item_id != '14469' AND i.item_id != '16005' AND i.item_id != '12934' AND i.item_id != '14470' AND i.item_id != '16006' AND i.item_id != '12935' AND i.item_id != '14471' AND i.item_id != '16007' AND i.item_id != '12936' AND i.item_id != '14472' AND i.item_id != '16008' AND i.item_id != '12937' AND i.item_id != '14473' AND i.item_id != '16009' AND i.item_id != '12938' AND i.item_id != '14474' AND i.item_id != '16010' AND i.item_id != '12939' AND i.item_id != '14475' AND i.item_id != '16011' AND i.item_id != '12940' AND i.item_id != '14476' AND i.item_id != '16012' AND i.item_id != '12941' AND i.item_id != '14477' AND i.item_id != '16013' AND i.item_id != '12942' AND i.item_id != '14478' AND i.item_id != '16014' AND i.item_id != '12943' AND i.item_id != '14479' AND i.item_id != '16015' AND i.item_id != '12944' AND i.item_id != '14480' AND i.item_id != '16016' AND i.item_id != '12945' AND i.item_id != '14481' AND i.item_id != '16017' AND i.item_id != '12946' AND i.item_id != '14482' AND i.item_id != '16018' AND i.item_id != '12947' AND i.item_id != '14483' AND i.item_id != '16019' AND i.item_id != '12948' AND i.item_id != '14484' AND i.item_id != '16020' AND i.item_id != '12949' AND i.item_id != '14485' AND i.item_id != '16021' AND i.item_id != '12950' AND i.item_id != '14486' AND i.item_id != '16022' AND i.item_id != '12951' AND i.item_id != '14487' AND i.item_id != '16023' AND i.item_id != '12952' AND i.item_id != '14488' AND i.item_id != '16024' AND i.item_id != '12953' AND i.item_id != '14489' AND i.item_id != '12954' AND i.item_id != '14490' AND i.item_id != '12955' AND i.item_id != '14491' AND i.item_id != '12956' AND i.item_id != '14492' AND i.item_id != '12957' AND i.item_id != '14493' AND i.item_id != '12958' AND i.item_id != '14494' AND i.item_id != '12959' AND i.item_id != '14495' AND i.item_id != '12960' AND i.item_id != '14496' AND i.item_id != '12961' AND i.item_id != '14497' AND i.item_id != '12962' AND i.item_id != '14498' AND i.item_id != '12963' AND i.item_id != '14499' AND i.item_id != '12964' AND i.item_id != '14500' AND i.item_id != '12965' AND i.item_id != '14501' AND i.item_id != '12966' AND i.item_id != '14502' AND i.item_id != '12967' AND i.item_id != '14503' AND i.item_id != '12968' AND i.item_id != '14504' AND i.item_id != '12969' AND i.item_id != '14505' AND i.item_id != '12970' AND i.item_id != '14506' AND i.item_id != '10667' AND i.item_id != '12971' AND i.item_id != '14507' AND i.item_id != '10668' AND i.item_id != '12972' AND i.item_id != '14508' AND i.item_id != '10669' AND i.item_id != '12973' AND i.item_id != '14509' AND i.item_id != '10670' AND i.item_id != '12974' AND i.item_id != '14510' AND i.item_id != '10671' AND i.item_id != '12975' AND i.item_id != '14511' AND i.item_id != '10672' AND i.item_id != '12976' AND i.item_id != '14512' AND i.item_id != '10673' AND i.item_id != '12977' AND i.item_id != '14513' AND i.item_id != '10674' AND i.item_id != '14514' AND i.item_id != '10675' AND i.item_id != '14515' AND i.item_id != '10676' AND i.item_id != '14516' AND i.item_id != '10677' AND i.item_id != '14517' AND i.item_id != '10678' AND i.item_id != '14518' AND i.item_id != '10679' AND i.item_id != '14519' AND i.item_id != '10680' AND i.item_id != '14520' AND i.item_id != '10681' AND i.item_id != '14521' AND i.item_id != '10682' AND i.item_id != '14522' AND i.item_id != '10683' AND i.item_id != '14523' AND i.item_id != '10684' AND i.item_id != '14524' AND i.item_id != '10685' AND i.item_id != '14525' AND i.item_id != '10686' AND i.item_id != '10687' AND i.item_id != '10688' AND i.item_id != '14528' AND i.item_id != '10689' AND i.item_id != '14529' AND i.item_id != '10690' AND i.item_id != '10691' AND i.item_id != '10692' AND i.item_id != '10693' AND i.item_id != '10694' AND i.item_id != '10695' AND i.item_id != '10696' AND i.item_id != '10697' AND i.item_id != '10698' AND i.item_id != '10699' AND i.item_id != '10700' AND i.item_id != '10701' AND i.item_id != '10702' AND i.item_id != '10703' AND i.item_id != '10704' AND i.item_id != '10705' AND i.item_id != '10706' AND i.item_id != '10707' AND i.item_id != '10708' AND i.item_id != '10709' AND i.item_id != '10710' AND i.item_id != '10711' AND i.item_id != '10712' AND i.item_id != '10713' AND i.item_id != '10714' AND i.item_id != '10715' AND i.item_id != '10716' AND i.item_id != '10717' AND i.item_id != '10718' AND i.item_id != '14558' AND i.item_id != '10719' AND i.item_id != '10720' AND i.item_id != '10721' AND i.item_id != '10722' AND i.item_id != '10723' AND i.item_id != '10724' AND i.item_id != '10725' AND i.item_id != '10726' AND i.item_id != '10727' AND i.item_id != '10728' AND i.item_id != '10729' AND i.item_id != '10730' AND i.item_id != '10731' AND i.item_id != '10732' AND i.item_id != '10733' AND i.item_id != '10734' AND i.item_id != '10735' AND i.item_id != '10736' AND i.item_id != '10737' AND i.item_id != '10738' AND i.item_id != '10739' AND i.item_id != '10740' AND i.item_id != '10741' AND i.item_id != '10742' AND i.item_id != '10743' AND i.item_id != '10744' AND i.item_id != '10745' AND i.item_id != '10746' AND i.item_id != '10747' AND i.item_id != '10748' AND i.item_id != '10749' AND i.item_id != '10750' AND i.item_id != '10751'";
		
		public function filter($value, $string = false) {
			return $string ? str_replace("\'", "&apos;", addslashes(trim($value))) : preg_replace("/(\D)/i" , "" , $value);
		}
		
		public function classe_name($classe_id){
			switch ($classe_id){
				case 0:
					$class = "Fighter"; break;
				case 1:
					$class = "Warrior"; break;
				case 2:
					$class = "Gladiator"; break;
				case 3:
					$class = "Warlord"; break;
				case 4:
					$class = "Knight"; break;
				case 5:
					$class = "Paladin"; break;
				case 6:
					$class = "Dark Avenger"; break;
				case 7:
					$class = "Rogue"; break;
				case 8:
					$class = "Treasure Hunter"; break;
				case 9:
					$class = "Hawkeye"; break;
				case 10:
					$class = "Mage"; break;
				case 11:
					$class = "Wizard"; break;
				case 12:
					$class = "Sorceror"; break;
				case 13:
					$class = "Necromancer"; break;
				case 14:
					$class = "Warlock"; break;
				case 15:
					$class = "Cleric"; break;
				case 16:
					$class = "Bishop"; break;
				case 17:
					$class = "Prophet"; break;
				case 18:
					$class = "Fighter"; break;
				case 19:
					$class = "Knight"; break;
				case 20:
					$class = "Temple Knight"; break;
				case 21:
					$class = "Sword Singer"; break;
				case 22:
					$class = "Scout"; break;
				case 23:
					$class = "PlainsWalker"; break;
				case 24:
					$class = "SilverRanger"; break;
				case 25:
					$class = "Mage"; break;
				case 26:
					$class = "Wizard"; break;
				case 27:
					$class = "Spell Singer"; break;
				case 28:
					$class = "Elemental Summoner"; break;
				case 29:
					$class = "Oracle"; break;
				case 30:
					$class = "Elder"; break;
				case 31:
					$class = "Fighter"; break;
				case 32:
					$class = "Paulus Knight"; break;
				case 33:
					$class = "Shillien Knight"; break;
				case 34:
					$class = "Blade Dancer"; break;
				case 35:
					$class = "Assassin"; break;
				case 36:
					$class = "Abyss Walker"; break;
				case 37:
					$class = "Phantom Ranger"; break;
				case 38:
					$class = "Mage"; break;
				case 39:
					$class = "Dark Wizard"; break;
				case 40:
					$class = "Spellhowler"; break;
				case 41:
					$class = "Phantom Summoner"; break;
				case 42:
					$class = "Shillien Oracle"; break;
				case 43:
					$class = "Shillien Elder"; break;
				case 44:
					$class = "Fighter"; break;
				case 45:
					$class = "Raider"; break;
				case 46:
					$class = "Destroyer"; break;
				case 47:
					$class = "Monk"; break;
				case 48:
					$class = "Tyrant"; break;
				case 49:
					$class = "Mage"; break;
				case 50:
					$class = "Shaman"; break;
				case 51:
					$class = "Overlord"; break;
				case 52:
					$class = "Warcryer"; break;
				case 53:
					$class = "Fighter"; break;
				case 54:
					$class = "Scavenger"; break;
				case 55:
					$class = "BountyHunter"; break;
				case 56:
					$class = "Artisan"; break;
				case 57:
					$class = "Warsmith"; break;
				case 88:
					$class = "Duelist"; break;
				case 89:
					$class = "Dreadnought"; break;
				case 90:
					$class = "Phoenix Knight"; break;
				case 91:
					$class = "Hell Knight"; break;
				case 92:
					$class = "Sagittarius"; break;
				case 93:
					$class = "Adventurer"; break;
				case 94:
					$class = "Archmage"; break;
				case 95:
					$class = "Soultaker"; break;
				case 96:
					$class = "Arcana Lord"; break;
				case 97:
					$class = "Cardinal"; break;
				case 98:
					$class = "Hierophant"; break;
				case 99:
					$class = "Eva Templar"; break;
				case 100:
					$class = "Sword Muse"; break;
				case 101:
					$class = "Wind Rider"; break;
				case 102:
					$class = "Moonlight Sentinel"; break;
				case 103:
					$class = "Mystic Muse"; break;
				case 104:
					$class = "Elemental Master"; break;
				case 105:
					$class = "Eva Saint"; break;
				case 106:
					$class = "Shillien Templar"; break;
				case 107:
					$class = "Spectral Dancer"; break;
				case 108:
					$class = "Ghost Hunter"; break;
				case 109:
					$class = "Ghost Sentinel"; break;
				case 110:
					$class = "Storm Screamer"; break;
				case 111:
					$class = "Spectral Master"; break;
				case 112:
					$class = "Shillien Saint"; break;
				case 113:
					$class = "Titan"; break;
				case 114:
					$class = "Grand Khauatari"; break;
				case 115:
					$class = "Dominator"; break;
				case 116:
					$class = "Doomcryer"; break;
				case 117:
					$class = "Fortune Seeker"; break;
				case 118:
					$class = "Maestro"; break;
				case 123:
					$class = "Male Soldier"; break;
				case 124:
					$class = "Female Soldier"; break;
				case 125:
					$class = "Trooper"; break;
				case 126:
					$class = "Warder"; break;
				case 127:
					$class = "Berserker"; break;
				case 128:
					$class = "Male Soulbreaker"; break;
				case 129:
					$class = "Female Soulbreaker"; break;
				case 130:
					$class = "Arbalester"; break;
				case 131:
					$class = "Doombringer"; break;
				case 132:
					$class = "Male Soulhound"; break;
				case 133:
					$class = "Female Soulhound"; break;
				case 134:
					$class = "Trickster"; break;
				case 135:
					$class = "Inspector"; break;
				case 136:
					$class = "Judicator"; break;
				case 139:
					$class = "Sigel Knight"; break;
				case 140:
					$class = "Tyrr Warrior"; break;
				case 141:
					$class = "Othell Rogue"; break;
				case 142:
					$class = "Yul Archer"; break;
				case 143:
					$class = "Feoh Wizard"; break;
				case 144:
					$class = "Iss Enchanter"; break;
				case 145:
					$class = "Wynn Summoner"; break;
				case 146:
					$class = "Aeore Healer"; break;
				case 148:
					$class = "Sigel Phoenix Knight"; break;
				case 149:
					$class = "Sigel Hell Knight"; break;
				case 150:
					$class = "Sigel Evas Templar"; break;
				case 151:
					$class = "Sigel Shilen Templar"; break;
				case 152:
					$class = "Tyrr Duelist"; break;
				case 153:
					$class = "Tyrr Dreadnought"; break;
				case 154:
					$class = "Tyrr Titan"; break;
				case 155:
					$class = "Tyrr Grand Khavatari"; break;
				case 156:
					$class = "Tyrr Maestro"; break;
				case 157:
					$class = "Tyrr Doombringer"; break;
				case 158:
					$class = "Othell Adventurer"; break;
				case 159:
					$class = "Othell Wind Rider"; break;
				case 160:
					$class = "Othell Ghost Hunter"; break;
				case 161:
					$class = "Othell Fortune Seeker"; break;
				case 162:
					$class = "Yul Sagittarius"; break;
				case 163:
					$class = "Yul Moonlight Sentinel"; break;
				case 164:
					$class = "Yul Ghost Sentinel"; break;
				case 165:
					$class = "Yul Trickster"; break;
				case 166:
					$class = "Feoh Archmage"; break;
				case 167:
					$class = "Feoh Soultaker"; break;
				case 168:
					$class = "Feoh Mystic Muse"; break;
				case 169:
					$class = "Feoh Storm Screamer"; break;
				case 170:
					$class = "Feoh Soul Hound"; break;
				case 171:
					$class = "Iss Hierophant"; break;
				case 172:
					$class = "Iss Sword Muse"; break;
				case 173:
					$class = "Iss Spectral Dancer"; break;
				case 174:
					$class = "Iss Dominator"; break;
				case 175:
					$class = "Iss Doomcryer"; break;
				case 176:
					$class = "Wynn Arcana Lord"; break;
				case 177:
					$class = "Wynn Elemental Master"; break;
				case 178:
					$class = "Wynn Spectral Master"; break;
				case 179:
					$class = "Aeore Cardinal"; break;
				case 180:
					$class = "Aeore Evas Saint"; break;
				case 181:
					$class = "Aeore Shillien Saint"; break;
				case 182:
					$class = "Ertheia Fighter"; break;
				case 183:
					$class = "Ertheia Wizard"; break;
				case 184:
					$class = "Marauder"; break;
				case 185:
					$class = "Cloud Breaker"; break;
				case 186:
					$class = "Ripper"; break;
				case 187:
					$class = "Stratomancer"; break;
				case 188:
					$class = "Eviscerator"; break;
				case 189:
					$class = "Sayha's Seer"; break;
				case 192:
					$class = "Jin Kamael Soldier"; break;
				case 193:
					$class = "Soul Finder"; break;
				case 194:
					$class = "Soul Breaker"; break;
				case 195:
					$class = "Soul Hound"; break;
				case 196:
					$class = "Death Pilgrim"; break;
				case 197:
					$class = "Death Blade"; break;
				case 198:
					$class = "Death Messenger"; break;
				case 199:
					$class = "Death Knight"; break;
				case 200:
					$class = "Death Pilgrim"; break;
				case 201:
					$class = "Death Blade"; break;
				case 202:
					$class = "Death Messenger"; break;
				case 203:
					$class = "Death Knight"; break;
				case 204:
					$class = "Death Pilgrim"; break;
				case 205:
					$class = "Death Blade"; break;
				case 206:
					$class = "Death Messenger"; break;
				case 207:
					$class = "Death Knight"; break;
				case 208:
					$class = "Sylph Gunner"; break;
				case 209:
					$class = "Sharpshooter"; break;
				case 210:
					$class = "Wind Sniper"; break;
				case 211:
					$class = "Storm Blaster"; break;
				case 217:
					$class = "Orc Lancer"; break;
				case 218:
					$class = "Rider"; break;
				case 219:
					$class = "Dragoon"; break;
				case 220:
					$class = "Vanguard Rider"; break;
				default:
					$class = "n/a"; break;
			}
			return $class;
		}
		
		function clanHallName($id){
			switch ($id){
				case 21:
					$name = "Fortress of Resistance"; break;
				case 22:
					$name = "Moonstone Hall"; break;
				case 23:
					$name = "Onyx Hall"; break;
				case 24:
					$name = "Topaz Hall"; break;
				case 25:
					$name = "Ruby Hall"; break;
				case 26:
					$name = "Crystal Hall"; break;
				case 27:
					$name = "Onyx Hall"; break;
				case 28:
					$name = "Sapphire Hall"; break;
				case 29:
					$name = "Moonstone Hall"; break;
				case 30:
					$name = "Emerald Hall"; break;
				case 31:
					$name = "The Atramental Barracks"; break;
				case 32:
					$name = "The Scarlet Barracks"; break;
				case 33:
					$name = "The Viridian Barracks"; break;
				case 34:
					$name = "Devastated Castle"; break;
				case 35:
					$name = "Bandit Stronghold"; break;
				case 36:
					$name = "The Golden Chamber"; break;
				case 37:
					$name = "The Silver Chamber"; break;
				case 38:
					$name = "The Mithril Chamber"; break;
				case 39:
					$name = "Silver Manor"; break;
				case 40:
					$name = "Gold Manor"; break;
				case 41:
					$name = "The Bronze Chamber"; break;
				case 42:
					$name = "The Golden Chamber"; break;
				case 43:
					$name = "The Silver Chamber"; break;
				case 44:
					$name = "The Mithril Chamber"; break;
				case 45:
					$name = "The Bronze Chamber"; break;
				case 46:
					$name = "Silver Manor"; break;
				case 47:
					$name = "Moonstone Hall"; break;
				case 48:
					$name = "Onyx Hall"; break;
				case 49:
					$name = "Emerald Hall"; break;
				case 50:
					$name = "Sapphire Hall"; break;
				case 51:
					$name = "Mont Chamber"; break;
				case 52:
					$name = "Astaire Chamber"; break;
				case 53:
					$name = "Aria Chamber"; break;
				case 54:
					$name = "Yiana Chamber"; break;
				case 55:
					$name = "Roien Chamber"; break;
				case 56:
					$name = "Luna Chamber"; break;
				case 57:
					$name = "Traban Chamber"; break;
				case 58:
					$name = "Eisen Hall"; break;
				case 59:
					$name = "Heavy Metal Hall"; break;
				case 60:
					$name = "Molten Ore Hall"; break;
				case 61:
					$name = "Titan Hall"; break;
				case 62:
					$name = "Rainbow Springs"; break;
				case 63:
					$name = "Beast Farm"; break;
				case 64:
					$name = "Fortress of the Dead"; break;
				case 65:
					$name = "Emerald Hall"; break;
				case 66:
					$name = "Crystal Hall"; break;
				case 67:
					$name = "Sapphire Hall"; break;
				case 68:
					$name = "Aquamarine Hall"; break;
				case 69:
					$name = "Blue Barracks"; break;
				case 70:
					$name = "Brown Barracks"; break;
				case 71:
					$name = "Yellow Barracks"; break;
				case 72:
					$name = "White Barracks"; break;
				case 73:
					$name = "Black Barracks"; break;
				case 74:
					$name = "Green Barracks"; break;
				case 186:
					$name = "Orchid Hall 1"; break;
				case 187:
					$name = "Ellia Hall 1"; break;
				case 188:
					$name = "Laurell Hall 1"; break;
				case 189:
					$name = "Orchid Hall 2"; break;
				case 190:
					$name = "Ellia Hall 2"; break;
				case 191:
					$name = "Laurell Hall 2"; break;
				case 192:
					$name = "Orchid Hall 3"; break;
				case 193:
					$name = "Ellia Hall 3"; break;
				case 194:
					$name = "Laurell Hall 3"; break;
				default:
					$name = "n/a"; break;
			}
			return $name;
		}
		
		protected function quest_name($quest_id){
			switch ($quest_id){
				case 1:
					$quest_name = "_letters_of_love"; break;
				case 2:
					$quest_name = "_what_women_want"; break;
				case 3:
					$quest_name = "_will_the_seal_be_broken"; break;
				case 4:
					$quest_name = "_long_live_the_paagrio_lord"; break;
				case 5:
					$quest_name = "_miners_favor"; break;
				case 6:
					$quest_name = "_step_into_the_future"; break;
				case 7:
					$quest_name = "_a_trip_begins"; break;
				case 8:
					$quest_name = "_an_adventure_begins"; break;
				case 9:
					$quest_name = "_into_the_city_of_humans"; break;
				case 10:
					$quest_name = "_into_the_world"; break;
				case 11:
					$quest_name = "_secret_meeting_with_ketra_orcs"; break;
				case 12:
					$quest_name = "_secret_meeting_with_varka_silenos"; break;
				case 13:
					$quest_name = "_parcel_delivery"; break;
				case 14:
					$quest_name = "_whereabouts_of_the_archaeologist"; break;
				case 15:
					$quest_name = "_sweet_whispers"; break;
				case 16:
					$quest_name = "_the_coming_darkness"; break;
				case 17:
					$quest_name = "_light_and_darkness"; break;
				case 18:
					$quest_name = "_meeting_with_the_golden_ram"; break;
				case 19:
					$quest_name = "_go_to_the_pastureland"; break;
				case 20:
					$quest_name = "_bring_up_with_love"; break;
				case 21:
					$quest_name = "_hidden_truth"; break;
				case 22:
					$quest_name = "_tragedy_in_von_hellmann_forest"; break;
				case 23:
					$quest_name = "_lidias_heart"; break;
				case 24:
					$quest_name = "_inhabitants_of_the_forest_of_the_dead"; break;
				case 25:
					$quest_name = "_hiding_behind_the_truth"; break;
				case 27:
					$quest_name = "_chest_caught_with_a_bait_of_wind"; break;
				case 28:
					$quest_name = "_chest_caught_with_a_bait_of_icy_air"; break;
				case 29:
					$quest_name = "_chest_caught_with_a_bait_of_earth"; break;
				case 30:
					$quest_name = "_chest_caught_with_a_bait_of_fire"; break;
				case 31:
					$quest_name = "_secret_buried_in_the_swamp"; break;
				case 32:
					$quest_name = "_an_obvious_lie"; break;
				case 33:
					$quest_name = "_make_a_pair_of_dress_shoes"; break;
				case 34:
					$quest_name = "_in_search_of_cloth"; break;
				case 35:
					$quest_name = "_find_glittering_jewelry"; break;
				case 36:
					$quest_name = "_make_a_sewing_kit"; break;
				case 37:
					$quest_name = "_make_formal_wear"; break;
				case 38:
					$quest_name = "_dragon_fangs"; break;
				case 39:
					$quest_name = "_red-eyed_invaders"; break;
				case 40:
					$quest_name = "_a_special_order"; break;
				case 42:
					$quest_name = "_help_the_uncle"; break;
				case 43:
					$quest_name = "_help_the_sister"; break;
				case 44:
					$quest_name = "_help_the_son"; break;
				case 45:
					$quest_name = "_to_talking_island"; break;
				case 46:
					$quest_name = "_once_more_in_the_arms_of_the_mother_tree"; break;
				case 47:
					$quest_name = "_into_the_dark_forest"; break;
				case 48:
					$quest_name = "_to_the_immortal_plateau"; break;
				case 49:
					$quest_name = "_the_road_home"; break;
				case 50:
					$quest_name = "_lanoscos_special_bait"; break;
				case 51:
					$quest_name = "_ofulles_special_bait"; break;
				case 52:
					$quest_name = "_willies_special_bait"; break;
				case 53:
					$quest_name = "_linnaeus_special_bait"; break;
				case 70:
					$quest_name = "_succession_to_the_legend_phoenix_knight"; break;
				case 71:
					$quest_name = "_succession_to_the_legend_evas_templar"; break;
				case 72:
					$quest_name = "_succession_to_the_legend_sword_muse"; break;
				case 73:
					$quest_name = "_succession_to_the_legend_duelist"; break;
				case 74:
					$quest_name = "_succession_to_the_legend_dreadnoughts"; break;
				case 75:
					$quest_name = "_succession_to_the_legend_titan"; break;
				case 76:
					$quest_name = "_succession_to_the_legend_grand_khavatari"; break;
				case 77:
					$quest_name = "_succession_to_the_legend_dominator"; break;
				case 78:
					$quest_name = "_succession_to_the_legend_doomcryer"; break;
				case 79:
					$quest_name = "_succession_to_the_legend_adventurer"; break;
				case 80:
					$quest_name = "_succession_to_the_legend_wind_rider"; break;
				case 81:
					$quest_name = "_succession_to_the_legend_ghost_hunter"; break;
				case 82:
					$quest_name = "_succession_to_the_legend_sagittarius"; break;
				case 83:
					$quest_name = "_succession_to_the_legend_moonlight_sentinel"; break;
				case 84:
					$quest_name = "_succession_to_the_legend_ghost_sentinel"; break;
				case 85:
					$quest_name = "_succession_to_the_legend_cardinal"; break;
				case 86:
					$quest_name = "_succession_to_the_legend_hierophant"; break;
				case 87:
					$quest_name = "_succession_to_the_legend_evas_saint"; break;
				case 88:
					$quest_name = "_succession_to_the_legend_archmage"; break;
				case 89:
					$quest_name = "_succession_to_the_legend_mystic_muse"; break;
				case 90:
					$quest_name = "_succession_to_the_legend_storm_screamer"; break;
				case 91:
					$quest_name = "_succession_to_the_legend_arcana_lord"; break;
				case 92:
					$quest_name = "_succession_to_the_legend_elemental_master"; break;
				case 93:
					$quest_name = "_succession_to_the_legend_spectral_master"; break;
				case 94:
					$quest_name = "_succession_to_the_legend_soultaker"; break;
				case 95:
					$quest_name = "_succession_to_the_legend_hell_knight"; break;
				case 96:
					$quest_name = "_succession_to_the_legend_spectral_dancer"; break;
				case 97:
					$quest_name = "_succession_to_the_legend_shillien_templar"; break;
				case 98:
					$quest_name = "_succession_to_the_legend_shillien_saint"; break;
				case 99:
					$quest_name = "_succession_to_the_legend_fortune_seeker"; break;
				case 100:
					$quest_name = "_succession_to_the_legend_maestro"; break;
				case 101:
					$quest_name = "_sword_of_solidarity"; break;
				case 102:
					$quest_name = "_sea_of_spores_fever"; break;
				case 103:
					$quest_name = "_spirit_of_craftsman"; break;
				case 104:
					$quest_name = "_spirit_of_mirrors"; break;
				case 105:
					$quest_name = "_skirmish_with_the_orcs"; break;
				case 106:
					$quest_name = "_forgotten_truth"; break;
				case 107:
					$quest_name = "_merciless_punishment"; break;
				case 108:
					$quest_name = "_jumble_tumble_diamond_fuss"; break;
				case 115:
					$quest_name = "_the_other_side_of_truth"; break;
				case 118:
					$quest_name = "_to_lead_and_be_led"; break;
				case 119:
					$quest_name = "_last_imperial_prince"; break;
				case 127:
					$quest_name = "_fishing_specialists_request"; break;
				case 151:
					$quest_name = "_cure_for_fever_disease"; break;
				case 152:
					$quest_name = "_shards_of_golem"; break;
				case 153:
					$quest_name = "_deliver_goods"; break;
				case 154:
					$quest_name = "_sacrifice_to_the_sea"; break;
				case 155:
					$quest_name = "_find_sir_windawood"; break;
				case 156:
					$quest_name = "_millennium_love"; break;
				case 157:
					$quest_name = "_recover_smuggled_goods"; break;
				case 158:
					$quest_name = "_seed_of_evil"; break;
				case 159:
					$quest_name = "_protect_the_water_source"; break;
				case 160:
					$quest_name = "_nerupas_request"; break;
				case 161:
					$quest_name = "_fruit_of_the_mothertree"; break;
				case 162:
					$quest_name = "_curse_of_the_underground_fortress"; break;
				case 163:
					$quest_name = "_legacy_of_the_poet"; break;
				case 164:
					$quest_name = "_blood_fiend"; break;
				case 165:
					$quest_name = "_shilens_hunt"; break;
				case 166:
					$quest_name = "_mass_of_darkness"; break;
				case 167:
					$quest_name = "_dwarven_kinship"; break;
				case 168:
					$quest_name = "_deliver_supplies"; break;
				case 169:
					$quest_name = "_offspring_of_nightmares"; break;
				case 170:
					$quest_name = "_dangerous_seduction"; break;
				case 171:
					$quest_name = "_acts_of_evil"; break;
				case 201:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 202:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 203:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 204:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 205:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 206:
					$quest_name = "_tutorial:_blue_gemstones"; break;
				case 211:
					$quest_name = "_trial_of_the_challenger"; break;
				case 212:
					$quest_name = "_trial_of_duty"; break;
				case 213:
					$quest_name = "_trial_of_the_seeker"; break;
				case 214:
					$quest_name = "_trial_of_the_scholar"; break;
				case 215:
					$quest_name = "_trial_of_the_pilgrim"; break;
				case 216:
					$quest_name = "_trial_of_the_guildsman"; break;
				case 217:
					$quest_name = "_testimony_of_trust"; break;
				case 218:
					$quest_name = "_testimony_of_life"; break;
				case 219:
					$quest_name = "_testimony_of_fate"; break;
				case 220:
					$quest_name = "_testimony_of_glory"; break;
				case 221:
					$quest_name = "_testimony_of_prosperity"; break;
				case 222:
					$quest_name = "_test_of_the_duelist"; break;
				case 223:
					$quest_name = "_test_of_the_champion"; break;
				case 224:
					$quest_name = "_test_of_sagittarius"; break;
				case 225:
					$quest_name = "_test_of_the_searcher"; break;
				case 226:
					$quest_name = "_test_of_the_healer"; break;
				case 227:
					$quest_name = "_test_of_the_reformer"; break;
				case 228:
					$quest_name = "_test_of_magus"; break;
				case 229:
					$quest_name = "_test_of_witchcraft"; break;
				case 230:
					$quest_name = "_test_of_the_summoner"; break;
				case 231:
					$quest_name = "_test_of_the_maestro"; break;
				case 232:
					$quest_name = "_test_of_the_lord"; break;
				case 233:
					$quest_name = "_test_of_the_war_spirit"; break;
				case 234:
					$quest_name = "_fates_whisper"; break;
				case 235:
					$quest_name = "_mimirs_elixir"; break;
				case 241:
					$quest_name = "_possessor_of_a_precious_soul_-_1"; break;
				case 242:
					$quest_name = "_possessor_of_a_precious_soul_-_2"; break;
				case 246:
					$quest_name = "_possessor_of_a_precious_soul_-_3"; break;
				case 247:
					$quest_name = "_possessor_of_a_precious_soul_-_4"; break;
				case 255:
					$quest_name = "_tutorial"; break;
				case 257:
					$quest_name = "_the_guard_is_busy"; break;
				case 258:
					$quest_name = "_bring_wolf_pelts"; break;
				case 259:
					$quest_name = "_ranchers_plea"; break;
				case 260:
					$quest_name = "_hunt_the_orcs"; break;
				case 261:
					$quest_name = "_collectors_dream"; break;
				case 262:
					$quest_name = "_trade_with_the_ivory_tower"; break;
				case 263:
					$quest_name = "_orc_subjugation"; break;
				case 264:
					$quest_name = "_keen_claws"; break;
				case 265:
					$quest_name = "_chains_of_slavery"; break;
				case 266:
					$quest_name = "_pleas_of_pixies"; break;
				case 267:
					$quest_name = "_wrath_of_verdure"; break;
				case 271:
					$quest_name = "_proof_of_valor"; break;
				case 272:
					$quest_name = "_wrath_of_ancestors"; break;
				case 273:
					$quest_name = "_invaders_of_the_holy_land"; break;
				case 274:
					$quest_name = "_skirmish_with_the_werewolves"; break;
				case 275:
					$quest_name = "_dark_winged_spies"; break;
				case 276:
					$quest_name = "_totem_of_the_hestui"; break;
				case 277:
					$quest_name = "_gatekeepers_offering"; break;
				case 282:
					$quest_name = "_a_day_of_kindness_and_caring"; break;
				case 291:
					$quest_name = "_revenge_of_the_redbonnet"; break;
				case 292:
					$quest_name = "_brigands_sweep"; break;
				case 293:
					$quest_name = "_the_hidden_veins"; break;
				case 294:
					$quest_name = "_covert_business"; break;
				case 295:
					$quest_name = "_dreaming_of_the_skies"; break;
				case 296:
					$quest_name = "_tarantulas_spider_silk"; break;
				case 297:
					$quest_name = "_gatekeepers_favor"; break;
				case 298:
					$quest_name = "_lizardmens_conspiracy"; break;
				case 299:
					$quest_name = "_gather_ingredients_for_pie"; break;
				case 300:
					$quest_name = "_hunting_leto_lizardman"; break;
				case 303:
					$quest_name = "_collect_arrowheads"; break;
				case 306:
					$quest_name = "_crystals_of_fire_and_ice"; break;
				case 313:
					$quest_name = "_collect_spores"; break;
				case 316:
					$quest_name = "_destroy_plague_carriers"; break;
				case 317:
					$quest_name = "_catch_the_wind"; break;
				case 319:
					$quest_name = "_scent_of_death"; break;
				case 320:
					$quest_name = "_bones_tell_the_future"; break;
				case 324:
					$quest_name = "_sweetest_venom"; break;
				case 325:
					$quest_name = "_grim_collector"; break;
				case 326:
					$quest_name = "_vanquish_remnants"; break;
				case 327:
					$quest_name = "_recover_the_farmland"; break;
				case 328:
					$quest_name = "_sense_for_business"; break;
				case 329:
					$quest_name = "_curiosity_of_a_dwarf"; break;
				case 330:
					$quest_name = "_adept_of_taste"; break;
				case 331:
					$quest_name = "_arrow_of_vengeance"; break;
				case 333:
					$quest_name = "_hunt_of_the_black_lion"; break;
				case 334:
					$quest_name = "_the_wishing_potion"; break;
				case 335:
					$quest_name = "_song_of_the_hunter"; break;
				case 336:
					$quest_name = "_coin_of_magic"; break;
				case 337:
					$quest_name = "_audience_with_the_land_dragon"; break;
				case 338:
					$quest_name = "_alligator_hunter"; break;
				case 340:
					$quest_name = "_subjugation_of_lizardmen"; break;
				case 341:
					$quest_name = "_hunting_for_wild_beasts"; break;
				case 343:
					$quest_name = "_under_the_shadow_of_the_ivory_tower"; break;
				case 344:
					$quest_name = "_1000_years_the_end_of_lamentation"; break;
				case 345:
					$quest_name = "_method_to_raise_the_dead"; break;
				case 347:
					$quest_name = "_go_get_the_calculator"; break;
				case 348:
					$quest_name = "_an_arrogant_search"; break;
				case 350:
					$quest_name = "_enhance_your_weapon"; break;
				case 351:
					$quest_name = "_black_swan"; break;
				case 352:
					$quest_name = "_help_rood_raise_a_new_pet"; break;
				case 353:
					$quest_name = "_power_of_darkness"; break;
				case 354:
					$quest_name = "_conquest_of_alligator_island"; break;
				case 355:
					$quest_name = "_family_honor"; break;
				case 356:
					$quest_name = "_dig_up_the_sea_of_spores"; break;
				case 357:
					$quest_name = "_warehouse_keepers_ambition"; break;
				case 358:
					$quest_name = "_illegitimate_child_of_a_goddess"; break;
				case 359:
					$quest_name = "_for_sleepless_deadmen"; break;
				case 360:
					$quest_name = "_plunder_their_supplies"; break;
				case 362:
					$quest_name = "_bards_mandolin"; break;
				case 363:
					$quest_name = "_sorrowful_sound_of_flute"; break;
				case 364:
					$quest_name = "_jovial_accordion"; break;
				case 365:
					$quest_name = "_devils_legacy"; break;
				case 366:
					$quest_name = "_silver_haired_shaman"; break;
				case 367:
					$quest_name = "_electrifying_recharge"; break;
				case 368:
					$quest_name = "_trespassing_into_the_sacred_area"; break;
				case 369:
					$quest_name = "_collector_of_jewels"; break;
				case 370:
					$quest_name = "_a_wiseman_sows_seeds"; break;
				case 371:
					$quest_name = "_shriek_of_ghosts"; break;
				case 372:
					$quest_name = "_legacy_of_insolence"; break;
				case 373:
					$quest_name = "_supplier_of_reagents"; break;
				case 374:
					$quest_name = "_whisper_of_dreams_part_1"; break;
				case 375:
					$quest_name = "_whisper_of_dreams_part_2"; break;
				case 376:
					$quest_name = "_exploration_of_giants_cave_part_1"; break;
				case 377:
					$quest_name = "_exploration_of_giants_cave_part_2"; break;
				case 378:
					$quest_name = "_magnificent_feast"; break;
				case 379:
					$quest_name = "_fantasy_wine"; break;
				case 380:
					$quest_name = "_bring_out_the_flavor_of_ingredients"; break;
				case 381:
					$quest_name = "_lets_become_a_royal_member"; break;
				case 382:
					$quest_name = "_kails_magic_coin"; break;
				case 383:
					$quest_name = "_searching_for_treasure"; break;
				case 384:
					$quest_name = "_warehouse_keepers_pastime"; break;
				case 385:
					$quest_name = "_yoke_of_the_past"; break;
				case 386:
					$quest_name = "_stolen_dignity"; break;
				case 401:
					$quest_name = "_path_to_a_warrior"; break;
				case 402:
					$quest_name = "_path_to_a_human_knight"; break;
				case 403:
					$quest_name = "_path_to_a_rogue"; break;
				case 404:
					$quest_name = "_path_to_a_human_wizard"; break;
				case 405:
					$quest_name = "_path_to_a_cleric"; break;
				case 406:
					$quest_name = "_path_to_an_elven_knight"; break;
				case 407:
					$quest_name = "_path_to_an_elven_scout"; break;
				case 408:
					$quest_name = "_path_to_an_elven_wizard"; break;
				case 409:
					$quest_name = "_path_to_an_elven_oracle"; break;
				case 410:
					$quest_name = "_path_to_a_palus_knight"; break;
				case 411:
					$quest_name = "_path_to_an_assassin"; break;
				case 412:
					$quest_name = "_path_to_a_dark_wizard"; break;
				case 413:
					$quest_name = "_path_to_a_shillien_oracle"; break;
				case 414:
					$quest_name = "_path_to_an_orc_raider"; break;
				case 415:
					$quest_name = "_path_to_a_monk"; break;
				case 416:
					$quest_name = "_path_to_an_orc_shaman"; break;
				case 417:
					$quest_name = "_path_to_become_a_scavenger"; break;
				case 418:
					$quest_name = "_path_to_an_artisan"; break;
				case 419:
					$quest_name = "_get_a_pet"; break;
				case 420:
					$quest_name = "_little_wing"; break;
				case 421:
					$quest_name = "_little_wings_big_adventure"; break;
				case 422:
					$quest_name = "_repent_your_sins"; break;
				case 426:
					$quest_name = "_quest_for_fishing_shot"; break;
				case 431:
					$quest_name = "_wedding_march"; break;
				case 432:
					$quest_name = "_birthday_party_song"; break;
				case 452:
					$quest_name = "_finding_the_lost_soldiers"; break;
				case 453:
					$quest_name = "_not_strong_enough_alone"; break;
				case 454:
					$quest_name = "_completely_lost"; break;
				case 459:
					$quest_name = "_the_villain_of_the_underground_mine_teredor"; break;
				case 460:
					$quest_name = "_precious_research_material"; break;
				case 465:
					$quest_name = "_we_are_friends"; break;
				case 466:
					$quest_name = "_placing_my_small_power"; break;
				case 468:
					$quest_name = "_be_lost_in_the_mysterious_scent"; break;
				case 469:
					$quest_name = "_suspicious_gardener"; break;
				case 471:
					$quest_name = "_breaking_through_the_emerald_square"; break;
				case 472:
					$quest_name = "_challenge_steam_corridor"; break;
				case 473:
					$quest_name = "_in_the_coral_garden"; break;
				case 493:
					$quest_name = "_kicking_out_unwelcome_guests"; break;
				case 494:
					$quest_name = "_incarnation_of_greed_zellaka_group"; break;
				case 495:
					$quest_name = "_incarnation_of_jealousy_pelline_group"; break;
				case 496:
					$quest_name = "_incarnation_of_gluttony_kalios_group"; break;
				case 497:
					$quest_name = "_incarnation_of_greed_zellaka_solo"; break;
				case 498:
					$quest_name = "_incarnation_of_jealousy_pelline_solo"; break;
				case 499:
					$quest_name = "_incarnation_of_gluttony_kalios_solo"; break;
				case 500:
					$quest_name = "_brothers_bound_in_chains"; break;
				case 501:
					$quest_name = "_proof_of_clan_alliance"; break;
				case 502:
					$quest_name = "_brothers_bound_in_chains"; break;
				case 503:
					$quest_name = "_pursuit_of_clan_ambition"; break;
				case 504:
					$quest_name = "_competition_for_the_bandit_stronghold"; break;
				case 505:
					$quest_name = "_blood_offering"; break;
				case 508:
					$quest_name = "_a_clan_s_reputation"; break;
				case 509:
					$quest_name = "_the_clan_s_prestigue"; break;
				case 511:
					$quest_name = "_awl_under_foot"; break;
				case 512:
					$quest_name = "_blade_under_foot"; break;
				case 513:
					$quest_name = "_red_libra_request_-_field_of_silence"; break;
				case 514:
					$quest_name = "_red_libra_request_-_field_of_whispers"; break;
				case 515:
					$quest_name = "_red_libra_request_-_plains_of_the_lizardmen"; break;
				case 516:
					$quest_name = "_red_libra_request_-_sel_mahum_training_grounds"; break;
				case 517:
					$quest_name = "_red_libra_request_-_alligator_island"; break;
				case 518:
					$quest_name = "_red_libra_request_-_tanor_canyon"; break;
				case 519:
					$quest_name = "_request_from_the_red_libra_guild_-_ivory_tower_crater"; break;
				case 520:
					$quest_name = "_request_from_the_red_libra_guild_-_breka's_stronghold"; break;
				case 521:
					$quest_name = "_request_from_the_red_libra_guild_-_isle_of_prayer"; break;
				case 525:
					$quest_name = "_snow's_plea_-_altar_of_evil"; break;
				case 526:
					$quest_name = "_snow's_plea_-_fairy_settlement"; break;
				case 527:
					$quest_name = "_snow's_plea_-_isle_of_souls"; break;
				case 528:
					$quest_name = "_snow's_plea_-_seal_of_shilen"; break;
				case 529:
					$quest_name = "_regular_barrier_maintenance"; break;
				case 530:
					$quest_name = "_snow's_plea_-_raider's_crossroads"; break;
				case 531:
					$quest_name = "_snow's_plea_-_silent_valley"; break;
				case 532:
					$quest_name = "_snow's_plea_-_hellbound"; break;
				case 533:
					$quest_name = "_snow's_plea_-_enchanted_valley"; break;
				case 534:
					$quest_name = "_snow's_plea_-_giant's_cave"; break;
				case 535:
					$quest_name = "_snow's_plea_-_garden_of_spirits"; break;
				case 536:
					$quest_name = "_snow's_plea_-_atelia_fortress"; break;
				case 537:
					$quest_name = "_snow's_plea_-_superion"; break;
				case 538:
					$quest_name = "_snow's_plea_-_shadow_of_the_mother_tree"; break;
				case 539:
					$quest_name = "_snow's_plea_-_atelia_refinery"; break;
				case 540:
					$quest_name = "_snow's_plea_-_sea_of_spores"; break;
				case 541:
					$quest_name = "_snow's_plea_-_field_of_silence"; break;
				case 542:
					$quest_name = "_snow's_plea_-_field_of_whispers"; break;
				case 543:
					$quest_name = "_snow's_plea_-_kartia's_labyrinth"; break;
				case 544:
					$quest_name = "_snow's_plea_-_crystal_prison_(baylor)"; break;
				case 545:
					$quest_name = "_snow's_plea_-_nightmare_kamaloka"; break;
				case 546:
					$quest_name = "_snow's_plea_-_embryo_command_post"; break;
				case 547:
					$quest_name = "_snow's_plea_-_altar_of_shilen"; break;
				case 548:
					$quest_name = "_snow's_plea_-_fallen_emperor's_throne"; break;
				case 549:
					$quest_name = "_snow's_plea_-_fall_of_etina"; break;
				case 550:
					$quest_name = "_snow's_plea_-_krofin's_nest"; break;
				case 551:
					$quest_name = "_olympiad_starter"; break;
				case 553:
					$quest_name = "_olympiad_undefeated"; break;
				case 555:
					$quest_name = "_red_libra_request_-_atelia_refinery"; break;
				case 556:
					$quest_name = "_red_libra_request_-_fallen_emperor's_throne"; break;
				case 557:
					$quest_name = "_red_libra_request_-_fall_of_etina"; break;
				case 558:
					$quest_name = "_red_libra_request_-_sea_of_spores"; break;
				case 559:
					$quest_name = "_request_from_the_red_libra_guild_-_krofin's_nest"; break;
				case 560:
					$quest_name = "_how_to_overcome_fear"; break;
				case 561:
					$quest_name = "_basic_mission_harnak_underground_ruins"; break;
				case 564:
					$quest_name = "_basic_mission_kartias_labyrinth_solo"; break;
				case 567:
					$quest_name = "_basic_mission_isle_of_souls"; break;
				case 568:
					$quest_name = "_special_mission:_nornil's_cave"; break;
				case 569:
					$quest_name = "_basic_mission:_seal_of_shilen"; break;
				case 570:
					$quest_name = "_special_mission:_kartia's_labyrinth_(party)"; break;
				case 571:
					$quest_name = "_special_mission:_proof_of_unity_(field_raid)"; break;
				case 572:
					$quest_name = "_special_mission:_proof_of_courage_(field_raid)"; break;
				case 573:
					$quest_name = "_special_mission:_proof_of_strength_(field_raid)"; break;
				case 574:
					$quest_name = "_special_mission:_nornil's_garden"; break;
				case 576:
					$quest_name = "_special_mission:_defeat_spezion"; break;
				case 577:
					$quest_name = "_basic_mission:_silent_valley"; break;
				case 578:
					$quest_name = "_basic_mission:_cemetery"; break;
				case 580:
					$quest_name = "_beyond_the_memories"; break;
				case 585:
					$quest_name = "_can't_go_against_the_time"; break;
				case 586:
					$quest_name = "_mutated_creatures"; break;
				case 587:
					$quest_name = "_more_aggressive_operation"; break;
				case 588:
					$quest_name = "_head-on_crash"; break;
				case 589:
					$quest_name = "_a_secret_change"; break;
				case 590:
					$quest_name = "_to_each_their_own"; break;
				case 591:
					$quest_name = "_great_ambitions"; break;
				case 592:
					$quest_name = "_snow's_plea_-_monsters_from_three_areas"; break;
				case 593:
					$quest_name = "_basic_mission:_pagan_temple"; break;
				case 594:
					$quest_name = "_basic_mission:_dimensional_rift"; break;
				case 595:
					$quest_name = "_special_mission:_raider's_crossroads"; break;
				case 596:
					$quest_name = "_special_mission:_defeat_baylor"; break;
				case 599:
					$quest_name = "_demons_and_dimensional_energy"; break;
				case 600:
					$quest_name = "_key_to_the_refining_process"; break;
				case 601:
					$quest_name = "_watching_eyes"; break;
				case 602:
					$quest_name = "_shadow_of_light"; break;
				case 603:
					$quest_name = "_daimon_the_white-eyed_-_part_1"; break;
				case 604:
					$quest_name = "_daimon_the_white-eyed_-_part_2"; break;
				case 605:
					$quest_name = "_alliance_with_ketra_orcs"; break;
				case 606:
					$quest_name = "_war_with_varka_silenos"; break;
				case 607:
					$quest_name = "_prove_your_courage"; break;
				case 608:
					$quest_name = "_slay_the_enemy_commander"; break;
				case 609:
					$quest_name = "_magical_power_of_water_-_part_1"; break;
				case 610:
					$quest_name = "_magical_power_of_water_-_part_2"; break;
				case 611:
					$quest_name = "_alliance_with_varka_silenos"; break;
				case 612:
					$quest_name = "_war_with_ketra_orcs"; break;
				case 613:
					$quest_name = "_prove_your_courage"; break;
				case 614:
					$quest_name = "_slay_the_enemy_commander"; break;
				case 615:
					$quest_name = "_magical_power_of_fire_-_part_1"; break;
				case 616:
					$quest_name = "_magical_power_of_fire_-_part_2"; break;
				case 617:
					$quest_name = "_gather_the_flames"; break;
				case 618:
					$quest_name = "_into_the_flame"; break;
				case 619:
					$quest_name = "_relics_of_the_old_empire"; break;
				case 620:
					$quest_name = "_four_goblets"; break;
				case 621:
					$quest_name = "_egg_delivery"; break;
				case 622:
					$quest_name = "_delivery_of_special_liquor"; break;
				case 623:
					$quest_name = "_the_finest_food"; break;
				case 624:
					$quest_name = "_the_finest_ingredients_-_part_1"; break;
				case 625:
					$quest_name = "_the_finest_ingredients_-_part_2"; break;
				case 626:
					$quest_name = "_a_dark_twilight"; break;
				case 627:
					$quest_name = "_heart_in_search_of_power"; break;
				case 628:
					$quest_name = "_hunt_of_the_golden_ram_mercenary_force"; break;
				case 629:
					$quest_name = "_clean_up_the_swamp_of_screams"; break;
				case 630:
					$quest_name = "_pirate_treasure_hunt"; break;
				case 631:
					$quest_name = "_delicious_top_choice_meat"; break;
				case 632:
					$quest_name = "_necromancers_request"; break;
				case 633:
					$quest_name = "_in_the_forgotten_village"; break;
				case 634:
					$quest_name = "_in_search_of_fragments_of_dimension"; break;
				case 635:
					$quest_name = "_in_the_dimension_rift"; break;
				case 636:
					$quest_name = "_truth_beyond_the_gate"; break;
				case 637:
					$quest_name = "_through_the_gate_once_more"; break;
				case 638:
					$quest_name = "_seekers_of_the_holy_grail"; break;
				case 640:
					$quest_name = "_the_zero_hour"; break;
				case 642:
					$quest_name = "_a_powerful_primeval_creature"; break;
				case 643:
					$quest_name = "_rise_and_fall_of_the_elroki_tribe"; break;
				case 647:
					$quest_name = "_influx_of_the_machines"; break;
				case 648:
					$quest_name = "_an_ice_merchant_dream"; break;
				case 655:
					$quest_name = "_a_grand_plan_for_taming_wild_beasts"; break;
				case 662:
					$quest_name = "_a_game_of_cards"; break;
				case 663:
					$quest_name = "_seductive_whispers"; break;
				case 664:
					$quest_name = "_quarrels_time"; break;
				case 665:
					$quest_name = "_basic_training_for_hunter_guild_member"; break;
				case 666:
					$quest_name = "_knowledgeable_hunter_guild_member"; break;
				case 668:
					$quest_name = "_fight_with_the_giants"; break;
				case 669:
					$quest_name = "_intense_fight_against_dragon"; break;
				case 670:
					$quest_name = "_defeating_the_lord_of_seed"; break;
				case 671:
					$quest_name = "_path_to_finding_the_past"; break;
				case 672:
					$quest_name = "_embryo_the_archenemy"; break;
				case 673:
					$quest_name = "_beleth'_ambition"; break;
				case 674:
					$quest_name = "_changes_in_the_shadow_of_the_mother_tree"; break;
				case 675:
					$quest_name = "_what_the_thread_of_the_past_shows"; break;
				case 682:
					$quest_name = "_the_strong_in_the_closed_space"; break;
				case 683:
					$quest_name = "_advent_of_krofin_subspecies"; break;
				case 684:
					$quest_name = "_disturbed_fields"; break;
				case 688:
					$quest_name = "_defeat_the_elrokian_raiders"; break;
				case 690:
					$quest_name = "_|attack|_begin_alliance_base_defense_-_1"; break;
				case 691:
					$quest_name = "_|attack|_begin_alliance_base_defense_-_2"; break;
				case 692:
					$quest_name = "_|attack|_begin_alliance_base_defense_-_3"; break;
				case 693:
					$quest_name = "_|defense|_protecting_military_power_-_1"; break;
				case 694:
					$quest_name = "_|defense|_protecting_military_power_-_2"; break;
				case 695:
					$quest_name = "_|defense|_protecting_military_power_-_3"; break;
				case 696:
					$quest_name = "_|support|_defense_battery_crafting_support_-_1"; break;
				case 697:
					$quest_name = "_|support|_defense_battery_crafting_support_-_2"; break;
				case 698:
					$quest_name = "_|support|_defense_battery_crafting_support_-_3"; break;
				case 699:
					$quest_name = "_|attack|_sabotage_embryo_soldier_-_1"; break;
				case 700:
					$quest_name = "_|attack|_sabotage_embryo_soldier_-_2"; break;
				case 701:
					$quest_name = "_|attack|_sabotage_embryo_soldier_-_3"; break;
				case 702:
					$quest_name = "_|defense|_gracian_soldier_support_-_1"; break;
				case 703:
					$quest_name = "_|defense|_gracian_soldier_support_-_2"; break;
				case 704:
					$quest_name = "_|defense|_gracian_soldier_support_-_3"; break;
				case 705:
					$quest_name = "_|support|_defense_battery_cannonball_crafting_-_1"; break;
				case 706:
					$quest_name = "_|support|_defense_battery_cannonball_crafting_-_2"; break;
				case 707:
					$quest_name = "_|support|_defense_battery_cannonball_crafting_-_3"; break;
				case 708:
					$quest_name = "_|attack|_neutralize_embryo_-_1"; break;
				case 709:
					$quest_name = "_|attack|_neutralize_embryo_-_2"; break;
				case 710:
					$quest_name = "_|attack|_neutralize_embryo_-_3"; break;
				case 711:
					$quest_name = "_|defense|_all-out_battle_-_1"; break;
				case 712:
					$quest_name = "_|defense|_all-out_battle_-_2"; break;
				case 713:
					$quest_name = "_|defense|_all-out_battle_-_3"; break;
				case 714:
					$quest_name = "_|support|_installing_defense_battery_-_1"; break;
				case 715:
					$quest_name = "_|support|_installing_defense_battery_-_2"; break;
				case 716:
					$quest_name = "_|support|_installing_defense_battery_-_3"; break;
				case 717:
					$quest_name = "_|attack|_eliminate_embryo_captain_-_1"; break;
				case 718:
					$quest_name = "_|attack|_eliminate_embryo_captain_-_2"; break;
				case 719:
					$quest_name = "_|attack|_eliminate_embryo_captain_-_3"; break;
				case 720:
					$quest_name = "_|defense|_eliminate_embryo_captain_-_1"; break;
				case 721:
					$quest_name = "_|defense|_eliminate_embryo_captain_-_2"; break;
				case 722:
					$quest_name = "_|defense|_eliminate_embryo_captain_-_3"; break;
				case 723:
					$quest_name = "_|support|_eliminate_enemy_with_defense_battery_-_1"; break;
				case 724:
					$quest_name = "_|support|_eliminate_enemy_with_defense_battery_-_2"; break;
				case 725:
					$quest_name = "_|support|_eliminate_enemy_with_defense_battery_-_3"; break;
				case 726:
					$quest_name = "_light_within_the_darkness"; break;
				case 727:
					$quest_name = "_hope_within_the_darkness"; break;
				case 729:
					$quest_name = "_|attack|_impede_kain"; break;
				case 730:
					$quest_name = "_|defense|_impede_kain"; break;
				case 731:
					$quest_name = "_|support|_impede_kain"; break;
				case 732:
					$quest_name = "_red_libra's_request_-_kartia's_labyrinth"; break;
				case 733:
					$quest_name = "_red_libra_request_-_crystal_prison_(baylor)"; break;
				case 734:
					$quest_name = "_red_libra_request_-_nightmare_kamaloka"; break;
				case 735:
					$quest_name = "_red_libra_request_-_embryo_command_post"; break;
				case 736:
					$quest_name = "_red_libra_request_-_altar_of_shilen"; break;
				case 737:
					$quest_name = "_a_sword_hidden_in_a_smile"; break;
				case 738:
					$quest_name = "_dimensional_exploration_of_the_unworldly_visitors"; break;
				case 743:
					$quest_name = "_at_the_altar_of_oblivion"; break;
				case 749:
					$quest_name = "_ties_with_the_guardians"; break;
				case 752:
					$quest_name = "_uncover_the_secret"; break;
				case 753:
					$quest_name = "_reacting_to_a_crisis"; break;
				case 754:
					$quest_name = "_assisting_the_rebel_forces"; break;
				case 755:
					$quest_name = "_in_need_of_petras"; break;
				case 756:
					$quest_name = "_top_quality_petra"; break;
				case 757:
					$quest_name = "_triols_movement"; break;
				case 758:
					$quest_name = "_the_fallen_kings_men"; break;
				case 759:
					$quest_name = "_the_dwarven_nightmare_continues"; break;
				case 760:
					$quest_name = "_block_the_exit"; break;
				case 773:
					$quest_name = "_to_calm_the_flood"; break;
				case 774:
					$quest_name = "_dreaming_of_peace"; break;
				case 775:
					$quest_name = "_retrieving_the_chaos_fragment"; break;
				case 776:
					$quest_name = "_slay_dark_lord_ekimus"; break;
				case 777:
					$quest_name = "_slay_dark_lord_tiat"; break;
				case 778:
					$quest_name = "_operation_roaring_flame"; break;
				case 779:
					$quest_name = "_utilize_the_darkness_seed_of_destruction"; break;
				case 780:
					$quest_name = "_utilize the_darkness_-_seed_of_infinity"; break;
				case 781:
					$quest_name = "_utilize the_darkness_-_seed_of_annihilation"; break;
				case 782:
					$quest_name = "_utilize the_darkness_-_seed_of_hellfire"; break;
				case 783:
					$quest_name = "_vestige_of_the_magic_power"; break;
				case 790:
					$quest_name = "_obtaining_ferins_trust"; break;
				case 792:
					$quest_name = "_the_superion_giants"; break;
				case 816:
					$quest_name = "_plans_to_repair_the_stronghold"; break;
				case 823:
					$quest_name = "_disappeared_race_new_fairy"; break;
				case 824:
					$quest_name = "_command_post_raid"; break;
				case 826:
					$quest_name = "_in_search_of_the_secret_weapon"; break;
				case 827:
					$quest_name = "_einhasads_order"; break;
				case 828:
					$quest_name = "_evas_blessing"; break;
				case 829:
					$quest_name = "_maphrs_salvation"; break;
				case 830:
					$quest_name = "_the_way_of_the_giants_pawn"; break;
				case 831:
					$quest_name = "_sayhas_scheme"; break;
				case 833:
					$quest_name = "_devils_treasure_tauti"; break;
				case 834:
					$quest_name = "_against_dragonclaw"; break;
				case 835:
					$quest_name = "_pitiable_melisa"; break;
				case 836:
					$quest_name = "_request_from_the_blackbird_clan"; break;
				case 837:
					$quest_name = "_request_from_the_giant _trackers"; break;
				case 838:
					$quest_name = "_request_from_the_mother_tree_guardians"; break;
				case 839:
					$quest_name = "_request_from_the_unworldly_visitors"; break;
				case 840:
					$quest_name = "_request_from_the_kingdom's_royal_guard"; break;
				case 842:
					$quest_name = "_captive_demons"; break;
				case 843:
					$quest_name = "_giant_evolution_control"; break;
				case 844:
					$quest_name = "_giants_treasure"; break;
				case 845:
					$quest_name = "_sabotage_the_embryo_supplies"; break;
				case 846:
					$quest_name = "_building_up_strength"; break;
				case 861:
					$quest_name = "_kain's_choice"; break;
				case 863:
					$quest_name = "_red_libra_request_-_shadow_of_the_mother_tree"; break;
				case 901:
					$quest_name = "_how_lavasauruses_are_made"; break;
				case 903:
					$quest_name = "_the_call_of_antharas"; break;
				case 905:
					$quest_name = "_refined_dragon_blood"; break;
				case 906:
					$quest_name = "_the_call_of_valakas"; break;
				case 910:
					$quest_name = "_request_from_the_red_libra_guild_-_basic"; break;
				case 911:
					$quest_name = "_request_from_the_red_libra_guild_-_intermediate"; break;
				case 912:
					$quest_name = "_request_from_the_red_libra_guild_-_advanced"; break;
				case 913:
					$quest_name = "_request_from_the_red_libra_guild_-_super_advanced"; break;
				case 914:
					$quest_name = "_request_from_the_red_libra_guild_-_lv._5"; break;
				case 915:
					$quest_name = "_red_libra_request_-_enchanted_valley"; break;
				case 916:
					$quest_name = "_red_libra_request_-_giant's_cave"; break;
				case 917:
					$quest_name = "_red_libra_request_-_garden_of_spirits"; break;
				case 918:
					$quest_name = "_red_libra_request_-_atelia_fortress"; break;
				case 919:
					$quest_name = "_red_libra_request_-_superion"; break;
				case 923:
					$quest_name = "_shinedust_extraction"; break;
				case 924:
					$quest_name = "_recovered_giants"; break;
				case 926:
					$quest_name = "_30_day_search_operation"; break;
				case 928:
					$quest_name = "_100_day_subjugation_operation"; break;
				case 929:
					$quest_name = "_seeker_rescue"; break;
				case 930:
					$quest_name = "_disparaging_the_phantoms"; break;
				case 931:
					$quest_name = "_memories_of_the_wind"; break;
				case 932:
					$quest_name = "_sayha's_energy"; break;
				case 933:
					$quest_name = "_exploring_the_west_wing_of_the_dungeon_of_abyss"; break;
				case 935:
					$quest_name = "_exploring_the_east_wing_of_the_dungeon_of_abyss"; break;
				case 937:
					$quest_name = "_to_revive_the_fishing_guild"; break;
				case 938:
					$quest_name = "_deton's_second_request"; break;
				case 939:
					$quest_name = "_deton's_third_request"; break;
				case 940:
					$quest_name = "_deton's_fourth_request"; break;
				case 941:
					$quest_name = "_deton's_fifth_request"; break;
				case 942:
					$quest_name = "_deton's_sixth_request"; break;
				case 943:
					$quest_name = "_deton's_seventh_request"; break;
				case 944:
					$quest_name = "_deton's_eighth_request"; break;
				case 945:
					$quest_name = "_deton's_ninth_request"; break;
				case 946:
					$quest_name = "_deton's_tenth_request"; break;
				case 985:
					$quest_name = "_adventure_guilds_special_request_lv1"; break;
				case 986:
					$quest_name = "_adventure_guilds_special_request_lv2"; break;
				case 987:
					$quest_name = "_adventure_guilds_special_request_lv3"; break;
				case 988:
					$quest_name = "_adventure_guilds_special_request_lv4"; break;
				case 989:
					$quest_name = "_adventure_guilds_special_request_lv5"; break;
				case 1900:
					$quest_name = "_storm_isle_secret_spot"; break;
				case 1901:
					$quest_name = "_storm_isle_furtive_deal"; break;
				case 10282:
					$quest_name = "_to_the_seed_of_annihilation"; break;
				case 10283:
					$quest_name = "_request_of_ice_merchant"; break;
				case 10284:
					$quest_name = "_acquisition_of_divine_sword"; break;
				case 10285:
					$quest_name = "_meeting_sirra"; break;
				case 10286:
					$quest_name = "_reunion_with_sirra"; break;
				case 10287:
					$quest_name = "_story_of_those_left"; break;
				case 10290:
					$quest_name = "_a_trip_begins"; break;
				case 10291:
					$quest_name = "_more_experience"; break;
				case 10292:
					$quest_name = "_secret_garden"; break;
				case 10293:
					$quest_name = "_death_mysteries"; break;
				case 10294:
					$quest_name = "_spore_infested_place"; break;
				case 10295:
					$quest_name = "_respectfor_graves"; break;
				case 10296:
					$quest_name = "_lets_pay_respects_to_our_fallen_brethren"; break;
				case 10297:
					$quest_name = "_grand_opening_come_to_our_pub"; break;
				case 10298:
					$quest_name = "_wasteland_queen"; break;
				case 10299:
					$quest_name = "_get_incredible_power"; break;
				case 10300:
					$quest_name = "_exploring_the_cruma_tower"; break;
				case 10301:
					$quest_name = "_not_so_silent_valley"; break;
				case 10303:
					$quest_name = "_crossroads_between_light_and_darkness"; break;
				case 10355:
					$quest_name = "_blacksmiths_soul1"; break;
				case 10356:
					$quest_name = "_blacksmiths_soul2"; break;
				case 10373:
					$quest_name = "_exploring_the_dimension_sealing_the_dimension"; break;
				case 10381:
					$quest_name = "_to_the_seed_of_hellfire"; break;
				case 10383:
					$quest_name = "_fergasons_offer"; break;
				case 10386:
					$quest_name = "_mysterious_journey"; break;
				case 10387:
					$quest_name = "_soulless_one"; break;
				case 10388:
					$quest_name = "_conspiracy_behind_door"; break;
				case 10389:
					$quest_name = "_the_voice_of_authority"; break;
				case 10418:
					$quest_name = "_the_immortal_pirate_king"; break;
				case 10423:
					$quest_name = "_embryo_stronghold_raid"; break;
				case 10445:
					$quest_name = "_an_impending_threat"; break;
				case 10446:
					$quest_name = "_hit_and_run"; break;
				case 10447:
					$quest_name = "_timing_is_everything"; break;
				case 10450:
					$quest_name = "_a_dark_ambition"; break;
				case 10454:
					$quest_name = "_final_embryo_apostle"; break;
				case 10455:
					$quest_name = "_elikias_letter"; break;
				case 10457:
					$quest_name = "_kefensis_illusion"; break;
				case 10459:
					$quest_name = "_a_sick_ambition"; break;
				case 10501:
					$quest_name = "_zaken_embroidered_soul_cloak"; break;
				case 10502:
					$quest_name = "_freya_embroidered_soul_cloak"; break;
				case 10503:
					$quest_name = "_frintezza_embroidered_soul_cloak"; break;
				case 10514:
					$quest_name = "_new_path_to_glory"; break;
				case 10515:
					$quest_name = "_new_way_for_pride"; break;
				case 10516:
					$quest_name = "_unveiled_fafurion_temple"; break;
				case 10517:
					$quest_name = "_fafurions_minions"; break;
				case 10518:
					$quest_name = "_succeeding_the_priestess"; break;
				case 10519:
					$quest_name = "_controlling_your_temper"; break;
				case 10520:
					$quest_name = "_temple_guardians"; break;
				case 10529:
					$quest_name = "_ivory_towers_research_floating_sea_journal"; break;
				case 10533:
					$quest_name = "_orfens_ambition"; break;
				case 10535:
					$quest_name = "_blacksmiths_soul3"; break;
				case 10537:
					$quest_name = "_kamael_disarray"; break;
				case 10538:
					$quest_name = "_giants_evolution"; break;
				case 10539:
					$quest_name = "_energy_supply_cutoff_plan"; break;
				case 10540:
					$quest_name = "_thwarting_mimirs_plan"; break;
				case 10552:
					$quest_name = "_thwarting_mimirs_plan"; break;
				case 10553:
					$quest_name = "_thwarting_mimirs_plan"; break;
				case 10554:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10555:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10556:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10557:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10558:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10559:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10563:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10564:
					$quest_name = "_kamael's_technological_advancement"; break;
				case 10565:
					$quest_name = "_kamael's_technological_advancement"; break;
				case 10566:
					$quest_name = "_best_choice"; break;
				case 10567:
					$quest_name = "_special_mission_nornils_garden"; break;
				case 10568:
					$quest_name = "_kamaels_technological_advancement"; break;
				case 10569:
					$quest_name = "_declaration_of_war"; break;
				case 10570:
					$quest_name = "_hurrah_for_kamaels_independence"; break;
				case 10571:
					$quest_name = "_strategic_reconciliation"; break;
				case 10572:
					$quest_name = "_to_expel_the_embryos_forces"; break;
				case 10575:
					$quest_name = "_lets_go_fishing"; break;
				case 10576:
					$quest_name = "_glittering_weapons"; break;
				case 10577:
					$quest_name = "_temper_a_rusting_blade"; break;
				case 10578:
					$quest_name = "_the_soul_of_a_sword"; break;
				case 10579:
					$quest_name = "_containing_the_attribute_power"; break;
				case 10589:
					$quest_name = "_where_fates_intersect"; break;
				case 10590:
					$quest_name = "_reawakened_fate"; break;
				case 10591:
					$quest_name = "_noble_material"; break;
				case 10594:
					$quest_name = "_fergasons_scheme"; break;
				case 10595:
					$quest_name = "_the_dimensional_warp_part8"; break;
				case 10596:
					$quest_name = "_the_dimensional_warp_part9"; break;
				case 10597:
					$quest_name = "_escape_to_the_shadow_of_the_mother_tree"; break;
				case 10598:
					$quest_name = "_with_all_your_might"; break;
				case 10599:
					$quest_name = "_thread_of_fate_hanging_on_the_mother_tree"; break;
				case 10658:
					$quest_name = "_makkum_in_the_dimension"; break;
				case 10673:
					$quest_name = "_valentine's_day,_lucy's_reply"; break;
				case 10701:
					$quest_name = "_the_road_to_destruction"; break;
				case 10702:
					$quest_name = "_the_road_to_infinity"; break;
				case 10748:
					$quest_name = "_mysterious_suggestion1"; break;
				case 10749:
					$quest_name = "_mysterious_suggestion2"; break;
				case 10801:
					$quest_name = "_the_dimensional_warp_part1"; break;
				case 10802:
					$quest_name = "_the_dimensional_warp_part2"; break;
				case 10803:
					$quest_name = "_the_dimensional_warp_part3"; break;
				case 10804:
					$quest_name = "_the_dimensional_warp_part4"; break;
				case 10805:
					$quest_name = "_the_dimensional_warp_part5"; break;
				case 10806:
					$quest_name = "_the_dimensional_warp_part6"; break;
				case 10807:
					$quest_name = "_the_dimensional_warp_part7"; break;
				case 10811:
					$quest_name = "_exalted_one_who_faces_the_limit"; break;
				case 10812:
					$quest_name = "_facing_sadness"; break;
				case 10813:
					$quest_name = "_for_glory"; break;
				case 10814:
					$quest_name = "_befitting_of_the_status"; break;
				case 10815:
					$quest_name = "_step_up"; break;
				case 10817:
					$quest_name = "_exalted_one_who_overcomes_the_limit"; break;
				case 10818:
					$quest_name = "_confronting_a_giant_monster"; break;
				case 10819:
					$quest_name = "_for_honor"; break;
				case 10820:
					$quest_name = "_relationships_befitting_of_the_status"; break;
				case 10821:
					$quest_name = "_helping_others"; break;
				case 10823:
					$quest_name = "_exalted_one_who_shatters_the_limit"; break;
				case 10824:
					$quest_name = "_confronting_the_greatest_danger"; break;
				case 10825:
					$quest_name = "_for_victory"; break;
				case 10826:
					$quest_name = "_luck_befitting_of_the_status"; break;
				case 10827:
					$quest_name = "_step_up_to_lead"; break;
				case 10829:
					$quest_name = "_in_search_of_the_cause"; break;
				case 10830:
					$quest_name = "_the_lost_garden_of_spirits"; break;
				case 10831:
					$quest_name = "_unbelievable_sight"; break;
				case 10832:
					$quest_name = "_energy_of_sadness_and_anger"; break;
				case 10833:
					$quest_name = "_put_the_queen_of_spirits_to_sleep"; break;
				case 10836:
					$quest_name = "_disappeared_clan_member"; break;
				case 10837:
					$quest_name = "_looking_for_the_blackbird_clan_member"; break;
				case 10838:
					$quest_name = "_the_reason_for_not_being_able_to_get_out"; break;
				case 10839:
					$quest_name = "_blackbirds_name_value"; break;
				case 10840:
					$quest_name = "_time_to_recover"; break;
				case 10843:
					$quest_name = "_anomaly_in_the_enchanted_valley"; break;
				case 10844:
					$quest_name = "_bloody_battle_seizing_supplies"; break;
				case 10845:
					$quest_name = "_bloody_battle_rescue_the_smiths"; break;
				case 10846:
					$quest_name = "_bloody_battle_meeting_the_commander"; break;
				case 10848:
					$quest_name = "_trials_before_the_battle"; break;
				case 10849:
					$quest_name = "_trials_for_adaptation"; break;
				case 10851:
					$quest_name = "_elven_botany"; break;
				case 10852:
					$quest_name = "_the_mother_tree_revival_project"; break;
				case 10853:
					$quest_name = "_to_weaken_the_giants"; break;
				case 10854:
					$quest_name = "_to_seize_the_fortress"; break;
				case 10856:
					$quest_name = "_superion_appears"; break;
				case 10857:
					$quest_name = "_secret_teleport"; break;
				case 10861:
					$quest_name = "_monster_arena_-_the_birth_of_a_warrior"; break;
				case 10862:
					$quest_name = "_monster_arena_-_challenge:_10_battles"; break;
				case 10863:
					$quest_name = "_monster_arena_-_new_challenge:_15_battles"; break;
				case 10864:
					$quest_name = "_monster_arena_-_brave_warrior:_25_battles"; break;
				case 10865:
					$quest_name = "_monster_arena_-_last_call:_40_battles"; break;
				case 10866:
					$quest_name = "_punitive_operation_on_the_devil_isle"; break;
				case 10867:
					$quest_name = "_gone_missing"; break;
				case 10868:
					$quest_name = "_the_dark_side_of_power"; break;
				case 10870:
					$quest_name = "_unfinished_device"; break;
				case 10871:
					$quest_name = "_death_to_the_pirate_king!"; break;
				case 10873:
					$quest_name = "_exalted_reaching_another_level"; break;
				case 10874:
					$quest_name = "_against_the_new_enemy"; break;
				case 10875:
					$quest_name = "_for_reputation"; break;
				case 10876:
					$quest_name = "_leaders_grace"; break;
				case 10877:
					$quest_name = "_break_through_crisis"; break;
				case 10879:
					$quest_name = "_exalted_guide_to_power"; break;
				case 10880:
					$quest_name = "_the_last_one_standing"; break;
				case 10881:
					$quest_name = "_for_the_pride"; break;
				case 10882:
					$quest_name = "_victory_collection"; break;
				case 10883:
					$quest_name = "_immortal_honor"; break;
				case 10885:
					$quest_name = "_savior's_path_-_discovery"; break;
				case 10886:
					$quest_name = "_saviors_path_search_the_refinery"; break;
				case 10887:
					$quest_name = "_saviors_path_demons_and_atelia"; break;
				case 10888:
					$quest_name = "_saviors_path_defeat_the_embryo"; break;
				case 10889:
					$quest_name = "_saviors_path_fallen_emperors_throne"; break;
				case 10890:
					$quest_name = "_saviors_path_fall_of_etina"; break;
				case 10891:
					$quest_name = "_at_a_new_place"; break;
				case 10892:
					$quest_name = "_revenge_one_step_at_a_time"; break;
				case 10893:
					$quest_name = "_end_of_twisted_fate"; break;
				case 10896:
					$quest_name = "_visit_the_adventure_guild"; break;
				case 10897:
					$quest_name = "_show_your_ability"; break;
				case 10898:
					$quest_name = "_toward_a_goal"; break;
				case 10899:
					$quest_name = "_veteran_adventurer"; break;
				case 10900:
					$quest_name = "_path_to_strength"; break;
				case 10901:
					$quest_name = "_a_model_adventurer"; break;
				case 10950:
					$quest_name = "_fiercest_flame"; break;
				case 10951:
					$quest_name = "_new_flame_of_orcs"; break;
				case 10952:
					$quest_name = "_protect_at_all_costs"; break;
				case 10953:
					$quest_name = "_valiant_orcs"; break;
				case 10954:
					$quest_name = "_sayha_children"; break;
				case 10955:
					$quest_name = "_new_life_lessons"; break;
				case 10956:
					$quest_name = "_we_sylphs"; break;
				case 10957:
					$quest_name = "_the_life_of_a_death_knight"; break;
				case 10958:
					$quest_name = "_exploring_new_opportunities"; break;
				case 10959:
					$quest_name = "_challenging_your_destiny"; break;
				case 10960:
					$quest_name = "_tutorial"; break;
				case 10961:
					$quest_name = "_effective_training"; break;
				case 10962:
					$quest_name = "_new_horizons"; break;
				case 10963:
					$quest_name = "_exploring_the_ant_nest"; break;
				case 10964:
					$quest_name = "_secret_garden"; break;
				case 10965:
					$quest_name = "_death_mysteries"; break;
				case 10966:
					$quest_name = "_a_trip_begins"; break;
				case 10967:
					$quest_name = "_cultured_adventurer"; break;
				case 10968:
					$quest_name = "_the_power_of_the_magic_lamp"; break;
				case 10971:
					$quest_name = "_talisman_enchant"; break;
				case 10972:
					$quest_name = "_combining_gems"; break;
				case 10973:
					$quest_name = "_enchanting_agathions"; break;
				case 10974:
					$quest_name = "_new_stylish_equipment"; break;
				case 10978:
					$quest_name = "_missing_pets"; break;
				case 10981:
					$quest_name = "_unbearable_wolves_howling"; break;
				case 10982:
					$quest_name = "_spider_hunt"; break;
				case 10983:
					$quest_name = "_troubled_forest"; break;
				case 10984:
					$quest_name = "_collect_spiderweb"; break;
				case 10985:
					$quest_name = "_cleaning_up_the_ground"; break;
				case 10986:
					$quest_name = "_swamp_monster"; break;
				case 10987:
					$quest_name = "_plundered_graves"; break;
				case 10988:
					$quest_name = "_conspiracy"; break;
				case 10989:
					$quest_name = "_dangerous_predators"; break;
				case 10990:
					$quest_name = "_poison_extraction"; break;
				case 11024:
					$quest_name = "_path_of_destiny_-_beginning"; break;
				case 11025:
					$quest_name = "_path_of_destiny_-_proving"; break;
				case 11026:
					$quest_name = "_path_of_destiny_-_conviction"; break;
				case 11027:
					$quest_name = "_path_of_destiny_-_overcome"; break;
				case 11028:
					$quest_name = "_path_of_destiny_-_encounter"; break;
				case 11029:
					$quest_name = "_path_of_destiny_-_promise"; break;
				case 11030:
					$quest_name = "_path_of_destiny_-_choice"; break;
				case 11031:
					$quest_name = "_training_begins_now"; break;
				case 11032:
					$quest_name = "_curse_of_undying"; break;
				case 11033:
					$quest_name = "_antidote_ingredients"; break;
				case 11034:
					$quest_name = "_resurrected_one"; break;
				case 11035:
					$quest_name = "_deathly_mischief"; break;
				case 11036:
					$quest_name = "_changed_spirits"; break;
				case 11037:
					$quest_name = "_why_are_the_ratel_here"; break;
				case 11038:
					$quest_name = "_growlers_turned_violent"; break;
				case 11039:
					$quest_name = "_communication_breakdown"; break;
				case 11040:
					$quest_name = "_attack_of_the_enraged_forest"; break;
				case 11042:
					$quest_name = "_suspicious_movements"; break;
				case 11043:
					$quest_name = "_someones_trace"; break;
				case 11044:
					$quest_name = "_ketra_orcs"; break;
				case 11045:
					$quest_name = "_they_must_be_up_to_something"; break;
				case 11046:
					$quest_name = "_praying_for_safety"; break;
				default:
					$quest_name = "_quest_name_error"; break;
			}
			return $quest_name;
		}
		
		function castleName($id){
			switch ($id){
				case 1:
					$name = "Gludio"; break;
				case 2:
					$name = "Dion"; break;
				case 3:
					$name = "Giran"; break;
				case 4:
					$name = "Oren"; break;
				case 5:
					$name = "Aden"; break;
				case 6:
					$name = "Innadril"; break;
				case 7:
					$name = "Goddard"; break;
				case 8:
					$name = "Rune"; break;
				case 9:
					$name = "Schuttgart"; break;
				default:
					$name = "n/a"; break;
			}
			return $name;
		}
		
		function clanHallLoc($id){
			if($id >= 22 && $id <= 25)
				$loc = "Gludio";
			elseif($id >= 26 && $id <= 30)
				$loc = "Gludin";
			elseif($id >= 31 && $id <= 33)
				$loc = "Dion";
			elseif($id >= 36 && $id <= 41)
				$loc = "Aden";
			elseif($id >= 42 && $id <= 46)
				$loc = "Giran";
			elseif($id >= 47 && $id <= 50)
				$loc = "Goddard";
			elseif($id >= 51 && $id <= 57)
				$loc = "Rune";
			elseif($id >= 58 && $id <= 61)
				$loc = "Schuttgart";
			elseif($id >= 65 && $id <= 68)
				$loc = "Gludio";
			elseif($id >= 69 && $id <= 72)
				$loc = "Dion";
			elseif($id >= 73 && $id <= 74)
				$loc = "Floran";
			elseif($id >= 186 && $id <= 194)
				$loc = "Talking Island";
			else
				$loc = null;
			return $loc;
		}
		
		public function getRace($baseclass) {
			$arrHuman = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,88,89,90,91,92,93,94,95,96,97,98,148,149,152,153,158,162,166,167,171,176,179,196,197,198,199];
			$arrElf = [18,19,20,21,22,23,24,25,26,27,28,29,30,99,100,101,102,103,104,105,150,159,163,168,172,177,180,200,201,202,203];
			$arrDarkElf = [31,32,33,34,35,36,37,38,39,40,41,42,43,106,107,108,109,110,111,112,151,160,164,169,173,178,181,204,205,206,207];
			$arrOrc = [44,45,46,47,48,49,50,51,52,113,114,115,116,154,155,174,175,217,218,219,220];
			$arrDwarf = [53,54,55,56,57,117,118,156,161];
			$arrKamael = [123,124,125,126,127,128,129,130,131,132,133,134,135,136,157,165,170,192,193,194,195];
			$arrErtheia = [182,183,184,185,186,187,188,189];
			$arrSylph = [208,209,210,211];
			if(in_array($baseclass,$arrHuman)){ $raca = 0; }
			elseif(in_array($baseclass,$arrElf)){ $raca = 1; }
			elseif(in_array($baseclass,$arrDarkElf)){ $raca = 2; }
			elseif(in_array($baseclass,$arrOrc)){ $raca = 3; }
			elseif(in_array($baseclass,$arrDwarf)){ $raca = 4; }
			elseif(in_array($baseclass,$arrKamael)){ $raca = 5; }
			elseif(in_array($baseclass,$arrErtheia)){ $raca = 6; }
			elseif(in_array($baseclass,$arrSylph)){ $raca = 30; }
			else{ $raca = 100; }
			return $raca;
		}
		
		public function remainingTime($data,$abrevia = false) {
			$diff = time() - (time() - $data);
			$calc1 = ($diff % 86400);
			$calc2 = ($diff % 3600);
			$dias  = floor($diff / 86400);
			$horas = floor($calc1 / 3600);
			$minut = floor($calc2 / 60);
			$segun = ($calc2 % 60);
			$return = null;
			$return .= $dias > 0 ? "<strong>".$dias."</strong>" : null;
			$return .= $dias > 0 ? $abrevia ? "d, " : " day(s), " : null;
			$return .= $horas > 0 ? "<strong>".$horas."</strong>" : null;
			$return .= $horas > 0 ? $abrevia ? "h, " : " hour(s), " : null;
			$return .= $minut > 0 ? "<strong>".$minut."</strong>" : null;
			$return .= $minut > 0 ? $abrevia ? "m, " : " minute(s), " : null;
			$return .= $segun >= 0 ? "<strong>".$segun."</strong>" : null;
			$return .= $segun >= 0 ? $abrevia ? "s." : " second(s)." : null;
			return $return;
		}
		
		protected function formatDate($str){
			if(strlen($str) == 13){
				$date = date("Y-m-d H:i:s",($str/1000));
			}else{
				$date = date("Y-m-d H:i:s",$str);
			}
			return $date;
		}
		
		protected function execute($query,$params=[],$db=null){
			if(empty($query)){
				return die("Invalid query.");
			}
			$records = $db == "login" ? $this->loginServer->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL)) : $this->gameServer->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
			if($records->execute(is_array($params) ? count($params) > 0 ? $params : null : null)){
				$query_type = explode(" ",$query);
				if($query_type[0] == "SELECT" ? 1 : 0){
					return $records->fetchAll(\PDO::FETCH_ASSOC);
				}else{
					return 1;
				}
			}else{
				return 0;
			}
		}
		
		public function kkk($qtd){
			$qtdk = null;
			if($qtd >= 1000){
				$ponto = null;
				$qtd = number_format($qtd,0,'.','.');
				$qt = explode(".", $qtd);
				for($k=0;$k<(count($qt)-1);$k++){
					if((count($qt) - 1) != '0'){
						$qtdk .= 'K';
					}else{
						$qtdk .= null;
					}
					$p = $k;
				}
				for($pt=0;$pt<$p;$pt++){
					$ponto .= '.';
				}
				$qtda = substr(str_replace("0", "", $qtd), 0, 3);
				if($qtda == substr(str_replace("0", "", $qtd), 0, 1)."." and strlen(preg_replace("/(\D)/i" , "" , $qtd."'")) == 6){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1)."00";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1)."." and strlen(preg_replace("/(\D)/i" , "" , $qtd."'")) == 5){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1)."0";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1)."."){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1);
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1).".." and strlen(preg_replace("/(\D)/i" , "" , $qtd."'")) == 9){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1)."00";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1).".." and strlen(preg_replace("/(\D)/i" , "" , $qtd."'")) == 8){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1)."0";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1).".."){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1);
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 1)){
					$qtda = substr(str_replace("0", "", $qtd), 0, 1);
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 2).$ponto and strlen(preg_replace("/(\D)/i" , "" , $qtd."'").$ponto) == 6){
					$qtda = substr(str_replace("0", "", $qtd), 0, 2)."0";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 2).$ponto and strlen(preg_replace("/(\D)/i" , "" , $qtd."'").$ponto) == 5){
					$qtda = substr(str_replace("0", "", $qtd), 0, 2);
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 2).$ponto and strlen(preg_replace("/(\D)/i" , "" , $qtd."'").$ponto) == 10){
					$qtda = substr(str_replace("0", "", $qtd), 0, 2)."0";
				}elseif($qtda == substr(str_replace("0", "", $qtd), 0, 2).$ponto and strlen(preg_replace("/(\D)/i" , "" , $qtd."'").$ponto) == 9){
					$qtda = substr(str_replace("0", "", $qtd), 0, 2);
				}
			}else{
				$qtda = $qtd;
			}
			return $qtda.$qtdk;
		}
		
		protected function showCrest($ClanId){
			if(isset($this->QUERY_CRESTS) && !empty($this->QUERY_CRESTS) && isset($ClanId) && !empty($ClanId)){
				require_once('engine/classes/Crests.php');
				$crest = new \Crest($this->gameServer,$this->QUERY_CRESTS);
				$crest->getCrest($ClanId);
			}
		}
		
		public function pagination($num_page, $registers){
			$url = explode("?", $_SERVER['REQUEST_URI']);
			$url2 = explode("&", $url[1]);
			$link = null;
			for($y=0;$y<count($url2);$y++){
				$url3 = explode("=", $url2[$y]);
				if($url3[0] != "page"){
					$link .= $y == 0 ? "?" : "&";
					$link .= $url3[0]."=".$url3[1];
				}
			}
			$pagination = null;
			if($registers > 0){
				if($num_page > 0){
					$pagination .= "<a href=\"index.php".$link."&page=".($num_page-1)."\" class=\"pag\"><b>&laquo; Previous</b></a>";
				}else{
					$pagination .= "<a onclick='return false;' class=\"desatived\">&laquo; Previous</a>";
				}
				for($x=1;$x<=$registers;$x++){
					if($num_page == ($x-1)){
						$pagination .= "&nbsp;<a onclick='return false;' class=\"atual\">[".$x."]</a>&nbsp;";
					}else{
						$pagination .= "&nbsp;<a href=\"index.php".$link."&page=".($x-1)."\" class=\"pag\"><b>".$x."</b></a>&nbsp;";
					}
				}
				if(($num_page+1) < $registers){
					$pagination .= "<a href=\"index.php".$link."&page=".($num_page+1)."\" class=\"pag\"><b>Next &raquo;</b></a>";
				}else{
					$pagination .= "<a onclick='return false;' class=\"desatived\">Next &raquo;</a>";
				}
			}
			return $pagination;
		}
		
		public function paginationPanel($num_page, $registers){
			$url = explode("?", $_SERVER['REQUEST_URI']);
			$url2 = explode("&", $url[1]);
			$link = null;
			for($y=0;$y<count($url2);$y++){
				$url3 = explode("=", $url2[$y]);
				if($url3[0] != "page"){
					$link .= $y == 0 ? "?" : "&";
					$link .= $url3[0]."=".$url3[1];
				}
			}
			$pagination = null;
			if($registers > 0){
				if($num_page > 0){
					$pagination .= "<li class=\"page-item\"><a href=\"index.php".$link."&page=".($num_page-1)."\" class=\"page-link\"><b>&laquo; Previous</b></a></li>";
				}else{
					$pagination .= "<li class=\"page-item disabled\"><a onclick='return false;' class=\"page-link\" tabindex=\"-1\">&laquo; Previous</a></li>";
				}
				for($x=1;$x<=$registers;$x++){
					if($num_page == ($x-1)){
						$pagination .= "<li class=\"page-item active\"><span class=\"page-link\"><span class=\"sr-only\">".$x."</span></span></li>";
					}else{
						$pagination .= "<li class=\"page-item\"><a href=\"index.php".$link."&page=".($x-1)."\" class=\"page-link\"><b>".$x."</b></a></li>";
					}
				}
				if(($num_page+1) < $registers){
					$pagination .= "<li class=\"page-item\"><a href=\"index.php".$link."&page=".($num_page+1)."\" class=\"page-link\"><b>Next &raquo;</b></a></li>";
				}else{
					$pagination .= "<li class=\"page-item disabled\"><a onclick='return false;' class=\"page-link\">Next &raquo;</a></li>";
				}
			}
			return $pagination;
		}

	}

}