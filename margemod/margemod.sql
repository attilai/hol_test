-- phpMyAdmin SQL Dump
-- version 3.3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 21 Jun 2011 om 15:35
-- Serverversie: 5.0.67
-- PHP-Versie: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `silver_margemod`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `margemod`
--

CREATE TABLE IF NOT EXISTS `margemod` (
  `products_id` int(11) NOT NULL,
  `products_name` varchar(255) collate utf8_bin NOT NULL,
  `qty` float NOT NULL,
  `value` float NOT NULL,
  `margin` float NOT NULL,
  `commission` float NOT NULL,
  `profit` float NOT NULL,
  `btw` float NOT NULL,
  `price` float NOT NULL,
  `total_price` float NOT NULL,
  `category` varchar(25) collate utf8_bin NOT NULL,
  `staffel` float NOT NULL,
  UNIQUE KEY `products_id` (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
--
-- Tabelstructuur voor tabel `margemod_values`
--

CREATE TABLE IF NOT EXISTS `margemod_values` (
  `name` varchar(10) NOT NULL,
  `value` float NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
