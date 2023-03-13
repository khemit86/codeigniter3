<?php

/**
 * 
 * Common DB utilities
 */

define('USERS_TABLE', 'ppusers');
define('ORDERS_TABLE', 'pporders');

/**
 * Returns a new mysql conncetion
 * @throws Exception
 * @return unknown
 */
function getConnection() {
	
	$usersTableCreateQuery = "CREATE TABLE IF NOT EXISTS `ppusers` (`user_id` int(11) NOT NULL AUTO_INCREMENT,  `email` varchar(254) DEFAULT NULL,  `password` varchar(50) DEFAULT NULL,  `creditcard_id` varchar(40) DEFAULT NULL,  PRIMARY KEY (`user_id`),  UNIQUE KEY `email` (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$ordersTableCreateQuery = "CREATE TABLE IF NOT EXISTS `pporders` (`order_id` int(11) NOT NULL AUTO_INCREMENT,  `user_id` varchar(254) DEFAULT NULL,  `payment_id` varchar(50) DEFAULT NULL,  `state` varchar(20) DEFAULT NULL,  `amount` varchar(20) DEFAULT NULL,  `description` varchar(40) DEFAULT NULL,  `created_time` datetime DEFAULT NULL,   PRIMARY KEY (`order_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

	$link = mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD,MYSQL_DB);

	if(!$link) {
		throw new Exception('Could not connect to mysql ' . mysqli_error() . PHP_EOL .
				'. Please check connection parameters in app/bootstrap.php');
	}
	
	mysqli_query($link, $usersTableCreateQuery);
	mysqli_query($link, $ordersTableCreateQuery);
	return $link;
}