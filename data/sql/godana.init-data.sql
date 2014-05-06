-- phpMyAdmin SQL Dump
-- version 3.3.7deb5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2014 at 04:13 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `godana`
--

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `parent_id`, `roleId`) VALUES
(1, NULL, 'guest'),
(2, 1, 'user'),
(3, 2, 'moderator'),
(4, 3, 'admin'),
(5, 2, 'shop-owner'),
(6, 2, 'cooperative-admin'),
(7, 2, 'cooperative-teller');

INSERT INTO `gdn_contact_type` (`id_contact_type`, `name`) VALUES
(1, 'mobile'),
(2, 'email'),
(3, 'address');

INSERT INTO `gdn_category` (`id_category`, `id_parent_category`, `category_name`, `category_ident`, `category_type`) VALUES
(1, NULL, 'appliance', 'appliance', 0),
(2, NULL, 'agriculture', 'agriculture', 0),
(3, NULL, 'housing', 'housing', 0),
(4, NULL, 'technology', 'technology', 0),
(5, NULL, 'transportation', 'transportation', 1),
(6, NULL, 'mining', 'mining', 0),
(7, NULL, 'quincaillerie', 'quincaillerie', 1),
(8, NULL, 'immobilier', 'immobilier', 1),
(9, NULL, 'habillement', 'habillement', 1),
(10, NULL, 'charcuterie', 'charcuterie', 1),
(12, NULL, 'boucherie', 'boucherie', 1);

INSERT INTO `gdn_file` (`id_file`, `type`, `relativePath`, `name`, `size`, `title`, `description`) VALUES (NULL, 'image/png', '/files/users/default/no-picture.png', 'no-picture.png', '1150', NULL, NULL);

INSERT INTO `gdn_image` (`id`, `file_id`, `dimension`, `name`) VALUES (NULL, '1', 'xs', 'no-picture.png_w24_cx0_cy0_cw59_ch59.png'), (NULL, '1', 'sm', 'no-picture.png_w40_cx0_cy0_cw59_ch59.png'), (NULL, '1', 'md', 'no-picture.png_w60_cx0_cy0_cw59_ch59.png');
