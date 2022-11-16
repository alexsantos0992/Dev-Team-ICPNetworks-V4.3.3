<?php
# Encriptação das senhas
# 0 = md5
# 1 = sha1 (Padrão)
# 2 = whirlpool
# 3 = sha3-256
# 4 = hash L2OFF
# 5 = encriptação nova aCis
$config["encrypt"] = 4;

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
$config["CHRONICLE_ID"] = 6;

$config["QUERY_LOGIN_1"] = "SELECT TOP 1 u.account AS login, (SELECT CONCAT(uid,';',pay_stat) FROM user_account WHERE account = u.account) AS uid, CASE WHEN (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = u.account) IS NULL THEN '' ELSE (SELECT CONCAT(email, ';', accessLevel, ';', vip_end, ';', status) FROM icp_accounts WHERE login = u.account) END AS icp_table FROM user_auth AS u WHERE u.account = ? AND u.password LIKE ?";
$config["QUERY_LOGIN_2"] = "SELECT TOP 1 * FROM user_auth WHERE account = ? AND password LIKE ?";
$config["QUERY_LOGIN_3"] = "SELECT account FROM user_auth WHERE account = ?";
$config["QUERY_LOGIN_4"] = "SELECT * FROM user_auth WHERE account = ?";
$config["QUERY_LOGIN_5"] = "SELECT uid FROM user_account WHERE account = ?";
$config["QUERY_REGISTER_1"] = "INSERT INTO [ssn](ssn,name,email,job,phone,zip,addr_main,addr_etc,account_num) VALUES (?,?,?,?,?,?,?,?,?)";
$config["QUERY_REGISTER_2"] = "INSERT INTO user_account (account,pay_stat) VALUES (?,?)";
$config["QUERY_REGISTER_3"] = "INSERT INTO user_info (account,ssn,kind) VALUES (?,?,?)";
$config["QUERY_REGISTER_4"] = "INSERT INTO user_auth (account,password,quiz1,quiz2,answer1,answer2) VALUES (?,?,'','',?,?)";
$config["QUERY_REGISTER_5"] = "INSERT INTO icp_accounts (login, email, acc_id, vip_end, status, accessLevel) VALUES (?,?,?,?,'1','0')";
$config["QUERY_PASS_CHANGE"] = "UPDATE user_auth SET password = ? WHERE account = ?";
$config["QUERY_BAN_ACC"] = "UPDATE user_account SET pay_stat = ? WHERE account = ?";
$config["QUERY_PUT_CHARACTER_FOR_SALE_1"] = "INSERT INTO icp_shop_chars (account, owner_id, type, price, date, status) VALUES (?,?,?,?,?,'1')";
$config["QUERY_ITEMS_DETAILS_1"] = "SELECT i.*, (i.item_type) AS item_id, (i.item_id) AS object_id, (i.amount) AS count, (i.enchant) AS enchant_level, t.itemGrade FROM user_item AS i, icp_icons_interlude AS t WHERE i.item_type = t.itemId AND i.item_id = ? AND i.char_id = ? AND (SELECT CASE WHEN login < logout THEN '0' ELSE '1' END FROM user_data WHERE char_id = i.char_id) = '0' AND (SELECT account_name FROM user_data WHERE char_id = i.char_id) = ?";
$config["QUERY_UNLOCK_CHARACTER_1"] = "SELECT char_name, (align) AS karma, CASE WHEN login > logout THEN '1' ELSE '0' END AS online, (xloc) AS x, (yloc) AS y FROM user_data WHERE account_name = ? AND char_id = ?";
$config["QUERY_SELECT_CHARACTER"] = "SELECT * FROM user_data WHERE char_id = ?";
$config["QUERY_SELECT_CHARACTER_OFFLINE"] = "SELECT *, (class) AS base_class, (gender) AS sex FROM user_data WHERE char_id = ? AND account_name = ? AND login < logout";
$config["QUERY_SELECT_CHARACTER_ACC"] = "SELECT account_name FROM user_data WHERE char_id = ?";
$config["QUERY_CANCEL_ITEM_BROKER"] = "SELECT s.* FROM icp_shop_items AS s WHERE s.status = '1' AND (SELECT account_name FROM user_data WHERE char_id = s.owner_id) = ? AND CASE WHEN s.type > '2' THEN CASE WHEN (SELECT MAX(value) FROM icp_shop_items_auction WHERE bidId = s.id) IS NULL THEN '0' ELSE '1' END ELSE '0' END = '0' AND s.id = ? ORDER BY s.id DESC";
$config["QUERY_SELECT_ITEM_TO_ENCHANT"] = "SELECT i.*, (enchant) AS enchant_level, (SELECT itemGrade FROM icp_icons_interlude WHERE itemId = i.item_type) AS itemGrade FROM user_item AS i, user_data AS c WHERE i.char_id = c.char_id AND i.item_id = ? AND c.char_id = ? AND c.account_name = ?";
$config["QUERY_CHARACTER_CHANGE_CLASS"] = "SELECT *, (subjob0_class) AS base_class FROM user_data WHERE account_name = ? AND char_id = ? AND login < logout";
$config["QUERY_SELECT_CHARACTER_NAME_1"] = "SELECT char_name FROM user_data WHERE char_name = ?";
$config["QUERY_SELECT_SERVER_STATISTICS_1"] = "SELECT COUNT(account) AS accounts FROM user_auth";
$config["QUERY_SELECT_SERVER_STATISTICS_2"] = "SELECT COUNT(char_name) AS players_on, (SELECT COUNT(char_name) FROM user_data) AS chars, (SELECT COUNT(name) FROM Pledge) AS clans FROM user_data WHERE login > logout";
$config["QUERY_RANKING_TOP_PVP_1"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.DUEL) AS pvpkills, (c.pledge_id) SA clanid, (c.subjob0_class) AS base_class, CASE WHEN c.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS clan, CASE WHEN (SELECT alliance_id FROM Pledge WHERE pledge_id = c.pledge_id) > '0' THEN (SELECT name FROM Alliance WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS ally FROM user_data AS c WHERE c.builder = '0' AND c.DUEL > '0' ORDER BY c.DUEL DESC, c.char_name ASC";
$config["QUERY_RANKING_TOP_PVP_2"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.DUEL) AS pvpkills, (c.pledge_id) SA clanid, (c.subjob0_class) AS base_class, CASE WHEN c.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS clan, CASE WHEN (SELECT alliance_id FROM Pledge WHERE pledge_id = c.pledge_id) > '0' THEN (SELECT name FROM Alliance WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS ally FROM user_data AS c WHERE c.builder = '0' AND c.DUEL > '0' AND subjob0_class = ? ORDER BY c.DUEL DESC, c.char_name ASC";
$config["QUERY_RANKING_TOP_PK_1"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.PK) AS pkkills, (c.pledge_id) SA clanid, (c.subjob0_class) AS base_class, CASE WHEN c.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS clan, CASE WHEN (SELECT alliance_id FROM Pledge WHERE pledge_id = c.pledge_id) > '0' THEN (SELECT name FROM Alliance WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS ally FROM user_data AS c WHERE c.builder = '0' AND c.PK > '0' ORDER BY c.PK DESC, c.char_name ASC";
$config["QUERY_RANKING_TOP_PK_2"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.PK) AS pkkills, (c.pledge_id) SA clanid, (c.subjob0_class) AS base_class, CASE WHEN c.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS clan, CASE WHEN (SELECT alliance_id FROM Pledge WHERE pledge_id = c.pledge_id) > '0' THEN (SELECT name FROM Alliance WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS ally FROM user_data AS c WHERE c.builder = '0' AND c.PK > '0' AND subjob0_class = ? ORDER BY c.PK DESC, c.char_name ASC";
$config["QUERY_RANKING_CASTLES_1"] = "SELECT (p.pledge_id) AS clanid, p.name AS defensores FROM castle_war cw left join pledge p on p.pledge_id=cw.pledge_id WHERE cw.castle_id = ? AND cw.type = ?";
$config["QUERY_RANKING_CASTLES_2"] = "SELECT id, name, (tax_rate) AS taxPercent, (next_war_time) AS siegeDate FROM castle";
$config["QUERY_RANKING_CASTLES_3"] = "SELECT (p.pledge_id) AS clanid, (p.name) AS clan, (SELECT name FROM Alliance WHERE master_pledge_id = p.pledge_id) AS ally FROM Pledge AS p WHERE p.castle_id = ?";
$config["QUERY_RANKING_CASTLES_4"] = "SELECT (SELECT char_name FROM user_data WHERE char_id = cd.ruler_id) AS leader FROM Pledge AS cd WHERE cd.castle_id = ?";
$config["QUERY_RANKING_GRANDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'Interlude' AND type = 'GrandBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_GRANDBOSSES_2"] = "SELECT (time_low) AS respawn_time FROM npc_boss WHERE boss_id = ?";
$config["QUERY_RANKING_RAIDBOSSES_1"] = "SELECT * FROM icp_bosses WHERE chronicle = 'Interlude' AND type = 'RaidBoss' ORDER BY level DESC, name ASC";
$config["QUERY_RANKING_RAIDBOSSES_2"] = "SELECT (time_low) AS respawn_time FROM npc_boss WHERE boss_id = ?";
$config["QUERY_RANKING_OLYMPIADS"] = "SELECT u.char_name, (u.pledge_id) AS clanid, (u.subjob0_class) AS class_id, oly.point as olympiad_points, oly.match_count AS competitions_done, cl.name as clan FROM olympiad_result AS oly INNER JOIN user_data AS u on u.char_id=oly.char_id LEFT JOIN pledge AS cl ON u.pledge_id = cl.pledge_id OR cl.pledge_id IS NULL ORDER BY class_id ASC, olympiad_points DESC, competitions_done ASC, u.char_name ASC";
$config["QUERY_RANKING_HEROES_1"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.pledge_id) AS clanid, c.subjob0_class, cl.name as clan, h.win_count as count, (SELECT name FROM Alliance WHERE id = c.pledge_id) AS ally FROM user_data AS c LEFT JOIN user_nobless AS h ON c.char_id = h.char_id LEFT JOIN pledge AS cl ON c.pledge_id = cl.pledge_id OR cl.pledge_id IS NULL WHERE h.hero_type in (1,2) AND c.builder='0' ORDER BY count DESC, c.char_name ASC";
$config["QUERY_RANKING_HEROES_2"] = "SELECT c.char_name, (c.pledge_id) AS clanid, c.subjob0_class, cl.name as clan, h.win_count as count, (SELECT name FROM Alliance WHERE id = c.pledge_id) AS ally FROM user_data AS c LEFT JOIN user_nobless AS h ON c.char_id = h.char_id LEFT JOIN pledge AS cl ON c.pledge_id = cl.pledge_id OR cl.pledge_id IS NULL WHERE h.hero_type in (1,2) AND c.builder='0' ORDER BY c.subjob0_class ASC";
$config["QUERY_RANKING_CLANS"] = "SELECT TOP {MAX_LIMIT} (c.name) AS clan_name, (c.skill_level) AS clan_level, (c.pledge_id) AS clanid, (SELECT reputation_points FROM pledge_ext WHERE pledge_id = c.pledge_id) AS reputation_score, CASE WHEN c.alliance_id > '0' THEN (SELECT name FROM Alliance WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS ally_name, (SELECT char_name FROM user_data WHERE char_id = c.ruler_id) AS leader, (SELECT SUM(DUEL) FROM user_data WHERE pledge_id = c.pledge_id) AS toppvp FROM Pledge AS c WHERE (SELECT builder FROM user_data WHERE char_id = c.ruler_id) = '0' ORDER BY ?";
$config["QUERY_RANKING_ONLINE"] = "SELECT TOP {MAX_LIMIT} c.char_name, (c.use_time) AS onlinetime, (c.pledge_id) AS clanid, CASE WHEN c.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = c.pledge_id) ELSE 'n/a' END AS clan FROM user_data AS c WHERE c.builder = '0' AND c.use_time > '0' ORDER BY c.use_time DESC, c.char_name ASC";
$config["QUERY_RANKING_ADENA"] = "SELECT TOP {MAX_LIMIT} c.char_name, CASE WHEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '3470') > '0' THEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '3470') ELSE '0' END AS gold_bar, CASE WHEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '57' AND warehouse = '0') > '0' THEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '57' AND warehouse = '0') ELSE '0' END AS adena_inv, CASE WHEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '57' AND warehouse = '1') > '0' THEN (SELECT SUM(amount) FROM user_item WHERE char_id = c.char_id AND item_type = '57' AND warehouse = '1') ELSE '0' END AS adena_war FROM user_data AS c WHERE c.builder = '0' ORDER BY gold_bar DESC, adena_inv DESC, adena_war DESC";
$config["QUERY_RANKING_CLANHALL_IDS"] = array(22,23,24,25,26,27,28,29,30,31,32,33,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61);
$config["QUERY_RANKING_CLANHALL"] = "SELECT (ch.pledge_id) AS clanid, CASE WHEN ch.pledge_id > '0' THEN (SELECT name FROM Pledge WHERE pledge_id = ch.pledge_id) ELSE '-' END AS clan_name, CASE WHEN ch.pledge_id > '0' THEN (SELECT name FROM Alliance WHERE master_pledge_id = ch.pledge_id) ELSE '-' END AS ally_name FROM agit AS ch WHERE ch.id = ?";
$config["QUERY_ACTIVATE_ACC"] = "UPDATE user_account SET pay_stat = '1' WHERE account = ? AND pay_stat = '0'";
$config["QUERY_CHARACTER_STATUS_1"] = "SELECT c.*, (c.subjob0_class) AS base_class, (c.pledge_id) AS clanid, (c.gender) AS sex, (c.use_time) AS onlinetime, (c.login) AS lastAccess, (c.xloc) AS x, (c.yloc) AS y, (c.Duel) AS pvpkills, (c.PK) AS pkkills, (c.align) AS karma, CASE WHEN (SELECT COUNT(*) FROM user_nobless WHERE char_id = c.char_id) > '0' THEN '1' ELSE '0' END AS nobless, CASE WHEN (SELECT count(*) FROM icp_shop_chars WHERE owner_id = c.char_id AND account = c.account_name) > '0' THEN '1' ELSE '0' END AS charBroker, CASE WHEN c.subjob1_class != '-1' THEN (SELECT level FROM user_subjob WHERE char_id = c.char_id AND subjob_id = '0') ELSE c.Lev END AS level FROM user_data AS c WHERE c.char_id = ? AND c.account_name = ?";
$config["QUERY_CHARACTER_STATUS_2"] = "SELECT c.*, (c.subjob0_class) AS base_class, (c.pledge_id) AS clanid, (c.gender) AS sex, (c.use_time) AS onlinetime, (c.login) AS lastAccess, (c.xloc) AS x, (c.yloc) AS y, (c.Duel) AS pvpkills, (c.PK) AS pkkills, (c.align) AS karma, CASE WHEN (SELECT COUNT(*) FROM user_nobless WHERE char_id = c.char_id) > '0' THEN '1' ELSE '0' END AS nobless, CASE WHEN (SELECT count(*) FROM icp_shop_chars WHERE owner_id = c.char_id AND account = c.account_name) > '0' THEN '1' ELSE '0' END AS charBroker, CASE WHEN c.subjob1_class != '-1' THEN (SELECT level FROM user_subjob WHERE char_id = c.char_id AND subjob_id = '0') ELSE c.Lev END AS level FROM user_data AS c WHERE c.account_name = ?";
$config["QUERY_CHARACTER_STATUS_3"] = "SELECT (cd.name) AS clan_name, (SELECT name FROM Alliance WHERE id = cd.pledge_id) AS ally_name FROM Pledge AS cd WHERE cd.pledge_id = ?";
$config["QUERY_CHARACTER_STATUS_4"] = "SELECT hero_type FROM user_nobless WHERE char_id = ?";
$config["QUERY_GET_BASE_CLASS"] = "SELECT (subjob0_class) AS base_class FROM user_data WHERE char_id = ? AND account_name = ?";
$config["QUERY_GET_SUB_CLASSES"] = "SELECT subjob1_class, subjob2_class, subjob3_class FROM user_data WHERE char_id = ? AND account_name = ?";
$config["QUERY_GET_CHARACTER_QUESTS"] = "SELECT c.*, (SELECT char_name FROM user_data WHERE char_id = c.char_id) AS char_name FROM quest AS c WHERE c.char_id = ? AND (SELECT account_name FROM user_data WHERE char_id = c.char_id) = ?";
$config["QUERY_GET_CHARACTER_SKILLS"] = "SELECT c.*, s.icon, (c.skill_lev) AS skill_level, s.name, s.level, (SELECT char_name FROM user_data WHERE char_id = c.char_id) AS char_name FROM user_skill AS c, icp_skills AS s WHERE s.type='normal' AND c.skill_id = s.skill_id AND c.char_id = ? AND (SELECT account_name FROM user_data WHERE char_id = c.char_id) = ? AND c.subjob_id = ?";
$config["QUERY_GET_STORE_ITEMS"] = "SELECT item_id FROM icp_shop_items WHERE owner_id = ?";
$config["QUERY_GET_CHARACTER_ITEMS_1"] = "SELECT i.*, m.*, (i.item_id) AS object_id, (i.amount) AS count, (i.enchant) AS enchant_level, (SELECT char_name FROM user_data WHERE char_id = i.char_id) AS char_name FROM user_item AS i, icp_icons_interlude AS m, user_data AS u WHERE i.item_type=m.itemId AND i.char_id=u.char_id AND i.char_id = ? AND u.account_name = ? AND u.login < u.logout AND m.itemType IN ('Armor','Weapon') AND i.warehouse = '1' AND m.itemDrop='true' AND m.itemSell='true' AND m.itemTrade='true' AND i.enchant >= '0' AND m.itemGrade != ''{WHERE_PVP} AND i.item_id NOT IN('{STORE}') ORDER BY m.itemName ASC";
$config["QUERY_GET_CHARACTER_ITEMS_2"] = "SELECT i.*, m.*, (i.item_id) AS object_id, (i.amount) AS count, (i.enchant) AS enchant_level, (SELECT char_name FROM user_data WHERE char_id = i.char_id) AS char_name FROM user_item AS i, icp_icons_interlude AS m, user_data AS u WHERE i.item_type=m.itemId AND i.char_id=u.char_id AND i.char_id = ? AND u.account_name = ? AND u.login < u.logout AND m.itemType IN ('Armor','Weapon') AND i.warehouse = '0' AND m.itemDrop='true' AND m.itemSell='true' AND m.itemTrade='true' AND i.enchant >= '0' AND m.itemGrade != ''{WHERE_PVP} AND CASE WHEN i.item_id IN(u.ST_underware,u.ST_right_ear,u.ST_left_ear,u.ST_neck,u.ST_right_finger,u.ST_left_finger,u.ST_head,u.ST_right_hand,u.ST_left_hand,u.ST_gloves,u.ST_chest,u.ST_legs,u.ST_feet,u.ST_back,u.ST_both_hand,u.ST_hair,u.ST_hair_deco,u.ST_hair_all) THEN 'PAPERDOLL' ELSE 'INVENTORY' END = ? AND i.item_id NOT IN('{STORE}') ORDER BY m.itemName ASC";
$config["QUERY_GET_CHARACTER_ITEMS_3"] = "SELECT i.*, m.*, (i.item_id) AS object_id, (i.amount) AS count, (i.enchant) AS enchant_level, (SELECT char_name FROM user_data WHERE char_id = i.char_id) AS char_name FROM user_item AS i, icp_icons_interlude AS m, user_data AS u WHERE i.item_type = m.itemId AND i.char_id = u.char_id AND i.char_id = ? AND i.warehouse = '1' AND u.account_name = ? AND i.item_id NOT IN('{STORE}') ORDER BY m.itemId ASC";
$config["QUERY_GET_CHARACTER_ITEMS_4"] = "SELECT i.*, m.*, u.*, (i.item_id) AS object_id, (i.amount) AS count, (i.enchant) AS enchant_level, (SELECT char_name FROM user_data WHERE char_id = i.char_id) AS char_name FROM user_item AS i, icp_icons_interlude AS m, user_data AS u WHERE i.item_type = m.itemId AND i.char_id = u.char_id AND i.char_id = ? AND i.warehouse = '0' AND u.account_name = ? AND CASE WHEN i.item_id IN(u.ST_underware,u.ST_right_ear,u.ST_left_ear,u.ST_neck,u.ST_right_finger,u.ST_left_finger,u.ST_head,u.ST_right_hand,u.ST_left_hand,u.ST_gloves,u.ST_chest,u.ST_legs,u.ST_feet,u.ST_back,u.ST_both_hand,u.ST_hair,u.ST_hair_deco,u.ST_hair_all) THEN 'PAPERDOLL' ELSE 'INVENTORY' END = ? AND i.item_id NOT IN('{STORE}') ORDER BY m.itemId ASC";
$config["QUERY_LIST_MY_CHARACTERS"] = "SELECT char_name, char_id, CASE WHEN login > logout THEN '1' ELSE '0' END AS online FROM user_data WHERE account_name = ? AND char_id >= '0'";