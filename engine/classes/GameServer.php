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
	
	class GameServer {
		
		private $noPvpItems = " AND i.item_id != '21923' AND i.item_id != '21924' AND i.item_id != '21925' AND i.item_id != '21926' AND i.item_id != '21931' AND i.item_id != '21932' AND i.item_id != '21933' AND i.item_id != '21934' AND i.item_id != '21936' AND i.item_id != '21938' AND i.item_id != '21943' AND i.item_id != '21944' AND i.item_id != '21945' AND i.item_id != '21946' AND i.item_id != '21951' AND i.item_id != '21952' AND i.item_id != '21953' AND i.item_id != '21954' AND i.item_id != '21956' AND i.item_id != '21958' AND i.item_id != '21963' AND i.item_id != '21964' AND i.item_id != '21965' AND i.item_id != '21970' AND i.item_id != '21971' AND i.item_id != '21972' AND i.item_id != '10752' AND i.item_id != '10753' AND i.item_id != '10754' AND i.item_id != '10755' AND i.item_id != '10756' AND i.item_id != '10757' AND i.item_id != '10758' AND i.item_id != '16134' AND i.item_id != '10759' AND i.item_id != '16135' AND i.item_id != '10760' AND i.item_id != '16136' AND i.item_id != '10761' AND i.item_id != '16137' AND i.item_id != '10762' AND i.item_id != '16138' AND i.item_id != '10763' AND i.item_id != '16139' AND i.item_id != '10764' AND i.item_id != '16140' AND i.item_id != '10765' AND i.item_id != '16141' AND i.item_id != '10766' AND i.item_id != '16142' AND i.item_id != '10767' AND i.item_id != '16143' AND i.item_id != '10768' AND i.item_id != '16144' AND i.item_id != '10769' AND i.item_id != '16145' AND i.item_id != '10770' AND i.item_id != '16146' AND i.item_id != '10771' AND i.item_id != '16147' AND i.item_id != '10772' AND i.item_id != '10773' AND i.item_id != '16149' AND i.item_id != '10774' AND i.item_id != '10775' AND i.item_id != '16151' AND i.item_id != '10776' AND i.item_id != '10777' AND i.item_id != '16153' AND i.item_id != '10778' AND i.item_id != '10779' AND i.item_id != '14363' AND i.item_id != '16155' AND i.item_id != '10780' AND i.item_id != '14364' AND i.item_id != '10781' AND i.item_id != '14365' AND i.item_id != '16157' AND i.item_id != '10782' AND i.item_id != '14366' AND i.item_id != '10783' AND i.item_id != '14367' AND i.item_id != '16159' AND i.item_id != '10784' AND i.item_id != '14368' AND i.item_id != '10785' AND i.item_id != '14369' AND i.item_id != '10786' AND i.item_id != '14370' AND i.item_id != '10787' AND i.item_id != '14371' AND i.item_id != '10788' AND i.item_id != '14372' AND i.item_id != '10789' AND i.item_id != '14373' AND i.item_id != '10790' AND i.item_id != '14374' AND i.item_id != '10791' AND i.item_id != '14375' AND i.item_id != '10792' AND i.item_id != '14376' AND i.item_id != '16168' AND i.item_id != '10793' AND i.item_id != '14377' AND i.item_id != '15913' AND i.item_id != '16169' AND i.item_id != '10794' AND i.item_id != '14378' AND i.item_id != '15914' AND i.item_id != '16170' AND i.item_id != '10795' AND i.item_id != '14379' AND i.item_id != '15915' AND i.item_id != '16171' AND i.item_id != '10796' AND i.item_id != '14380' AND i.item_id != '15916' AND i.item_id != '16172' AND i.item_id != '10797' AND i.item_id != '14381' AND i.item_id != '15917' AND i.item_id != '16173' AND i.item_id != '10798' AND i.item_id != '14382' AND i.item_id != '15918' AND i.item_id != '16174' AND i.item_id != '10799' AND i.item_id != '14383' AND i.item_id != '15919' AND i.item_id != '16175' AND i.item_id != '10800' AND i.item_id != '14384' AND i.item_id != '15920' AND i.item_id != '16176' AND i.item_id != '10801' AND i.item_id != '14385' AND i.item_id != '15921' AND i.item_id != '10802' AND i.item_id != '14386' AND i.item_id != '15922' AND i.item_id != '10803' AND i.item_id != '14387' AND i.item_id != '15923' AND i.item_id != '16179' AND i.item_id != '10804' AND i.item_id != '12852' AND i.item_id != '14388' AND i.item_id != '15924' AND i.item_id != '16180' AND i.item_id != '10805' AND i.item_id != '12853' AND i.item_id != '14389' AND i.item_id != '15925' AND i.item_id != '16181' AND i.item_id != '10806' AND i.item_id != '12854' AND i.item_id != '14390' AND i.item_id != '15926' AND i.item_id != '16182' AND i.item_id != '10807' AND i.item_id != '12855' AND i.item_id != '14391' AND i.item_id != '15927' AND i.item_id != '16183' AND i.item_id != '10808' AND i.item_id != '12856' AND i.item_id != '14392' AND i.item_id != '15928' AND i.item_id != '16184' AND i.item_id != '10809' AND i.item_id != '12857' AND i.item_id != '14393' AND i.item_id != '15929' AND i.item_id != '16185' AND i.item_id != '10810' AND i.item_id != '12858' AND i.item_id != '14394' AND i.item_id != '15930' AND i.item_id != '16186' AND i.item_id != '10811' AND i.item_id != '12859' AND i.item_id != '14395' AND i.item_id != '15931' AND i.item_id != '16187' AND i.item_id != '10812' AND i.item_id != '12860' AND i.item_id != '14396' AND i.item_id != '15932' AND i.item_id != '16188' AND i.item_id != '10813' AND i.item_id != '12861' AND i.item_id != '14397' AND i.item_id != '15933' AND i.item_id != '16189' AND i.item_id != '10814' AND i.item_id != '12862' AND i.item_id != '14398' AND i.item_id != '15934' AND i.item_id != '16190' AND i.item_id != '10815' AND i.item_id != '12863' AND i.item_id != '14399' AND i.item_id != '15935' AND i.item_id != '16191' AND i.item_id != '10816' AND i.item_id != '12864' AND i.item_id != '14400' AND i.item_id != '15936' AND i.item_id != '16192' AND i.item_id != '10817' AND i.item_id != '12865' AND i.item_id != '14401' AND i.item_id != '15937' AND i.item_id != '16193' AND i.item_id != '10818' AND i.item_id != '12866' AND i.item_id != '14402' AND i.item_id != '15938' AND i.item_id != '16194' AND i.item_id != '10819' AND i.item_id != '12867' AND i.item_id != '14403' AND i.item_id != '15939' AND i.item_id != '16195' AND i.item_id != '10820' AND i.item_id != '12868' AND i.item_id != '14404' AND i.item_id != '15940' AND i.item_id != '16196' AND i.item_id != '10821' AND i.item_id != '12869' AND i.item_id != '14405' AND i.item_id != '15941' AND i.item_id != '16197' AND i.item_id != '10822' AND i.item_id != '12870' AND i.item_id != '14406' AND i.item_id != '15942' AND i.item_id != '16198' AND i.item_id != '10823' AND i.item_id != '12871' AND i.item_id != '14407' AND i.item_id != '15943' AND i.item_id != '16199' AND i.item_id != '10824' AND i.item_id != '12872' AND i.item_id != '14408' AND i.item_id != '15944' AND i.item_id != '16200' AND i.item_id != '10825' AND i.item_id != '12873' AND i.item_id != '14409' AND i.item_id != '15945' AND i.item_id != '16201' AND i.item_id != '10826' AND i.item_id != '12874' AND i.item_id != '14410' AND i.item_id != '15946' AND i.item_id != '16202' AND i.item_id != '10827' AND i.item_id != '12875' AND i.item_id != '14411' AND i.item_id != '15947' AND i.item_id != '16203' AND i.item_id != '10828' AND i.item_id != '12876' AND i.item_id != '14412' AND i.item_id != '15948' AND i.item_id != '16204' AND i.item_id != '10829' AND i.item_id != '12877' AND i.item_id != '14413' AND i.item_id != '15949' AND i.item_id != '16205' AND i.item_id != '10830' AND i.item_id != '12878' AND i.item_id != '14414' AND i.item_id != '15950' AND i.item_id != '16206' AND i.item_id != '10831' AND i.item_id != '12879' AND i.item_id != '14415' AND i.item_id != '15951' AND i.item_id != '16207' AND i.item_id != '10832' AND i.item_id != '12880' AND i.item_id != '14416' AND i.item_id != '15952' AND i.item_id != '16208' AND i.item_id != '10833' AND i.item_id != '12881' AND i.item_id != '14417' AND i.item_id != '15953' AND i.item_id != '16209' AND i.item_id != '10834' AND i.item_id != '12882' AND i.item_id != '14418' AND i.item_id != '15954' AND i.item_id != '16210' AND i.item_id != '10835' AND i.item_id != '12883' AND i.item_id != '14419' AND i.item_id != '15955' AND i.item_id != '16211' AND i.item_id != '12884' AND i.item_id != '14420' AND i.item_id != '15956' AND i.item_id != '16212' AND i.item_id != '12885' AND i.item_id != '14421' AND i.item_id != '15957' AND i.item_id != '16213' AND i.item_id != '12886' AND i.item_id != '14422' AND i.item_id != '15958' AND i.item_id != '16214' AND i.item_id != '12887' AND i.item_id != '14423' AND i.item_id != '15959' AND i.item_id != '16215' AND i.item_id != '12888' AND i.item_id != '14424' AND i.item_id != '15960' AND i.item_id != '16216' AND i.item_id != '12889' AND i.item_id != '14425' AND i.item_id != '15961' AND i.item_id != '16217' AND i.item_id != '12890' AND i.item_id != '14426' AND i.item_id != '15962' AND i.item_id != '16218' AND i.item_id != '12891' AND i.item_id != '14427' AND i.item_id != '15963' AND i.item_id != '16219' AND i.item_id != '12892' AND i.item_id != '14428' AND i.item_id != '15964' AND i.item_id != '16220' AND i.item_id != '12893' AND i.item_id != '14429' AND i.item_id != '15965' AND i.item_id != '12894' AND i.item_id != '14430' AND i.item_id != '15966' AND i.item_id != '12895' AND i.item_id != '14431' AND i.item_id != '15967' AND i.item_id != '12896' AND i.item_id != '14432' AND i.item_id != '15968' AND i.item_id != '12897' AND i.item_id != '14433' AND i.item_id != '15969' AND i.item_id != '12898' AND i.item_id != '14434' AND i.item_id != '15970' AND i.item_id != '12899' AND i.item_id != '14435' AND i.item_id != '15971' AND i.item_id != '12900' AND i.item_id != '14436' AND i.item_id != '15972' AND i.item_id != '12901' AND i.item_id != '14437' AND i.item_id != '15973' AND i.item_id != '12902' AND i.item_id != '14438' AND i.item_id != '15974' AND i.item_id != '12903' AND i.item_id != '14439' AND i.item_id != '15975' AND i.item_id != '12904' AND i.item_id != '14440' AND i.item_id != '15976' AND i.item_id != '12905' AND i.item_id != '14441' AND i.item_id != '15977' AND i.item_id != '12906' AND i.item_id != '14442' AND i.item_id != '15978' AND i.item_id != '12907' AND i.item_id != '14443' AND i.item_id != '15979' AND i.item_id != '12908' AND i.item_id != '14444' AND i.item_id != '15980' AND i.item_id != '12909' AND i.item_id != '14445' AND i.item_id != '15981' AND i.item_id != '12910' AND i.item_id != '14446' AND i.item_id != '15982' AND i.item_id != '12911' AND i.item_id != '14447' AND i.item_id != '15983' AND i.item_id != '12912' AND i.item_id != '14448' AND i.item_id != '15984' AND i.item_id != '12913' AND i.item_id != '14449' AND i.item_id != '15985' AND i.item_id != '12914' AND i.item_id != '14450' AND i.item_id != '15986' AND i.item_id != '12915' AND i.item_id != '14451' AND i.item_id != '15987' AND i.item_id != '12916' AND i.item_id != '14452' AND i.item_id != '15988' AND i.item_id != '12917' AND i.item_id != '14453' AND i.item_id != '15989' AND i.item_id != '12918' AND i.item_id != '14454' AND i.item_id != '15990' AND i.item_id != '12919' AND i.item_id != '14455' AND i.item_id != '15991' AND i.item_id != '12920' AND i.item_id != '14456' AND i.item_id != '15992' AND i.item_id != '12921' AND i.item_id != '14457' AND i.item_id != '15993' AND i.item_id != '12922' AND i.item_id != '14458' AND i.item_id != '15994' AND i.item_id != '12923' AND i.item_id != '14459' AND i.item_id != '15995' AND i.item_id != '12924' AND i.item_id != '14460' AND i.item_id != '15996' AND i.item_id != '12925' AND i.item_id != '14461' AND i.item_id != '15997' AND i.item_id != '12926' AND i.item_id != '14462' AND i.item_id != '15998' AND i.item_id != '12927' AND i.item_id != '14463' AND i.item_id != '15999' AND i.item_id != '12928' AND i.item_id != '14464' AND i.item_id != '16000' AND i.item_id != '12929' AND i.item_id != '14465' AND i.item_id != '16001' AND i.item_id != '12930' AND i.item_id != '14466' AND i.item_id != '16002' AND i.item_id != '12931' AND i.item_id != '14467' AND i.item_id != '16003' AND i.item_id != '12932' AND i.item_id != '14468' AND i.item_id != '16004' AND i.item_id != '12933' AND i.item_id != '14469' AND i.item_id != '16005' AND i.item_id != '12934' AND i.item_id != '14470' AND i.item_id != '16006' AND i.item_id != '12935' AND i.item_id != '14471' AND i.item_id != '16007' AND i.item_id != '12936' AND i.item_id != '14472' AND i.item_id != '16008' AND i.item_id != '12937' AND i.item_id != '14473' AND i.item_id != '16009' AND i.item_id != '12938' AND i.item_id != '14474' AND i.item_id != '16010' AND i.item_id != '12939' AND i.item_id != '14475' AND i.item_id != '16011' AND i.item_id != '12940' AND i.item_id != '14476' AND i.item_id != '16012' AND i.item_id != '12941' AND i.item_id != '14477' AND i.item_id != '16013' AND i.item_id != '12942' AND i.item_id != '14478' AND i.item_id != '16014' AND i.item_id != '12943' AND i.item_id != '14479' AND i.item_id != '16015' AND i.item_id != '12944' AND i.item_id != '14480' AND i.item_id != '16016' AND i.item_id != '12945' AND i.item_id != '14481' AND i.item_id != '16017' AND i.item_id != '12946' AND i.item_id != '14482' AND i.item_id != '16018' AND i.item_id != '12947' AND i.item_id != '14483' AND i.item_id != '16019' AND i.item_id != '12948' AND i.item_id != '14484' AND i.item_id != '16020' AND i.item_id != '12949' AND i.item_id != '14485' AND i.item_id != '16021' AND i.item_id != '12950' AND i.item_id != '14486' AND i.item_id != '16022' AND i.item_id != '12951' AND i.item_id != '14487' AND i.item_id != '16023' AND i.item_id != '12952' AND i.item_id != '14488' AND i.item_id != '16024' AND i.item_id != '12953' AND i.item_id != '14489' AND i.item_id != '12954' AND i.item_id != '14490' AND i.item_id != '12955' AND i.item_id != '14491' AND i.item_id != '12956' AND i.item_id != '14492' AND i.item_id != '12957' AND i.item_id != '14493' AND i.item_id != '12958' AND i.item_id != '14494' AND i.item_id != '12959' AND i.item_id != '14495' AND i.item_id != '12960' AND i.item_id != '14496' AND i.item_id != '12961' AND i.item_id != '14497' AND i.item_id != '12962' AND i.item_id != '14498' AND i.item_id != '12963' AND i.item_id != '14499' AND i.item_id != '12964' AND i.item_id != '14500' AND i.item_id != '12965' AND i.item_id != '14501' AND i.item_id != '12966' AND i.item_id != '14502' AND i.item_id != '12967' AND i.item_id != '14503' AND i.item_id != '12968' AND i.item_id != '14504' AND i.item_id != '12969' AND i.item_id != '14505' AND i.item_id != '12970' AND i.item_id != '14506' AND i.item_id != '10667' AND i.item_id != '12971' AND i.item_id != '14507' AND i.item_id != '10668' AND i.item_id != '12972' AND i.item_id != '14508' AND i.item_id != '10669' AND i.item_id != '12973' AND i.item_id != '14509' AND i.item_id != '10670' AND i.item_id != '12974' AND i.item_id != '14510' AND i.item_id != '10671' AND i.item_id != '12975' AND i.item_id != '14511' AND i.item_id != '10672' AND i.item_id != '12976' AND i.item_id != '14512' AND i.item_id != '10673' AND i.item_id != '12977' AND i.item_id != '14513' AND i.item_id != '10674' AND i.item_id != '14514' AND i.item_id != '10675' AND i.item_id != '14515' AND i.item_id != '10676' AND i.item_id != '14516' AND i.item_id != '10677' AND i.item_id != '14517' AND i.item_id != '10678' AND i.item_id != '14518' AND i.item_id != '10679' AND i.item_id != '14519' AND i.item_id != '10680' AND i.item_id != '14520' AND i.item_id != '10681' AND i.item_id != '14521' AND i.item_id != '10682' AND i.item_id != '14522' AND i.item_id != '10683' AND i.item_id != '14523' AND i.item_id != '10684' AND i.item_id != '14524' AND i.item_id != '10685' AND i.item_id != '14525' AND i.item_id != '10686' AND i.item_id != '10687' AND i.item_id != '10688' AND i.item_id != '14528' AND i.item_id != '10689' AND i.item_id != '14529' AND i.item_id != '10690' AND i.item_id != '10691' AND i.item_id != '10692' AND i.item_id != '10693' AND i.item_id != '10694' AND i.item_id != '10695' AND i.item_id != '10696' AND i.item_id != '10697' AND i.item_id != '10698' AND i.item_id != '10699' AND i.item_id != '10700' AND i.item_id != '10701' AND i.item_id != '10702' AND i.item_id != '10703' AND i.item_id != '10704' AND i.item_id != '10705' AND i.item_id != '10706' AND i.item_id != '10707' AND i.item_id != '10708' AND i.item_id != '10709' AND i.item_id != '10710' AND i.item_id != '10711' AND i.item_id != '10712' AND i.item_id != '10713' AND i.item_id != '10714' AND i.item_id != '10715' AND i.item_id != '10716' AND i.item_id != '10717' AND i.item_id != '10718' AND i.item_id != '14558' AND i.item_id != '10719' AND i.item_id != '10720' AND i.item_id != '10721' AND i.item_id != '10722' AND i.item_id != '10723' AND i.item_id != '10724' AND i.item_id != '10725' AND i.item_id != '10726' AND i.item_id != '10727' AND i.item_id != '10728' AND i.item_id != '10729' AND i.item_id != '10730' AND i.item_id != '10731' AND i.item_id != '10732' AND i.item_id != '10733' AND i.item_id != '10734' AND i.item_id != '10735' AND i.item_id != '10736' AND i.item_id != '10737' AND i.item_id != '10738' AND i.item_id != '10739' AND i.item_id != '10740' AND i.item_id != '10741' AND i.item_id != '10742' AND i.item_id != '10743' AND i.item_id != '10744' AND i.item_id != '10745' AND i.item_id != '10746' AND i.item_id != '10747' AND i.item_id != '10748' AND i.item_id != '10749' AND i.item_id != '10750' AND i.item_id != '10751'";
		
		public function __construct($db_type, $loginServer, $gameServer, $config, $db_conn) {
			$this->db_type = $db_type;
			$this->loginServer = $loginServer;
			$this->gameServer = $gameServer;
			$this->DB_IP = $db_conn["db_ip"];
			$this->CACHED_PORT = 2012;
			foreach($config AS $key => $val){
				$this->{$key} = $val;
			}
		}
		
		private function filter($value, $string = false) {
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
		
		public function resposta($msg,$title=null,$type=null,$redirect=null){
			echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\" type=\"text/javascript\"></script><script src=\"//cdn.jsdelivr.net/npm/sweetalert2@10\"></script><script type=\"text/javascript\">$(document).ready(function(){Swal.fire({ title: '".$title."', html: '".$msg."', icon: '".$type."'".(!empty($redirect) ? ", confirmButtonText: 'Ok', preConfirm: () => { return [ window.location.href = '".$redirect."' ] } })" : "})")."})</script>";
		}
		
		private function execute($query,$params=[],$db=null){
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
		
		public function sendDonate($login,$coins,$type,$admin,$senderPrivId){
			if($senderPrivId >= 9){
				$checkName = $this->execute($this->QUERY_LOGIN_4,[$login],"login");
				if(count($checkName) == 1){
					$profileName = $this->execute('SELECT name FROM icp_staff WHERE login = ?',[$admin]);
					if(count($profileName) == 1){
						$admin = $profileName[0]["name"];
					}
					if(!empty($type) && $type == 1){
						$this->addDonate($coins,$login);
						$this->addDonateLog("The Staff Member ".$admin." added ".$coins." ".$this->DONATE_COIN_NAME." to his account.",0,$login);
						return $this->resposta("Coins successfully sent","Success!","success");
					}elseif(!empty($type) && $type == 2){
						$balance = $this->donateBalance($login);
						if($balance >= $coins){
							$this->debitDonate($coins,$login);
							$this->addDonateLog("The Staff Member ".$admin." removed ".$coins." ".$this->DONATE_COIN_NAME." from his account.",$coins,$login);
							return $this->resposta("Coins successfully removed","Success!","success");
						}else{
							return $this->resposta("The player does not have that amount of coins to remove.","Oops...","error");
						}
					}else
						return $this->resposta("Something went wrong","Oops...","error");
				}else{
					return $this->resposta("Account not found.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		private function donateBalance($login){
			$doacao = $this->execute('SELECT (total - used) AS credit FROM icp_donate WHERE login = ?',[$login]);
			if(count($doacao) == 1){
				return $doacao[0]["credit"];
			}
			return 0;
		}
		
		private function addDonate($value,$login){
			$doacao = $this->execute('SELECT * FROM icp_donate WHERE login = ?',[$login]);
			if(count($doacao) == 1){
				$adding = $this->execute("UPDATE icp_donate SET total = (total + ?) WHERE login = ?",[$value,$login]);
			}else{
				$adding = $this->execute("INSERT INTO icp_donate (login, total, used) VALUES (?,?,'0')",[$login,$value]);
			}
		}
		
		private function debitDonate($value,$login){
			$debiting = $this->execute("UPDATE icp_donate SET used = (used + ?) WHERE login = ?",[$value,$login]);
		}
		
		private function addDonateLog($description,$cost,$login){
			$donateLog = $this->execute("INSERT INTO icp_donate_log (date, description, cost, account) VALUES (?,?,?,?)",[date("Y-m-d H:i:s"),$description,$cost,$login]);
		}
		
		private function accountHash($numAlpha=25,$numNonAlpha=10){
			$listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$listNonAlpha = ':!.$/*-+&@_+./*&$-!';
			return time().str_shuffle(
				substr(str_shuffle($listAlpha),0,$numAlpha) .
				substr(str_shuffle($listNonAlpha),0,$numNonAlpha)
			);
		}
		
		private function teleToTown($x,$y){
			$towns = array(
						array(83229,148614,-3406,'Giran Town'),
						array(146331,25762,-2018,'Aden Town'),
						array(147928,-55273,-2734,'Goddard Town'),
						array(43799,-47727,-798,'Rune Town'),
						array(15670,142983,-2705,'Dion Town'),
						array(82956,53162,-1495,'Oren Town'),
						array(-12672,122776,-3116,'Gludio Town'),
						array(87386,-143246,-1293,'Shuttgart Town'),
						array(111409,219364,-3545,'Heine Town'),
						array(-80826,149775,-3043,'Gludin Village'),
						array(116819,76994,-2714,'Hunters Village'),
						array(-84433,244484,-3728,'Talking Island Village'),
						array(115113,-178212,-901,'Dwarven Village'),
						array(-44836,-112524,-235,'Orc Village'),
						array(9745,15606,-4574,'Dark Elven Village'),
						array(46934,51467,-2977,'Elven Village'),
						array(-117251,46771,360,'Kamael Village')
					);
			$townLoc = null;
			$teleTo = null;
			for($z=0;$z<count($towns);$z++){
				$dist = 2 * asin(sqrt(pow(sin((deg2rad($towns[$z][0]) - deg2rad($x)) / 2), 2) +
				cos(deg2rad($x)) * cos(deg2rad($towns[$z][0])) * pow(sin((deg2rad($towns[$z][1]) - deg2rad($y)) / 2), 2)));
				$townLoc = $z == 0 ? $dist : $townLoc;
				if($townLoc >= $dist){
					$townLoc = $dist;
					$teleTo = array($towns[$z][0],$towns[$z][1],$towns[$z][2],$towns[$z][3]);
				}
			}
			return $teleTo;
		}
		
		private function teleport($char_id,$x,$y,$z){
			$buf=pack("cVVVVV",2,$char_id,1,$x,$y,$z).$this->tounicode("admin");
			$this->sendBuf($buf);
		}
		
		private function kick_char($char_id){
			$buf=pack("cV",5,$char_id).$this->tounicode("admin");
			$this->sendBuf($buf);
		}
		
		private function tounicode($string){
			$rs="";
			for($i=0;$i<strlen($string);$i++) $rs.=$string[$i].chr(0);
			return($rs.chr(0).chr(0));
		}
		
		private function sendBuf($buf){
			$cachedsocket=@fsockopen($this->DB_IP,$this->CACHED_PORT,$errno,$errstr,1);
			fwrite($cachedsocket,pack("s",(strlen($buf)+2)).$buf);
			fclose($cachedsocket);
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
		
		public function putCharForSale($charid,$price,$type,$login){
			if(empty(preg_replace("/(\D)/i" , "" , $charid))){
				return $this->resposta("Invalid character ID!","Oops...","error");
			}
			if(!$this->ENABLE_CHARACTER_BROKER){
				return $this->resposta("Character broker is disabled!","Oooh no!","error");
			}
			$checkForSale = $this->execute("SELECT * FROM icp_shop_items WHERE status='1' AND owner_id = ?",[$charid]);
			if(count($checkForSale) > 0){
				return $this->resposta("You cannot sell the character because this character have item(s) is for sale in Item Broker.","Oops...","warning");
			}
			if(empty($type)){
				$type = 1;
			}else{
				if($type == 1){
					$type = 1;
				}else{
					if($this->ALLOW_AUCTION_CHARACTER_BROKER){
						$type = 2;
					}else{
						$type = 1;
					}
				}
			}
			$result = null;
			$records = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
			if(count($records) == 1){
				if($records[0]["level"] < $this->MIN_CHARACTER_BROKER_LEVEL){
					return $this->resposta("Minimum level for sale is ".$this->MIN_CHARACTER_BROKER_LEVEL.".","Oops!","error");
				}
				if(preg_replace("/(\D)/i" , "" , $price) > 0){
					if($this->db_type){
						$newacc = $this->accountHash();
						$records2 = $this->execute($this->QUERY_PUT_CHARACTER_FOR_SALE_1,[$newacc,$charid,$login]);
						$records3 = $this->execute($this->QUERY_PUT_CHARACTER_FOR_SALE_2,[$charid,$login,$newacc,$type,$price]);
					}else{
						$records2 = $this->execute($this->QUERY_PUT_CHARACTER_FOR_SALE_1,[$login,$charid,$type,$price,date("Y-m-d H:i:s")]);
					}
					if($type == 1){
						$result .= "Character successfully put up for sale.";
					}else{
						$result .= "The auction was created and the character was successfully put up for sale.";
					}
					return $this->resposta($result,"Good job!","success");
				}else{
					$result .= "Invalid price!";
				}
			}else{
				$result .= "Character not found!<br>Check if the character is offline.";
			}
			return $this->resposta($result,"Oooh no!","error");
		}
		
		function putItemsForSale($charid,$items,$price,$type,$login){
			if(!$this->ENABLE_ITEM_BROKER){
				return $this->resposta("Item Broker is disabled.","Oops...","error");
			}
			if(count($items) == 0){
				return $this->resposta("Select an item!","Oops...","warning");
			}
			if(!$this->ALLOW_ITEM_BROKER_SALE_COMBO_ITEMS && count($items) > 1){
				return $this->resposta("You can sell only one item at a time.","Oops...","warning");
			}
			if(count($items) > 24){
				return $this->resposta("Maximum limit of 24 items.","Oops...","warning");
			}
			if(count(array_unique($items)) != count($items)){
				return $this->resposta("An error has happened!","Oops...","error");
			}
			$checkForSale = $this->execute("SELECT * FROM icp_shop_chars WHERE status='1' AND owner_id = ?",[$charid]);
			if(count($checkForSale) > 0){
				return $this->resposta("You cannot sell the item because this character is for sale.","Oops...","error");
			}
			if(empty($type)){
				$type = count($items) > 1 ? 2 : 1;
			}else{
				if($this->ALLOW_AUCTION_ITEM_BROKER){
					$type = count($items) > 1 ? 4 : 3;
				}else{
					$type = count($items) > 1 ? 2 : 1;
				}
			}
			$result = null;
			$item_id = null;
			$noPvpItems = $this->db_type ? $this->noPvpItems : str_replace("i.item_id","i.item_type",$this->noPvpItems);
			$count = null;
			$enchant = null;
			$fire = null;
			$water = null;
			$wind = null;
			$earth = null;
			$holy = null;
			$unholy = null;
			$augment = null;
			$augment_ref = null;
			for($x=0;$x<count($items);$x++){
				$wherePvP = !$this->ALLOW_ITEM_BROKER_SALE_PVP_ITEMS ? $noPvpItems : null;
				$records = $this->execute($this->QUERY_ITEMS_DETAILS_1.$wherePvP,[$items[$x],$charid,$login]);
				if(count($records) == 1){
					$allowedGrades = explode(",",$this->allowSellItemsGrade);
					if(!in_array($records[0]["itemGrade"],$allowedGrades)){
						return $this->resposta(ucfirst($records[0]["itemGrade"])."-grade items are not allowed.","Oops...","warning");
					}
					if((isset($records[0]["attribute_fire"]) && isset($records[0]["attribute_fire"])) || (isset($records[0]["attribute_water"]) && isset($records[0]["attribute_water"])) || (isset($records[0]["attribute_wind"]) && isset($records[0]["attribute_wind"])) || (isset($records[0]["attribute_earth"]) && isset($records[0]["attribute_earth"])) || (isset($records[0]["attribute_holy"]) && isset($records[0]["attribute_holy"])) || (isset($records[0]["attribute_unholy"]) && isset($records[0]["attribute_unholy"])) || (isset($records[0]["augmentation_id"]) && isset($records[0]["augmentation_id"]))){
						if(isset($records[0]["augmentation_id"]) && $records[0]["augmentation_id"] > 0 && !$this->ALLOW_ITEM_BROKER_SALE_AUGMENTED_ITEMS)
							return $this->resposta("Augmented item is prohibited!","Oops...","error");
						$fire .= isset($records[0]["attribute_fire"]) && empty($records[0]["attribute_fire"]) ? "0;" : $records[0]["attribute_fire"].";";
						$water .= isset($records[0]["attribute_water"]) && empty($records[0]["attribute_water"]) ? "0;" : $records[0]["attribute_water"].";";
						$wind .= isset($records[0]["attribute_wind"]) && empty($records[0]["attribute_wind"]) ? "0;" : $records[0]["attribute_wind"].";";
						$earth .= isset($records[0]["attribute_earth"]) && empty($records[0]["attribute_earth"]) ? "0;" : $records[0]["attribute_earth"].";";
						$holy .= isset($records[0]["attribute_holy"]) && empty($records[0]["attribute_holy"]) ? "0;" : $records[0]["attribute_holy"].";";
						$unholy .= isset($records[0]["attribute_unholy"]) && empty($records[0]["attribute_unholy"]) ? "0;" : $records[0]["attribute_unholy"].";";
						$augment .= isset($records[0]["augmentation_id"]) && empty($records[0]["augmentation_id"]) ? "0;" : "1;";
						$augment_ref .= isset($records[0]["attribute_fire"]) && empty($records[0]["attribute_fire"]) && isset($records[0]["attribute_water"]) && empty($records[0]["attribute_water"]) && isset($records[0]["attribute_wind"]) && empty($records[0]["attribute_wind"]) && isset($records[0]["attribute_earth"]) && empty($records[0]["attribute_earth"]) && isset($records[0]["attribute_holy"]) && empty($records[0]["attribute_holy"]) && isset($records[0]["attribute_unholy"]) && empty($records[0]["attribute_unholy"]) && isset($records[0]["augmentation_id"]) && empty($records[0]["augmentation_id"]) ? "0;" : $records[0]["augmentation_id"].";";
					}else{
						$augElem = explode(",", $this->getAugElem($records[0]["object_id"]));
						if(($augElem[6] > 0 && !$this->ALLOW_ITEM_BROKER_SALE_AUGMENTED_ITEMS) || (isset($records[0]["augmentation"]) && $records[0]["augmentation"] > 0 && $this->ALLOW_ITEM_BROKER_SALE_AUGMENTED_ITEMS)){
							return $this->resposta("Augmented item is prohibited!","Oops...","error");
						}
						$fire .= empty($augElem[0]) ? "0;" : $augElem[0].";";
						$water .= empty($augElem[1]) ? "0;" : $augElem[1].";";
						$wind .= empty($augElem[2]) ? "0;" : $augElem[2].";";
						$earth .= empty($augElem[3]) ? "0;" : $augElem[3].";";
						$holy .= empty($augElem[4]) ? "0;" : $augElem[4].";";
						$unholy .= empty($augElem[5]) ? "0;" : $augElem[5].";";
						$augment .= empty($augElem[6]) || (isset($records[0]["augmentation"]) && $records[0]["augmentation"] > 0) ? "0;" : "1;";
						$augment_ref .= empty($augElem[0]) && empty($augElem[1]) && empty($augElem[2]) && empty($augElem[3]) && empty($augElem[4]) && empty($augElem[5]) && empty($augElem[6]) ? "0;" : $records[0]["object_id"].";";
					}
					$item_id .= $records[0]["item_id"].";";
					$count .= $records[0]["count"].";";
					$enchant .= $records[0]["enchant_level"].";";
				}else{
					return $this->resposta("Character not found!<br>Check if the character is offline.","Oops...","error");
				}
			}
			if($this->db_type){
				$records2 = $this->execute("INSERT INTO icp_shop_items (item_id, owner_id, count, enchant, augmented, augment_ref, fire, water, wind, earth, holy, unholy, type, price) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$item_id,$charid,$count,$enchant,$augment,$augment_ref,$fire,$water,$wind,$earth,$holy,$unholy,$type,$price]);
				if($records2){
					for($y=0;$y<count($items);$y++){
						$records3 = $this->execute($this->QUERY_ITEMS_DELETE,[$items[$y]]);
					}
				}
			}else{
				$records2 = $this->execute("INSERT INTO icp_shop_items (item_id, owner_id, type, price, date, status) VALUES (?,?,?,?,?,'1')",[$item_id,$charid,$type,$price,date("Y-m-d H:i:s")]);
			}
			$result = $type == 1 ? "The item successfully put up for sale." : $result;
			$result = $type == 2 ? "The combo items was created and successfully put up for sale." : $result;
			$result = $type == 3 ? "The auction was created and the item was successfully put up for sale." : $result;
			$result = $type == 4 ? "The auction was created and the combo items was successfully put up for sale." : $result;
			return $this->resposta($result,"Good job!","success");
		}
		
		public function unlock($charid,$login){
				$results = $this->execute($this->QUERY_UNLOCK_CHARACTER_1,[$login,$charid]);
				if(count($results) == 1){
					if($results[0]["online"] > 0){
						return $this->resposta("The character ".$results[0]["char_name"]." is online!<br>Logout and try again.","Oooh no!","error");
					}elseif($results[0]["karma"] > 0){
						return $this->resposta("The character ".$results[0]["char_name"]." have karma!<br>Unable to unlock character with karma.","Oooh no!","error");
					}else{
						$destravar = $this->teleToTown($results[0]["x"],$results[0]["y"]);
						if(!$this->db_type){
							$this->kick_char($charid);
							$this->teleport($charid, $destravar[0], $destravar[1], $destravar[2]);
						}else{
							$destravando1 = $this->execute($this->QUERY_UNLOCK_CHARACTER_2,[$destravar[0],$destravar[1],$destravar[2],$login,$charid]);
							$destravando2 = $this->execute($this->QUERY_UNLOCK_CHARACTER_4,[$charid]);
							$destravando3 = $this->execute($this->QUERY_UNLOCK_CHARACTER_5,[$charid]);
							$destravando4 = $this->execute($this->QUERY_UNLOCK_CHARACTER_6,[$charid]);
							$destravando5 = $this->execute($this->QUERY_UNLOCK_CHARACTER_7,[$charid]);
						}
						if(isset($this->QUERY_UNLOCK_CHARACTER_3) && !empty($this->QUERY_UNLOCK_CHARACTER_3)){
							$destravando6 = $this->execute($this->QUERY_UNLOCK_CHARACTER_3,[$charid]);
						}
						return $this->resposta("The character ".$results[0]["char_name"]." has been successfully unlocked!<br>Your character has been teleported to ".$destravar[3].", nearest city.".(!$this->db_type ? "<br>All of your equipped items have been shipped to Warehouse." : null),"Oh yeah!","success");
					}
				}else{
					return $this->resposta("Character not found.","Oops...","error");
				}
		}
		
		public function sendScreenshot($legend, $author, $photo, $login){
			$error = null;
			if (!empty($photo["name"])) {
				$height = 1024;
				$width = 1600;
				$weight = 1000000; // 1000000 = 1MB
				$dimensions = getimagesize($photo["tmp_name"]);
				if($dimensions){
					$error .= !in_array($dimensions[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)) ? "This is not an image.<br>" : null;
					$error .= $dimensions[1] > $height ? "The image height must not exceed ".$height." pixels.<br>" : null;
					$error .= $dimensions[0] > $width ? "The image width must not exceed ".$width." pixels.<br>" : null;
					$error .= $photo["size"] > $weight ? "The image must have a maximum of ".$weight." bytes.<br>" : null;
					if(empty($error)){
						preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $photo["name"], $ext);
						$imgName = md5(uniqid(time())) . "." . $ext[1];
						$imagePath = "images/screenshots/" . $imgName;
						move_uploaded_file($photo["tmp_name"], $imagePath);
						$this->createThumb("images/screenshots/",$imgName,"images/screenshots/thumbs/",$imgName,150,$ext[1]);
						$records = $this->execute("INSERT INTO icp_gallery_screenshots (legend, author, date, screenshot, account) VALUES (?,?,?,?,?)",[$legend,$author,date("Y-m-d H:i:s"),$imgName,$login]);
						return $this->resposta("ScreenShot sent!!!<br>Wait for approval from Staff.","Success!","success");
					}else{
						return $this->resposta($error,"Oops...","error");
					}
				}else{
					return $this->resposta("This is not an image.","Oops...","error");
				}
			}
		}
		
		public function sendVideo($legend, $author, $link, $login){
			if(substr(trim($link), 0, 31) == "http://www.youtube.com/watch?v=" || substr(trim($link), 0, 32) == "https://www.youtube.com/watch?v="){
				$id = substr(trim($link), 0, 31) == "http://www.youtube.com/watch?v=" ? substr(trim($link), 31, 11) : substr(trim($link), 32, 11);
				$video = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$id.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
				$photo = "http://img.youtube.com/vi/".$id."/default.jpg";
				$saving = $this->execute("INSERT INTO icp_gallery_videos (legend, author, date, link, photo, url, account) VALUES (?,?,?,?,?,?,?)",[$legend,$author,date("Y-m-d H:i:s"),$video,$photo,str_replace("watch?v=","embed/",$link),$login]);
				return $this->resposta("Video uploaded successfully!<br>Wait for approval from Staff.","Success!","success");
			}else{
				return $this->resposta("The video was not uploaded.<br>Cause: Invalid link!","Oops...","error");
			}
		}
		
		public function buyChar($charid,$login,$userId=null){
			if(!$this->ENABLE_CHARACTER_BROKER)
				return $this->resposta("Character Broker is disabled.","Oops...","error");
			$timeAuction = $this->AUCTION_CHARACTER_BROKER_DAYS * 86400;
			$checkMaxChars = $this->execute($this->QUERY_SELECT_CHARACTER,[$charid]);
			if(count($checkMaxChars) >= 7 || count($checkMaxChars) < 0){
				return $this->resposta("You already have the maximum number of characters allowed on this account. (".count($checkMaxChars)." characters)<br>You need to remove 1 character to add another one.","Oops...","error");
			}
			if($this->db_type){
				$records = $this->execute("SELECT s.*, CASE WHEN s.type = '2' THEN (SELECT CONCAT(account, ';', value) FROM icp_shop_chars_auction WHERE bidId = s.id ORDER BY id DESC LIMIT 1) END AS auction_details FROM icp_shop_chars AS s WHERE s.status = '1' AND s.id = ?",[$charid]);
			}else{
				$records = $this->execute("SELECT s.*, CASE WHEN s.type = '2' THEN (SELECT TOP 1 CONCAT(account, ';', value) FROM icp_shop_chars_auction WHERE bidId = s.id ORDER BY id DESC) END AS auction_details FROM icp_shop_chars AS s WHERE s.status = '1' AND s.id = ?",[$charid]);
			}
			if(count($records) == 1){
				if($records[0]["type"] == 2){
					if(empty($records[0]["auction_details"])){
						return $this->resposta("Unable to receive the character.","Oops...","error");
					}else{
						if((strtotime($records[0]["date"])+$timeAuction) < time()){
							$last_bid = explode(";",$records[0]["auction_details"]);
							if($login == $last_bid[0]){
								$this->addDonate($last_bid[1],$records[0]["account"]);
								$this->addDonateLog($last_bid[1]." ".$this->DONATE_COIN_NAME." added. Character sold at auction on Character Broker. ID: ".ltrim($records[0]["id"], "0"),0,$records[0]["account"]);
								$this->addDonateLog("Character purchase at auction on Character Broker. ID: ".ltrim($records[0]["id"], "0"),$last_bid[1],$login);
								if($this->db_type){
									$buying = $this->execute("UPDATE icp_shop_chars SET status = '0' WHERE id = ?",[$charid]);
									$buying2 = $this->execute($this->QUERY_CHANGE_CHARACTER_ACC,[$login,$records[0]["owner_id"],$records[0]["has_account"]]);
								}else{
									$buying_a = $this->execute("DELETE FROM icp_shop_chars_auction WHERE bidId = ?",[$charid]);
									$buying = $this->execute("DELETE FROM icp_shop_chars WHERE id = ?",[$charid]);
									if($records[0]["account"] != $login){
										$this->kick_char($records[0]["owner_id"]);
										$buf=pack("cVV",31,$records[0]["owner_id"],$userId).$this->tounicode($login).$this->tounicode("admin");
										$this->sendBuf($buf);
									}
								}
								return $this->resposta("Character successfully purchased!","Oh yeah!","success","?icp=panel&show=character-broker");
							}else{
								return $this->resposta("Old owner not found.","Oops...","error");
							}
						}else{
							return $this->resposta("The auction is in progress yet.","Oops...","error");
						}
					}
				}else{
					$credito = $this->donateBalance($login);
					if($credito >= $records[0]["price"]){
						$this->addDonate($records[0]["price"],$records[0]["account"]);
						$this->addDonateLog($records[0]["price"]." ".$this->DONATE_COIN_NAME." added. Character sold on Character Broker. ID: ".ltrim($records[0]["id"], "0"),0,$records[0]["account"]);
						$this->debitDonate($records[0]["price"],$login);
						$this->addDonateLog("Character purchase on Character Broker. ID: ".ltrim($records[0]["id"], "0"),$records[0]["price"],$login);
						if($this->db_type){
							$buying = $this->execute("UPDATE icp_shop_chars SET status = '0' WHERE id = ?",[$charid]);
							$buying2 = $this->execute($this->QUERY_CHANGE_CHARACTER_ACC,[$login,$records[0]["owner_id"],$records[0]["has_account"]]);
						}else{
							$buying = $this->execute("DELETE FROM icp_shop_chars WHERE id = ?",[$charid]);
							if($records[0]["account"] != $login){
								$this->kick_char($records[0]["owner_id"]);
								$buf=pack("cVV",31,$records[0]["owner_id"],$userId).$this->tounicode($login).$this->tounicode("admin");
								$this->sendBuf($buf);
							}
						}
						return $this->resposta("Character successfully purchased!","Oh yeah!","success","?icp=panel&show=character-broker");
					}else{
						return $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credito." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
					}
				}
			}
			return $this->resposta("Character not found.","Oops...","error");
		}
		
		public function buyItem($itemid,$charid,$login,$store=true){
			if(!$this->ENABLE_ITEM_BROKER && $store){
				return $this->resposta("Item Broker is disabled.","Oops...","error");
			}
			if(!$this->ENABLE_PRIME_SHOP && !$store){
				return $this->resposta("Prime Shop is disabled.","Oops...","error");
			}
			$timeAuction = $this->AUCTION_ITEM_BROKER_DAYS * 86400;
			if($store){
				if($this->db_type){
					$records = $this->execute("SELECT s.*, CASE WHEN s.type > '2' THEN (SELECT CONCAT(account, ';', value) FROM icp_shop_items_auction WHERE bidId = s.id ORDER BY id DESC LIMIT 1) END AS auction_details FROM icp_shop_items AS s WHERE s.status = '1' AND s.id = ?",[$itemid]);
				}else{
					$records = $this->execute("SELECT s.*, CASE WHEN s.type > '2' THEN (SELECT TOP 1 CONCAT(account, ';', value) FROM icp_shop_items_auction WHERE bidId = s.id ORDER BY id DESC) END AS auction_details FROM icp_shop_items AS s WHERE s.status = '1' AND s.id = ?",[$itemid]);
				}
			}else{
				$records = $this->execute("SELECT * FROM icp_prime_shop WHERE id = ?",[$itemid]);
			}
			if(count($records) == 1){
				$owner = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
				if(count($owner) == 1){
					$credito = $this->donateBalance($login);
					if($store){
						if($records[0]["type"] > 0 && $records[0]["type"] < 3){
							if($credito >= $records[0]["price"]){
								$old_owner = $this->execute($this->QUERY_SELECT_CHARACTER_ACC,[$records[0]["owner_id"]]);
								if(count($old_owner) == 1){
									$this->addDonate($records[0]["price"],$old_owner[0]["account_name"]);
									$this->addDonateLog($records[0]["price"]." ".$this->DONATE_COIN_NAME." added. Item(s) sold on Item Broker. ID: ".ltrim($records[0]["id"], "0"),0,$old_owner[0]["account_name"]);
									$this->debitDonate($records[0]["price"],$login);
									$this->addDonateLog("Item(s) purchase on Item Broker. ID: ".ltrim($records[0]["id"], "0"),$records[0]["price"],$login);
									if($this->db_type){
										$buying = $this->execute("UPDATE icp_shop_items SET status = '0' WHERE id = ?",[$itemid]);
									}else{
										$buying = $this->execute("DELETE FROM icp_shop_items WHERE id = ?",[$itemid]);
									}
									$items = explode(";", $records[0]["item_id"]);
									if($this->db_type){
										$count = explode(";", $records[0]["count"]);
										$enchant = explode(";", $records[0]["enchant"]);
										$augment_ref = explode(";", $records[0]["augment_ref"]);
										$fire = explode(";", $records[0]["fire"]);
										$water = explode(";", $records[0]["water"]);
										$wind = explode(";", $records[0]["wind"]);
										$earth = explode(";", $records[0]["earth"]);
										$holy = explode(";", $records[0]["holy"]);
										$unholy = explode(";", $records[0]["unholy"]);
										$loc = $this->ITEM_BROKER_LOC_PLACE == "INVENTORY" ? "INVENTORY" : "WAREHOUSE";
									}
									for($x=0;$x<(count($items)-1);$x++){
										if($this->db_type){
											if(empty($fire[$x]) && empty($water[$x]) && empty($wind[$x]) && empty($earth[$x]) && empty($holy[$x]) && empty($unholy[$x]) && empty($augment_ref[$x])){
												$augAtt = null;
											}else{
												$augAtt = $fire[$x].",".$water[$x].",".$wind[$x].",".$earth[$x].",".$holy[$x].",".$unholy[$x].",".$augment_ref[$x];
											}
											$this->sendItem($items[$x],$count[$x],$enchant[$x],$loc,$charid,false,$augAtt);
										}else{
											if($records[0]["owner_id"] != $charid){
												$this->kick_char($records[0]["owner_id"]);
												$this->kick_char($charid);
												$buf=pack("cVVVV",40,$records[0]["owner_id"],$items[$x],$charid,1).$this->tounicode("admin");
												$this->sendBuf($buf);
											}
										}
									}
									return $this->resposta("Successfully purchased item!","Oh yeah!","success","?icp=panel&show=item-broker");
								}else{
									return $this->resposta("Old owner not found.","Oops...","error");
								}
							}else{
								return $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credito." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
							}
						}elseif($records[0]["type"] > 2 && $records[0]["type"] < 5){
							if(empty($records[0]["auction_details"])){
								return $this->resposta("Unable to receive the item(s).","Oops...","error");
							}else{
								if((strtotime($records[0]["date"])+$timeAuction) < time()){
									$old_owner = $this->execute($this->QUERY_SELECT_CHARACTER_ACC,$records[0]["owner_id"]);
									if(count($old_owner) == 1){
										$last_bid = explode(";",$records[0]["auction_details"]);
										$this->addDonate($last_bid[1],$old_owner[0]["account_name"]);
										$this->addDonateLog($last_bid[1]." ".$this->DONATE_COIN_NAME." added. Item(s) sold at auction on Item Broker. ID: ".ltrim($records[0]["id"], "0"),0,$old_owner[0]["account_name"]);
										$this->addDonateLog("Item(s) purchase at auction on Item Broker. ID: ".ltrim($records[0]["id"], "0"),$last_bid[1],$login);
										if($this->db_type)
											$buying = $this->execute("UPDATE icp_shop_items SET status = '0' WHERE id = ?",[$itemid]);
										else{
											$buying_a = $this->execute("DELETE FROM icp_shop_items_auction WHERE bidId = ?",[$itemid]);
											$buying = $this->execute("DELETE FROM icp_shop_items WHERE id = ?",[$itemid]);
										}
										$items = explode(";", $records[0]["item_id"]);
										if($this->db_type){
											$count = explode(";", $records[0]["count"]);
											$enchant = explode(";", $records[0]["enchant"]);
											$augment_ref = explode(";", $records[0]["augment_ref"]);
											$fire = explode(";", $records[0]["fire"]);
											$water = explode(";", $records[0]["water"]);
											$wind = explode(";", $records[0]["wind"]);
											$earth = explode(";", $records[0]["earth"]);
											$holy = explode(";", $records[0]["holy"]);
											$unholy = explode(";", $records[0]["unholy"]);
											$loc = $this->ITEM_BROKER_LOC_PLACE == "INVENTORY" ? "INVENTORY" : "WAREHOUSE";
										}
										for($x=0;$x<(count($items)-1);$x++){
											if($this->db_type){
												if(empty($fire[$x]) && empty($water[$x]) && empty($wind[$x]) && empty($earth[$x]) && empty($holy[$x]) && empty($unholy[$x]) && empty($augment_ref[$x])){
													$augAtt = null;
												}else{
													$augAtt = $fire[$x].",".$water[$x].",".$wind[$x].",".$earth[$x].",".$holy[$x].",".$unholy[$x].",".$augment_ref[$x];
												}
												$this->sendItem($items[$x],$count[$x],$enchant[$x],$loc,$charid,false,$augAtt);
											}else{
												if($records[0]["owner_id"] != $charid){
													$this->kick_char($records[0]["owner_id"]);
													$this->kick_char($charid);
													$buf=pack("cVVVV",40,$records[0]["owner_id"],$items[$x],$charid,1).$this->tounicode("admin");
													$this->sendBuf($buf);
												}
											}
										}
										return $this->resposta("Item(s) successfully purchased!","Oh yeah!","success","?icp=panel&show=item-broker");
									}else{
										return $this->resposta("Old owner not found.","Oops...","error");
									}
								}else{
									return $this->resposta("The auction is in progress yet.","Oops...","error");
								}
							}
						}
					}else{
						if($credito >= $records[0]["price"]){
							$this->debitDonate($records[0]["price"],$login);
							$this->addDonateLog("Item(s) purchase on Prime Shop. ID: ".ltrim($records[0]["id"], "0"),$records[0]["price"],$login);
							$items = explode(",", $records[0]["item_id"]);
							$count = explode(",", $records[0]["count"]);
							$enchant = explode(",", $records[0]["enchant"]);
							$fire = explode(",", $records[0]["attribute_fire"]);
							$water = explode(",", $records[0]["attribute_water"]);
							$wind = explode(",", $records[0]["attribute_wind"]);
							$earth = explode(",", $records[0]["attribute_earth"]);
							$holy = explode(",", $records[0]["attribute_holy"]);
							$unholy = explode(",", $records[0]["attribute_unholy"]);
							$loc = $this->PRIME_SHOP_LOC_PLACE == "INVENTORY" ? "INVENTORY" : "WAREHOUSE";
							for($x=0;$x<(count($items)-1);$x++){
								$stack = true;
								if(empty($fire[$x]) && empty($water[$x]) && empty($wind[$x]) && empty($earth[$x]) && empty($holy[$x]) && empty($unholy[$x])){
									$augAtt = null;
								}else{
									$augAtt = $fire[$x].",".$water[$x].",".$wind[$x].",".$earth[$x].",".$holy[$x].",".$unholy[$x];
								}
								$stackable = $this->execute("SELECT itemType FROM ".$this->chronicleTables("icp_icons")." WHERE itemId = ?",[$items[$x]]);
								if(count($stackable) == 1){
									$stack = $stackable[0]["itemType"] == "Armor" || $stackable[0]["itemType"] == "Weapon" ? false : $stack;
								}
								$this->sendItem($items[$x],$count[$x],$enchant[$x],$loc,$charid,$stack,$augAtt,$store);
							}
							return $this->resposta("Item(s) successfully purchased!","Oh yeah!","success","?icp=panel&show=prime-shop");
						}else{
							return $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credito." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
						}
					}
				}else{
					return $this->resposta("Character not found!<br>Check if the character is offline.","Oops...","error");
				}
			}
			return $this->resposta("Item not found.","Oops...","error");
		}
		
		private function sendItem($itemid,$count,$enchant,$loc,$ownerid,$stackable=true,$augAtt=null,$store=true){
			if($this->db_type){
				if($stackable){
					$records = $this->execute($this->QUERY_ITEMS_DETAILS_2,[$itemid,$ownerid,$loc]);
					if(count($records) > 0){
						$updating = $this->execute($this->QUERY_ITEMS_UPDATE,[$count,$enchant,$itemid,$ownerid,$loc]);
					}else{
						$id_max = $this->execute($this->QUERY_ITEMS_MAX_ID);
						$new_id = 1000 + $id_max[0]["max"];
						$column = null;
						$colNum = 0;
						$colVal = null;
						$stmt = $this->gameServer->prepare('SHOW COLUMNS FROM items');
						$defaultZero = array("price_sell", "price_buy", "custom_type1", "custom_type2", "custom_flags", "life_time", "augmentation_id", "attribute_fire", "attribute_water", "attribute_wind", "attribute_earth", "attribute_holy", "attribute_unholy", "agathion_energy", "creator_id", "fish_owner_id", "creation_time", "visual_item_id");
						$defaultNull = array("time_of_use", "data");
						$defaultEmpty = array("attributes", "process");
						$defaultNegative = array("mana_left", "time");
						if($stmt->execute()){
							while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
								if($colNum > 6){
									if(in_array($row["Field"], $defaultZero)){
										$colVal .= "'0'";
									}elseif(in_array($row["Field"], $defaultNull)){
										$colVal .= "NULL";
									}elseif(in_array($row["Field"], $defaultEmpty)){
										$colVal .= "''";
									}elseif(in_array($row["Field"], $defaultNegative)){
										$colVal .= "'-1'";
									}
									$column .= $row["Field"];
									if($colNum < ($stmt->rowCount()-1)){
										$column .= ", ";
										$colVal .= ", ";
									}
								}
								$colNum++;
							}
						}
						$inserting = $this->execute(str_replace("{CUSTOM_VALS}",$colVal,str_replace("{CUSTOM_COLS}",$column,$this->QUERY_ITEMS_INSERT)),[$ownerid,$new_id,$itemid,$count,$enchant,$loc]);
					}
					return true;
				}else{
					$id_max = $this->execute($this->QUERY_ITEMS_MAX_ID);
					$new_id = 1000 + $id_max[0]["max"];
					$att = null;
					if(!empty($augAtt)){
						$att = explode(",", $augAtt);
						if($store){
							if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS)){
								$item_variations = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS,[$new_id,$att[6]]);
							}
							if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS)){
								$item_elementals = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS,[$new_id,$att[6]]);
							}
							if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES)){
								$item_attributes = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES,[$new_id,$att[6]]);
							}
							if(isset($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS) && !empty($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS)){
								$item_augmentations = $this->execute($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS,[$new_id,$att[6]]);
							}
						}else{
							if(isset($this->QUERY_INSERT_ITEM_STATS_ITEM_ATTRIBUTES) && !empty($this->QUERY_INSERT_ITEM_STATS_ITEM_ATTRIBUTES)){
								for($y=0;$y<6;$y++){
									if(!empty($att[$y])){
										$item_attributes = $this->execute($this->QUERY_INSERT_ITEM_STATS_ITEM_ATTRIBUTES,[$new_id,$y,$att[$y]]);
									}
								}
							}
							if(isset($this->QUERY_INSERT_ITEM_STATS_ITEM_ELEMENTALS) && !empty($this->QUERY_INSERT_ITEM_STATS_ITEM_ELEMENTALS)){
								for($x=0;$x<6;$x++){
									if(!empty($att[$x])){
										$item_elementals = $this->execute($this->QUERY_INSERT_ITEM_STATS_ITEM_ELEMENTALS,[$new_id,$x,$att[$x]]);
									}
								}
							}
						}
					}
					$column = null;
					$colNum = 0;
					$colVal = null;
					$stmt = $this->gameServer->prepare('SHOW COLUMNS FROM items');
					$defaultZero = array("price_sell", "price_buy", "custom_type1", "custom_type2", "custom_flags", "life_time", "augmentation_id", "attribute_fire", "attribute_water", "attribute_wind", "attribute_earth", "attribute_holy", "attribute_unholy", "agathion_energy", "visual_item_id", "variation_stone_id", "variation1_id", "variation2_id", "appearance_stone_id", "visual_id", "isBlessed");
					$defaultNull = array("time_of_use");
					$defaultEmpty = array("attributes", "data");
					$process = array("process");
					$creator = array("creator_id", "first_owner_id");
					$creator_time = array("creation_time");
					$defaultNegative = array("mana_left", "time");
					if($stmt->execute()){
						while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
							if($colNum > 6){
								if(in_array($row["Field"], $defaultZero)){
									if(!empty($augAtt)){
										if($row["Field"] == "attribute_fire"){
											$colVal .= !empty($att[0]) ? "'".$att[0]."'" : "'0'";
										}elseif($row["Field"] == "attribute_water"){
											$colVal .= !empty($att[1]) ? "'".$att[1]."'" : "'0'";
										}elseif($row["Field"] == "attribute_wind"){
											$colVal .= !empty($att[2]) ? "'".$att[2]."'" : "'0'";
										}elseif($row["Field"] == "attribute_earth"){
											$colVal .= !empty($att[3]) ? "'".$att[3]."'" : "'0'";
										}elseif($row["Field"] == "attribute_holy"){
											$colVal .= !empty($att[4]) ? "'".$att[4]."'" : "'0'";
										}elseif($row["Field"] == "attribute_unholy"){
											$colVal .= !empty($att[5]) ? "'".$att[5]."'" : "'0'";
										}elseif($row["Field"] == "augmentation_id"){
											$colVal .= !empty($att[6]) ? $att[6] > 0 ? "'".$att[6]."'" : "'0'" : "'0'";
										}else{
											$colVal .= "'0'";
										}
									}else{
										$colVal .= "'0'";
									}
								}elseif(in_array($row["Field"], $defaultNull)){
									$colVal .= "NULL";
								}elseif(in_array($row["Field"], $defaultEmpty)){
									$colVal .= "''";
								}elseif(in_array($row["Field"], $process)){
									$colVal .= "'Init'";
								}elseif(in_array($row["Field"], $creator)){
									$colVal .= "'".$ownerid."'";
								}elseif(in_array($row["Field"], $creator_time)){
									$colVal .= "'".time()."'";
								}elseif(in_array($row["Field"], $defaultNegative)){
									$colVal .= "'-1'";
								}
								$column .= $row["Field"];
								if($colNum < ($stmt->rowCount()-1)){
									$column .= ", ";
									$colVal .= ", ";
								}
							}
							$colNum++;
						}
					}
					$inserting = $this->execute(str_replace("{CUSTOM_VALS}",$colVal,str_replace("{CUSTOM_COLS}",$column,$this->QUERY_ITEMS_INSERT)),[$ownerid,$new_id,$itemid,$count,$enchant,$loc]);
					return true;
				}
			}else{
				$this->kick_char($ownerid);
				$loc = $loc == 'INVENTORY' ? 0 : 1;
				$buf=pack("cVVVVVVVVV",55,$ownerid,$loc,$itemid,$count,$enchant,0,0,0,0).$this->tounicode("admin");
				$this->sendBuf($buf);
				return true;
			}
			return false;
		}
		
		public function bid($id,$value,$login,$itemBroker=false){
			if(!$itemBroker && !$this->ALLOW_AUCTION_CHARACTER_BROKER || $itemBroker && !$this->ALLOW_AUCTION_ITEM_BROKER)
				return $this->resposta("Auctions is disabled.","Oops...","error");
			$table = !$itemBroker ? "icp_shop_items" : "icp_shop_chars";
			$auctionBrokerDays = !$itemBroker ? $this->AUCTION_ITEM_BROKER_DAYS : $this->AUCTION_CHARACTER_BROKER_DAYS;
			if($this->db_type){
				$checkAuction = $this->execute("SELECT * FROM ".$table." WHERE IF(type > '2', (UNIX_TIMESTAMP(date) + '".($auctionBrokerDays * 86400)."') > '".time()."', '1'='1') AND status = '1' AND id = ?",[$id]);
			}else{
				$checkAuction = $this->execute("SELECT * FROM ".$table." WHERE CASE WHEN type > '2' THEN CASE WHEN DATEADD(DAY,".($auctionBrokerDays * 86400).",s.date) > '".date("Y-m-d H:i:s")."' THEN '0' ELSE '1' END ELSE '0' END = '0' AND status = '1' AND id = ?",[$id]);
			}
			if(count($checkAuction) > 0){
				$result = null;
				$table2 = !$itemBroker ? "icp_shop_items_auction" : "icp_shop_chars_auction";
				if($this->db_type){
					$records = $this->execute("SELECT value, account FROM ".$table2." WHERE bidId = ? ORDER BY id DESC LIMIT 1",[$id]);
				}else{
					$records = $this->execute("SELECT TOP 1 value, account FROM ".$table2." WHERE bidId = ? ORDER BY id DESC",[$id]);
				}
				$credito = $this->donateBalance($login);
				if(count($records) > 0){
					for($x=0;$x<count($records);$x++){
						if($records[$x]["value"] < $value && $credito > $records[$x]["value"] && $credito >= $value){
							if($this->insertBid($table2,$id,$login,$value,$records[$x]["value"],$records[$x]["account"],false)){
								$result .= "Bid successfully sent!";
							}
						}
					}
				}else{
					for($x=0;$x<count($checkAuction);$x++){
						if($checkAuction[$x]["price"] <= $value && $credito >= $checkAuction[$x]["price"] && $credito >= $value){
							if($this->insertBid($table2,$id,$login,$value)){
								$result .= "Bid successfully sent!";
							}
						}
					}
				}
				return empty($result) ? $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credito." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error") : $this->resposta($result,"Oooh yeah!","success");
			}else
				return $this->resposta("The auction has ended!","Oooh no!","error");
		}
		
		private function insertBid($table,$id,$login,$value,$lastBidValue=0,$lastBidAccount=null,$firstBid=true){
			$biding = $this->execute("INSERT INTO ".$table." (bidId, account, value, date) VALUES (?,?,?,?)",[$id,$login,$value,date("Y-m-d H:i:s")]);
			if($biding){
				if(!$firstBid){
					$this->addDonate($lastBidValue,$lastBidAccount);
					$this->addDonateLog($lastBidValue." ".$this->DONATE_COIN_NAME." added. Auction bid refund. Auction ID: ".ltrim($id,"0"),0,$lastBidAccount);
				}
				$this->debitDonate($value,$login);
				$this->addDonateLog("Auction bidding. Auction ID: ".ltrim($id, "0"),$value,$login);
				return true;
			}else{
				return false;
			}
		}
		
		public function cancelItemBroker($id,$login){
			$timeAuction = $this->AUCTION_ITEM_BROKER_DAYS * 86400;
			$records = $this->execute($this->QUERY_CANCEL_ITEM_BROKER,[$login,$id]);
			if(count($records) == 1){
				if($this->db_type){
					$items = explode(";", $records[0]["item_id"]);
					$count = explode(";", $records[0]["count"]);
					$enchant = explode(";", $records[0]["enchant"]);
					$augment_ref = explode(";", $records[0]["augment_ref"]);
					$fire = explode(";", $records[0]["fire"]);
					$water = explode(";", $records[0]["water"]);
					$wind = explode(";", $records[0]["wind"]);
					$earth = explode(";", $records[0]["earth"]);
					$holy = explode(";", $records[0]["holy"]);
					$unholy = explode(";", $records[0]["unholy"]);
					$loc = $this->ITEM_BROKER_LOC_PLACE == "INVENTORY" ? "INVENTORY" : "WAREHOUSE";
					for($y=0;$y<(count($items)-1);$y++){
						if(empty($fire[$y]) && empty($water[$y]) && empty($wind[$y]) && empty($earth[$y]) && empty($holy[$y]) && empty($unholy[$y]) && empty($augment_ref[$y])){
							$augAtt = null;
						}else{
							$augAtt = $fire[$y].",".$water[$y].",".$wind[$y].",".$earth[$y].",".$holy[$y].",".$unholy[$y].",".$augment_ref[$y];
						}
						$this->sendItem($items[$y],$count[$y],$enchant[$y],$loc,$records[0]["owner_id"],false,$augAtt);
					}
				}
				$deleting = $this->execute("DELETE FROM icp_shop_items WHERE id = ?",[$id]);
				return $this->resposta("Items returned successfully.","Success!","success");
			}else{
				return $this->resposta("Something went wrong.","Oops...","error");
			}
		}
		
		public function cancelCharacterBroker($id,$login){
			$records = $this->execute("SELECT s.* FROM icp_shop_chars AS s WHERE CASE WHEN s.type = '2' THEN CASE WHEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) IS NULL THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.status = '1' AND s.account = ? AND s.id = ?",[$login,$id]);
			if(count($records) == 1){
				if($this->db_type){
					$updating = $this->execute($this->QUERY_CHANGING_ACC,[$records[0]["account"],$records[0]["has_account"],$records[0]["owner_id"]]);
				}
				$deleting = $this->execute("DELETE FROM icp_shop_chars WHERE id = ?",[$id]);
				return $this->resposta("Character returned successfully.","Success!","success");
			}else{
				return $this->resposta("Something went wrong.","Oops...","error");
			}
		}
		
		public function enchantItem($charid,$login,$itemid){
			if(!$this->ENABLE_SAFE_ENCHANT_SYSTEM){
				return $this->resposta("Safe enchant system is disabled.","Oops...","error");
			}
			$noPvpItems = !$this->db_type ? str_replace("i.item_id","i.item_type",$this->noPvpItems) : $this->noPvpItems;
			$wherePvP = !$this->ALLOW_ENCHANT_PVP_ITEMS ? $noPvpItems : null;
			$records = $this->execute($this->QUERY_SELECT_ITEM_TO_ENCHANT.$wherePvP,[$itemid,$charid,$login]);
			if(count($records) == 1){
				$allowedGrades = explode(",",$this->allowEnchantItemsGrade);
				if(!in_array($records[0]["itemGrade"],$allowedGrades)){
					return $this->resposta(ucfirst($records[0]["itemGrade"])."-grade items are not allowed.","Oops...","warning");
				}
				if($records[0]["enchant_level"] >= $this->MAX_ENCHANT){
					return $this->resposta("This item already has the maximum enchants allowed.","Oops...","warning");
				}
				$augment = false;
				if(isset($records[0]["augmentation_id"]) && !empty($records[0]["augmentation_id"])){
					$augment = $records[0]["augmentation_id"] > 0 ? true : false;
				}
				if(isset($records[0]["augmentation"]) && !empty($records[0]["augmentation"])){
					$augment = $records[0]["augmentation"] > 0 ? true : false;
				}
				if(!isset($records[0]["augmentation_id"]) && !isset($records[0]["augmentation"])){
					$augElem = explode(",", $this->getAugElem($itemid));
					$augment = $augElem[6] > 0 ? true : false;
				}
				if($augment && !$this->ALLOW_ENCHANT_AUGMENTED_ITEMS){
					return $this->resposta("Augmented items are prohibited.","Oops...","error");
				}
				switch ($records[0]["itemGrade"]){
					case "d":
						$price = $this->PRICE_D_GRADE_ITEMS; break;
					case "c":
						$price = $this->PRICE_C_GRADE_ITEMS; break;
					case "b":
						$price = $this->PRICE_B_GRADE_ITEMS; break;
					case "a":
						$price = $this->PRICE_A_GRADE_ITEMS; break;
					case "s":
						$price = $this->PRICE_S_GRADE_ITEMS; break;
					case "s80":
						$price = $this->PRICE_S80_GRADE_ITEMS; break;
					case "s84":
						$price = $this->PRICE_S84_GRADE_ITEMS; break;
					case "r":
						$price = $this->PRICE_R_GRADE_ITEMS; break;
					case "r95":
						$price = $this->PRICE_R95_GRADE_ITEMS; break;
					case "r99":
						$price = $this->PRICE_R99_GRADE_ITEMS; break;
					case "r110":
						$price = $this->PRICE_R110_GRADE_ITEMS; break;
					default:
						$price = 1000; break;
				}
				$current_enchant = is_numeric($records[0]["enchant_level"]) ? $records[0]["enchant_level"] : 1000000;
				$price = !empty($price) ? $price : 1000000;
				if($current_enchant < 1000000){
					$credito = $this->donateBalance($login);
					if($credito >= $price){
						$chance = $this->ENCHANT_SYSTEM_CHANCE;
						$percent = rand(1,100);
						if($percent <= $chance){
							if($this->db_type){
								$id_max = $this->execute($this->QUERY_ITEMS_MAX_ID);
								$new_id = 1000 + $id_max[0]["max"];
								if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS)){
									$item_variations = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_VARIATIONS,[$new_id,$itemid]);
								}
								if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS)){
									$item_elementals = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_ELEMENTALS,[$new_id,$itemid]);
								}
								if(isset($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES) && !empty($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES)){
									$item_attributes = $this->execute($this->QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES,[$new_id,$itemid]);
								}
								if(isset($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS) && !empty($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS)){
									$item_augmentations = $this->execute($this->QUERY_UPDATE_ITEM_STATS_AUGMENTATIONS,[$new_id,$itemid]);
								}
								$enchanting = $this->execute($this->QUERY_UPDATE_ITEM_ENCHANTING,[($current_enchant + 1),$new_id,$itemid]);
							}else{
								$new_id = $records[0]["item_id"];
								$this->kick_char($charid);
								$buf=pack("cVVVVVVVVVV",14,$charid,$records[0]["warehouse"],$itemid,$records[0]["item_type"],1,($current_enchant + 1),0,0,0,0).$this->tounicode("admin");
								$this->sendBuf($buf);
							}
						}
						$this->debitDonate($price,$login);
						$itemid = $percent > $chance ? $itemid : $new_id;
						$this->addDonateLog($percent <= $chance ? "Enchant item ID[".$itemid."] of +".$current_enchant." to +".($current_enchant + 1)."." : "Enchantment of item ID[".$itemid."] failed",$price,$login);
						$enchanting = $percent > $chance ? "Swal.fire('Oooh no!', 'The enchantment failed.', 'error')" : "Swal.fire('Oh yeah!', 'The enchantment was a success!!!', 'success')";
						$timeJS = 8500;
						$timeSuccess = $timeJS * ($chance / 100);
						$timeFail = $timeJS - $timeSuccess;
						$percentBar1 = $percent > $chance ? $chance : $percent;
						$percentBar2 = $percent > $chance ? $percent - $chance : 0;
						return "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\" type=\"text/javascript\"></script><script src=\"//cdn.jsdelivr.net/npm/sweetalert2@10\"></script><script type=\"text/javascript\">$(document).ready(function(){ Swal.fire({ title: 'Enchanting', html: '<div class=\"card mt-3 mb-4\"><div class=\"card-header text-center\">Enchanting your item, please wait...</div><div class=\"card-body\"><div class=\"progress mb-2\"><div class=\"progress-bar progress-bar-striped progress-bar-animated bg-success\" id=\"successBar\" style=\"width:0%\"><span id=\"percentBar\"></span></div><div class=\"progress-bar progress-bar-striped progress-bar-animated bg-danger\" id=\"failBar\" style=\"width:0%\"></div></div><div style=\"width:100%;\"><div style=\"width:".$chance."%; float:left; border:1px solid #ccc; border-top:0px; font-size:4px;\">&nbsp;</div><div style=\"width:".(100 - $chance)."%; float:left; border:1px solid #ccc; border-left:0px; border-top:0px; font-size:4px;\">&nbsp;</div><div style=\"width:".$chance."%; float:left; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:4px;\">&nbsp;</div><div style=\"width:".(100 - $chance)."%; float:left; border-right:1px solid #ccc; font-size:4px;\">&nbsp;</div><div style=\"width:".$chance."%; float:left; font-size:14px; text-align:center; overflow:hidden; margin-top:3px;\"><div style=\"position:relative;top:0px;left:0px;\"><div style=\"position:absolute;top:0px;left:5px;\">0%</div><div style=\"position:absolute;top:0px;right:0px;\">".$chance."%</div></div>&nbsp;</div><div style=\"width:".(100 - $chance)."%; float:left; font-size:14px; text-align:center; overflow:hidden; margin-top:3px;\"><div style=\"position:relative;top:0px;left:0px;\"><div style=\"position:absolute;top:0px;right:0px;\">100%</div></div>&nbsp;</div></div></div></div>', allowOutsideClick: false, showDenyButton: false, showCloseButton: false, showCancelButton: false, showConfirmButton: false }); var percent = 1; var percentBar = document.getElementById('percentBar'); function increment(){ if(percent <= ".$percent.") { percentBar.innerHTML = percent + '%'; } if(percent == ".$percent."){ clearInterval(run); setTimeout(function(){ ".$enchanting." },500); } ++percent; } var run = setInterval(increment, parseInt(".($timeJS-($percent > $chance ? 0 : $timeFail))."/".$percent.")); $(\"#successBar\").animate({ width: \"".$percentBar1."%\" }, ".$timeSuccess."); setTimeout(function(){ $(\"#failBar\").animate({ width: \"".$percentBar2."%\" }, ".$timeFail."); }, ".$timeSuccess."); });</script>";
					}else{
						return $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credito." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
					}
				}
			}
			return $this->resposta("Something went wrong.","Oops...","error");
		}
		
		public function accountChange($charid,$login,$account){
			if(empty($charid)){
				return $this->resposta("Select a character!","Oops...","error");
			}
			if(empty($account)){
				return $this->resposta("Invalid new account!","Oops...","error");
			}
			if(!$this->ALLOW_CHARACTER_NICKNAME_CHANGE){
				return $this->resposta("Account change is disabled.","Oops...","error");
			}
			$results = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
			if(count($results) == 1){
				$nick_check = $this->execute($this->db_type ? $this->QUERY_LOGIN_3 : $this->QUERY_LOGIN_5,[$account],"login");
				if(count($nick_check) == 1){
					$credit = $this->donateBalance($login);
					if($credit >= $this->CHARACTER_ACCOUNT_CHANGE_PRICE){
						$this->debitDonate($this->CHARACTER_ACCOUNT_CHANGE_PRICE,$login);
						$this->addDonateLog("Account changed: ".$results[0]["char_name"]." changed to ".$account.".",$this->CHARACTER_ACCOUNT_CHANGE_PRICE,$login);
						if($this->db_type){
							$acc_changing = $this->execute($this->QUERY_CHANGING_ACC,[$account,$login,$charid]);
						}else{
							$this->kick_char($charid);
							$buf=pack("cVV",31,$charid,$nick_check[0]["uid"]).$this->tounicode($account).$this->tounicode("admin");
							$this->sendBuf($buf);
						}
						return $this->resposta("The character has been successfully transferred to the ".$account." account.","Success!","success");
					}else{
						return $this->resposta("You dont have enough coins to execute this action.<br>Your current balance is ".$credit." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.");
					}
				}else{
					return $this->resposta("The account ".$account." does not exist.<br>Try again.","Oops...","error");
				}
			}else{
				return $this->resposta("The character was not found.<br>The character maybe is online!<br>Try again.","Oops","error");
			}
		}
		
		public function classChange($charid,$login,$newBaseId){
			if(empty($charid)){
				return $this->resposta("Select a character!","Oops...","error");
			}
			if(!$this->ALLOW_CHARACTER_BASE_CLASS_CHANGE){
				return $this->resposta("Base class change is disabled.","Oops...","error");
			}
			$raca = $this->getRace($newBaseId);
			if($raca != 100){
				$results = $this->execute($this->QUERY_CHARACTER_CHANGE_CLASS,[$login,$charid]);
				if(count($results) == 1){
					$credit = $this->donateBalance($login);
					if($credit >= $this->CHARACTER_BASE_CLASS_CHANGE_PRICE){
						if(($results[0]["base_class"] >= 123 and $results[0]["base_class"] <= 136) || ($results[0]["base_class"] >= 192 and $results[0]["base_class"] <= 195)){
							return $this->resposta("Sorry, the requisition has been canceled!<br>Kamaeis are prohibited of base change.","Oops...","error");
						}else{
							$this->debitDonate($this->CHARACTER_BASE_CLASS_CHANGE_PRICE,$login);
							$this->addDonateLog("Base class changed: ".$results[0]["char_name"]." changed base class ".$this->classe_name($results[0]["base_class"])." to ".$this->classe_name($newBaseId).".",$this->CHARACTER_BASE_CLASS_CHANGE_PRICE,$login);
							if($this->db_type){
								$deleting_skills = $this->execute($this->QUERY_DELETE_CHARACTER_SKILLS,[$charid]);
								$updating_oly = $this->execute($this->QUERY_UPDATE_CHARACTER_OLYMPIADS,[$newBaseId,$charid]);
								if(isset($this->QUERY_UPDATE_CHARACTER_HEROES) && !empty($this->QUERY_UPDATE_CHARACTER_HEROES)){
									$updating_hero = $this->execute($this->QUERY_UPDATE_CHARACTER_HEROES,[$newBaseId,$charid]);
								}
								$changing_base = $this->execute($this->QUERY_UPDATE_CHARACTER_BASECLASS,[$newBaseId,$raca,$newBaseId,$login,$charid]);
							}else{
								$this->kick_char($charid);
								$buf=pack("cVVVVVVV",16,$charid,$results[0]["gender"],$raca,$newBaseId,$results[0]["face_index"],$results[0]["hair_shape_index"],$results[0]["hair_color_index"]).$this->tounicode("admin");
								$this->sendBuf($buf);
							}
							return $this->resposta("Base Class of the character has been successfully exchanged!","Success!","success");
						}
					}else{
						return $this->resposta("You do not have ".$this->DONATE_COIN_NAME." enoug.<br>Your current balance is ".$credit." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
					}
				}else{
					return $this->resposta("The character was not found.<br>The character can be online, log out!<br>Try again.","Oops...","error");
				}
			}else{
				return $this->resposta("An error happened when trying to switch the base class.","Oops...","error");
			}
		}
		
		public function sexChange($charid,$login,$sex){
			if(empty($charid)){
				return $this->resposta("Select a character!","Oops...","error");
			}
			if(!$this->ALLOW_CHARACTER_SEX_CHANGE){
				return $this->resposta("Sex change is disabled.","Oops...","error");
			}
			$results = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
			if(count($results) == 1){
				$credit = $this->donateBalance($login);
				if($credit >= $this->CHARACTER_SEX_CHANGE_PRICE){
					if(($results[0]["base_class"] >= 123 and $results[0]["base_class"] <= 136) || ($results[0]["base_class"] >= 192 and $results[0]["base_class"] <= 195)){
						return $this->resposta("Sorry, the requisition has been canceled!<br>Kamaeis are prohibited of sex change.","Oops...","error");
					}elseif(($results[0]["base_class"] >= 196 and $results[0]["base_class"] <= 207) || ($results[0]["base_class"] >= 217 and $results[0]["base_class"] <= 220)){
						return $this->resposta("Sorry, the requisition has been canceled!<br>".$this->classe_name($results[0]["base_class"])." are prohibited of sex change.","Oops...","error");
					}else{
						$sex = $sex == 1 ? 0 : 1;
						$currentSex = $results[0]["sex"] == 0 ? "Male" : "Female";
						$newSex = $sex == 0 ? "Male" : "Female";
						if($currentSex == $newSex){
							return $this->resposta("You selected ".$newSex.", but your character is already ".$newSex.".<br>This action was canceled and no ".$this->DONATE_COIN_NAME." was used.","Oops...","error");
						}else{
							$this->debitDonate($this->CHARACTER_SEX_CHANGE_PRICE,$login);
							$this->addDonateLog("Sex changed: ".$results[0]["char_name"]." changed sex from ".$currentSex." to ".$newSex.".",$this->CHARACTER_SEX_CHANGE_PRICE,$login);
							if($this->db_type){
								$sex_changing = $this->execute($this->QUERY_UPDATE_CHARACTER_SEX,[$sex,$login,$charid]);
							}else{
								$this->kick_char($charid);
								$raca = $this->getRace($results[0]["base_class"]);
								if($raca != 100){
									$buf=pack("cVVVVVVV",16,$charid,$sex,$raca,$results[0]["base_class"],$results[0]["face_index"],$results[0]["hair_shape_index"],$results[0]["hair_color_index"]).$this->tounicode("admin");
									$this->sendBuf($buf);
								}
							}
							return $this->resposta("The sex has been successfully changed to ".$newSex.".","Success!","success");
						}
					}
				}else{
					return $this->resposta("You dont have enough coins to execute this action.<br>Your current balance is ".$credit." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
				}
			}else{
				return $this->resposta("The character was not found.<br>The character maybe is online!<br>Try again.","Oops...","error");
			}
		}
		
		public function nickChange($charid,$login,$nick){
			if(empty($charid)){
				return $this->resposta("Select a character!","Oops...","error");
			}
			if(empty($nick)){
				return $this->resposta("Invalid new nickname!","Oops...","error");
			}
			if(!$this->ALLOW_CHARACTER_NICKNAME_CHANGE){
				return $this->resposta("Nickname change is disabled.","Oops...","error");
			}
			$results = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
			if(count($results) == 1){
				$nick_check = $this->execute($this->QUERY_SELECT_CHARACTER_NAME_1,[$nick]);
				if(count($nick_check) > 0){
					return $this->resposta("This nick name is already in use.<br>Choose another and try again.","Oops...","error");
				}else{
					$credit = $this->donateBalance($login);
					if($credit >= $this->CHARACTER_NICKNAME_CHANGE_PRICE){
						$this->debitDonate($this->CHARACTER_NICKNAME_CHANGE_PRICE,$login);
						$this->addDonateLog("Nickname changed: ".$results[0]["char_name"]." changed nickname to ".$nick.".",$this->CHARACTER_NICKNAME_CHANGE_PRICE,$login);
						if($this->db_type){
							if(isset($this->QUERY_UPDATE_CHARACTER_NAME_OLYMPIADS) && !empty($this->QUERY_UPDATE_CHARACTER_NAME_OLYMPIADS)){
								$update_oly = $this->execute($this->QUERY_UPDATE_CHARACTER_NAME_OLYMPIADS,[$nick,$charid]);
							}
							if(isset($this->QUERY_UPDATE_CHARACTER_NAME_HEROES) && !empty($this->QUERY_UPDATE_CHARACTER_NAME_HEROES)){
								$update_hero = $this->execute($this->QUERY_UPDATE_CHARACTER_NAME_HEROES,[$nick,$charid]);
							}
							$nick_changing = $this->execute($this->QUERY_UPDATE_CHARACTER_NAME,[$nick,$login,$charid]);
						}else{
							$this->kick_char($charid);
							$buf=pack("cV",4,$charid).$this->tounicode($nick).$this->tounicode("admin");
							$this->sendBuf($buf);
						}
						return $this->resposta("The nick name has been successfully changed to ".$nick.".","Success!","success");
					}else{
						return $this->resposta("You dont have enough coins to execute this action.<br>Your current balance is ".$credit." ".$this->DONATE_COIN_NAME.".<br>Make a donation and increase your balance.","Oooh no!","error");
					}
				}
			}else{
				return $this->resposta("The character was not found.<br>The character maybe is online!<br>Try again.","Oops...","error");
			}
		}
		
		protected function chronicleTables($table){
			switch ($table){
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
				default:
					$name = "chronicleTables ERROR"; break;
			}
			return $name;
		}
		
		private function getItemName($itemId){
			$item = $this->execute("SELECT itemName FROM ".$this->chronicleTables("icp_icons")." WHERE itemId = ?",[$itemId]);
			if(count($item) == 1){
				return $item[0]["itemName"];
			}else{
				return "No_name";
			}
		}
		
		private function kkk($qtd){
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
		
		private function remainingTime($data,$abrevia = false) {
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
			$return .= $segun > 0 ? "<strong>".$segun."</strong>" : null;
			$return .= $segun > 0 ? $abrevia ? "s." : " second(s)." : null;
			return $return;
		}
		
		private function reward($login){
			if(!$this->ENABLE_REWARD_SYSTEM){
				return "0;0;0";
			}
			if($this->db_type){
				$reward = $this->execute("SELECT SUM(c.onlinetime) AS online_time, SUM(c.pvpkills) AS pvp, SUM(c.pkkills) AS pk, IF((SELECT COUNT(*) FROM icp_rewards WHERE account = c.account_name) > 0, (SELECT CONCAT(onlinetime, ';', pvpkills, ';', pkkills) FROM icp_rewards WHERE account = c.account_name), '0;0;0') AS reward_records FROM characters AS c WHERE c.account_name = ?",[$login]);
			}else{
				$reward = $this->execute("SELECT SUM(c.use_time) AS online_time, SUM(c.Duel) AS pvp, SUM(c.PK) AS pk, CASE WHEN (SELECT COUNT(*) FROM icp_rewards WHERE account = c.account_name) > '0' THEN (SELECT CONCAT(onlinetime, ';', pvpkills, ';', pkkills) FROM icp_rewards WHERE account = c.account_name) ELSE '0;0;0' END AS reward_records FROM user_data AS c WHERE c.account_name = ?",[$login]);
			}
			if(count($reward) == 1){
				$reward_records = explode(";", $reward[0]["reward_records"]);
				return ($reward[0]["online_time"] - $reward_records[0] ?? 0).";".($reward[0]["pvp"] - $reward_records[1] ?? 0).";".($reward[0]["pk"] - $reward_records[2] ?? 0);
			}else{
				return "0;0;0";
			}
		}
		
		public function getReward($charid,$online,$pvp,$pk,$login){
			if(!$this->ENABLE_REWARD_SYSTEM || !$this->ALLOW_REWARD_ONLINE_TIME && !$this->ALLOW_REWARD_PVP && !$this->ALLOW_REWARD_PK){
				return $this->resposta("Rewards system is disabled.","Oops...","error");
			}
			$result = null;
			$rewards = explode(";", $this->reward($login));
			$online = !empty($online) ? (floor($rewards[0] / (86400 * $this->REWARD_ONLINE_TIME_DAYS)) * 86400) : 0;
			$pvp = !empty($pvp) ? (floor($rewards[1] / $this->REWARD_PVP_COUNT) * $this->REWARD_PVP_COUNT) : 0;
			$pk = !empty($pk) ? (floor($rewards[2] / $this->REWARD_PK_COUNT) * $this->REWARD_PK_COUNT) : 0;
			if(empty($online) && empty($pvp) && empty($pk)){
				return $this->resposta("You have no rewards to receive.","Oooh no!","error");
			}else{
				$online_check = $this->execute($this->QUERY_SELECT_CHARACTER_OFFLINE,[$charid,$login]);
				if(count($online_check) == 1){
					$records = $this->execute("SELECT * FROM icp_rewards WHERE account = ?",[$login]);
					if(count($records) > 0){
						$updating_reward = $this->execute("UPDATE icp_rewards SET onlinetime = (onlinetime + ?), pvpkills = (pvpkills + ?), pkkills = (pkkills + ?) WHERE account = ?",[$online,$pvp,$pk,$login]);
					}else{
						$inserting_reward = $this->execute("INSERT INTO icp_rewards (onlinetime, pvpkills, pkkills, account) VALUES (?,?,?,?)",[$online,$pvp,$pk,$login]);
					}
					if($this->ALLOW_REWARD_ONLINE_TIME && $online > 0){
						$result .= "You won: ";
						$onlineItems = explode(";", $this->REWARD_ONLINE_TIME_ITEMS);
						for($x=0;$x<(count($onlineItems)-1);$x++){
							$OI = explode(",", $onlineItems[$x]);
							$OICount = $OI[1] * ($online/86400);
							$result .= $OICount > 999 ? $this->kkk($OICount)." of " : $this->kkk($OICount)." ";
							$result .= $OI[0] == 18000 ? $this->DONATE_COIN_NAME : $this->getItemName($OI[0]);
							if($x==(count($onlineItems)-2)){
								$result .= " ";
							}elseif($x==(count($onlineItems)-3)){
								$result .= " and ";
							}else{
								$result .= ", ";
							}
							if($OI[0] == 18000){
								$this->addDonate($OICount,$login);
								$this->addDonateLog("Reward System: You won: ".$OICount." ".$this->DONATE_COIN_NAME." for ".str_replace("<strong>", "", str_replace("</strong>", "", str_replace(", ", "", $this->remainingTime($online, false))))." online.",0,$login);
							}else{
								$this->sendItem($OI[0],$OICount,0,$this->REWARD_SYSTEM_LOC,$charid);
							}
						}
						$result .= "for ".str_replace("<strong>", "", str_replace("</strong>", "", str_replace(", ", "", $this->remainingTime($online, false))))." online exchanged.<br>";
					}
					if($this->ALLOW_REWARD_PVP && $pvp > 0){
						$result .= "You won: ";
						$pvpItems = explode(";", $this->REWARD_PVP_ITEMS);
						for($z=0;$z<(count($pvpItems)-1);$z++){
							$PI = explode(",", $pvpItems[$z]);
							$PICount = $PI[1] * ($pvp/$this->REWARD_PVP_COUNT);
							$result .= $PICount > 999 ? $this->kkk($PICount)." of " : $this->kkk($PICount)." ";
							$result .= $PI[0] == 18000 ? $this->DONATE_COIN_NAME : $this->getItemName($PI[0]);
							if($z==(count($pvpItems)-2)){
								$result .= " ";
							}elseif($z==(count($pvpItems)-3)){
								$result .= " and ";
							}else{
								$result .= ", ";
							}
							if($PI[0] == 18000){
								$this->addDonate($PICount,$login);
								$this->addDonateLog("Reward System: You won: ".$PICount." ".$this->DONATE_COIN_NAME." for ".$pvp." PvP(s) points.",0,$login);
							}else{
								$this->sendItem($PI[0],$PICount,0,$this->REWARD_SYSTEM_LOC,$charid);
							}
						}
						$result .= "for ".$pvp." PvP\'s points exchanged.<br>";
					}
					if($this->ALLOW_REWARD_PK && $pk > 0){
						$result .= "You won: ";
						$pkItems = explode(";", $this->REWARD_PK_ITEMS);
						for($y=0;$y<(count($pkItems)-1);$y++){
							$PkI = explode(",", $pkItems[$y]);
							$PkICount = $PkI[1] * ($pk/$this->REWARD_PK_COUNT);
							$result .= $PkICount > 999 ? $this->kkk($PkICount)." of " : $this->kkk($PkICount)." ";
							$result .= $PkI[0] == 18000 ? $this->DONATE_COIN_NAME : $this->getItemName($PkI[0]);
							if($y==(count($pkItems)-2)){
								$result .= " ";
							}elseif($y==(count($pkItems)-3)){
								$result .= " and ";
							}else{
								$result .= ", ";
							}
							if($PkI[0] == 18000){
								$this->addDonate($PkICount,$login);
								$this->addDonateLog("Reward System: You won: ".$PkICount." ".$this->DONATE_COIN_NAME." for ".$pk." Pk(s) points.",0,$login);
							}else{
								$this->sendItem($PkI[0],$PkICount,0,$this->REWARD_SYSTEM_LOC,$charid);
							}
						}
						$result .= "for ".$pk." Pk\'s points exchanged.<br>";
					}
				}else{
					return $this->resposta("Character not found.<br>Check if the character is offline and try again.","Oops...","error");
				}
			}
			return empty($result) ? $this->resposta("You have no rewards to receive.","Oooh no!","error") : $this->resposta($result,"Good job!","success");
		}
		
		public function saveConfigs($SITE_NAME,$siteTitle,$SERVER,$safeEnchant,$maxEnchant,$xpRate,$spRate,$dropRate,$spoilRate,$template,$olyPeriod,$timezone,$instagram,$youtube,$facebook,$discord,$maxRankings,$maxIndexRankings,$clientDownload,$systemDownload,$accCreateByEmail,$accRecoveryByEmail,$smtpHost,$smtpPort,$smtpEmail,$smtpPass,$donateCoinName,$enableDeposit,$depositBank,$depositBranch,$depositAccount,$depositType,$depositBeneficiary,$depositCpf,$donateEmail,$enableMercadopago,$mpCurrency,$mpCoins,$mpToken,$enablePagseguro,$psCurrency,$psCoins,$psEmail,$psToken,$enablePaypal,$ppCurrency,$ppCoins,$ppEmail,$enableMessages,$enableNews,$maxNews,$enableScreenshots,$maxScreenshots,$enableVideos,$maxVideos,$enableBosses,$enableCastles,$enableClanHalls,$enableTopPvp,$enableTopClassPvp,$maxClassPvp,$enableTopPk,$enableTopClassPk,$maxClassPk,$enableTopOnline,$enableTopAdena,$goldbarValue,$enableTopClan,$enableTopClanByPvp,$enableTopOly,$enableTopHero,$enableTopRaid,$enableRewardSystem,$rewardSystemLoc,$enableRewardOnline,$rewardOnlineDays,$rewardOnlineItems,$enableRewardPvp,$rewardPvpCount,$rewardPvpItems,$enableRewardPk,$rewardPkCount,$rewardPkItems,$enablePrimeShop,$maxPrimeShop,$primeShopLoc,$enableItemBroker,$allowSellItemsGrade,$allowComboItems,$allowPvpItems,$allowAugmentedItems,$allowAuctionItems,$auctionItemsDay,$auctionRangeItems,$maxItemBroker,$itemBrokerLoc,$enableCharacterBroker,$allowAuctionCharacters,$auctionCharactersDay,$auctionRangeCharacters,$minCharacterBrokerLevel,$maxCharacterBroker,$enableSafeEnchant,$allowPvpEnchant,$allowAugmentedEnchant,$enchantChance,$allowEnchantItemsGrade,$enchantDGrade,$enchantCGrade,$enchantBGrade,$enchantAGrade,$enchantSGrade,$enchantS80Grade,$enchantS84Grade,$enchantRGrade,$enchantR95Grade,$enchantR99Grade,$enchantR110Grade,$enableCharacterChanges,$enableBaseChange,$baseChangePrice,$enableSexChange,$sexChangePrice,$enableNickChange,$nickChangePrice,$enableAccChange,$accChangePrice,$enableCheckStatus,$forceLoginOnline,$forceGameOnline,$allowServerStats,$enableFakePlayers,$fakePlayers,$login,$senderPrivId){
			if($senderPrivId == 10){
				$saveConfigs = $this->execute("INSERT INTO icp_configs (SITE_NAME, SITE_TITLE, SERVER, SAFE_ENCHANT, MAX_ENCHANT, XP_RATE, SP_RATE, DROP_RATE, SPOIL_RATE, TEMPLATE, OLY_PERIOD_DAYS, TIME_ZONE, INSTAGRAM, YOUTUBE, FACEBOOK, DISCORD, MAX_RANKINGS, MAX_INDEX_RANKINGS, CLIENT_DOWNLOAD_LINK, FILES_DOWNLOAD_LINK, CreateAccWithEmail, RecoveryAccWithEmail, SMTP_HOST, SMTP_PORT, SMTP_EMAIL, SMTP_PASS, DONATE_COIN_NAME, enable_deposit, bank_name, bank_branch, bank_account, bank_type, bank_beneficiary, bank_cpf, email_donate_confirmation, enable_mercadopago, mp_currency, mp_amount, mp_token, enable_pagseguro, ps_currency, ps_amount, ps_email, ps_token, enable_paypal, pp_currency, pp_amount, pp_email, enable_messages, enable_news, MAX_NEWS, enable_screenshots, MAX_SCREENSHOTS_GALLERY, enable_videos, MAX_VIDEOS_GALLERY, enable_bosses, enable_castles, enable_clan_halls, enable_top_pvp, enable_top_class_pvp, MAX_RANKING_PVP_BY_CLASSES, enable_top_pk, enable_top_class_pk, MAX_RANKING_PK_BY_CLASSES, enable_top_online, enable_top_adena, GOLDBAR_VALUE, enable_top_clan, TOP_CLAN_BY_PVP, enable_top_oly, enable_top_hero, enable_top_raid, ENABLE_REWARD_SYSTEM, REWARD_SYSTEM_LOC, ALLOW_REWARD_ONLINE_TIME, REWARD_ONLINE_TIME_DAYS, REWARD_ONLINE_TIME_ITEMS, ALLOW_REWARD_PVP, REWARD_PVP_COUNT, REWARD_PVP_ITEMS, ALLOW_REWARD_PK, REWARD_PK_COUNT, REWARD_PK_ITEMS, ENABLE_PRIME_SHOP, MAX_PRIME_SHOP_ITEMS, PRIME_SHOP_LOC_PLACE, ENABLE_ITEM_BROKER, allowSellItemsGrade, ALLOW_ITEM_BROKER_SALE_COMBO_ITEMS, ALLOW_ITEM_BROKER_SALE_PVP_ITEMS, ALLOW_ITEM_BROKER_SALE_AUGMENTED_ITEMS, ALLOW_AUCTION_ITEM_BROKER, AUCTION_ITEM_BROKER_DAYS, AUCTION_ITEM_RANGES_BID, MAX_ITEM_BROKER_LIST, ITEM_BROKER_LOC_PLACE, ENABLE_CHARACTER_BROKER, ALLOW_AUCTION_CHARACTER_BROKER, AUCTION_CHARACTER_BROKER_DAYS, AUCTION_CHARACTER_RANGES_BID, MIN_CHARACTER_BROKER_LEVEL, MAX_CHARACTER_BROKER_LIST, ENABLE_SAFE_ENCHANT_SYSTEM, ALLOW_ENCHANT_PVP_ITEMS, ALLOW_ENCHANT_AUGMENTED_ITEMS, ENCHANT_SYSTEM_CHANCE, allowEnchantItemsGrade, PRICE_D_GRADE_ITEMS, PRICE_C_GRADE_ITEMS, PRICE_B_GRADE_ITEMS, PRICE_A_GRADE_ITEMS, PRICE_S_GRADE_ITEMS, PRICE_S80_GRADE_ITEMS, PRICE_S84_GRADE_ITEMS, PRICE_R_GRADE_ITEMS, PRICE_R95_GRADE_ITEMS, PRICE_R99_GRADE_ITEMS, PRICE_R110_GRADE_ITEMS, enable_character_changes, ALLOW_CHARACTER_BASE_CLASS_CHANGE, CHARACTER_BASE_CLASS_CHANGE_PRICE, ALLOW_CHARACTER_SEX_CHANGE, CHARACTER_SEX_CHANGE_PRICE, ALLOW_CHARACTER_NICKNAME_CHANGE, CHARACTER_NICKNAME_CHANGE_PRICE, ALLOW_CHARACTER_ACCOUNT_CHANGE, CHARACTER_ACCOUNT_CHANGE_PRICE, enable_servers_check, force_login_server, force_game_server, allow_server_stats, enable_fake_players, fake_players_number, saved_by, saved_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$SITE_NAME,$siteTitle,$SERVER,$safeEnchant,$maxEnchant,$xpRate,$spRate,$dropRate,$spoilRate,$template,$olyPeriod,$timezone,$instagram,$youtube,$facebook,$discord,$maxRankings,$maxIndexRankings,empty($clientDownload) ? "#" : $clientDownload,empty($systemDownload) ? "#" : $systemDownload,!empty($accCreateByEmail) ? 1 : $accCreateByEmail,!empty($accRecoveryByEmail) ? 1 : $accRecoveryByEmail,$smtpHost,$smtpPort,$smtpEmail,$smtpPass,$donateCoinName,!empty($enableDeposit) ? 1 : $enableDeposit,$depositBank,$depositBranch,$depositAccount,$depositType,$depositBeneficiary,$depositCpf,$donateEmail,!empty($enableMercadopago) ? 1 : $enableMercadopago,$mpCurrency,$mpCoins,$mpToken,!empty($enablePagseguro) ? 1 : $enablePagseguro,$psCurrency,$psCoins,$psEmail,$psToken,!empty($enablePaypal) ? 1 : $enablePaypal,$ppCurrency,$ppCoins,$ppEmail,!empty($enableMessages) ? 1 : $enableMessages,!empty($enableNews) ? 1 : $enableNews,$maxNews,!empty($enableScreenshots) ? 1 : $enableScreenshots,$maxScreenshots,!empty($enableVideos) ? 1 : $enableVideos,$maxVideos,!empty($enableBosses) ? 1 : $enableBosses,!empty($enableCastles) ? 1 : $enableCastles,!empty($enableClanHalls) ? 1 : $enableClanHalls,!empty($enableTopPvp) ? 1 : $enableTopPvp,!empty($enableTopClassPvp) ? 1 : $enableTopClassPvp,$maxClassPvp,!empty($enableTopPk) ? 1 : $enableTopPk,!empty($enableTopClassPk) ? 1 : $enableTopClassPk,$maxClassPk,!empty($enableTopOnline) ? 1 : $enableTopOnline,!empty($enableTopAdena) ? 1 : $enableTopAdena,$goldbarValue,!empty($enableTopClan) ? 1 : $enableTopClan,!empty($enableTopClanByPvp) ? 1 : $enableTopClanByPvp,!empty($enableTopOly) ? 1 : $enableTopOly,!empty($enableTopHero) ? 1 : $enableTopHero,!empty($enableTopRaid) ? 1 : $enableTopRaid,!empty($enableRewardSystem) ? 1 : $enableRewardSystem,$rewardSystemLoc,!empty($enableRewardOnline) ? 1 : $enableRewardOnline,$rewardOnlineDays,$rewardOnlineItems,!empty($enableRewardPvp) ? 1 : $enableRewardPvp,$rewardPvpCount,$rewardPvpItems,!empty($enableRewardPk) ? 1 : $enableRewardPk,$rewardPkCount,$rewardPkItems,!empty($enablePrimeShop) ? 1 : $enablePrimeShop,$maxPrimeShop,$primeShopLoc,!empty($enableItemBroker) ? 1 : $enableItemBroker,$allowSellItemsGrade,!empty($allowComboItems) ? 1 : $allowComboItems,!empty($allowPvpItems) ? 1 : $allowPvpItems,!empty($allowAugmentedItems) ? 1 : $allowAugmentedItems,!empty($allowAuctionItems) ? 1 : $allowAuctionItems,$auctionItemsDay,$auctionRangeItems,$maxItemBroker,$itemBrokerLoc,!empty($enableCharacterBroker) ? 1 : $enableCharacterBroker,!empty($allowAuctionCharacters) ? 1 : $allowAuctionCharacters,$auctionCharactersDay,$auctionRangeCharacters,$minCharacterBrokerLevel,$maxCharacterBroker,!empty($enableSafeEnchant) ? 1 : $enableSafeEnchant,!empty($allowPvpEnchant) ? 1 : $allowPvpEnchant,!empty($allowAugmentedEnchant) ? 1 : $allowAugmentedEnchant,$enchantChance,$allowEnchantItemsGrade,$enchantDGrade,$enchantCGrade,$enchantBGrade,$enchantAGrade,$enchantSGrade,$enchantS80Grade,$enchantS84Grade,$enchantRGrade,$enchantR95Grade,$enchantR99Grade,$enchantR110Grade,!empty($enableCharacterChanges) ? 1 : $enableCharacterChanges,!empty($enableBaseChange) ? 1 : $enableBaseChange,$baseChangePrice,!empty($enableSexChange) ? 1 : $enableSexChange,$sexChangePrice,!empty($enableNickChange) ? 1 : $enableNickChange,$nickChangePrice,!empty($enableAccChange) ? 1 : $enableAccChange,$accChangePrice,!empty($enableCheckStatus) ? 1 : $enableCheckStatus,!empty($forceLoginOnline) ? 1 : $forceLoginOnline,!empty($forceGameOnline) ? 1 : $forceGameOnline,!empty($allowServerStats) ? 1 : $allowServerStats,!empty($enableFakePlayers) ? 1 : $enableFakePlayers,$fakePlayers,$login,date("Y-m-d H:i:s")]);
				if($saveConfigs){
					return $this->resposta("Settings saved successfully!","Good Job!","success","?icp=panel&show=adm-configs");
				}else{
					return $this->resposta("Error trying to save settings","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function addPrimeShop($itemId=array(),$itemCount=array(),$itemEnchant=array(),$itemFire=array(),$itemWater=array(),$itemWind=array(),$itemEarth=array(),$itemHoly=array(),$itemDark=array(),$itemPrice,$senderPrivId){
			if($senderPrivId >= 9){
				if($this->ENABLE_PRIME_SHOP){
					$item_price = $this->filter($itemPrice) > 0 ? $this->filter($itemPrice) : 1;
					$item_arr = array();
					$item_id = null;
					$item_count = null;
					$item_enchant = null;
					$item_fire = null;
					$item_water = null;
					$item_wind = null;
					$item_earth = null;
					$item_holy = null;
					$item_dark = null;
					$qtd_itens = count($itemId);
					for($x=0;$x<$qtd_itens;$x++){
						$item_id = $this->filter($itemId[$x]) > 0 ? $this->filter($itemId[$x]) : 1;
						$item_count = $this->filter($itemCount[$x]) > 0 ? $this->filter($itemCount[$x]) : 1;
						$item_enchant = $this->filter($itemEnchant[$x]) > 0 ? $this->filter($itemEnchant[$x]) : 0;
						$item_fire = $this->filter($itemFire[$x]) > 0 ? $this->filter($itemFire[$x]) : 0;
						$item_water = $this->filter($itemWater[$x]) > 0 ? $this->filter($itemWater[$x]) : 0;
						$item_wind = $this->filter($itemWind[$x]) > 0 ? $this->filter($itemWind[$x]) : 0;
						$item_earth = $this->filter($itemEarth[$x]) > 0 ? $this->filter($itemEarth[$x]) : 0;
						$item_holy = $this->filter($itemHoly[$x]) > 0 ? $this->filter($itemHoly[$x]) : 0;
						$item_dark = $this->filter($itemDark[$x]) > 0 ? $this->filter($itemDark[$x]) : 0;
						array_push($item_arr, array("id" => $item_id, $item_count, $item_enchant, $item_fire, $item_water, $item_wind, $item_earth, $item_holy, $item_dark));
					}
					foreach ($item_arr as $key => $row) {
						$id[$key]  = $row['id'];
					}
					array_multisort($id, SORT_ASC, $item_arr);
					$item_id = null;
					$item_count = null;
					$item_enchant = null;
					$item_fire = null;
					$item_water = null;
					$item_wind = null;
					$item_earth = null;
					$item_holy = null;
					$item_dark = null;
					for($y=0;$y<count($item_arr);$y++){
						$item_id .= $item_arr[$y]["id"].",";
						$item_count .= $item_arr[$y][0].",";
						$item_enchant .= $item_arr[$y][1].",";
						$item_fire .= $item_arr[$y][2].",";
						$item_water .= $item_arr[$y][3].",";
						$item_wind .= $item_arr[$y][4].",";
						$item_earth .= $item_arr[$y][5].",";
						$item_holy .= $item_arr[$y][6].",";
						$item_dark .= $item_arr[$y][7].",";
					}
					$records = $this->execute("INSERT INTO icp_prime_shop (item_id,price,count,enchant,attribute_fire,attribute_water,attribute_wind,attribute_earth,attribute_holy,attribute_unholy) VALUES (?,?,?,?,?,?,?,?,?,?)",[$item_id,$item_price,$item_count,$item_enchant,$item_fire,$item_water,$item_wind,$item_earth,$item_holy,$item_dark]);
					if($records){
						return $this->resposta($qtd_itens > 1 ? "The combo of items was created successfully!" : "The item was created successfully!","Success!","success");
					}else{
						return $this->resposta("There was an error adding item.","Oops...","error");
					}
				}else{
					return $this->resposta("The Prime Shop system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function deletePrimeShop($id,$senderPrivId){
			if($senderPrivId >= 9){
				if($this->ENABLE_PRIME_SHOP){
					if(empty($id)){
						return $this->resposta("Item(s) not found.","Oops...","error");
					}
					$records = $this->execute("DELETE FROM icp_prime_shop WHERE id = ?",[$id]);
					if($records){
						return $this->resposta("Item(s) successfully removed.","Success!","success");
					}else{
						return $this->resposta("There was an error removing the item.","Oops...","error");
					}
				}else{
					return $this->resposta("The Prime Shop system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function approveScreenshot($id,$senderPrivId){
			if($senderPrivId >= 7){
				if($this->enable_screenshots){
					$records = $this->execute("UPDATE icp_gallery_screenshots SET status = CASE WHEN status = '0' THEN '1' ELSE '0' END WHERE id = ?",[$id]);
					if($records){
						return $this->resposta("Screenshot approved/disapproved successfully.","Success!","success");
					}else{
						return $this->resposta("There was an error approving/disapproved the screenshot.","Oops...","error");
					}
				}else{
					return $this->resposta("The Screenshot system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function deleteScreenshot($id,$senderPrivId){
			if($senderPrivId >= 7){
				if($this->enable_screenshots){
					$records = $this->execute("SELECT screenshot FROM icp_gallery_screenshots WHERE id = ?",[$id]);
					if(count($records) == 1){
						unlink("images/screenshots/".$records[0]["screenshot"]);
						unlink("images/screenshots/thumbs/".$records[0]["screenshot"]);
						$records2 = $this->execute("DELETE FROM icp_gallery_screenshots WHERE id = ?",[$id]);
						if($records2){
							return $this->resposta("Screenshot deleted successfully.","Success!","success");
						}else{
							return $this->resposta("There was an error deleting the screenshot.","Oops...","error");
						}
					}else{
						return $this->resposta("There was an error deleting the screenshot.","Oops...","error");
					}
				}else{
					return $this->resposta("The Screenshot system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function approveVideo($id,$senderPrivId){
			if($senderPrivId >= 7){
				if($this->enable_videos){
					$records = $this->execute("UPDATE icp_gallery_videos SET status = CASE WHEN status = '0' THEN '1' ELSE '0' END WHERE id = ?",[$id]);
					if($records){
						return $this->resposta("Video approved/disapproved successfully.","Success!","success");
					}else{
						return $this->resposta("There was an error approving/disapproved the video.","Oops...","error");
					}
				}else{
					return $this->resposta("The Video system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function deleteVideo($id,$senderPrivId){
			if($senderPrivId >= 7){
				if($this->enable_videos){
					$records = $this->execute("DELETE FROM icp_gallery_videos WHERE id = ?",[$id]);
					if($records){
						return $this->resposta("Video deleted successfully.","Success!","success");
					}else{
						return $this->resposta("There was an error deleting the video.","Oops...","error");
					}
				}else{
					return $this->resposta("The Video system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function addNews($title,$news,$edit=0,$login,$senderPrivId){
			if($senderPrivId >= 8){
				if($this->enable_news){
					if(empty($title)){
						return $this->resposta("Title is required!","Oops...","error");
					}
					if(empty($news)){
						return $this->resposta("News is required!","Oops...","error");
					}
					if($edit > 0){
						$records = $this->execute("UPDATE icp_news SET news = ?, title = ? WHERE id = ?",[preg_replace("/\r|\n/","",$news),$title,$edit]);
					}else{
						$records = $this->execute("INSERT INTO icp_news (news, title, author, date) VALUES (?,?,?,?)",[preg_replace("/\r|\n/","",$news),$title,$login,date("Y-m-d H:i:s")]);
					}
					if($records){
						return $this->resposta($edit > 0 ? "News successfully edited." : "News successfully posted.","Success!","success","?icp=panel&show=adm-news");
					}else{
						return $this->resposta("An error occurred while trying to post/edit the news.","Oops...","error","?icp=panel&show=adm-news");
					}
				}else{
					return $this->resposta("The news system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function editNews($id,$senderPrivId){
			if($senderPrivId >= 8){
				if($this->enable_news){
					$records = $this->execute("SELECT * FROM icp_news WHERE id = ?",[$id]);
					if(count($records) == 1){
						return array($records[0]["title"],$records[0]["news"],$records[0]["id"]);
					}
				}
			}
		}
		
		public function deleteNews($id,$senderPrivId){
			if($senderPrivId >= 8){
				if($this->enable_news){
					$records = $this->execute("DELETE FROM icp_news WHERE id = ?",[$id]);
					if($records){
						return $this->resposta("News deleted successfully.","Success!","success","?icp=panel&show=adm-news");
					}else{
						return $this->resposta("An error occurred while trying to delete the news.","Oops...","error","?icp=panel&show=adm-news");
					}
				}else{
					return $this->resposta("The news system is disabled.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		private function createThumb($imgPath,$imgName,$thumbPath,$thumbName,$thumbWidth,$ext){
			switch($ext){
				case "gif" : $source = imagecreatefromgif($imgPath.$imgName); break;
				case "jpeg" : $source = imagecreatefromjpeg($imgPath.$imgName); break;
				case "png" : $source = imagecreatefrompng($imgPath.$imgName); break;
				case "bmp" : $source = imagecreatefrombmp($imgPath.$imgName); break;
				default: $source = imagecreatefromjpeg($imgPath.$imgName); break;
			}
			$ratio = $thumbWidth / imagesx($source);
			$height = imagesy($source) * $ratio;
			$new_image = imagecreatetruecolor($thumbWidth, $height);
			imagecopyresampled($new_image, $source, 0, 0, 0, 0, $thumbWidth, $height, imagesx($source), imagesy($source));
			switch($ext){
				case "gif" : imagegif($new_image,$thumbPath.$thumbName); break;
				case "jpeg" : imagejpeg($new_image,$thumbPath.$thumbName); break;
				case "png" : imagepng($new_image,$thumbPath.$thumbName); break;
				case "bmp" : imagebmp($new_image,$thumbPath.$thumbName); break;
				default: imagejpeg($new_image,$thumbPath.$thumbName); break;
			}
		}
		
		public function editProfile($name,$email,$photo,$login,$senderPrivId){
			if($senderPrivId >= 6){
				if (!empty($photo["name"])) {
					$error = null;
					$height = 1024;
					$width = 1600;
					$weight = 1000000; // 1000000 = 1MB
					$dimensions = getimagesize($photo["tmp_name"]);
					if($dimensions){
						$error .= !in_array($dimensions[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)) ? "This is not an image.<br>" : null;
						$error .= $dimensions[1] > $height ? "The image height must not exceed ".$height." pixels.<br>" : null;
						$error .= $dimensions[0] > $width ? "The image width must not exceed ".$width." pixels.<br>" : null;
						$error .= $photo["size"] > $weight ? "The image must have a maximum of ".$weight." bytes.<br>" : null;
						if(empty($error)){
							preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $photo["name"], $ext);
							$imgName = md5(uniqid(time())) . "." . $ext[1];
							$imagePath = "images/profiles/" . $imgName;
							move_uploaded_file($photo["tmp_name"], $imagePath);
							$this->createThumb("images/profiles/",$imgName,"images/profiles/",$imgName,100,$ext[1]);
							$records = $this->execute("SELECT * FROM icp_staff WHERE login = ?",[$login]);
							if(count($records) == 1){
								if(!empty($records[0]["img"])){
									if(file_exists("images/profiles/".$records[0]["img"])){
										unlink("images/profiles/".$records[0]["img"]);
									}
								}
								$records2 = $this->execute("UPDATE icp_staff SET name = ?, email = ?, img = ? WHERE login = ?",[$name,$email,$imgName,$login]);
							}else{
								$records2 = $this->execute("INSERT INTO icp_staff (name, email, img, login) VALUES (?,?,?,?)",[$name,$email,$imgName,$login]);
							}
							if($records2){
								return $this->resposta("Profile saved successfully.","Success!","success");
							}else{
								return $this->resposta("An error occurred while trying to edit the profile.","Oops...","error");
							}
						}else{
							return $this->resposta($error,"Oops...","error");
						}
					}else{
						return $this->resposta("This is not an image.","Oops...","error");
					}
				}else{
					$records = $this->execute("SELECT * FROM icp_staff WHERE login = ?",[$login]);
					if(count($records) == 1){
						$records2 = $this->execute("UPDATE icp_staff SET name = ?, email = ? WHERE login = ?",[$name,$email,$login]);
					}else{
						$records2 = $this->execute("INSERT INTO icp_staff (name, email, img, login) VALUES (?,?,?,?)",[$name,$email,"",$login]);
					}
					if($records2){
						return $this->resposta("Profile saved successfully.","Success!","success");
					}else{
						return $this->resposta("An error occurred while trying to edit the profile.","Oops...","error");
					}
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function deleteProfile($login,$senderPrivId){
			if($senderPrivId >= 6){
				$records = $this->execute("SELECT img FROM icp_staff WHERE login = ?",[$login]);
				if(count($records) == 1){
					$records2 = $this->execute("DELETE FROM icp_staff WHERE login = ?",[$login]);
					if($records2){
						if(!empty($records[0]["img"])){
							if(file_exists("images/profiles/".$records[0]["img"])){
								unlink("images/profiles/".$records[0]["img"]);
							}
						}
						return $this->resposta("Profile deleted successfully.","Success!","success");
					}
				}
				return $this->resposta("An error occurred while trying to delete the profile.","Oops...","error");
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
		public function sendMsg($title,$msg,$id,$attachment,$recipient,$login,$senderPrivId){
			if($this->enable_messages){
				$records2 = $this->execute("SELECT * FROM icp_tickets_ban WHERE login = ? AND status = '1'",[$login]);
				if(count($records2) > 0){
					return $this->resposta("You are unable to send messages.","Oops...","error");
				}else{
					if(!empty($attachment["name"])){
						$error = null;
						if($attachment["type"] == "application/pdf"){
							$weight = 1000000; // 1000000 = 1MB
							$error .= $attachment["size"] > $weight ? "The PDF must have a maximum of ".$weight." bytes.<br>" : null;
							if(empty($error)){
								$pdfName = md5(uniqid(time())) . ".pdf";
								$pdfPath = "images/attachment/" . $pdfName;
								move_uploaded_file($attachment["tmp_name"], $pdfPath);
								if(!empty($id)){
									$records = $this->execute("SELECT * FROM icp_tickets WHERE id = ?",[$id]);
									if(count($records) == 1){
										if($records[0]["sender"] == $login || $senderPrivId > 5){
											if($records[0]["status"] == 1){
												$reply = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$id,strip_tags($msg),date("Y-m-d H:i:s"),$login,$pdfName]);
												if($reply){
													return $this->resposta("Posted successfully.","Success!","success");
												}else{
													return $this->resposta("An error occurred while trying to post.","Oops...","error");
												}
											}else{
												return $this->resposta("The topic has been locked and is unable to receive new messages.","Oops...","error");
											}
										}else{
											return $this->resposta("Posting not allowed.","Oops...","error");
										}
									}else
										return $this->resposta("Message not found.","Oops...","error");
								}else{
									$newMsg = $this->execute("INSERT INTO icp_tickets (title, sender, status) VALUES (?,?,'1')",[strip_tags($title),!empty($recipient) && $senderPrivId > 5 ? $recipient : $login]);
									if($newMsg){
										$newMsg2 = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$this->gameServer->lastInsertId(),strip_tags($msg),date("Y-m-d H:i:s"),$login,$pdfName]);
										if($newMsg2){
											return $this->resposta("Posted successfully.","Success!","success");
										}else{
											return $this->resposta("An error occurred while trying to post.","Oops...","error");
										}
									}else{
										return $this->resposta("An error occurred while trying to post.","Oops...","error");
									}
								}
							}else{
								return $this->resposta($error,"Oops...","error");
							}
						}else{
							$height = 1024;
							$width = 1600;
							$weight = 1000000; // 1000000 = 1MB
							$dimensions = getimagesize($attachment["tmp_name"]);
							if($dimensions){
								$error .= !in_array($dimensions[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)) ? "This is not an image.<br>" : null;
								$error .= $dimensions[1] > $height ? "The image height must not exceed ".$height." pixels.<br>" : null;
								$error .= $dimensions[0] > $width ? "The image width must not exceed ".$width." pixels.<br>" : null;
								$error .= $attachment["size"] > $weight ? "The image must have a maximum of ".$weight." bytes.<br>" : null;
								if(empty($error)){
									preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $attachment["name"], $ext);
									$imgName = md5(uniqid(time())) . "." . $ext[1];
									$imagePath = "images/attachment/" . $imgName;
									move_uploaded_file($attachment["tmp_name"], $imagePath);
									if(!empty($id)){
										$records = $this->execute("SELECT * FROM icp_tickets WHERE id = ?",[$id]);
										if(count($records) == 1){
											if($records[0]["sender"] == $login || $senderPrivId > 5){
												if($records[0]["status"] == 1){
													$reply = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$id,strip_tags($msg),date("Y-m-d H:i:s"),$login,$imgName]);
													if($reply){
														return $this->resposta("Posted successfully.","Success!","success");
													}else{
														return $this->resposta("An error occurred while trying to post.","Oops...","error");
													}
												}else{
													return $this->resposta("The topic has been locked and is unable to receive new messages.","Oops...","error");
												}
											}else{
												return $this->resposta("Posting not allowed.","Oops...","error");
											}
										}else
											return $this->resposta("Message not found.","Oops...","error");
									}else{
										$newMsg = $this->execute("INSERT INTO icp_tickets (title, sender, status) VALUES (?,?,'1')",[strip_tags($title),!empty($recipient) && $senderPrivId > 5 ? $recipient : $login]);
										if($newMsg){
											$newMsg2 = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$this->gameServer->lastInsertId(),strip_tags($msg),date("Y-m-d H:i:s"),$login,$imgName]);
											if($newMsg2){
												return $this->resposta("Posted successfully.","Success!","success");
											}else{
												return $this->resposta("An error occurred while trying to post.","Oops...","error");
											}
										}else{
											return $this->resposta("An error occurred while trying to post.","Oops...","error");
										}
									}
								}else{
									return $this->resposta($error,"Oops...","error");
								}
							}else{
								return $this->resposta("This is not an image.","Oops...","error");
							}
						}
					}else{
						if(!empty($id)){
							$records = $this->execute("SELECT * FROM icp_tickets WHERE id = ?",[$id]);
							if(count($records) == 1){
								if($records[0]["sender"] == $login || $senderPrivId > 5){
									if($records[0]["status"] == 1){
										$reply = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$id,strip_tags($msg),date("Y-m-d H:i:s"),$login,/*$attachment*/""]);
										if($reply){
											return $this->resposta("Posted successfully.","Success!","success");
										}else{
											return $this->resposta("An error occurred while trying to post.","Oops...","error");
										}
									}else{
										return $this->resposta("The topic has been locked and is unable to receive new messages.","Oops...","error");
									}
								}else{
									return $this->resposta("Posting not allowed.","Oops...","error");
								}
							}else
								return $this->resposta("Message not found.","Oops...","error");
						}else{
							$newMsg = $this->execute("INSERT INTO icp_tickets (title, sender, status) VALUES (?,?,'1')",[strip_tags($title),!empty($recipient) && $senderPrivId > 5 ? $recipient : $login]);
							if($newMsg){
								$newMsg2 = $this->execute("INSERT INTO icp_tickets_msgs (msg_id, message, date, answered, attach) VALUES (?,?,?,?,?)",[$this->gameServer->lastInsertId(),strip_tags($msg),date("Y-m-d H:i:s"),$login,/*$attachment*/""]);
								if($newMsg2){
									return $this->resposta("Posted successfully.","Success!","success");
								}else{
									return $this->resposta("An error occurred while trying to post.","Oops...","error");
								}
							}else{
								return $this->resposta("An error occurred while trying to post.","Oops...","error");
							}
						}
					}
				}
			}
		}
		
		public function banAccountMsgs($login,$type,$sender,$senderPrivId){
			if($this->enable_messages){
				if($senderPrivId >= 6){
					$checkName = $this->execute($this->db_type ? $this->QUERY_LOGIN_3 : $this->QUERY_LOGIN_5,[$login],"login");
					if(count($checkName) == 1){
						$records = $this->execute("SELECT login FROM icp_tickets_ban WHERE login = ? AND status = '1'",[$login]);
						if(count($records) == 1){
							if($type == 1){
								return $this->resposta("This user is already blocked.","Oops...","error");
							}elseif($type == 2){
								$records2 = $this->execute("UPDATE icp_tickets_ban SET status = ?, unblockedLogin = ?, unblockedDate = ? WHERE login = ? AND status = ?",["0",$sender,date("Y-m-d H:i:s"),$login,"1"]);
								return $records2 ? $this->resposta("Account successfully unblocked.","Success!","success") : $this->resposta("Error unblocking account","Oops...","error");
							}else{
								return $this->resposta("You are not allowed to do this.","Oops...","error");
							}
						}else{
							if($type == 1){
								$records2 = $this->execute("INSERT INTO icp_tickets_ban (login, blockedLogin, blockedDate, unblockedLogin, unblockedDate, status) VALUES (?,?,?,?,?,?)",[$login,$sender,date("Y-m-d H:i:s"),"","","1"]);
								return $records2 ? $this->resposta("Account successfully blocked.","Success!","success") : $this->resposta("Error unblocking account","Oops...","error");
							}elseif($type == 2){
								return $this->resposta("This user is already unblocked.","Oops...","error");
							}else
								return $this->resposta("You are not allowed to do this.","Oops...","error");
						}
					}else
						return $this->resposta("Account not found.","Oops...","error");
				}else{
					return $this->resposta("You are not allowed to do this.","Oops...","error");
				}
			}
		}
		
	}
	
}