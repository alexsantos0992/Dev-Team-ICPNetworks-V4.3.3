<?php
# Encriptação das senhas
# 0 = md5
# 1 = sha1 (Padrão)
# 2 = whirlpool
# 3 = sha3-256
# 4 = hash L2OFF
# 5 = encriptação nova aCis
$config["encrypt"] = 1;

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
$config["CHRONICLE_ID"] = 4;

$config["QUERY_LOGIN_1"] = "SELECT a.login, a.access_level, CASE WHEN (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = a.login) IS NULL THEN '' ELSE (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = a.login) END AS icp_table FROM accounts AS a WHERE a.login = ? AND a.password = ?";
$config["QUERY_LOGIN_2"] = "SELECT * FROM accounts WHERE login = ? AND password = ?";
$config["QUERY_LOGIN_3"] = "SELECT login FROM accounts WHERE login = ?";
$config["QUERY_LOGIN_4"] = "SELECT * FROM accounts WHERE login = ?";
$config["QUERY_REGISTER_1"] = "INSERT INTO accounts (login, password, access_level) VALUES (?,?,'-1')";
$config["QUERY_REGISTER_2"] = "INSERT INTO accounts (login, password, access_level) VALUES (?,?,'0')";
$config["QUERY_PASS_CHANGE"] = "UPDATE accounts SET password = ? WHERE login = ?";
$config["QUERY_BAN_ACC"] = "UPDATE accounts SET access_level = ? WHERE login = ?";
$config["QUERY_PUT_CHARACTER_FOR_SALE_1"] = "UPDATE characters SET account_name = ? WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_PUT_CHARACTER_FOR_SALE_2"] = "INSERT INTO icp_shop_chars (owner_id, account, has_account, type, price) VALUES (?,?,?,?,?)";
$config["QUERY_ITEMS_DETAILS_1"] = "SELECT i.*, t.itemGrade FROM items AS i, icp_icons_c4 AS t WHERE i.item_id = t.itemId AND i.object_id = ? AND i.owner_id = ? AND (SELECT online FROM characters WHERE obj_Id = i.owner_id) = '0' AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ?";
$config["QUERY_ITEMS_DETAILS_2"] = "SELECT * FROM items WHERE item_id = ? AND owner_id = ? AND loc = ?";
$config["QUERY_ITEMS_MAX_ID"] = "SELECT MAX(object_id) AS max FROM items";
$config["QUERY_ITEMS_INSERT"] = "INSERT INTO items (owner_id, object_id, item_id, count, enchant_level, loc, loc_data,{CUSTOM_COLS}) VALUES (?,?,?,?,?,?,'0',{CUSTOM_VALS})";
$config["QUERY_ITEMS_UPDATE"] = "UPDATE items SET count = (count + ?), enchant_level = ? WHERE item_id = ? AND owner_id = ? AND loc = ?";
$config["QUERY_ITEMS_DELETE"] = "DELETE FROM items WHERE object_id = ?";
$config["QUERY_UNLOCK_CHARACTER_1"] = "SELECT char_name, karma, online, x, y FROM characters WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_UNLOCK_CHARACTER_2"] = "UPDATE characters SET x=?, y=?, z=?, curHp = maxHP WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_UNLOCK_CHARACTER_4"] = "UPDATE items SET loc='WAREHOUSE', loc_data='0' WHERE owner_id = ? AND loc = 'PAPERDOLL'";
$config["QUERY_UNLOCK_CHARACTER_5"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '840'";
$config["QUERY_UNLOCK_CHARACTER_6"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '841'";
$config["QUERY_UNLOCK_CHARACTER_7"] = "DELETE FROM character_skills_save WHERE char_obj_id = ? AND skill_id = '842'";
$config["QUERY_SELECT_CHARACTER"] = "SELECT * FROM characters WHERE obj_Id = ?";
$config["QUERY_SELECT_CHARACTER_OFFLINE"] = "SELECT * FROM characters WHERE obj_Id = ? AND account_name = ? AND online = '0'";
$config["QUERY_SELECT_CHARACTER_ACC"] = "SELECT account_name FROM characters WHERE obj_Id = ?";
$config["QUERY_CHANGE_CHARACTER_ACC"] = "UPDATE characters SET account_name = ? WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_CANCEL_ITEM_BROKER"] = "SELECT s.* FROM icp_shop_items AS s WHERE s.status = '1' AND (SELECT account_name FROM characters WHERE obj_Id = s.owner_id) = ? AND CASE WHEN s.type > '2' THEN CASE WHEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) IS NULL THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.id = ? ORDER BY s.id DESC";
$config["QUERY_CHANGING_ACC"] = "UPDATE characters SET account_name = ? WHERE account_name = ? AND obj_Id = ?";
$config["QUERY_SELECT_ITEM_TO_ENCHANT"] = "SELECT i.*, (SELECT itemGrade FROM icp_icons_c4 WHERE itemId = i.item_id) AS itemGrade FROM items AS i, characters AS c WHERE i.owner_id = c.obj_Id AND i.object_id = ? AND c.obj_Id = ? AND c.account_name = ?";
$config["QUERY_UPDATE_ITEM_ENCHANTING"] = "UPDATE items SET enchant_level = ?, object_id = ? WHERE object_id = ?";
$config["QUERY_CHARACTER_CHANGE_CLASS"] = "SELECT char_name, base_class FROM characters WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_DELETE_CHARACTER_SKILLS"] = "DELETE FROM character_skills WHERE char_obj_id = ?";
$config["QUERY_UPDATE_CHARACTER_OLYMPIADS"] = "UPDATE olympiad_nobles SET class_id = ?, olympiad_points = '0', competitions_done = '0', competitions_won = '0', competitions_lost = '0', competitions_drawn = '0' WHERE char_id = ?";
$config["QUERY_UPDATE_CHARACTER_HEROES"] = "UPDATE heroes SET class_id = ? WHERE char_id = ?";
$config["QUERY_UPDATE_CHARACTER_BASECLASS"] = "UPDATE characters SET base_class = ?, race = ?, classid = ? WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_SELECT_CHARACTER_NAME_1"] = "SELECT char_name FROM characters WHERE char_name = ?";
$config["QUERY_SELECT_CHARACTER_NAME_2"] = "SELECT char_name FROM characters WHERE obj_Id = ";
$config["QUERY_UPDATE_CHARACTER_NAME"] = "UPDATE characters SET char_name = ? WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_UPDATE_CHARACTER_SEX"] = "UPDATE characters SET sex = ? WHERE account_name = ? AND obj_Id = ? AND online='0'";
$config["QUERY_SELECT_SERVER_STATISTICS_1"] = "SELECT COUNT(login) AS accounts FROM accounts";
$config["QUERY_SELECT_SERVER_STATISTICS_2"] = "SELECT COUNT(char_name) AS players_on, (SELECT COUNT(char_name) FROM characters) AS chars, (SELECT COUNT(*) FROM clan_data) AS clans FROM characters WHERE online = '1'";
$config["QUERY_RANKING_TOP_PVP_1"] = "SELECT c.char_name, c.pvpkills, c.clanid, c.base_class, IF((SELECT clan_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = c.clanid)) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pvpkills > '0' ORDER BY c.pvpkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PVP_2"] = "SELECT c.char_name, c.pvpkills, c.clanid, c.base_class, IF((SELECT clan_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = c.clanid)) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pvpkills > '0' AND base_class = ? ORDER BY c.pvpkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PK_1"] = "SELECT c.char_name, c.pkkills, c.clanid, c.base_class, IF((SELECT clan_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = c.clanid)) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pkkills > '0' ORDER BY c.pkkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_TOP_PK_2"] = "SELECT c.char_name, c.pkkills, c.clanid, c.base_class, IF((SELECT clan_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = c.clanid)) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = c.clanid)) AS ally FROM characters AS c WHERE c.accesslevel = '0' AND c.pkkills > '0' AND base_class = ? ORDER BY c.pkkills DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_CASTLES_1"] = "SELECT (sc.clan_id) AS clanid, (SELECT clan_name FROM clan_data WHERE clan_id = sc.clan_id) AS clan FROM siege_clans AS sc WHERE sc.castle_id = ? AND sc.type = ?";
$config["QUERY_RANKING_CASTLES_2"] = "SELECT id, name, taxPercent, siegeDate FROM castle";
$config["QUERY_RANKING_CASTLES_3"] = "SELECT (clan_id) AS clanid, (clan_name) AS clan, (ally_name) AS ally FROM clan_data WHERE hasCastle = ?";
$config["QUERY_RANKING_CASTLES_4"] = "SELECT (SELECT char_name FROM characters WHERE obj_Id = cd.leader_id) AS leader FROM clan_data AS cd WHERE cd.hasCastle = ?";
$config["QUERY_RANKING_GRANDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'C4' AND type = 'GrandBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_GRANDBOSSES_2"] = "SELECT respawn_time FROM grandboss_data WHERE boss_id = ?";
$config["QUERY_RANKING_RAIDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'C4' AND type = 'RaidBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_RAIDBOSSES_2"] = "SELECT respawn_time FROM raidboss_spawnlist WHERE boss_id = ?";
$config["QUERY_RANKING_OLYMPIADS"] = "SELECT o.*, (SELECT clanid FROM characters WHERE obj_Id = o.char_id) AS clanid, (SELECT char_name FROM characters WHERE obj_Id = o.char_id) AS char_name, IF((SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = o.char_id)) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = o.char_id))) AS clan FROM olympiad_nobles AS o WHERE (SELECT accesslevel FROM characters WHERE obj_Id = o.char_id) = '0' AND o.competitions_done > '8' ORDER BY o.class_id ASC, o.olympiad_points DESC, o.competitions_done ASC, char_name ASC";
$config["QUERY_RANKING_HEROES_1"] = "SELECT h.count, (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AS clanid, (SELECT base_class FROM characters WHERE obj_Id = h.char_id) AS base, (SELECT char_name FROM characters WHERE obj_Id = h.char_id) AS char_name, IF((SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) AS ally FROM heroes AS h WHERE (SELECT accesslevel FROM characters WHERE obj_Id = h.char_id) = '0' ORDER BY h.count DESC, char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_HEROES_2"] = "SELECT h.count, (SELECT clanid FROM characters WHERE obj_Id = h.char_id) AS clanid, (SELECT base_class FROM characters WHERE obj_Id = h.char_id) AS base, (SELECT char_name FROM characters WHERE obj_Id = h.char_id) AS char_name, IF((SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) AS clan, IF((SELECT ally_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id)) IS NULL, 'n/a', (SELECT ally_name FROM clan_data WHERE clan_id = (SELECT clanid FROM characters WHERE obj_Id = h.char_id))) AS ally FROM heroes AS h WHERE h.played='1' AND (SELECT accesslevel FROM characters WHERE obj_Id = h.char_id) = '0' ORDER BY base ASC";
$config["QUERY_RANKING_CLANS"] = "SELECT c.clan_name, c.clan_level, (c.clan_id) AS clanid, ('0') AS reputation_score, IF((c.ally_name) IS NULL, 'n/a', (c.ally_name)) AS ally_name, (SELECT char_name FROM characters WHERE obj_Id = c.leader_id) AS leader, (SELECT SUM(pvpkills) FROM characters WHERE clanid = c.clan_id) AS toppvp FROM clan_data AS c WHERE (SELECT accesslevel FROM characters WHERE obj_Id = c.leader_id) = '0' ORDER BY ? LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_ONLINE"] = "SELECT c.char_name, c.onlinetime, c.clanid, IF((SELECT clan_name FROM clan_data WHERE clan_id = c.clanid) IS NULL, 'n/a', (SELECT clan_name FROM clan_data WHERE clan_id = c.clanid)) AS clan FROM characters AS c WHERE c.accesslevel = '0' AND c.onlinetime > '0' ORDER BY c.onlinetime DESC, c.char_name ASC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_ADENA"] = "SELECT c.char_name, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '3470') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '3470') ELSE '0' END AS gold_bar, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'INVENTORY') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'INVENTORY') ELSE '0' END AS adena_inv, CASE WHEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'WAREHOUSE') > '0' THEN (SELECT SUM(count) FROM items WHERE owner_id = c.obj_Id AND item_id = '57' AND loc = 'WAREHOUSE') ELSE '0' END AS adena_war FROM characters AS c WHERE c.accesslevel = '0' ORDER BY gold_bar DESC, adena_inv DESC, adena_war DESC LIMIT {MAX_LIMIT}";
$config["QUERY_RANKING_CLANHALL_IDS"] = array(22,23,24,25,26,27,28,29,30,31,32,33,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61);
$config["QUERY_RANKING_CLANHALL"] = "SELECT CASE WHEN ch.ownerId > '0' THEN ch.ownerId ELSE '0' END AS clanid, CASE WHEN (SELECT clan_name FROM clan_data WHERE clan_id = ch.ownerId) IS NULL THEN '-' ELSE (SELECT clan_name FROM clan_data WHERE clan_id = ch.ownerId) END AS clan_name, CASE WHEN (SELECT ally_name FROM clan_data WHERE clan_id = ch.ownerId) IS NULL THEN '-' ELSE (SELECT ally_name FROM clan_data WHERE clan_id = ch.ownerId) END AS ally_name FROM clanhall AS ch WHERE ch.id = ?";
$config["QUERY_ACTIVATE_ACC"] = "UPDATE accounts SET access_level = '0' WHERE login = ? AND access_level = '-1'";
$config["QUERY_CHARACTER_STATUS_1"] = "SELECT *, (obj_Id) AS char_id, ('0') AS charBroker FROM characters WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_CHARACTER_STATUS_2"] = "SELECT *, (obj_Id) AS char_id, ('0') AS charBroker FROM characters WHERE account_name = ?";
$config["QUERY_CHARACTER_STATUS_3"] = "SELECT * FROM clan_data WHERE clan_id = ?";
$config["QUERY_CHARACTER_STATUS_4"] = "SELECT * FROM heroes WHERE played = '1' AND char_id = ?";
$config["QUERY_GET_BASE_CLASS"] = "SELECT base_class FROM characters WHERE obj_Id = ? AND account_name = ?";
$config["QUERY_GET_SUB_CLASSES"] = "SELECT c.class_id FROM character_subclasses AS c WHERE c.char_obj_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_obj_id) = ? AND class_index != '0'";
$config["QUERY_GET_CHARACTER_QUESTS"] = "SELECT c.*, (SELECT char_name FROM characters WHERE obj_Id = c.char_id) AS char_name FROM character_quests AS c WHERE c.char_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_id) = ? AND value in (SELECT MAX(value) FROM character_quests WHERE char_id = c.char_id GROUP by name)";
$config["QUERY_GET_CHARACTER_SKILLS"] = "SELECT c.*, s.icon, s.name, s.level, (SELECT char_name FROM characters WHERE obj_Id = c.char_obj_id) AS char_name FROM character_skills AS c, icp_skills AS s WHERE s.type='normal' AND c.skill_id = s.skill_id AND c.char_obj_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = c.char_obj_id) = ? AND c.class_index = ?";
$config["QUERY_GET_CHARACTER_ITEMS_1"] = "SELECT *, (SELECT char_name FROM characters WHERE obj_Id = i.owner_id) AS char_name FROM items AS i, icp_icons_c4 AS m WHERE i.item_id=m.itemId AND i.owner_id = ? AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ? AND (SELECT online FROM characters WHERE obj_Id = i.owner_id) = '0' AND m.itemType IN('Armor','Weapon') AND i.loc IN('PAPERDOLL','INVENTORY','WAREHOUSE') AND m.itemDrop='true' AND m.itemSell='true' AND m.itemTrade='true' AND enchant_level >= '0' AND itemGrade != ''{WHERE_PVP} AND i.loc IN('{LOC}') ORDER BY m.itemId ASC";
$config["QUERY_GET_CHARACTER_ITEMS_2"] = "SELECT *, (SELECT char_name FROM characters WHERE obj_Id = i.owner_id) AS char_name FROM items AS i, icp_icons_c4 AS m WHERE i.item_id = m.itemId AND owner_id = ? AND i.loc IN('{LOC}') AND (SELECT account_name FROM characters WHERE obj_Id = i.owner_id) = ? ORDER BY m.itemId ASC";
$config["QUERY_LIST_MY_CHARACTERS"] = "SELECT char_name, (obj_Id) AS char_id, online FROM characters WHERE account_name = ? AND obj_Id >= '0'";