<?php
$createTable[14] = "CREATE TABLE `icp_prime_shop` (`id` int(11) NOT NULL AUTO_INCREMENT, `item_id` varchar(255) NOT NULL DEFAULT '0,', `price` int(11) NOT NULL DEFAULT '0', `count` varchar(255) NOT NULL DEFAULT '1,', `enchant` varchar(255) NOT NULL DEFAULT '0,', `attribute_fire` varchar(255) NOT NULL DEFAULT '0,', `attribute_water` varchar(255) NOT NULL DEFAULT '0,', `attribute_wind` varchar(255) NOT NULL DEFAULT '0,', `attribute_earth` varchar(255) NOT NULL DEFAULT '0,', `attribute_holy` varchar(255) NOT NULL DEFAULT '0,', `attribute_unholy` varchar(255) NOT NULL DEFAULT '0,', PRIMARY KEY (`id`), KEY `key_owner_id` (`id`), KEY `key_item_id` (`item_id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
$tableName[14] = "icp_prime_shop";