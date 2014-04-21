-- phpMyAdmin SQL Dump
-- version 3.3.7deb5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2014 at 07:15 AM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `godana_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `gdn_attribute`
--

CREATE TABLE IF NOT EXISTS `gdn_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_bid`
--

CREATE TABLE IF NOT EXISTS `gdn_bid` (
  `id_bid` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `price_bid` float DEFAULT NULL,
  `id_city` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_bid`),
  UNIQUE KEY `UNIQ_40C75C60D1AA708F` (`id_post`),
  KEY `IDX_40C75C60A67B1E36` (`id_city`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_car`
--

CREATE TABLE IF NOT EXISTS `gdn_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `license` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cooperative_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D08090E8D0C5D40` (`cooperative_id`),
  KEY `IDX_7D08090E7975B7E7` (`model_id`),
  KEY `IDX_7D08090EC3423909` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_car_driver`
--

CREATE TABLE IF NOT EXISTS `gdn_car_driver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cooperative_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3640824F8D0C5D40` (`cooperative_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_car_file`
--

CREATE TABLE IF NOT EXISTS `gdn_car_file` (
  `car_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`car_id`,`file_id`),
  KEY `IDX_8700E1E3C3C6F69F` (`car_id`),
  KEY `IDX_8700E1E393CB796C` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_car_make`
--

CREATE TABLE IF NOT EXISTS `gdn_car_make` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1153A19D5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_car_model`
--

CREATE TABLE IF NOT EXISTS `gdn_car_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make_id` int(11) DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F32A4EA8CFBF73EB` (`make_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_category`
--

CREATE TABLE IF NOT EXISTS `gdn_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent_category` int(11) DEFAULT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_ident` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_type` int(11) NOT NULL,
  PRIMARY KEY (`id_category`),
  KEY `IDX_C6B9DA9E70DF7698` (`id_parent_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_comment`
--

CREATE TABLE IF NOT EXISTS `gdn_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `detail` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `is_deleted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DBDBA013A76ED395` (`user_id`),
  KEY `IDX_DBDBA0134B89032C` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_contact` (
  `id_contact` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_contact`),
  KEY `UNIQ_3CD14478CDE5729` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_contact_type`
--

CREATE TABLE IF NOT EXISTS `gdn_contact_type` (
  `id_contact_type` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_contact_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_admin`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_admin` (
  `cooperative_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`cooperative_id`,`admin_id`),
  KEY `IDX_995E71D78D0C5D40` (`cooperative_id`),
  KEY `IDX_995E71D7642B8210` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_contact` (
  `cooperative_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`cooperative_id`,`contact_id`),
  UNIQUE KEY `UNIQ_A2CD47D7E7A1254A` (`contact_id`),
  KEY `IDX_A2CD47D78D0C5D40` (`cooperative_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_line`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_line` (
  `cooperative_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  PRIMARY KEY (`cooperative_id`,`line_id`),
  KEY `IDX_8063492D8D0C5D40` (`cooperative_id`),
  KEY `IDX_8063492D4D7B7542` (`line_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_line_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_line_contact` (
  `line_contact_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`line_contact_id`,`contact_id`),
  UNIQUE KEY `UNIQ_B44C8D0EE7A1254A` (`contact_id`),
  KEY `IDX_B44C8D0E591A36A1` (`line_contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_teller`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_teller` (
  `cooperative_id` int(11) NOT NULL,
  `teller_id` int(11) NOT NULL,
  PRIMARY KEY (`cooperative_id`,`teller_id`),
  KEY `IDX_47DF20468D0C5D40` (`cooperative_id`),
  KEY `IDX_47DF2046E9894D10` (`teller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_cooperative_zone`
--

CREATE TABLE IF NOT EXISTS `gdn_cooperative_zone` (
  `cooperative_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  PRIMARY KEY (`cooperative_id`,`zone_id`),
  KEY `IDX_F19C3DDC8D0C5D40` (`cooperative_id`),
  KEY `IDX_F19C3DDC9F2C3FAB` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_country`
--

CREATE TABLE IF NOT EXISTS `gdn_country` (
  `id_country` int(11) NOT NULL AUTO_INCREMENT,
  `cc_fips` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `cc_iso` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `tld` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_driver_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_driver_contact` (
  `driver_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`driver_id`,`contact_id`),
  UNIQUE KEY `UNIQ_FAE7DAA4E7A1254A` (`contact_id`),
  KEY `IDX_FAE7DAA4C3423909` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_feed`
--

CREATE TABLE IF NOT EXISTS `gdn_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4A4CB3BA4B89032C` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_file`
--

CREATE TABLE IF NOT EXISTS `gdn_file` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relativePath` longtext COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_image`
--

CREATE TABLE IF NOT EXISTS `gdn_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) DEFAULT NULL,
  `dimension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AFE4285A93CB796C` (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_line`
--

CREATE TABLE IF NOT EXISTS `gdn_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure` int(11) DEFAULT NULL,
  `arrival` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B81843E745E9C671` (`departure`),
  KEY `IDX_B81843E75BE55CB4` (`arrival`),
  KEY `IDX_B81843E79F2C3FAB` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_line_car`
--

CREATE TABLE IF NOT EXISTS `gdn_line_car` (
  `line_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `seats` int(11) NOT NULL,
  `nb_column` int(11) NOT NULL,
  `fare` double NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`line_id`,`car_id`),
  KEY `IDX_448316984D7B7542` (`line_id`),
  KEY `IDX_44831698C3C6F69F` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_line_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_line_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cooperative_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BEE87FA8D0C5D40` (`cooperative_id`),
  KEY `IDX_BEE87FA4D7B7542` (`line_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_mada_cities`
--

CREATE TABLE IF NOT EXISTS `gdn_mada_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `city_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `city_accented` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `population` bigint(20) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18424 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_media`
--

CREATE TABLE IF NOT EXISTS `gdn_media` (
  `id_media` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `extension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_media`),
  KEY `IDX_F58D09D1AA708F` (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_passenger`
--

CREATE TABLE IF NOT EXISTS `gdn_passenger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_passenger_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_passenger_contact` (
  `passenger_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`passenger_id`,`contact_id`),
  UNIQUE KEY `UNIQ_DAF26981E7A1254A` (`contact_id`),
  KEY `IDX_DAF269814502E565` (`passenger_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_post`
--

CREATE TABLE IF NOT EXISTS `gdn_post` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `title_post` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail_post` longtext COLLATE utf8_unicode_ci,
  `date_published` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `is_deleted` int(11) NOT NULL,
  `ident_post` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_post`),
  KEY `IDX_33869B9C6B3CA4B` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_post_category`
--

CREATE TABLE IF NOT EXISTS `gdn_post_category` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`category_id`),
  KEY `IDX_1FEFEFE74B89032C` (`post_id`),
  KEY `IDX_1FEFEFE712469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_post_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_post_contact` (
  `post_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`contact_id`),
  UNIQUE KEY `UNIQ_83A2B1AEE7A1254A` (`contact_id`),
  KEY `IDX_83A2B1AE4B89032C` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_post_file`
--

CREATE TABLE IF NOT EXISTS `gdn_post_file` (
  `id_post` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  PRIMARY KEY (`id_post`,`id_file`),
  KEY `IDX_BEDEE8BDD1AA708F` (`id_post`),
  KEY `IDX_BEDEE8BD7BF2A12` (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_post_tag`
--

CREATE TABLE IF NOT EXISTS `gdn_post_tag` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `IDX_9A3BF9AF4B89032C` (`post_id`),
  KEY `IDX_9A3BF9AFBAD26311` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_product`
--

CREATE TABLE IF NOT EXISTS `gdn_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `price` double DEFAULT NULL,
  `measurement` double DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9CE5F6D2C54C8C93` (`type_id`),
  KEY `IDX_9CE5F6D24D16C4DD` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_producttype_attribute`
--

CREATE TABLE IF NOT EXISTS `gdn_producttype_attribute` (
  `producttype_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  PRIMARY KEY (`producttype_id`,`attribute_id`),
  KEY `IDX_B2EADF1C5E032AB4` (`producttype_id`),
  KEY `IDX_B2EADF1CB6E62EFA` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_product_attribute`
--

CREATE TABLE IF NOT EXISTS `gdn_product_attribute` (
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`),
  KEY `IDX_EAB69CCF4584665A` (`product_id`),
  KEY `IDX_EAB69CCFB6E62EFA` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_product_file`
--

CREATE TABLE IF NOT EXISTS `gdn_product_file` (
  `id_product` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  PRIMARY KEY (`id_product`,`id_file`),
  KEY `IDX_F35FF414DD7ADDD` (`id_product`),
  KEY `IDX_F35FF4147BF2A12` (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_product_type`
--

CREATE TABLE IF NOT EXISTS `gdn_product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `unit` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_reservation`
--

CREATE TABLE IF NOT EXISTS `gdn_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passenger_id` int(11) DEFAULT NULL,
  `board_id` int(11) DEFAULT NULL,
  `seat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  `payment` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_828A2064502E565` (`passenger_id`),
  KEY `IDX_828A206E7EC5785` (`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_reservation_board`
--

CREATE TABLE IF NOT EXISTS `gdn_reservation_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) DEFAULT NULL,
  `departure_time` datetime NOT NULL,
  `line_id` int(11) DEFAULT NULL,
  `cooperative_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_65453E59C3C6F69F` (`car_id`),
  KEY `IDX_65453E594D7B7542` (`line_id`),
  KEY `IDX_65453E598D0C5D40` (`cooperative_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_shop`
--

CREATE TABLE IF NOT EXISTS `gdn_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ident` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `is_deleted` int(11) NOT NULL,
  `cover_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C566BBB37E3C61F9` (`owner_id`),
  KEY `IDX_C566BBB3922726E9` (`cover_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_shop_category`
--

CREATE TABLE IF NOT EXISTS `gdn_shop_category` (
  `shop_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`shop_id`,`category_id`),
  KEY `IDX_7BBA9CD04D16C4DD` (`shop_id`),
  KEY `IDX_7BBA9CD012469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_shop_city`
--

CREATE TABLE IF NOT EXISTS `gdn_shop_city` (
  `shop_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`shop_id`,`city_id`),
  KEY `IDX_17BD5DE94D16C4DD` (`shop_id`),
  KEY `IDX_17BD5DE98BAC62AF` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_shop_contact`
--

CREATE TABLE IF NOT EXISTS `gdn_shop_contact` (
  `shop_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`shop_id`,`contact_id`),
  UNIQUE KEY `UNIQ_BD7946B7E7A1254A` (`contact_id`),
  KEY `IDX_BD7946B74D16C4DD` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_tag`
--

CREATE TABLE IF NOT EXISTS `gdn_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_user_meta`
--

CREATE TABLE IF NOT EXISTS `gdn_user_meta` (
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`meta_key`,`user_id`),
  KEY `IDX_5667E15AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdn_zone`
--

CREATE TABLE IF NOT EXISTS `gdn_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `roleId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6AB8C2FD88` (`roleId`),
  KEY `IDX_57698A6A727ACA70` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `displayName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_ip` int(11) DEFAULT NULL,
  `register_time` datetime NOT NULL,
  `register_ip` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  KEY `IDX_1483A5E993CB796C` (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE IF NOT EXISTS `users_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_51498A8EA76ED395` (`user_id`),
  KEY `IDX_51498A8ED60322AC` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_provider`
--

CREATE TABLE IF NOT EXISTS `user_provider` (
  `user_id` int(11) NOT NULL,
  `provider_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`provider_id`),
  UNIQUE KEY `provider_id` (`provider_id`,`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_remember_me`
--

CREATE TABLE IF NOT EXISTS `user_remember_me` (
  `sid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`sid`,`token`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_role_linker`
--

CREATE TABLE IF NOT EXISTS `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gdn_bid`
--
ALTER TABLE `gdn_bid`
  ADD CONSTRAINT `FK_40C75C60A67B1E36` FOREIGN KEY (`id_city`) REFERENCES `gdn_mada_cities` (`id`),
  ADD CONSTRAINT `FK_40C75C60D1AA708F` FOREIGN KEY (`id_post`) REFERENCES `gdn_post` (`id_post`);

--
-- Constraints for table `gdn_car`
--
ALTER TABLE `gdn_car`
  ADD CONSTRAINT `FK_7D08090E7975B7E7` FOREIGN KEY (`model_id`) REFERENCES `gdn_car_model` (`id`),
  ADD CONSTRAINT `FK_7D08090E8D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`),
  ADD CONSTRAINT `FK_7D08090EC3423909` FOREIGN KEY (`driver_id`) REFERENCES `gdn_car_driver` (`id`);

--
-- Constraints for table `gdn_car_driver`
--
ALTER TABLE `gdn_car_driver`
  ADD CONSTRAINT `FK_3640824F8D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`);

--
-- Constraints for table `gdn_car_file`
--
ALTER TABLE `gdn_car_file`
  ADD CONSTRAINT `FK_8700E1E393CB796C` FOREIGN KEY (`file_id`) REFERENCES `gdn_file` (`id_file`),
  ADD CONSTRAINT `FK_8700E1E3C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `gdn_car` (`id`);

--
-- Constraints for table `gdn_car_model`
--
ALTER TABLE `gdn_car_model`
  ADD CONSTRAINT `FK_F32A4EA8CFBF73EB` FOREIGN KEY (`make_id`) REFERENCES `gdn_car_make` (`id`);

--
-- Constraints for table `gdn_category`
--
ALTER TABLE `gdn_category`
  ADD CONSTRAINT `FK_C6B9DA9E70DF7698` FOREIGN KEY (`id_parent_category`) REFERENCES `gdn_category` (`id_category`);

--
-- Constraints for table `gdn_comment`
--
ALTER TABLE `gdn_comment`
  ADD CONSTRAINT `FK_DBDBA0134B89032C` FOREIGN KEY (`post_id`) REFERENCES `gdn_post` (`id_post`),
  ADD CONSTRAINT `FK_DBDBA013A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `gdn_contact`
--
ALTER TABLE `gdn_contact`
  ADD CONSTRAINT `FK_3CD14478CDE5729` FOREIGN KEY (`type`) REFERENCES `gdn_contact_type` (`id_contact_type`);

--
-- Constraints for table `gdn_cooperative_admin`
--
ALTER TABLE `gdn_cooperative_admin`
  ADD CONSTRAINT `FK_995E71D7642B8210` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_995E71D78D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`);

--
-- Constraints for table `gdn_cooperative_contact`
--
ALTER TABLE `gdn_cooperative_contact`
  ADD CONSTRAINT `FK_A2CD47D78D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`),
  ADD CONSTRAINT `FK_A2CD47D7E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_cooperative_line`
--
ALTER TABLE `gdn_cooperative_line`
  ADD CONSTRAINT `FK_8063492D4D7B7542` FOREIGN KEY (`line_id`) REFERENCES `gdn_line` (`id`),
  ADD CONSTRAINT `FK_8063492D8D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`);

--
-- Constraints for table `gdn_cooperative_line_contact`
--
ALTER TABLE `gdn_cooperative_line_contact`
  ADD CONSTRAINT `FK_B44C8D0E591A36A1` FOREIGN KEY (`line_contact_id`) REFERENCES `gdn_line_contact` (`id`),
  ADD CONSTRAINT `FK_B44C8D0EE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_cooperative_teller`
--
ALTER TABLE `gdn_cooperative_teller`
  ADD CONSTRAINT `FK_47DF20468D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`),
  ADD CONSTRAINT `FK_47DF2046E9894D10` FOREIGN KEY (`teller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `gdn_cooperative_zone`
--
ALTER TABLE `gdn_cooperative_zone`
  ADD CONSTRAINT `FK_F19C3DDC8D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`),
  ADD CONSTRAINT `FK_F19C3DDC9F2C3FAB` FOREIGN KEY (`zone_id`) REFERENCES `gdn_zone` (`id`);

--
-- Constraints for table `gdn_driver_contact`
--
ALTER TABLE `gdn_driver_contact`
  ADD CONSTRAINT `FK_FAE7DAA4C3423909` FOREIGN KEY (`driver_id`) REFERENCES `gdn_car_driver` (`id`),
  ADD CONSTRAINT `FK_FAE7DAA4E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_feed`
--
ALTER TABLE `gdn_feed`
  ADD CONSTRAINT `FK_4A4CB3BA4B89032C` FOREIGN KEY (`post_id`) REFERENCES `gdn_post` (`id_post`);

--
-- Constraints for table `gdn_image`
--
ALTER TABLE `gdn_image`
  ADD CONSTRAINT `FK_AFE4285A93CB796C` FOREIGN KEY (`file_id`) REFERENCES `gdn_file` (`id_file`);

--
-- Constraints for table `gdn_line`
--
ALTER TABLE `gdn_line`
  ADD CONSTRAINT `FK_B81843E745E9C671` FOREIGN KEY (`departure`) REFERENCES `gdn_mada_cities` (`id`),
  ADD CONSTRAINT `FK_B81843E75BE55CB4` FOREIGN KEY (`arrival`) REFERENCES `gdn_mada_cities` (`id`),
  ADD CONSTRAINT `FK_B81843E79F2C3FAB` FOREIGN KEY (`zone_id`) REFERENCES `gdn_zone` (`id`);

--
-- Constraints for table `gdn_line_car`
--
ALTER TABLE `gdn_line_car`
  ADD CONSTRAINT `FK_448316984D7B7542` FOREIGN KEY (`line_id`) REFERENCES `gdn_line` (`id`),
  ADD CONSTRAINT `FK_44831698C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `gdn_car` (`id`);

--
-- Constraints for table `gdn_line_contact`
--
ALTER TABLE `gdn_line_contact`
  ADD CONSTRAINT `FK_BEE87FA4D7B7542` FOREIGN KEY (`line_id`) REFERENCES `gdn_line` (`id`),
  ADD CONSTRAINT `FK_BEE87FA8D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`);

--
-- Constraints for table `gdn_media`
--
ALTER TABLE `gdn_media`
  ADD CONSTRAINT `FK_F58D09D1AA708F` FOREIGN KEY (`id_post`) REFERENCES `gdn_post` (`id_post`);

--
-- Constraints for table `gdn_passenger_contact`
--
ALTER TABLE `gdn_passenger_contact`
  ADD CONSTRAINT `FK_DAF269814502E565` FOREIGN KEY (`passenger_id`) REFERENCES `gdn_passenger` (`id`),
  ADD CONSTRAINT `FK_DAF26981E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_post`
--
ALTER TABLE `gdn_post`
  ADD CONSTRAINT `FK_33869B9C6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `gdn_post_category`
--
ALTER TABLE `gdn_post_category`
  ADD CONSTRAINT `FK_1FEFEFE712469DE2` FOREIGN KEY (`category_id`) REFERENCES `gdn_category` (`id_category`),
  ADD CONSTRAINT `FK_1FEFEFE74B89032C` FOREIGN KEY (`post_id`) REFERENCES `gdn_post` (`id_post`);

--
-- Constraints for table `gdn_post_contact`
--
ALTER TABLE `gdn_post_contact`
  ADD CONSTRAINT `FK_83A2B1AE4B89032C` FOREIGN KEY (`post_id`) REFERENCES `gdn_post` (`id_post`),
  ADD CONSTRAINT `FK_83A2B1AEE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_post_file`
--
ALTER TABLE `gdn_post_file`
  ADD CONSTRAINT `FK_BEDEE8BD7BF2A12` FOREIGN KEY (`id_file`) REFERENCES `gdn_file` (`id_file`),
  ADD CONSTRAINT `FK_BEDEE8BDD1AA708F` FOREIGN KEY (`id_post`) REFERENCES `gdn_post` (`id_post`);

--
-- Constraints for table `gdn_post_tag`
--
ALTER TABLE `gdn_post_tag`
  ADD CONSTRAINT `FK_9A3BF9AF4B89032C` FOREIGN KEY (`post_id`) REFERENCES `gdn_post` (`id_post`),
  ADD CONSTRAINT `FK_9A3BF9AFBAD26311` FOREIGN KEY (`tag_id`) REFERENCES `gdn_tag` (`id`);

--
-- Constraints for table `gdn_product`
--
ALTER TABLE `gdn_product`
  ADD CONSTRAINT `FK_9CE5F6D24D16C4DD` FOREIGN KEY (`shop_id`) REFERENCES `gdn_shop` (`id`),
  ADD CONSTRAINT `FK_9CE5F6D2C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `gdn_product_type` (`id`);

--
-- Constraints for table `gdn_producttype_attribute`
--
ALTER TABLE `gdn_producttype_attribute`
  ADD CONSTRAINT `FK_B2EADF1C5E032AB4` FOREIGN KEY (`producttype_id`) REFERENCES `gdn_product_type` (`id`),
  ADD CONSTRAINT `FK_B2EADF1CB6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `gdn_attribute` (`id`);

--
-- Constraints for table `gdn_product_attribute`
--
ALTER TABLE `gdn_product_attribute`
  ADD CONSTRAINT `FK_EAB69CCF4584665A` FOREIGN KEY (`product_id`) REFERENCES `gdn_product` (`id`),
  ADD CONSTRAINT `FK_EAB69CCFB6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `gdn_attribute` (`id`);

--
-- Constraints for table `gdn_product_file`
--
ALTER TABLE `gdn_product_file`
  ADD CONSTRAINT `FK_F35FF4147BF2A12` FOREIGN KEY (`id_file`) REFERENCES `gdn_file` (`id_file`),
  ADD CONSTRAINT `FK_F35FF414DD7ADDD` FOREIGN KEY (`id_product`) REFERENCES `gdn_product` (`id`);

--
-- Constraints for table `gdn_reservation`
--
ALTER TABLE `gdn_reservation`
  ADD CONSTRAINT `FK_828A2064502E565` FOREIGN KEY (`passenger_id`) REFERENCES `gdn_passenger` (`id`),
  ADD CONSTRAINT `FK_828A206E7EC5785` FOREIGN KEY (`board_id`) REFERENCES `gdn_reservation_board` (`id`);

--
-- Constraints for table `gdn_reservation_board`
--
ALTER TABLE `gdn_reservation_board`
  ADD CONSTRAINT `FK_65453E594D7B7542` FOREIGN KEY (`line_id`) REFERENCES `gdn_line` (`id`),
  ADD CONSTRAINT `FK_65453E598D0C5D40` FOREIGN KEY (`cooperative_id`) REFERENCES `gdn_cooperative` (`id`),
  ADD CONSTRAINT `FK_65453E59C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `gdn_car` (`id`);

--
-- Constraints for table `gdn_shop`
--
ALTER TABLE `gdn_shop`
  ADD CONSTRAINT `FK_C566BBB37E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_C566BBB3922726E9` FOREIGN KEY (`cover_id`) REFERENCES `gdn_file` (`id_file`);

--
-- Constraints for table `gdn_shop_category`
--
ALTER TABLE `gdn_shop_category`
  ADD CONSTRAINT `FK_7BBA9CD012469DE2` FOREIGN KEY (`category_id`) REFERENCES `gdn_category` (`id_category`),
  ADD CONSTRAINT `FK_7BBA9CD04D16C4DD` FOREIGN KEY (`shop_id`) REFERENCES `gdn_shop` (`id`);

--
-- Constraints for table `gdn_shop_city`
--
ALTER TABLE `gdn_shop_city`
  ADD CONSTRAINT `FK_17BD5DE94D16C4DD` FOREIGN KEY (`shop_id`) REFERENCES `gdn_shop` (`id`),
  ADD CONSTRAINT `FK_17BD5DE98BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `gdn_mada_cities` (`id`);

--
-- Constraints for table `gdn_shop_contact`
--
ALTER TABLE `gdn_shop_contact`
  ADD CONSTRAINT `FK_BD7946B74D16C4DD` FOREIGN KEY (`shop_id`) REFERENCES `gdn_shop` (`id`),
  ADD CONSTRAINT `FK_BD7946B7E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `gdn_contact` (`id_contact`);

--
-- Constraints for table `gdn_user_meta`
--
ALTER TABLE `gdn_user_meta`
  ADD CONSTRAINT `FK_5667E15AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `FK_57698A6A727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E993CB796C` FOREIGN KEY (`file_id`) REFERENCES `gdn_file` (`id_file`);

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `FK_51498A8EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_51498A8ED60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `user_provider`
--
ALTER TABLE `user_provider`
  ADD CONSTRAINT `user_provider_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
