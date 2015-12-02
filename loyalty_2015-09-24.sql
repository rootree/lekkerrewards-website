# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.44-0ubuntu0.14.04.1)
# Database: loyalty
# Generation Time: 2015-09-24 11:36:57 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table city
# ------------------------------------------------------------

DROP TABLE IF EXISTS `city`;

CREATE TABLE `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_country` int(11) unsigned NOT NULL,
  `fk_state` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_country_` (`fk_country`),
  KEY `fk_state_` (`fk_state`),
  CONSTRAINT `fk_state_` FOREIGN KEY (`fk_state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_country_` FOREIGN KEY (`fk_country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table country
# ------------------------------------------------------------

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table customer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_photo_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` date DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `e_mail` varchar(100) NOT NULL DEFAULT '',
  `gender` char(1) DEFAULT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `phone_number` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table merchant
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merchant`;

CREATE TABLE `merchant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_category` int(10) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `facebook` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `yelp_id` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_1` (`fk_category`),
  CONSTRAINT `fk_category_1` FOREIGN KEY (`fk_category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table merchant_branch
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merchant_branch`;

CREATE TABLE `merchant_branch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `fk_country` int(11) unsigned NOT NULL,
  `fk_city` int(11) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `fk_state` int(11) unsigned NOT NULL,
  `phone_number` varchar(25) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `zipcode` varchar(10) NOT NULL DEFAULT '',
  `permalink_path` varchar(100) NOT NULL DEFAULT '',
  `time_offset` varchar(10) NOT NULL DEFAULT '+02:00',
  PRIMARY KEY (`id`),
  KEY `fk_merchant_1` (`fk_merchant`),
  KEY `fk_city_1` (`fk_city`),
  KEY `fk_country_1` (`fk_country`),
  KEY `fk_state` (`fk_state`),
  CONSTRAINT `fk_state` FOREIGN KEY (`fk_state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_city_1` FOREIGN KEY (`fk_city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_country_1` FOREIGN KEY (`fk_country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_1` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table merchants__customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merchants__customers`;

CREATE TABLE `merchants__customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `fk_customer` int(11) unsigned NOT NULL,
  `fk_merchant_branch` int(11) unsigned NOT NULL,
  `first_at` date NOT NULL,
  `visits` smallint(5) unsigned NOT NULL DEFAULT '0',
  `redeems` smallint(5) unsigned NOT NULL DEFAULT '0',
  `points` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_merchant_8` (`fk_merchant`),
  KEY `fk_customer_3` (`fk_customer`),
  KEY `fk_merchant_branch_8` (`fk_merchant_branch`),
  CONSTRAINT `fk_merchant_branch_8` FOREIGN KEY (`fk_merchant_branch`) REFERENCES `merchant_branch` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_customer_3` FOREIGN KEY (`fk_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_8` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table owner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owner`;

CREATE TABLE `owner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `e_mail` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `birthday` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `phone_number` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_merchant_2` (`fk_merchant`),
  CONSTRAINT `fk_merchant_2` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table redeem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `redeem`;

CREATE TABLE `redeem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `fk_merchant_branch` int(11) unsigned NOT NULL,
  `fk_customer` int(11) unsigned NOT NULL,
  `fk_reward` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_merchant_7` (`fk_merchant`),
  KEY `fk_merchant_branch_3` (`fk_merchant_branch`),
  KEY `fk_customer_4` (`fk_customer`),
  KEY `fk_reward_4` (`fk_reward`),
  CONSTRAINT `fk_reward_4` FOREIGN KEY (`fk_reward`) REFERENCES `reward` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_customer_4` FOREIGN KEY (`fk_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_7` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_branch_3` FOREIGN KEY (`fk_merchant_branch`) REFERENCES `merchant_branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table reward
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reward`;

CREATE TABLE `reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `fk_owner` int(11) unsigned NOT NULL,
  `fk_merchant_branch` int(11) unsigned NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `points` smallint(5) unsigned NOT NULL,
  `name` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_merchant_4` (`fk_merchant`),
  KEY `fk_owner_1` (`fk_owner`),
  KEY `fk_merchant_branch_2` (`fk_merchant_branch`),
  CONSTRAINT `fk_merchant_branch_2` FOREIGN KEY (`fk_merchant_branch`) REFERENCES `merchant_branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_4` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_owner_1` FOREIGN KEY (`fk_owner`) REFERENCES `owner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table state
# ------------------------------------------------------------

DROP TABLE IF EXISTS `state`;

CREATE TABLE `state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_country` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_country` (`fk_country`),
  CONSTRAINT `fk_country` FOREIGN KEY (`fk_country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table visit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `visit`;

CREATE TABLE `visit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_merchant` int(11) unsigned NOT NULL,
  `fk_customer` int(11) unsigned NOT NULL,
  `fk_merchant_branch` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `obtained_points` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_merchant_3` (`fk_merchant`),
  KEY `fk_customer_1` (`fk_customer`),
  KEY `fk_merchant_branch` (`fk_merchant_branch`),
  CONSTRAINT `fk_merchant_branch` FOREIGN KEY (`fk_merchant_branch`) REFERENCES `merchant_branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_customer_1` FOREIGN KEY (`fk_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_merchant_3` FOREIGN KEY (`fk_merchant`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
