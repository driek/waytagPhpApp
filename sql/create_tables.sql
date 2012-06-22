-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Genereertijd: 21 jun 2012 om 11:55
-- Serverversie: 5.5.25
-- PHP-versie: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `waytagdemo`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Activities`
--

CREATE TABLE IF NOT EXISTS `Activities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Activities_Promoters`
--

CREATE TABLE IF NOT EXISTS `Activities_Promoters` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Activity_ID` int(11) NOT NULL,
  `Promoter_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Activity_ID` (`Activity_ID`,`Promoter_ID`),
  KEY `Promoter_ID` (`Promoter_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Checkins`
--

CREATE TABLE IF NOT EXISTS `Checkins` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `waytag_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `waytag_reference` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `checked_in_at` datetime NOT NULL,
  `checked_out_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Promoters`
--

CREATE TABLE IF NOT EXISTS `Promoters` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `waytag_ID` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `optional_status` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_2` (`ID`),
  KEY `ID` (`ID`),
  KEY `ID_3` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `waytag_user_name` varchar(50) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `waytag_user_name` (`waytag_user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `Activities_Promoters`
--
ALTER TABLE `Activities_Promoters`
  ADD CONSTRAINT `activities_promoters_ibfk_1` FOREIGN KEY (`Activity_ID`) REFERENCES `Activities` (`ID`),
  ADD CONSTRAINT `activities_promoters_ibfk_2` FOREIGN KEY (`Promoter_ID`) REFERENCES `Promoters` (`ID`);
