-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 09 apr 2015 om 23:53
-- Serverversie: 5.6.21
-- PHP-versie: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `command_poll`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `command_poll`
--

DROP TABLE IF EXISTS `command_poll`;
CREATE TABLE IF NOT EXISTS `command_poll` (
`poll_id` bigint(20) unsigned NOT NULL,
  `name` tinytext NOT NULL,
  `creator` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `open` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `command_poll`
--

INSERT INTO `command_poll` (`poll_id`, `name`, `creator`, `timestamp`, `open`) VALUES
(1, 'First poll', 'U02NQ1C2X', '2015-04-09 18:14:33', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `command_poll_option_data`
--

DROP TABLE IF EXISTS `command_poll_option_data`;
CREATE TABLE IF NOT EXISTS `command_poll_option_data` (
  `option_id` bigint(20) unsigned NOT NULL,
  `title` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `command_poll_option_data`
--

INSERT INTO `command_poll_option_data` (`option_id`, `title`) VALUES
(2, 'Option 1'),
(3, 'Option 2');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `command_poll_option_id`
--

DROP TABLE IF EXISTS `command_poll_option_id`;
CREATE TABLE IF NOT EXISTS `command_poll_option_id` (
`option_id` bigint(20) unsigned NOT NULL,
  `poll_id` bigint(20) unsigned NOT NULL,
  `option_index` int(1) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `command_poll_option_id`
--

INSERT INTO `command_poll_option_id` (`option_id`, `poll_id`, `option_index`) VALUES
(2, 1, 0),
(3, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `command_poll_votes`
--

DROP TABLE IF EXISTS `command_poll_votes`;
CREATE TABLE IF NOT EXISTS `command_poll_votes` (
  `option_id` bigint(20) unsigned NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `command_poll_votes`
--

INSERT INTO `command_poll_votes` (`option_id`, `user_id`, `timestamp`) VALUES
(2, 'U02NQ1C2X', '2015-04-09 18:21:49'),
(3, 'U02NQ1C2X', '2015-04-09 18:18:23');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `command_poll`
--
ALTER TABLE `command_poll`
 ADD PRIMARY KEY (`poll_id`);

--
-- Indexen voor tabel `command_poll_option_data`
--
ALTER TABLE `command_poll_option_data`
 ADD UNIQUE KEY `option_id` (`option_id`);

--
-- Indexen voor tabel `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
 ADD PRIMARY KEY (`option_id`,`poll_id`), ADD UNIQUE KEY `option_id_2` (`option_id`,`poll_id`,`option_index`), ADD KEY `poll_info_link` (`poll_id`);

--
-- Indexen voor tabel `command_poll_votes`
--
ALTER TABLE `command_poll_votes`
 ADD PRIMARY KEY (`option_id`,`user_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `command_poll`
--
ALTER TABLE `command_poll`
MODIFY `poll_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
MODIFY `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `command_poll_option_data`
--
ALTER TABLE `command_poll_option_data`
ADD CONSTRAINT `option_information_link` FOREIGN KEY (`option_id`) REFERENCES `command_poll_option_id` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
ADD CONSTRAINT `poll_info_link` FOREIGN KEY (`poll_id`) REFERENCES `command_poll` (`poll_id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `command_poll_votes`
--
ALTER TABLE `command_poll_votes`
ADD CONSTRAINT `poll_information_link` FOREIGN KEY (`option_id`) REFERENCES `command_poll_option_id` (`option_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
