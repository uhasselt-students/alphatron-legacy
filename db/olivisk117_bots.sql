-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 27, 2017 at 11:13 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olivisk117_bots`
--

-- --------------------------------------------------------

--
-- Table structure for table `command_poll`
--

CREATE TABLE `command_poll` (
  `poll_id` bigint(20) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `creator` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `open` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `command_poll`
--

INSERT INTO `command_poll` (`poll_id`, `name`, `creator`, `timestamp`, `open`) VALUES
(1, 'First poll', 'U02NQ1C2X', '2015-04-20 18:35:30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `command_poll_option_data`
--

CREATE TABLE `command_poll_option_data` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `title` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `command_poll_option_data`
--

INSERT INTO `command_poll_option_data` (`option_id`, `title`) VALUES
(2, 'Option 1'),
(3, 'Option 2'),
(4, 'NIEUWE NAAM OPT3');

-- --------------------------------------------------------

--
-- Table structure for table `command_poll_option_id`
--

CREATE TABLE `command_poll_option_id` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `poll_id` bigint(20) UNSIGNED NOT NULL,
  `option_index` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `command_poll_option_id`
--

INSERT INTO `command_poll_option_id` (`option_id`, `poll_id`, `option_index`) VALUES
(2, 1, 0),
(3, 1, 1),
(4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `command_poll_votes`
--

CREATE TABLE `command_poll_votes` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `command_poll_votes`
--

INSERT INTO `command_poll_votes` (`option_id`, `user_id`, `timestamp`) VALUES
(2, 'U02NQ1C2X', '2015-04-09 18:21:49'),
(3, 'U02NQ1C2X', '2015-04-20 18:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `definitions`
--

CREATE TABLE `definitions` (
  `author` varchar(256) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `definition` varchar(1024) NOT NULL,
  `defID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `definitions`
--

INSERT INTO `definitions` (`author`, `subject`, `definition`, `defID`) VALUES
('U02MPKU36', 'Een \'Faeske\' doen', ' Een zodanig moeilijke oplossing voor een heel simpel probleem maken zodat niemand de oplossing snapt buiten Axel.', 1),
('U02MPKU36', 'Olivier \"Drollie\" De Schaetzen', ' :shit:', 24),
('U02MPKU36', 'Een Axelke doen', ' Een heel obvious statement maken, bv: \"Lopen is gewoon lopen.\"', 6),
('U02MVVAGS', 'Epistemologische en ontologische paradigma\'s', ' bullshit.', 28),
('U02MPKU36', 'Catie Wayne', ' :praise: the kawaii-ness! :smile:', 27),
('U02MPMK3Y', 'Sociologie', ' Kritisch sociaal realisme in een mutually shaped deterministische reflectie van de wereld, onderzocht met een positivistsche Epistomoligische kijk voor het vormen van theoretische denkkaders.', 26),
('U02MVVAGS', 'Luuk', ' Niet Lucas!', 9),
('U02MVVAGS', 'Lucas', ' Niet Luuk!', 10),
('U02MPMK3Y', 'Alphatron', ' The best bot in the whole wide world.', 23),
('U02MVVAGS', 'Recursie', ' zie Recursie.', 13),
('U02MPKU36', 'JilJil', ' Jonny is love, Jonny is life.', 22),
('U02MPKU36', 'Clutch', ' Een gevecht waar je maar heel nipt gewonnen hebt.', 21),
('U02MPKU36', 'Kappa', ' :kappa: The main symbol/emote of <http://Twitch.tv|Twitch.tv>. It represents sarcasm, irony, puns, jokes, and trolls alike. If you see this term used outside of <http://Twitch.tv|Twitch.tv>, then this is not the correct definition. Usually used at the end of an ironic or sarcastic sentence. Sentences that contain a Kappa should not be taken seriously. If you search \"Kappa <http://Twitch.tv|Twitch.tv>\" in Google you can see what the emote looks like, and why it is used as it is. Sentences that use Kappa do not always have to make sense.', 19),
('U02MPKU36', 'Yandere', ' Contrary to the popular belief that it is the opposite of tsundere, it describes an anime character who is either psychotic or violent or both, and shows affection to the main character. Became a popular moe genre after the airing of the last episode of School Days.', 46),
('U02MPMK3Y', 'Prolog', ' P(X) :- P(X).', 30),
('U02MPKU36', 'Skype', ' Een chat programma dat Slack zijn bitch is geworden!', 31),
('U02MPKU36', 'citiral', 'Een filthy casual die met de database cheate na dat hij een stomme blunder begaan had.', 32),
('U02MPKU36', 'Priapus', 'A minor rustic fertility god, protector of livestock, fruit plants, gardens and male genitalia. Priapus is marked by his oversized, permanent erection, which gave rise to the medical term priapism.', 33),
('U02MPMK3Y', 'Fap Farmer', ' Equivalent to a jackoff or a wanker. Also a term for masturbating excessively.', 34),
('U02MPMK3Y', 'Masturwait', ' Fapping while you\'re watching porn but you\'re waiting because the video\'s buffering.', 35),
('U02MPMK3Y', 'Blessturbate', ' To say \"Bless me\" after sneezing. Coming from the word masturbate, but instead of pleasuring themself, the person is blessing themself.', 36),
('U02MPMK3Y', 'Fuckboi', ' A guy who tries to get with everyone. A player. A guy who will lie to a girl to make them hook up with them or send pics. They think they are the shit when they aren\'t. A guy who will only date a girl for their body. A total ass. A guy that will make a girl cry and laugh, and a guy who lies when they said I love you', 37),
('U02MPMK3Y', 'Butt Buddies', ' A term used to describe very very very close friends of the same sex. Almost as if they were gay but they certainly aren\'t gay. Often seen together 24/7.', 38),
('U02MPMK3Y', 'assgut', ' When you\'re so fat you have a duplicate belly hanging over the back of your pants', 39),
('U02MPMK3Y', 'basicism', ' the professional art of being basic', 40),
('U02MPMK3Y', 'nursing a semi', ' when a man gets half excited. not a full boner, but he is nearly there.', 41),
('U02MPMK3Y', 'Sad Masty', ' To masturbate while crying, and using the tears as lubricant.', 42),
('U02MPMK3Y', 'dildo', ' Hetgeen in tom zit.', 47),
('U02MPKU36', 'Creepypasta', ' Creepypasta is a popular subgenre of copypasta which consists of short horror fictions and urban legends mainly distributed through word of mouth via online message boards or e-mail. In recent years, some authors have re-appropriated the genre into an effective pretext for bait-and-switch trolling.', 48),
('U02MPMK3Y', 'recurise', ' zie Recurise.', 49);

-- --------------------------------------------------------

--
-- Table structure for table `fucks`
--

CREATE TABLE `fucks` (
  `id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fucks`
--

INSERT INTO `fucks` (`id`, `amount`) VALUES
('bertp', 933),
('citiral', 2131),
('monsieur_mahieu', 1124),
('shadowblink', 829),
('lucky_luuk', 661),
('slackbot', 0),
('maxzilla60', 2231),
('theaxe', 1285),
('lufo', 677),
('vidtje', 1348),
('grefo', 502),
('jessio_di_fiore', 1103),
('crushski', 710),
('cwout', 1132),
('pieterte', 1051),
('maderbot', 1116),
('mahieu', 1060),
('hepedoge', 821),
('hasinka1', 593),
('7rozen_7ear_', 1028),
('alx', 1237),
('kempen', 1010),
('mahieu12312', 964),
('suerrenein', 728),
('luukerine', 812),
('michielvm', 796),
('dries', 795),
('guy_t', 377),
('hendrikl', 802),
('emielverlinden', 677),
('niels', 666),
('jensv', 698);

-- --------------------------------------------------------

--
-- Table structure for table `giftime`
--

CREATE TABLE `giftime` (
  `user` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giftime`
--

INSERT INTO `giftime` (`user`, `time`) VALUES
('U02NY3LRC', '2015-10-25 19:04:43'),
('U02MSECBU', '2016-03-03 11:14:57'),
('U02NRFSAY', '2015-08-21 21:32:13'),
('U02MPMK3Y', '2016-09-05 08:57:44'),
('U02MPKU36', '2016-05-25 12:40:30'),
('U02MS0WM1', '2016-04-22 17:50:25'),
('U02NQ1C2X', '2016-06-25 17:00:57'),
('U02MN6V7H', '2015-10-22 16:26:57'),
('U02MSR6Q4', '2014-10-12 20:11:34'),
('USLACKBOT', '2014-10-13 06:15:09'),
('U02NSBALN', '2014-12-16 14:52:33'),
('U02P3UNLD', '2014-12-08 15:30:01'),
('U02NY183J', '2014-10-14 17:56:24'),
('U02N661KC', '2014-11-23 12:05:05'),
('U02QJD7D7', '2014-10-29 20:10:41'),
('U02Q7JSUU', '2014-11-20 13:53:58'),
('U02MUH64X', '2016-04-27 21:50:01'),
('U02MVVAGS', '2015-03-24 21:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `giftokens`
--

CREATE TABLE `giftokens` (
  `userid` varchar(255) NOT NULL,
  `tokens` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giftokens`
--

INSERT INTO `giftokens` (`userid`, `tokens`) VALUES
('U02MPKU36', 1),
('U02MPMK3Y', 0),
('U02MN6V7H', 0),
('U02NQ1C2X', 0),
('U056KENC5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `insults`
--

CREATE TABLE `insults` (
  `insultID` int(11) NOT NULL,
  `insult` text NOT NULL,
  `authorID` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `insults`
--

INSERT INTO `insults` (`insultID`, `insult`, `authorID`) VALUES
(1, 'houdt er van om in verotte bananen te knijpen!', 'U02MPKU36'),
(2, 'is een walvissenscheet!', 'U02MPKU36'),
(3, 'is een baka :anger: !', 'U02MPKU36'),
(4, 'is een pikkenzuiger!', 'U02MPKU36'),
(5, 'kan niet lezen!', 'U02MPKU36'),
(6, 'wilt stiekem een homohuwelijk.', 'U02MPMK3Y'),
(7, 'gebruikt een mac!', 'U02MPMK3Y'),
(8, 'is eigenlijk een hele toffe persoon. :heart:', 'U02MS0WM1'),
(9, 'luistert in zijn vrije tijd naar Justin Bieber.', 'U02MPMK3Y'),
(10, 'is zo traag dat zelfs internet explorer sneller is!', 'U02MPKU36'),
(11, 'likt de poes van zijn mama!', 'U02MPKU36'),
(12, 'zijn moeder is dik.', 'U02MPMK3Y'),
(13, 'is een respectvol persoon.', 'U02MS0WM1'),
(14, 'gebruikt een code-autoformatter.', 'U02MPMK3Y'),
(15, 'heeft geen harem.', 'U02MPMK3Y'),
(16, 'kan niet werken in niet garbage-gecollecte programmeertalen.', 'U02MPMK3Y'),
(17, 'heeft een potlood in zijn kont steken.', 'U02MPMK3Y'),
(18, 'is a fucking dimwit', 'U02MPKU36'),
(19, 'kan geen originele insults verzinnen!', 'U02MPMK3Y'),
(20, 'heeft een kleine piemel.', 'U02MPMK3Y'),
(21, 'is slecht in Dark Souls.', 'U02MPMK3Y'),
(22, 'has 99 problems and a chick is one.', 'U02MPMK3Y'),
(23, 'gebruikt een Windows phone', 'U02MUH64X'),
(24, 'wilt voor Donal Trump stemmen.', 'U02MPKU36'),
(25, 'forgot the D.', 'U02MPKU36');

-- --------------------------------------------------------

--
-- Table structure for table `nexus`
--

CREATE TABLE `nexus` (
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nexus`
--

INSERT INTO `nexus` (`name`) VALUES
('Dead');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `userid` varchar(256) NOT NULL,
  `packageid` varchar(256) NOT NULL,
  `type` varchar(50) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `status` varchar(2000) NOT NULL,
  `description` varchar(256) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `ID` int(11) NOT NULL,
  `text` text NOT NULL,
  `person` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote`
--

INSERT INTO `quote` (`ID`, `text`, `person`, `date`) VALUES
(1, 'Axel is gay', ':ollie:', '2014-12-17 21:13:18'),
(2, 'Swagbot zit precies vast', ':brent:', '2014-12-17 21:14:34'),
(8, 'Ik ben lang.', 'Penis', '2014-12-17 21:23:59'),
(7, 'Use the force Harry Potter! It\'s the only way you can defeat Ganondorf!', 'Gandalf', '2014-10-07 19:22:51'),
(6, 'fuckme', ':ollie:', '2014-12-17 21:13:26'),
(32, 'Remember, remember, the ninth of October. Remember, the night the bot took over.', ':max:', '2015-03-01 14:35:20'),
(30, 'Ik val op jongens.', ':luuk:', '2014-12-17 21:14:45'),
(31, '!tron: spambot 10 !tron: spambot 10  !tron: spambot 10  !tron: spambot 10', 'Spambot', '2014-10-09 20:16:14'),
(13, 'If it bleeds... we can kill it.', 'Arnold', '2014-10-08 08:35:46'),
(14, 'ALS JE NOG EEN KEER ALS JE NOG EEN KEER ALS JE NOG EEN KEER MET JE SCHILLEN!', 'Maderbot', '2014-10-08 07:08:30'),
(29, 'Out of all the channels, #chickas is the most important one to archive.', ':max:', '2015-03-01 14:35:20'),
(16, 'Dis is mein flammenwerfer, it werfs flammen!', 'Hitler', '2014-10-08 08:34:44'),
(17, 'Stoppen met die gifs! Ik wil kunnen fappen!', ':ollie:', '2015-06-15 13:51:00'),
(18, '*fap fap fapfap FAP fap*', ':ollie:', '2014-12-17 21:13:59'),
(28, 'Garbage Day!', 'Ricky', '2014-10-09 13:24:52'),
(20, 'May the mass times acceleration be with you', ':axel:', '2014-12-17 21:14:57'),
(21, 'Warm it up.... ptptptptpt.... That\'s a ten.', 'Unknown', '2014-10-08 10:29:47'),
(22, 'I had a dream!', 'Obama', '2014-10-08 10:31:39'),
(23, 'Yes we can!', 'JFK', '2014-10-08 10:32:00'),
(27, 'It\'s all-terrain, dummy!', 'Troy', '2014-10-09 10:12:38'),
(25, 'Snoiping\'s a good job, mate.', 'Sniper', '2014-10-08 13:18:05'),
(26, 'Gifbot is the bot slack needs but doesn\'t deserve.', ':brent:', '2014-12-17 21:15:09'),
(33, 'DOOITZ!', 'Sp4zie', '2014-10-09 20:34:51'),
(34, 'Someone please wake up the admin!', 'Unknown', '2014-10-09 20:41:18'),
(35, 'A mighty storm!', 'Ao Kuang', '2014-10-10 12:22:00'),
(36, 'The earth moves!', 'Geb', '2014-10-10 02:21:31'),
(37, 'Y\'see, kids.. they listen to the rap music.', 'Bill Cosby', '2014-10-10 12:22:40'),
(38, 'Als die nie werk, dan werk die nie, hè.', ':max:', '2015-03-01 14:35:20'),
(39, 'Double de Wukong, double de damage!', ':max:', '2015-03-01 14:35:20'),
(40, 'R.I.P. Spambot, NEVER FORGET 09/10/2014 - 10/10/2014!', ':faceless:', '2014-12-17 21:15:44'),
(41, 'This is mein panzerschreck, it shreks panzer!', 'Hitler', '2014-10-10 08:51:53'),
(42, 'This is mein panzerfaust, it fausts panzer!', 'Hitler', '2014-10-10 08:52:16'),
(44, 'Axel is gay Axel is gay Axel is gay Axel is gay Axel is gay Axel is gay Axel is gay', 'Spambot', '2014-10-10 12:40:51'),
(45, 'Wie een mens is, is hypocriet.', ':max:', '2015-03-01 14:35:20'),
(46, 'Ik ben lelijk.', 'Dennis', '2014-12-17 21:24:11'),
(47, ':fries::hamburger:', ':max:', '2015-03-01 14:35:20'),
(48, 'Het is een ramp!', 'Burgemeester', '2014-10-10 17:21:14'),
(49, 'Too many gifs!', ':ollie:', '2014-12-17 21:13:33'),
(50, 'Pop pop!', 'Magnetude', '2014-10-13 16:00:41'),
(51, 'Awel mama, ik mag geen Playstation? Dan ga ik die pik zuigen!', ':axel:', '2014-12-17 21:16:34'),
(52, 'I\'m here to kick ass and chew bubblegum...', 'Duke Nukem', '2014-12-17 21:19:59'),
(53, '...and I\'m all out of gum.', 'Duke Nukem', '2014-12-17 21:20:07'),
(54, 'fansie', ':pieter:', '2015-03-01 14:36:41'),
(90, 'You are all weak, you are all bleeders!', 'Soldier', '2014-12-17 23:07:45'),
(56, 'miemie(meme)', 'Alexander Vanspauwen', '2014-12-17 21:17:13'),
(57, 'Appelsien ging naar de winkel en kocht een wiskunde.', ':max:', '2015-03-01 14:35:20'),
(58, 'Do I look like I know what a JPEG is?', 'Hank', '2014-11-05 12:12:14'),
(59, 'Het is niet Ollie, het is Weledelgeboren Heer Ridder Olivier Dimitri Lea Guillain Marie de Schaetzen.', ':ollie:', '2014-12-17 21:19:34'),
(60, 'Gaben does not simply count to three.', 'Unknown', '2014-10-28 18:45:56'),
(61, 'What in Davy Jones\' locker did ye just bark at me, ye scurvy bilgerat? I\'ll have ye know I be the meanest cutthroat on the seven seas, and I\'ve led numerous raids on fishing villages, and raped over 300 wenches. I be trained in hit-and-run pillaging and be the deadliest with a pistol of all the captains on the high seas. Ye be nothing to me but another source o\' swag. I\'ll have yer guts for garters and keel haul ye like never been done before, hear me true. You think ye can hide behind your newfangled computing device? Think twice on that, scallywag. As we parley I be contacting my secret network o\' pirates across the sea and yer port is being tracked right now so ye better prepare for the typhoon, weevil. The kind o\' monsoon that\'ll wipe ye off the map. You\'re sharkbait, fool. I can sail anywhere, in any waters, and can kill ye in o\'er seven hundred ways, and that be just with me hook and fist. Not only do I be top o\' the line with a cutlass, but I have an entire pirate fleet at my beck and call and I\'ll damned sure use it all to wipe yer arse off o\' the world, ye dog. If only ye had had the foresight to know what devilish wrath your jibe was about to incur, ye might have belayed the comment. But ye couldn\'t, ye didn\'t, and now ye\'ll pay the ultimate toll, you buffoon. I\'ll shit fury all over ye and ye\'ll drown in the depths o\' it. You\'re fish food now, lad.', 'Pirate', '2014-10-28 19:25:24'),
(62, 'More chicks, less spam!', ':axel:', '2014-12-17 21:16:54'),
(63, 'Don\'t trust cute. Cute is always evil.', ':max:', '2015-03-01 14:35:20'),
(64, 'Crawling in my skiiiin, this orange will not peeeeel! :musical_note:', ':brent:', '2014-12-17 21:17:46'),
(66, 'Ik heb mijn lepel al helemaal tot aan zijn anus door zijn strot geramt.', ':brent:', '2014-12-17 21:17:55'),
(67, 'How many C++ programmers does it take to change a light bulb?\r\n\r\nA: You\'re still thinking procedurally. A properly designed light bulb object would inherit a change method from a generic light bulb class, so all you would have to do is call the light-bulb-change method.', 'Joke', '2014-12-17 21:23:37'),
(68, 'Ge zijt de persoon vergeten Ollie!', ':brent:', '2014-12-17 21:18:16'),
(69, 'Q: How many C++ programmers does it take to change a light bulb?\n\nA: Youâ€™re still thinking procedurally. A properly designed light bulb object would inherit a change method from a generic light bulb class, so all you would have to do is call the light-bulb-change method.', 'Joke', '2014-11-03 20:23:44'),
(70, 'Double the Wukong double the damage!', ':max:', '2015-03-01 14:35:20'),
(71, 'wilt ge mijn banaan ne keer lekken', ':ollie:', '2014-12-17 21:14:04'),
(72, ':creeper:', ':max:', '2015-03-01 14:35:20'),
(73, 'That\'s no good!', 'Sonic', '2014-11-12 11:30:19'),
(74, 'Klaatu Barada Nikto.', 'Ash', '2014-11-14 12:59:19'),
(75, 'Triviaal!', ':jelle:', '2014-12-17 21:18:40'),
(76, 'Op die moment.', ':ollie:', '2014-12-17 21:14:09'),
(77, 'Payday 2?', 'Wout', '2014-12-03 13:22:31'),
(78, 'Slaat Luuk op het kontje terwijl hij weg wandelt.', ':ollie:', '2014-12-17 21:14:14'),
(80, 'Jongen hoe werkt die shit?!', ':vincent:', '2014-12-17 21:24:24'),
(81, 'Die Fransen bezeiken ons al jaren in de botten.', ':brent:', '2015-06-15 13:50:02'),
(82, 'Bij die eerste examen!', ':luuk:', '2014-12-17 21:19:15'),
(83, 'Speel met mij!', 'Playstation', '2014-12-03 19:09:38'),
(84, 'Lopen dat is gewoon ... lopen!', ':axel:', '2014-12-17 21:17:22'),
(85, 'En dan steelt ge hun kinderen!', ':axel:', '2014-12-17 21:17:31'),
(86, 'More polygons means more emotions.', 'David Cage', '2014-12-17 21:18:51'),
(87, 'Do a barrel roll!', ':rabbit:', '2014-12-16 15:23:58'),
(88, 'sehr herdceur', ':ollie:', '2014-12-17 21:14:18'),
(89, 'too bad i don\'t give a fuck :stuck_out_tongue:', ':luuk:', '2014-12-17 13:28:37'),
(91, 'Ollie, ik heb gedroomd dat jij vele kleiner was en ik vele groter en jij ging dan op mijn schouders zitten en wij gingen dan op avonturen enzo. En toen vond je iets uit waardoor ik heel heel hoog kon springen (voelde zo echt) en jij was dat dan aan het uitleggen aan twee skeptische meneren aan de hand van een recursieve programma en toen werd ik wakker. :)', ':max:', '2015-03-01 14:35:20'),
(92, 'Is ze knap? Anders zegt ge maar dat ge iemand kent (-&gt; BERT PROESMANS)', ':bert:', '2015-03-01 14:33:48'),
(93, 'I am zhe brain of zhe operation.', 'Voltar', '2015-01-04 15:10:36'),
(94, 'YYES!', ':ollie:', '2015-01-04 16:51:12'),
(95, 'Nobody touches the database!', ':ollie:', '2015-01-05 11:57:31'),
(96, 'Hoe dichter bij de nul, hoe strakker rond de lul.', ':ollie:', '2015-01-05 13:42:55'),
(102, 'Quote number three is non-existent.', ':alphatron:', '2015-01-06 13:30:55'),
(103, 'De punten staan online!', ':brent:', '2015-03-01 14:34:17'),
(104, 'Bearer seek seek lest.', 'Emerald_Herald', '2015-01-10 20:59:20'),
(105, 'Free software is best software.', ':bert:', '2015-03-01 14:34:34'),
(106, 'Alt-F4.', ':ollie:', '2015-01-15 12:52:22'),
(107, 'Whatever you do... Don\'t fart on anyone\'s balls.', 'Cartman', '2015-01-15 12:54:09'),
(108, 'hlep', ':ollie:', '2015-02-12 17:52:38'),
(109, 'GUI is devil, GUI is hell!', ':bert:', '2015-03-01 14:22:12'),
(110, 'Niet in leerstof :max:', ':bert:', '2015-03-01 14:22:43'),
(111, 'Gotta go fast!', 'Sanic', '2015-03-01 14:24:40'),
(112, 'A stroll through memory lane.', ':ollie:', '2015-03-01 14:25:54'),
(113, '!tron: quote 11', ':brent:', '2015-03-01 14:26:21'),
(114, 'nu heb ik zin in een lange, dikke, vettige, druiperige ..', ':ollie:', '2015-03-01 14:28:06'),
(115, 'nu heb ik zin in een lange, dikke, vettige druiperige ..', ':ollie:', '2015-03-01 14:36:04'),
(116, ':topkek:', ':max:', '2015-03-01 14:39:57'),
(117, 'dees pseudocode zou ook niet pseudowerken', ':ollie:', '2015-03-03 17:55:24'),
(118, 'Badabing, badaboom.', 'VideogameDunkey', '2015-03-03 18:56:38'),
(119, ':ollie: zingt alleen valse :noot:en.', ':max:', '2015-03-03 22:19:29'),
(120, 'Schoon tetten.', ':bert:', '2015-06-15 13:47:14'),
(121, 'Be brave, horses\' asses!', 'Unknown', '2015-03-05 13:50:44'),
(122, 'Bij mij was dat rete buggy.', 'Robin', '2015-03-07 11:20:23'),
(123, 'Emojis zijn de toekomst.', ':max:', '2015-03-13 16:35:08'),
(124, 'What is your favourite scent to perfume your butthole with?', 'Sark', '2015-03-14 16:38:23'),
(125, 'Stel u veer.', 'Olivier', '2015-03-16 21:54:16'),
(126, 'hoe addquote ge ook alweer?', ':luuk:', '2015-03-17 17:08:11'),
(127, 'â™ªâ™« Tell me have you seen, the marvelous nekbaard!! :max: â™«â™ª', ':luuk:', '2015-03-17 17:09:22'),
(128, 'There are always comments.', ':max:', '2015-03-17 19:00:38'),
(129, 'Ik zal eens iets proberen, Luuk.', ':max:', '2015-03-17 19:19:26'),
(130, 'die luuk is echt wel cool', ':ollie:', '2015-03-17 20:54:04'),
(131, 'lettelriijk', ':vincent:', '2015-03-19 13:31:45'),
(132, 'Okay, I prefer the D anyway', ':ollie:', '2015-03-19 19:08:40'),
(133, 'Help, er steekt een penis vast in mijn kont!', ':max:', '2015-03-19 19:09:37'),
(134, 'Ollie\'s penis. :wink:', ':max:', '2015-03-19 19:10:07'),
(135, 'trouwens, ik ben letterlijk een drol', ':ollie:', '2015-03-23 16:44:48'),
(136, 'Dit is geen bitch.', ':bert:', '2015-03-24 21:21:46'),
(137, 'Brent :trekt: iedere avond.', ':ollie:', '2015-03-24 21:33:41'),
(138, 'I got 99 quotes but 99 ain\'t one!', ':luuk:', '2015-03-24 21:46:22'),
(139, '_Kch_', ':ollie:', '2015-03-25 13:16:35'),
(140, 'ik heb penis gegeten en het was zeer lekker', ':ollie:', '2015-03-25 16:47:06'),
(141, 'Op de poes?', ':bert:', '2015-03-28 12:06:12'),
(142, 'Wat als je zo een van die twee *zeikanten* de prent en de tekst omwisselt?', ':ollie:', '2015-03-29 15:39:45'),
(143, 'Ik heb hier een shotgun, hÃ¨. Ik ga die zo diep in uw gat steken dat ik toch nog een headshot haal.', ':ollie:', '2015-04-01 17:56:37'),
(144, 'de buttie is waar de kakkie uitkomt', ':ollie:', '2015-04-08 16:33:49'),
(145, 'Het kan evengoed ook een vrouw zijn.', ':ollie:', '2015-04-09 11:51:16'),
(146, 'In C programmeren is als SM. Ge tuigt haar goed af en krijgt dan veel voldoening.', ':axel:', '2015-04-10 09:57:21'),
(148, '\"Î˜Î¬Î½Î¿Ï‚\" Barthels Niks is bijzonder weinig.', 'Robin', '2015-04-10 10:07:04'),
(149, 'HET IS BUTT DAT OLIVIER', ':bert:', '2015-04-16 12:46:41'),
(150, '\"die fransen bezeiken ons al jaren in de botten\" ik wil niet moeilijk doen.. maar dat was mijn quote', ':bert:', '2015-04-25 18:38:08'),
(151, 'Ik heb wel iets dat ik heel graag aan Steven wil tonen, maar ik vrees dat dat niet echt psopv related is.', ':ollie:', '2015-04-26 14:17:23'),
(152, 'adds a whole new dimension to the concept of being a dirty girl.............. (you never heard me say that)', 'Reinaert', '2015-04-26 22:02:27'),
(153, 'Every sixty seconds in Africa... a minute goes by. :cry:', ':max:', '2015-04-28 19:06:20'),
(154, 'Kunt ge \nnewlines in                                              ( . )  ( . )\nde quotes                                                    \ndoen? 3==========D - - - - --       (|*|)', ':max:', '2015-04-30 09:28:28'),
(155, 'Beste bram\n\nsorry, maar deze zetel zit verdomd goed. Is morgen ook goed?\n\nMvg, Brent', ':brent::ollie:', '2015-04-30 09:29:24'),
(156, 'Kunt ge \nnewlines in                                              ( . )  ( . )\nde quotes                                                    \ndoen? 3==========D - - - - --       (|*|)', ':max:', '2015-04-30 11:22:49'),
(157, 'Ik ben ook nummer twee.', ':max:', '2015-05-04 16:42:15'),
(158, 'Je begint met een foto van z\'n gekidnapte vrouw gevolgd door \"I have your wife..\"', ':max:', '2015-05-04 16:43:02'),
(159, 'Is daar ook een nsfw van?', ':bert:', '2015-06-15 13:48:06'),
(160, 'Dat zou toch geen sexy ruimteschepen hoor.', ':ollie:', '2015-05-05 19:35:49'),
(161, 'I like big sweaty balls in my mouth.', ':max:', '2015-05-07 11:43:41'),
(162, 'Minecraft is eigenlijk maar heel dom.', ':max:', '2015-05-07 11:43:59'),
(163, '3=========D - - ---  -- -- --- :max:', ':max:', '2015-05-07 11:45:02'),
(164, 'de pluspunt aan het kidnappen van kinderen is dat, eens als je ze compleet gebroken hebt, je ze gewoon kan weg gooien en een nieuwe pikken.', ':ollie:', '2015-05-11 22:15:05'),
(165, '94 is een goed jaar.. ik bedoel maar, kijk naar mij enzo', ':bert:', '2015-05-26 20:30:08'),
(166, 'Ge weet toch dat een poot vier stoelen heeft?', ':brent:', '2015-05-29 07:10:37'),
(167, 'ah, damnit, nu sta ik hier helemaal naakt en heeft dat dus niets geholpen....', ':olivier:', '2015-06-07 11:47:44'),
(168, '1000% keer beter', ':ollie:', '2015-06-07 22:56:17'),
(169, 'punten van PSOPV staan online!!!!', ':luuk:', '2015-06-08 20:24:03'),
(170, 'recurise heujjj', 'Gilles', '2015-06-15 13:54:14'),
(171, 'Fun Fact: In Romeinse tijden droegen een hoop kerels halskettingen met lullen rond hun nek voor Priapus.', 'Gilles', '2015-06-15 13:54:04'),
(172, 'Ik neem die dildo en steek hem in mijn gat, als ge echt wilt', ':ollie:', '2015-06-15 13:52:43'),
(173, 'Ik neem die grotere dildo en steek hem in mijn gat, als ge echt wilt', ':luuk:', '2015-06-08 20:36:40'),
(174, 'Die dildo neemt mij en stopt mij in zijn gat, als ge echt wilt.', ':ollie:', '2015-06-15 13:53:25'),
(175, 'En Gilles, komt ge klaar?', 'Mama van Gilles', '2015-06-15 13:42:13'),
(176, 'Nee, ik doe niks. Ik ben niks, nee.', ':axel:', '2015-06-15 13:47:00'),
(177, 'Hij lekt weer aan zijn tong.', 'Papa van Gilles', '2015-06-15 13:51:59'),
(178, 'Dat is toch bizaar van Blizzaar.', ':max:', '2015-06-14 22:11:28'),
(179, 'Meisjes zijn ook niet veilig voor mij als ik aan al hun privates kon :wink:', ':bert:', '2015-06-15 13:37:10'),
(180, 'vegetable stark', ':ollie:', '2015-06-15 21:59:17'),
(181, ':kappa:', 'Slackbot', '2015-06-24 13:26:26'),
(182, 'Ik heb echt zo\'n shit banaan he?', ':drollie:', '2015-06-24 22:37:32'),
(183, 'Ik wist nog niet wat ne piemel was op 13 jaar.', 'Bert', '2015-06-25 15:09:19'),
(184, 'It just kept coming', ':reinaert:', '2015-06-25 19:09:20'),
(185, 'Beter een gat in uw venster dan een venster in uw gat.', ':brent:', '2015-06-25 19:51:34'),
(186, 'Ge punten ook u crafting verhogen en tools gebruiken', ':axel:', '2015-06-25 20:31:02'),
(187, '[To :ollie:] I hope you suck my dick.', ':brent:', '2015-06-28 16:16:36'),
(188, 'Ik zit permanent in een anus-bal', ':max:', '2015-07-01 13:45:45'),
(189, 'Spam heeft nut!', ':max:', '2015-07-10 20:31:12'),
(190, 'heb ik een homo&amp;negeronvriendelijke versie van slack ofzo?', 'Dennis', '2015-07-10 20:33:15'),
(191, 'Het leek me al bekend voor. :stuck_out_tongue:', ':max:', '2015-07-11 13:44:52'),
(192, 'kip met kip en kip', ':lucas:', '2015-07-20 09:50:42'),
(193, 'Failing to plan is planning to fail.', ':paul:', '2015-07-30 13:34:05'),
(194, 'I have the next 5 years planned, but donâ€™t ask me what weâ€™re going to do next week\"', ':paul:', '2015-07-30 13:35:13'),
(195, 'interrupts you * I THINK that...', '*', '2015-07-30 13:36:33'),
(196, 'What kind of animal would I be? Students: \"Bull, penguin or turtle.\"', ':paul:', '2015-07-30 13:39:30'),
(197, 'serial pet sitter', ':paul:', '2015-07-31 15:26:50'),
(198, 'Dat kost mss veel geld maar ik ben VR Porn ready', ':brent:', '2015-08-18 09:45:31'),
(199, 'McLawsuit', 'bertp', '2015-08-26 16:50:53'),
(200, 'Sex in a canoe is like american beer, fucking close to water.', 'TwitchChat', '2015-08-26 19:30:44'),
(201, 'Alles is een wallpaper als je hard genoeg je best doet. Zo is alles ook fapmateriaal. Of een dildo...', ':bert:', '2015-08-29 11:59:04'),
(202, 'De fransen komen uw server binnen, beginnen in hun baguettes te praten, en schelden u uit omdat ge het niet verstaat.', ':ollie:', '2015-09-02 18:58:06'),
(203, 'Maar een makkelijker deel was een zonnewijzer maken van kanker', 'Dennis', '2015-09-07 15:05:18'),
(204, 'ik heb liever light themed IDE en ik heb een enorme penis in mijn gat steken', 'citiral', '2015-09-21 12:48:54'),
(205, 'als er geen saucissen zijn, dan neem ik de uwe', ':drollie:', '2015-09-23 15:12:46'),
(206, 'Hahaha', ':axel:', '2015-09-23 15:46:40'),
(207, 'als er geen saucissen zijn, dan neem ik de uwe', ':drollie:', '2015-09-23 15:50:13'),
(208, 'Ja danku, ik heb graag bananen.', ':ollie:', '2015-09-24 12:26:23'),
(209, 'It didn\'t solve the problem, but it is an improvement.', ':reinaert:', '2015-09-26 14:58:08'),
(210, 'ik zat gisteren op xvideos', 'suerrenein', '2015-09-27 21:38:12'),
(211, 'ik heb op YouJizz gezeten', 'renaar', '2015-09-27 21:39:39'),
(212, '*BOOM* exception', ':brent:', '2015-09-28 12:46:15'),
(213, 'Ja, Heil Hitler natuurlijk.', ':reinaert:', '2015-09-28 13:56:06'),
(214, 'waarom is het logo van GNU een geit?', ':brent:', '2015-09-29 10:37:29'),
(215, 'Pryor, U.S. senator\" \"You don\'t have to pass an IQ test to be in the senate.\"', '\"Mark', '2015-10-02 11:51:22'),
(216, 'Immigrants cause cancer. (<https://vine.co/v/exzmjnziqIa>)', 'CheeseOfTruth', '2015-10-03 22:32:55'),
(217, 'OOOOOH NOOOOOOOO, YOU HACKED MY COMPUTER AND SHUT IT OFF, IM GONNA TELL MY DAD AND HE IS LIKE THE BEST SOLDIER EVAR', 'Gert', '2015-10-08 17:19:52'),
(218, 'Sorry for my shit taste.', ':axel:', '2015-10-08 18:17:24'),
(219, 'ceci n\'est pas une neus!', 'hepedoge', '2015-10-11 15:41:22'),
(220, 'die mag wel met mijn ballen spelen', 'Gert', '2015-10-14 12:40:29'),
(221, 'Alsof een :paul: in je oortje pist', ':ollie:', '2015-10-22 15:46:01'),
(222, '', '', '2015-10-22 16:01:59'),
(223, '!tron; quote', ':ollie:', '2015-10-22 16:04:09'),
(224, '!tron; quote en ik ben stiekem een Ã¼ber-homo.', ':axel:', '2015-10-22 16:05:27'),
(225, '!tron: addquote :axel: !tron; quote en ik ben stiekem een Ã¼ber-homo.', ':ollie:', '2015-10-22 16:05:55'),
(226, '!tron: addquote :axel: !tron; quote en ik ben awesome.', 'quoteception', '2015-10-22 16:06:35'),
(227, 'Quote 226 is een grap en bedoelt het omgekeerde. (Dat ik een Ã¼ber-homo ben)', ':axel:', '2015-10-22 16:07:40'),
(228, 'cool cool', 'jackask', '2015-10-22 16:08:40'),
(229, ':mag: Het is kanker', ':drollie:', '2015-10-25 19:13:33'),
(230, 'Er is maar Ã©Ã©n ding lelijker dan een gekleed wijf op uw wallpaper en dat is een gefotoshopped wijf op uw wallpaper.', ':bert:', '2015-10-27 11:55:53'),
(231, 'Wat valt dan onder filosofie', ':brent:', '2015-10-27 21:53:13'),
(232, 'ik hoop dat het zelfmoorden zijn', 'renaar', '2015-10-29 09:03:05'),
(233, 'Jammer genoeg geen fagship', ':luuk:', '2015-10-29 16:28:31'),
(234, '\'s Nachts niet willen slapen en \'s morgens niet willen opstaan.', ':max:', '2015-11-03 21:52:44'),
(235, '!tron: addquote :max: \'s Nachts niet willen slapen en \'s morgens niet willen opstaan.', ':max:', '2015-11-04 13:25:11'),
(236, '<http://www.beste-gaydatingsites-voor-hopeloze-gevallen-zoals-ik-online.be|www.beste-gaydatingsites-voor-hopeloze-gevallen-zoals-ik-online.be>', 'dennis', '2015-11-04 16:44:44'),
(237, 'Look at these happy little clouds and happy little trees, just having a great time.', ':kappaross:', '2015-11-07 12:09:44'),
(238, 'Ik heb een van mijn fans versleten, op low speed wined die heel hard. :iykwim:', ':bert:', '2015-11-09 10:50:54'),
(239, 'I don\'t really case.', ':axel:', '2015-11-09 21:19:15'),
(240, 'Ge kunt ook iemand doodknuppelen met een baby, dan verdenkt iedereen de baby ofzo.', 'dennis', '2015-11-09 21:36:38'),
(241, 'I like to add quotes', 'Grefo', '2015-11-09 21:37:11'),
(242, '!tron: quote 241', ':axel:', '2015-11-09 21:37:58'),
(243, 'ah 3 was wel een axel is gay denk ik', ':drollie:', '2015-11-09 21:44:11'),
(244, 'hitler confirmed alive', ':axel:', '2015-11-09 21:44:42'),
(245, 'Mooie schoenen, neuken?', ':reinaert:', '2015-11-09 21:47:57'),
(246, 'Maar niet zo heet als Dennis die langs een gespierde man staat', ':ollie:', '2015-11-10 10:12:39'),
(247, 'lul in een dode vis proppen en gaan met die banaan', 'Gilles', '2015-11-15 20:13:09'),
(248, 'Facebook zijn vuile gluiperds', ':reinaert:', '2015-12-07 17:15:39'),
(249, 'Luyten dat is een big no no', 'Kris', '2015-12-07 17:16:45'),
(250, 'I\'ve been bouncing on my boys dick for hours to this.', ':olivier:', '2015-12-30 23:02:47'),
(251, '!tron: qoute', 'Gilles', '2015-12-31 13:41:44'),
(252, '_joined #nsfw_ \"Ja maar er zijn ook fotos waar je haar tieten ziet, volledig ziet\" _left #nsfw_', 'Olivier', '2016-01-12 21:33:56'),
(253, 'ge moogt ook al op 12 seks hebben.. alleen hopen dat niemand erachter komt', 'Bert', '2016-01-21 18:45:15'),
(254, 'ik hou van frietjes en fijne tietjes', ':drollie:', '2016-01-26 16:21:09'),
(255, 'ik heb 2747 coconuts om naar uw bakkes te gooien', ':drollie:', '2016-01-26 16:28:31'),
(256, 'Niemand hier wilt je kokosnoten zien, Ollie.', 'Gilles', '2016-01-26 16:51:58'),
(257, 'Alle letters van het Griekse alfabet: alfa, beta, charlie, ...', 'michielvm', '2016-03-17 11:08:03'),
(258, 'Ja, als uw spieren pijn doen, dan is het spierpijn eh', 'Reinaert', '2016-04-29 10:53:07'),
(259, 'How much dedicated wam for a MineCraft server?', 'Kid', '2016-04-30 15:03:12'),
(260, 'Een kutbug is niet gezond.', 'michielvm', '2016-05-13 08:26:44'),
(261, '*100 keer per dag* Wa ne kak.', 'jensv', '2016-05-13 08:28:37'),
(262, '* nadat Axel zijn kaart vergeet* Axelleke, opletten jong!', 'Marie-Jeanne', '2016-05-13 08:56:51'),
(263, 'I forgot the D', 'shadowblink', '2016-05-19 10:02:31'),
(264, '* tegen Jens * Er zit een mug op uw scherm, klop die dood, met wat chance hebt ge uw scherm mee.', 'michielvm', '2016-05-20 06:41:32'),
(265, 'Wat voor een stuk code ben ik?', 'jensv', '2016-05-20 06:46:48'),
(266, 'Al maak je er een neger van, I don\'t give a shit.', 'jensv', '2016-05-20 07:55:37'),
(267, 'Ik ben goed in faken.', ':ollie:', '2016-09-17 20:34:47'),
(268, 'This does not tickle my pickle', 'Brent', '2016-09-23 17:02:06'),
(269, 'Professor Wilmots (*bedoeld Professor Wim Lamotte*)', 'Guy', '2016-10-07 11:28:46'),
(270, 'Once you go pinksteun, you never go back.', 'Brent', '2016-10-17 09:28:03'),
(271, 'mijn broek trilt', '<@U02NRFSAY>', '2016-11-17 08:30:42'),
(272, 'Ik dank dat ik dat ook hebt', ':brent:', '2016-11-22 21:07:09'),
(273, 'Want weet ge jongens, ik heb helemaal geen onderbroek aan', 'drollie', '2016-12-02 20:19:14'),
(274, 'Ik wil nu niets liever dan uw rugzak open doen en er ene in spuiten', 'drollie', '2016-12-02 22:12:37'),
(275, 'Haha, die smiley is zo hhhhnnnnnnggggggg', ':reinaert:', '2016-12-20 21:15:21'),
(276, 'Vormen mutsen de kern van het universum?', ':brent:', '2016-12-20 22:39:59'),
(277, 'Ik heb iets, das meer dan genoeg', ':brent:', '2017-01-05 19:50:01'),
(278, 'Ik heb de opgave niet gelezen. Misschien beter wel gedaan', ':brent:', '2017-01-05 19:51:43'),
(279, 'Gawd damn, Kelsey wil ik wel eens het plafond in boren :open_mouth:', ':bert:', '2017-01-15 18:26:50'),
(280, 'I\'m totally okay with Nestea tho :smile:', ':reinaert:', '2017-01-19 23:45:23'),
(281, '* na leerstof gestudeerd te hebben die niet op het examen bleek te komen * Ik had die tijd dus letterlijk in porno kunnen steken en ik had er meer aan gehad.', ':bert:', '2017-01-23 14:28:55'),
(282, 'Buit de mogelijkheid om aangrenzende tepels samen te voegen niet uit.', 'Prof._Dr._Jan_Van_den_Bussche', '2017-01-31 16:39:43'),
(283, 'Gebruik telkens verse tijdelijke registers om tepels te verbinden.', 'Prof._Dr._Jan_Van_den_Bussche', '2017-01-31 16:40:46'),
(284, 'Ik heb mijn tepels liefst in oorspronkelijke staat dankuwel.', ':brent:', '2017-01-31 18:23:25'),
(285, 'Toevallig heb ik net Reinaert z\'n examen verbeterd en ironisch\ngenoeg hebben vragen 2 en 3 hem wel gered, vraag 1 (dus de\n\"verwachte\" vraag) was zwak beantwoord.', 'JanVDB', '2017-02-06 15:13:45'),
(286, '(over <http://www.uhasselt.be/fotopers/1080.jpg>) fakking handsome dude op die foto, in tegenstelling tot de realiteit.. nohomo', ':bert:', '2017-02-06 15:15:39'),
(287, 'knap of niet, kerel moet uit zijn broekzak blijven', 'Gilles', '2017-02-06 15:16:50'),
(288, '* Wanneer hij gevraagd wordt om een definitie van AI * Ge maakt een paar foutjes, en woeps, weg mensheid.', 'Gert', '2017-02-21 11:37:08'),
(289, 'Das ne complete gaffel.', 'Jan_Van_den_Bussche', '2017-02-21 11:42:24'),
(290, 'I forgot my dongle.', 'Jan_Van_den_Bussche', '2017-02-21 12:12:28'),
(291, 'Niemand weet waar sex eigenlijk goed voor is', 'Jan_Van_den_Bussche', '2017-02-21 14:18:56'),
(292, 'dus hopen dat ik hard gebuisd ben op het examen dan -_-', ':bert:', '2017-02-26 12:33:29'),
(293, 'ieeh', ':bert:', '2017-02-26 12:33:40'),
(294, 'De P is de nieuwe T.', ':pieter:', '2017-03-01 13:42:16'),
(295, 'schpuitverven', ':pieter:', '2017-03-01 13:48:26'),
(296, 'Die van mij is ineens krom geworden', ':olivier:', '2017-03-08 12:35:05'),
(297, '\"Van den Bussche gebruikt die pauze om snel ff te gaan fappen\" *imiteert zeehond*', 'perBert', '2017-03-14 14:09:18'),
(298, 'Nooit u zakske te lang laten trekken', ':reinaert:', '2017-03-15 18:42:24'),
(299, 'Ik moet Olli two fiddy', ':brent:', '2017-03-22 16:46:09'),
(300, 'amazing', 'reinaert', '2017-03-22 16:46:51'),
(301, 'Jens, ge zijt een kieken.', 'Michiel', '2017-03-29 12:39:59'),
(302, 'Om een goed project te leiden, moeten er koekjes zijn.', ':pieter:', '2017-03-29 13:53:01'),
(303, 'Ik kan niet, ik zit met een peer in mijn hand. ...Volgens mij is die trouwens niet meer goed.', 'Michiel', '2017-04-03 13:36:51'),
(304, 'Ik snap nie de vraag ook nie dinge.', 'Jens', '2017-04-05 11:50:35'),
(305, 'Als het lukt met een dildo, dan moet ze het maar doen.', 'Jens', '2017-04-26 09:22:59'),
(306, 'Ik gebruik geen syntax.', 'Jens', '2017-04-26 09:42:05'),
(307, 'Misschien moeten we ook de planning plannen.', 'Lucas', '2017-04-26 11:54:20'),
(308, 'Goeie zwemmer gij!', 'Alexander', '2017-04-26 12:22:02'),
(309, '* terwijl hij druk aan het werk hoort te zijn * Moeten jullie de prijs van de duurste trampoline op Google eens weten?', 'Pieter', '2017-04-26 12:28:53'),
(310, 'Die mag mijnen auto ook eens komen kuisen.', 'Michiel', '2017-04-26 12:52:03'),
(311, 'IK HEB SPACE AIDS', ':olivier:', '2017-05-14 10:53:11'),
(312, 'Wa ne kak.', 'Jens', '2017-05-17 10:53:24'),
(313, '* wanneer hij pollen voorbij ziet zweven * Zijn dat geen beesten?', 'Alexander', '2017-05-17 11:11:39'),
(314, 'Das ne kakbox jom.', 'Jens', '2017-05-17 12:24:12'),
(315, '* wanneer hij <https://youtu.be/dP9Wp6QVbsk> hoort * Is dat zo Duitslandshit?', 'Alexander', '2017-05-17 12:31:14'),
(316, 'Waarom zit Michiel er twee keer in?', 'Jens', '2017-05-23 10:49:37'),
(317, 'We gaan eens een lijntje zetten :iykwim:', 'Michiel', '2017-05-23 11:59:16'),
(318, 'Het is lang geleden dat ik het niet helemaal in mijn mond krijg.', ':bert:', '2017-05-24 09:09:57'),
(319, 'Ik zit in Neven zijn friendzone.', 'Michiel', '2017-05-24 11:49:47'),
(320, 'Ze zijn allemaal zo lelijk, echt sletten, ik kan er niks anders over zeggen.', 'Michiel', '2017-05-24 13:33:23'),
(321, 'Normaal ga ik naar de vrouwen-wc.', ':bert:', '2017-05-30 10:21:10'),
(322, '* tegen Pieter * Waarom ben jij foto\'s van kleine meisjes aan het opzoeken in hun ondergoed?', 'Michiel', '2017-05-30 12:40:55'),
(323, 'welverdiende ontspanning noem ik dat :slightly_smiling_face:', ':reinaert:', '2017-06-08 14:47:06'),
(324, 'cosplay *whore* it better', ':bert:', '2017-06-08 14:49:48'),
(325, '* Als antwoord op de vraag \"Bent ge al aan die taak begonnen?\" * Ik heb er al over gepanikeerd.', ':brent:', '2017-06-12 09:25:53'),
(326, 'I\'ll bring my huge ass mushrooms', 'Reinaert', '2017-07-23 15:41:21'),
(327, 'Die the enemies in the face with your gun', ':drollie:', '2017-07-31 13:54:50'),
(328, 'An SSL error has occurred and a secure connection to the server cannot be made.', 'Shaekspeare', '2017-08-03 19:24:49'),
(329, 'Im gonna fuck myself with all those fucks', ':drollie:', '2017-08-15 16:58:07'),
(330, 'Alle script talen sucken', ':brent:', '2017-08-15 17:40:40'),
(331, '20% abrikoos', 'citiral', '2017-08-15 17:52:44'),
(332, 'Ik weet u penis masturberen!', ':drollie:', '2017-09-08 20:57:40'),
(333, 'Tis niet omdat er vanvoor een gaatje in zit, dat het een switch is.', 'Peter_Quax', '2017-09-21 09:53:40'),
(334, 'WOLLAH', 'shadowblink', '2017-09-24 15:16:24'),
(335, 'YEEEEEEAAAAAAAH BOOOOOI', 'shadowblink', '2017-09-24 16:15:50'),
(336, 'Ik begin echt honger te krijgen maar ik heb geen honger.', ':bert:', '2017-10-04 08:29:07'),
(337, 'Das kaka en veel erwten.', ':bert:', '2017-10-04 10:11:28'),
(338, 'Anaal is een ander woord voor precies zeker hÃ©? ... Anaal wordt heel veel gebruikt hÃ©... * kijkt opzij * Ja, nu moet gij dat weer aan die quotes gaan toevoegen hÃ©...', ':bert:', '2017-10-04 14:12:24'),
(339, 'FUCKY BITCHift...', 'Wout', '2017-10-19 14:25:03'),
(340, 'niemand transformeert zijn pompoen nog in 2017', ':olivier:', '2017-11-01 10:51:48'),
(341, '\"Ge wordt daardoor niet lichter, alleen maar minder zwaar.\"', 'Reinaert', '2017-11-13 11:44:13'),
(342, '\"Ofwel gaat ge naarvoor, en zit de piemel in uw mond, ofwel gaat ge naar achter, en zit hij in uw kont.\"', 'Kevin', '2017-11-13 11:45:12'),
(343, '* Na 20 minuten een gesprek aan te horen tussen Kevin, Olivier en Reinaert * Ik snap echt niet hoe dit is kunnen gebeuren.', 'Niels', '2017-11-13 12:29:03'),
(344, 'Goeie kontspieren kweken, hÃ¨.', ':max:', '2017-11-28 14:47:03'),
(345, 'ik ga het zelf proberen te vogelen', ':bert:', '2017-12-05 19:28:26'),
(346, 'Die slight smile vind ik er altijd zo depri uitzien. \"Mijn hond is vandaag gestorven maar ik heb 1 euro op straat gevonden :slightly_smiling_face:\"', ':brent:', '2017-12-10 15:03:27'),
(347, 'We kunnen mensen niet doorsnijden natuurlijk.', 'Frank_Neven', '2017-12-20 08:14:13'),
(348, 'een miljoen dollar is veel geld dus zeven is geen tien', 'Frank_Neven', '2017-12-20 09:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `command_poll`
--
ALTER TABLE `command_poll`
  ADD UNIQUE KEY `id` (`poll_id`);

--
-- Indexes for table `command_poll_option_data`
--
ALTER TABLE `command_poll_option_data`
  ADD UNIQUE KEY `option_id` (`option_id`);

--
-- Indexes for table `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
  ADD PRIMARY KEY (`option_id`,`poll_id`),
  ADD UNIQUE KEY `option_id_2` (`option_id`,`poll_id`,`option_index`),
  ADD KEY `poll_info_link` (`poll_id`);

--
-- Indexes for table `command_poll_votes`
--
ALTER TABLE `command_poll_votes`
  ADD PRIMARY KEY (`option_id`,`user_id`);

--
-- Indexes for table `definitions`
--
ALTER TABLE `definitions`
  ADD PRIMARY KEY (`defID`),
  ADD UNIQUE KEY `subject` (`subject`);

--
-- Indexes for table `fucks`
--
ALTER TABLE `fucks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `giftime`
--
ALTER TABLE `giftime`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `giftokens`
--
ALTER TABLE `giftokens`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `insults`
--
ALTER TABLE `insults`
  ADD PRIMARY KEY (`insultID`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packageid` (`packageid`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `command_poll`
--
ALTER TABLE `command_poll`
  MODIFY `poll_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `definitions`
--
ALTER TABLE `definitions`
  MODIFY `defID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `insults`
--
ALTER TABLE `insults`
  MODIFY `insultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `command_poll_option_data`
--
ALTER TABLE `command_poll_option_data`
  ADD CONSTRAINT `option_information_link` FOREIGN KEY (`option_id`) REFERENCES `command_poll_option_id` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `command_poll_option_id`
--
ALTER TABLE `command_poll_option_id`
  ADD CONSTRAINT `poll_info_link` FOREIGN KEY (`poll_id`) REFERENCES `command_poll` (`poll_id`) ON UPDATE CASCADE;

--
-- Constraints for table `command_poll_votes`
--
ALTER TABLE `command_poll_votes`
  ADD CONSTRAINT `poll_information_link` FOREIGN KEY (`option_id`) REFERENCES `command_poll_option_id` (`option_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
