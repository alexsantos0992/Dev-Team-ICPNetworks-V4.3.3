<?php
$createTable[12] = "CREATE TABLE `icp_news` (`id` int(11) NOT NULL AUTO_INCREMENT, `news` text NOT NULL, `title` varchar(45) NOT NULL DEFAULT 'Sem Titulo', `author` varchar(255) NOT NULL, `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
$tableName[12] = "icp_news";