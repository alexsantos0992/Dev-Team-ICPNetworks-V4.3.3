<?php
# Encriptação das senhas
# 0 = md5
# 1 = sha1 (Padrão)
# 2 = whirlpool
# 3 = sha3-256
# 4 = hash L2OFF
# 5 = encriptação nova aCis
$config["encrypt"] = 2;

# Cronicas
# 0 = C0 - Prelude
# 1 = C1 - Harbingers of War
# 2 = C2 - Age of Splendor
# 3 = C3 - Rise of Darkness
# 4 = C4 - Scions of Destiny
# 5 = C5 - Oath of Blood
# 6 = Interlude
# 7 = Kamael
# 8 = Hellbound
# 9 = Gracia - PT1
# 10 = Gracia - PT2
# 11 = Gracia Final
# 12 = Epilogue
# 13 = Freya
# 14 = High Five
# 15 = Goddess of Destruction - Awakening
# 16 = Goddess of Destruction - Harmony
# 17 = Goddess of Destruction - Tauti
# 18 = Goddess of Destruction - Glory Days
# 19 = Goddess of Destruction - Lindvior
# 20 = Goddess of Destruction - Valliance
# 21 = Epic Tale Of Aden - Ertheia
# 22 = Epic Tale Of Aden - Infinite Odyssey
# 23 = Epic Tale Of Aden - Hymn of the Soul
# 24 = Epic Tale Of Aden - Helios
# 25 = Epic Tale Of Aden - Grand Crusade
# 26 = Epic Tale Of Aden - Salvation
# 27 = Epic Tale Of Aden - Etina's Fate
# 28 = Epic Tale Of Aden - Fafurion
# 29 = Prologue - Prelude of War
# 30 = Classic 2.0 - Saviors
# 31 = Classic 2.5 - Zaken
# 32 = Classic 2.7 - Seven Sings
# 33 = Classic 2.9 - Secret of Empire
# 34 = Classic 3.0 - The Kamael
# 35 = Essence - Dwelling Of Spirits
# 36 = Essence - Frost Lord
# 37 = Essence - Battle Chronicle
$config["CHRONICLE_ID"] = 14;

$config["QUERY_LOGIN_1"] = "SELECT a.login, a.access_level, CASE WHEN (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = a.login) IS NULL THEN '' ELSE (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = a.login) END AS icp_table FROM accounts AS a WHERE a.login = ? AND a.password = ?";
$config["QUERY_LOGIN_2"] = "SELECT * FROM accounts WHERE login = ? AND password = ?";
$config["QUERY_LOGIN_3"] = "SELECT login FROM accounts WHERE login = ?";
$config["QUERY_LOGIN_4"] = "SELECT * FROM accounts WHERE login = ?";
$config["QUERY_REGISTER_1"] = "INSERT INTO accounts (login, password, access_level) VALUES (?,?,'-1')";
$config["QUERY_REGISTER_2"] = "INSERT INTO accounts (login, password, access_level) VALUES (?,?,'0')";
$config["QUERY_PASS_CHANGE"] = "UPDATE accounts SET password = ? WHERE login = ?";
$config["QUERY_BAN_ACC"] = "UPDATE accounts SET access_level = ? WHERE login = ?";
$config["QUERY_SELECT_ITEM_STATS_ITEM_ATTRIBUTES"] = "SELECT * FROM item_attributes WHERE object_id = ?";
$config["QUERY_UPDATE_ITEM_STATS_ITEM_ATTRIBUTES"] = "UPDATE item_attributes SET object_id = ? WHERE object_id = ?";
$config["QUERY_PUT_CHARACTER_FOR_SALE_1"] = "UPDATE characters SET account_name = ? WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_PUT_CHARACTER_FOR_SALE_2"] = "INSERT INTO icp_shop_chars (owner_id, account, has_account, type, price) VALUES (?,?,?,?,?)";
$config["QUERY_ITEMS_DETAILS_1"] = "SELECT i.*, t.itemGrade FROM items AS i, icp_icons_high_five AS t WHERE i.item_id = t.itemId AND i.object_id = ? AND i.owner_id = ? AND (SELECT online FROM characters WHERE obj_Id = i.owner_id) = '0' AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ?";
$config["QUERY_ITEMS_DETAILS_2"] = "SELECT * FROM items WHERE item_id = ? AND owner_id = ? AND loc = ?";
$config["QUERY_ITEMS_MAX_ID"] = "SELECT MAX(object_id) AS max FROM items";
$config["QUERY_ITEMS_INSERT"] = "INSERT INTO items (owner_id, object_id, item_id, count, enchant_level, loc, loc_data,{CUSTOM_COLS}) VALUES (?,?,?,?,?,?,'0',{CUSTOM_VALS})";
$config["QUERY_ITEMS_UPDATE"] = "UPDATE items SET count = (count + ?), enchant_level = ? WHERE item_id = ? AND owner_id = ? AND loc = ?";
$config["QUERY_ITEMS_DELETE"] = "DELETE FROM items WHERE object_id = ?";
$config["QUERY_UNLOCK_CHARACTER_1"] = "SELECT char_name, karma, online, x, y FROM characters WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_UNLOCK_CHARACTER_2"] = "UPDATE characters SET x=?, y=?, z=? WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_UNLOCK_CHARACTER_3"] = "UPDATE character_subclasses SET curHp = maxHP WHERE active = '1' AND char_obj_id = ?";
$config["QUERY_UNLOCK_CHARACTER_4"] = "UPDATE items SET loc='WAREHOUSE', loc_data='0' WHERE owner_id = ? AND loc = 'PAPERDOLL'";
$config["QUERY_UNLOCK_CHARACTER_5"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '840'";
$config["QUERY_UNLOCK_CHARACTER_6"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '841'";
$config["QUERY_UNLOCK_CHARACTER_7"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '842'";
$config["QUERY_SELECT_CHARACTER"] = "SELECT * FROM characters WHERE obj_Id = ?";
$config["QUERY_SELECT_CHARACTER_OFFLINE"] = "SELECT c.*, (SELECT level FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS level, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class FROM characters AS c WHERE c.obj_Id = ? AND c.account_name = ? AND c.online = '0'";
$config["QUERY_SELECT_CHARACTER_ACC"] = "SELECT account_name FROM characters WHERE obj_Id = ?";
$config["QUERY_CHANGE_CHARACTER_ACC"] = "UPDATE characters SET account_name = ? WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_CANCEL_ITEM_BROKER"] = "SELECT s.* FROM icp_shop_items AS s WHERE s.status = '1' AND (SELECT account_name FROM characters WHERE obj_Id = s.owner_id) = ? AND CASE WHEN s.type > '2' THEN CASE WHEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) IS NULL THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.id = ? ORDER BY s.id DESC";
$config["QUERY_CHANGING_ACC"] = "UPDATE characters SET account_name = ? WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_SELECT_ITEM_TO_ENCHANT"] = "SELECT i.*, (SELECT itemGrade FROM icp_icons_high_five WHERE itemId = i.item_id) AS itemGrade FROM items AS i, characters AS c WHERE i.owner_id = c.obj_Id AND i.object_id = ? AND c.obj_Id = ? AND c.account_name = ?";
$config["QUERY_UPDATE_ITEM_ENCHANTING"] = "UPDATE items SET enchant_level = ?, object_id = ? WHERE object_id = ?";
$config["QUERY_CHARACTER_CHANGE_CLASS"] = "SELECT c.char_name, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class FROM characters AS c WHERE c.account_name = ? AND c.obj_Id = ? AND c.online='0'";
$config["QUERY_DELETE_CHARACTER_SKILLS"] = "DELETE FROM character_skills WHERE char_obj_id = ?";
$config["QUERY_UPDATE_CHARACTER_OLYMPIADS"] = "UPDATE olympiad_nobles SET class_id = ?, olympiad_points = '0', olympiad_points_past = '0', olympiad_points_past_static = '0', competitions_done = '0', competitions_win = '0', competitions_loose = '0', game_classes_count = '0', game_noclasses_count = '0', game_team_count = '0' WHERE char_id = ?";
$config["QUERY_UPDATE_CHARACTER_BASECLASS"] = "UPDATE character_subclasses AS c SET c.class_id = ? WHERE 'GAMBIARRA_MAROTA' != ? AND 'GAMBIARRA_MAROTA' != ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_obj_id AND online='0') = ? AND char_obj_id = ? AND isBase = '1'";
$config["QUERY_SELECT_CHARACTER_NAME_1"] = "SELECT char_name FROM characters WHERE char_name = ?";
$config["QUERY_SELECT_CHARACTER_NAME_2"] = "SELECT char_name FROM characters WHERE obj_Id = ";
$config["QUERY_UPDATE_CHARACTER_NAME"] = "UPDATE characters SET char_name = ? WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_UPDATE_CHARACTER_SEX"] = "UPDATE characters SET sex = ? WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_SELECT_SERVER_STATISTICS_1"] = "SELECT COUNT(login) AS accounts FROM accounts";
$config["QUERY_SELECT_SERVER_STATISTICS_2"] = "SELECT COUNT(char_name) AS players_on, (SELECT COUNT(char_name) FROM characters) AS chars, (SELECT COUNT(name) FROM clan_subpledges WHERE type = '0') AS clans FROM characters WHERE online = '1'";
$config["QUERY_RANKING_TOP_PVP_1"] = "SELECT c.char_name, c.pvpkills, c.clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan, IF((SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid) IS NULL, '-', (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pvpkills > '0' ORDER BY c.pvpkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PVP_2"] = "SELECT c.char_name, c.pvpkills, c.clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan, IF((SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid) IS NULL, '-', (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pvpkills > '0' AND (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') = ? ORDER BY c.pvpkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PK_1"] = "SELECT c.char_name, c.pkkills, c.clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan, IF((SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid) IS NULL, '-', (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pkkills > '0' ORDER BY c.pkkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PK_2"] = "SELECT c.char_name, c.pkkills, c.clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan, IF((SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid) IS NULL, '-', (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pkkills > '0' AND (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') = ? ORDER BY c.pkkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_CASTLES_1"] = "SELECT (sc.clan_id) AS clanid, (SELECT name FROM clan_subpledges WHERE clan_id = sc.clan_id AND type = '0') AS clan FROM siege_clans AS sc WHERE sc.residence_id = ? AND sc.type = ?";
$config["QUERY_RANKING_CASTLES_2"] = "SELECT id, name, (tax_percent) AS taxPercent, (siege_date) AS siegeDate FROM castle";
$config["QUERY_RANKING_CASTLES_3"] = "SELECT (cs.clan_id) AS clanid, (cs.name) AS clan, (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) FROM clan_data AS cd WHERE cd.clan_id = cs.clan_id) AS ally FROM clan_subpledges AS cs WHERE (SELECT clan_id FROM clan_data WHERE hasCastle = ? AND clan_id = cs.clan_id) = cs.clan_id AND cs.type = '0'";
$config["QUERY_RANKING_CASTLES_4"] = "SELECT (SELECT char_name FROM characters WHERE obj_Id = cs.leader_id) AS leader FROM clan_subpledges AS cs WHERE cs.clan_id = (SELECT clan_id FROM clan_data WHERE hasCastle = ? AND clan_id = cs.clan_id) AND cs.type = '0'";
$config["QUERY_RANKING_GRANDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'HighFive' AND type = 'GrandBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_GRANDBOSSES_2"] = "SELECT (respawn_delay) AS respawn_time FROM raidboss_status WHERE id = ?";
$config["QUERY_RANKING_RAIDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'HighFive' AND type = 'RaidBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_RAIDBOSSES_2"] = "SELECT (respawn_delay) AS respawn_time FROM raidboss_status WHERE id = ?";
$config["QUERY_RANKING_RAIDPOINTS"] = "SELECT c.*, (SELECT SUM(points) FROM raidboss_points WHERE owner_id = c.obj_Id) AS raid_points, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = '1') AS base_class, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan FROM characters AS c WHERE c.obj_Id = '0' ORDER BY raid_points DESC, char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_OLYMPIADS"] = "SELECT o.*, (SELECT clanid FROM characters WHERE obj_Id = o.char_id) AS clanid, (SELECT char_name FROM characters WHERE obj_Id = o.char_id) AS char_name, IF((SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = o.char_id) AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = o.char_id) AND type = '0')) AS clan FROM olympiad_nobles AS o WHERE (SELECT accesslevel FROM characters WHERE obj_Id = o.char_id) = '0' AND o.competitions_done > '8' ORDER BY o.class_id ASC, o.olympiad_points DESC, o.competitions_done ASC, char_name ASC";
$config["QUERY_RANKING_HEROES_1"] = "SELECT h.count, (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AS clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = h.char_id AND isBase = '1') AS base, (SELECT char_name FROM characters WHERE obj_Id = h.char_id) AS char_name, IF((SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AND type = '0')) AS clan, IF((SELECT ally_name FROM ally_data WHERE ally_id = (SELECT ally_id FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) IS NULL, 'n/a', (SELECT ally_name FROM ally_data WHERE ally_id = (SELECT ally_id FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)))) AS ally FROM heroes AS h WHERE (SELECT accesslevel FROM characters WHERE obj_Id = h.char_id) = '0' ORDER BY h.count DESC, char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_HEROES_2"] = "SELECT h.count, (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AS clanid, (SELECT class_id FROM character_subclasses WHERE char_obj_id = h.char_id AND isBase = '1') AS base, (SELECT char_name FROM characters WHERE obj_Id = h.char_id) AS char_name, IF((SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AND type = '0')) AS clan, IF((SELECT ally_name FROM ally_data WHERE ally_id = (SELECT ally_id FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) IS NULL, 'n/a', (SELECT ally_name FROM ally_data WHERE ally_id = (SELECT ally_id FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)))) AS ally FROM heroes AS h WHERE h.played='1' AND (SELECT accesslevel FROM characters WHERE obj_Id = h.char_id) = '0' ORDER BY h.count DESC, char_name ASC";
$config["QUERY_RANKING_CLANS"] = "SELECT c.clan_level, (c.clan_id) AS clanid, c.reputation_score, (SELECT name FROM clan_subpledges WHERE clan_id = c.clan_id AND type='0') AS clan_name, IF((SELECT ally_name FROM ally_data WHERE ally_id = c.ally_id) IS NULL, 'n/a', (SELECT ally_name FROM ally_data WHERE ally_id = c.ally_id)) AS ally_name, (SELECT (SELECT char_name FROM characters WHERE obj_Id = cs.leader_id) AS leadername FROM clan_subpledges AS cs WHERE cs.clan_id = c.clan_id AND type = '0') AS leader, (SELECT SUM(pvpkills) FROM characters WHERE clanid = c.clan_id) AS toppvp FROM clan_data AS c WHERE (SELECT (SELECT accesslevel FROM characters WHERE obj_Id = cs.leader_id) AS accesslvl FROM clan_subpledges AS cs WHERE cs.clan_id = c.clan_id AND type = '0') = '0' ORDER BY ? LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_ONLINE"] = "SELECT c.char_name, c.onlinetime, c.clanid, IF((SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0') IS NULL, 'n/a', (SELECT name FROM clan_subpledges WHERE clan_id = c.clanid AND type = '0')) AS clan FROM characters AS c WHERE c.accesslevel = '0' AND c.onlinetime > '0' ORDER BY c.onlinetime DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_ADENA"] = "SELECT c.char_name, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '3470') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '3470') ELSE '0' END AS gold_bar, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'INVENTORY') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'INVENTORY') ELSE '0' END AS adena_inv, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'WAREHOUSE') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'WAREHOUSE') ELSE '0' END AS adena_war FROM characters AS c WHERE c.accesslevel = '0' ORDER BY gold_bar DESC, adena_inv DESC, adena_war DESC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_CLANHALL_IDS"] = array(21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64);
$config["QUERY_RANKING_CLANHALL"] = "SELECT CASE WHEN (SELECT clan_id FROM clan_data WHERE hasHideout = ch.id) IS NULL THEN '0' ELSE (SELECT clan_id FROM clan_data WHERE hasHideout = ch.id) END AS clanid, CASE WHEN (SELECT (SELECT name FROM clan_subpledges WHERE clan_id = cd.clan_id AND type='0') AS cname FROM clan_data AS cd WHERE cd.hasHideout = ch.id) IS NULL THEN '-' ELSE (SELECT (SELECT name FROM clan_subpledges WHERE clan_id = cd.clan_id AND type='0') AS cname FROM clan_data AS cd WHERE cd.hasHideout = ch.id) END AS clan_name, CASE WHEN (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.hasHideout = ch.id) IS NULL THEN '-' ELSE (SELECT (SELECT ally_name FROM ally_data WHERE ally_id = cd.ally_id) AS aname FROM clan_data AS cd WHERE cd.hasHideout = ch.id) END AS ally_name FROM clanhall AS ch WHERE ch.id = ?";
$config["QUERY_ACTIVATE_ACC"] = "UPDATE accounts SET access_level = '0' WHERE login = ? AND access_level = '-1'";
$config["QUERY_CHARACTER_STATUS_1"] = "SELECT c.*, ('0') AS charBroker, (c.obj_Id) AS char_id, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = 1) AS base_class, (SELECT level FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = 1) AS level, IF((SELECT char_id FROM olympiad_nobles WHERE char_id = c.obj_Id) IS NULL, 0, 1) AS nobless FROM characters AS c WHERE c.obj_Id = ? AND c.account_name = ?";
$config["QUERY_CHARACTER_STATUS_2"] = "SELECT c.*, ('0') AS charBroker, (c.obj_Id) AS char_id, (SELECT class_id FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = 1) AS base_class, (SELECT level FROM character_subclasses WHERE char_obj_id = c.obj_Id AND isBase = 1) AS level, IF((SELECT char_id FROM olympiad_nobles WHERE char_id = c.obj_Id) IS NULL, 0, 1) AS nobless FROM characters AS c WHERE c.account_name = ?";
$config["QUERY_CHARACTER_STATUS_3"] = "SELECT (cd.name) AS clan_name, (SELECT ally_name FROM ally_data WHERE ally_id = (SELECT ally_id FROM clan_data WHERE clan_id = cd.clan_id)) AS ally_name FROM clan_subpledges AS cd WHERE cd.clan_id = ? AND cd.type = '0'";
$config["QUERY_CHARACTER_STATUS_4"] = "SELECT * FROM heroes WHERE played = '1' AND char_id = ?";
$config["QUERY_GET_BASE_CLASS"] = "SELECT (cs.class_id) AS base_class FROM character_subclasses AS cs, characters AS c WHERE cs.char_obj_id = ? AND cs.char_obj_id = c.obj_Id AND c.account_name = ? AND cs.isBase = '1'";
$config["QUERY_GET_SUB_CLASSES"] = "SELECT c.class_id FROM character_subclasses AS c WHERE c.char_obj_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_obj_id) = ? AND isBase = '0'";
$config["QUERY_GET_CHARACTER_QUESTS"] = "SELECT c.*, (SELECT char_name FROM characters WHERE obj_Id = c.char_id) AS char_name FROM character_quests AS c WHERE c.char_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_id) = ? AND value in (SELECT MAX(value) FROM character_quests WHERE char_id = c.char_id GROUP by name)";
$config["QUERY_GET_CHARACTER_SKILLS"] = "SELECT c.*, s.icon, s.name, s.level, (SELECT char_name FROM characters WHERE obj_Id = c.char_obj_id) AS char_name FROM character_skills AS c, icp_skills AS s WHERE s.type='normal' AND c.skill_id = s.skill_id AND c.char_obj_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_obj_id) = ? AND c.class_index = ?";
$config["class_index"] = true;
$config["QUERY_GET_CHARACTER_ITEMS_1"] = "SELECT *, (SELECT char_name FROM characters WHERE obj_Id = i.owner_id) AS char_name FROM items AS i, icp_icons_high_five AS m WHERE i.item_id=m.itemId AND i.owner_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ? AND (SELECT online FROM characters WHERE obj_Id = i.owner_id) = '0' AND m.itemType IN('Armor','Weapon') AND i.loc IN('PAPERDOLL','INVENTORY','WAREHOUSE') AND m.itemDrop='true' AND m.itemSell='true' AND m.itemTrade='true' AND enchant_level >= '0' AND itemGrade != ''{WHERE_PVP} AND i.loc IN('{LOC}') ORDER BY m.itemId ASC";
$config["QUERY_GET_CHARACTER_ITEMS_2"] = "SELECT *, (SELECT char_name FROM characters WHERE obj_Id = i.owner_id) AS char_name FROM items AS i, icp_icons_high_five AS m WHERE i.item_id = m.itemId AND i.owner_id = ? AND i.loc IN('{LOC}') AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ? ORDER BY m.itemId ASC";
$config["QUERY_LIST_MY_CHARACTERS"] = "SELECT char_name, (obj_Id) AS char_id, online FROM characters WHERE account_name = ? AND obj_Id >= '0'";
$config["QUERY_CRESTS"] = "SELECT crest FROM clan_data WHERE clan_id = ?";