-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Stř 15. kvě 2013, 00:49
-- Verze MySQL: 5.5.31
-- Verze PHP: 5.4.15-1~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `eventCalendar_demo0_2`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `when` date NOT NULL,
  `desc` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=12 ;

--
-- Vypisuji data pro tabulku `events`
--

INSERT INTO `events` (`id`, `when`, `desc`) VALUES
(1, '2013-01-26', '**Last Saturday** by Nette community - never miss that!'),
(2, '2013-03-13', 'Happy birthday, daddy!'),
(3, '2013-07-05', 'Free day'),
(4, '2013-12-24', 'Christmas Eve'),
(5, '2014-01-23', 'Around the World in Eighty Days.'),
(6, '2015-03-21', 'Spring starts, yeah!'),
(7, '2013-04-27', 'Last Saturday @ "SocialBakers":http://analytics.socialbakers.com/'),
(9, '2013-09-22', 'New semester starts, oh no! *#ohgodwhy*'),
(10, '2013-05-25', 'Last Saturday again? anybody? I am thirsty ... *for Nette*, of course!'),
(11, '2013-11-17', 'International Students'' Day - **free day**!');

-- --------------------------------------------------------

--
-- Struktura tabulky `translation`
--

CREATE TABLE IF NOT EXISTS `translation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `lang` char(2) COLLATE utf8_czech_ci NOT NULL,
  `translation` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `origin` (`origin`,`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=24 ;

--
-- Vypisuji data pro tabulku `translation`
--

INSERT INTO `translation` (`id`, `origin`, `lang`, `translation`) VALUES
(1, '<', 'en', 'prev'),
(2, 'May', 'en', 'May'),
(3, '>', 'en', 'next'),
(4, 'Sunday', 'en', 'Sun'),
(5, 'Monday', 'en', 'Mon'),
(6, 'Tuesday', 'en', 'Tue'),
(7, 'Wednesday', 'en', 'Wed'),
(8, 'Thursday', 'en', 'Thu'),
(9, 'Friday', 'en', 'Fri'),
(10, 'Saturday', 'en', 'Sat'),
(11, 'Previous month', 'en', 'prev'),
(12, 'Next month', 'en', 'next'),
(13, 'April', 'en', 'April'),
(14, 'June', 'en', 'June'),
(15, 'July', 'en', 'July'),
(16, 'August', 'en', 'August'),
(17, 'September', 'en', 'September'),
(18, 'October', 'en', 'October'),
(19, 'November', 'en', 'November'),
(20, 'December', 'en', 'December'),
(21, 'January', 'en', 'January'),
(22, 'February', 'en', 'February'),
(23, 'March', 'en', 'March');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
