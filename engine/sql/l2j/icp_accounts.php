<?php
$createTable[0] = "CREATE TABLE `icp_accounts` (`id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT, `login` varchar(50) NOT NULL, `email` varchar(255) NOT NULL, `acc_id` int(15) NOT NULL, `status` int(1) NOT NULL DEFAULT 0, `repass` int(1) NOT NULL DEFAULT 1, `vip_end` timestamp NULL DEFAULT NULL, `accessLevel` int(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
$tableName[0] = "icp_accounts";