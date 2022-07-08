-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2017 at 05:27 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `efull`
--

-- --------------------------------------------------------

--
-- Table structure for table `jos_blocks`
--

CREATE TABLE `jos_blocks` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `jos_blogs`
--

CREATE TABLE `jos_blogs` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `who_id` int(11) DEFAULT NULL,
  `subject` text COLLATE utf8_bin,
  `text` text COLLATE utf8_bin,
  `date` datetime DEFAULT NULL,
  `vote_count` int(11) NOT NULL DEFAULT '0',
  `vote_sum` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `options` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_blogs`
--

INSERT INTO `jos_blogs` (`id`, `type`, `who_id`, `subject`, `text`, `date`, `vote_count`, `vote_sum`, `comments`, `options`) VALUES
(1, 1, 11000, 'Kako sam unistavan od...', '[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[b][u]... strane sebe idiota![/u][/b]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u][u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u][u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u][u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]\r\n[u]... strane sebe idiota![/u]', '2017-04-04 19:22:37', 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jos_changes`
--

CREATE TABLE `jos_changes` (
  `who_id` int(11) NOT NULL,
  `what` tinyint(4) NOT NULL,
  `time` int(11) NOT NULL,
  `tag` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jos_changes`
--

INSERT INTO `jos_changes` (`who_id`, `what`, `time`, `tag`) VALUES
(72, 3, 1489951627, '54079'),
(72, 4, 1489952383, 'I am the king of the sub kong'),
(11000, 2, 1491325512, NULL),
(10999, 1, 1491325645, NULL),
(11000, 4, 1491325684, ' opisan od <a href="/efull/profil/10999">Son_of_a_SIN</a> kao : Super je i tako to'),
(10999, 4, 1491325742, 'Cita spisak'),
(10999, 2, 1491325749, NULL),
(11000, 1, 1491326155, NULL),
(11000, 3, 1491326187, '54080');

-- --------------------------------------------------------

--
-- Table structure for table `jos_comments`
--

CREATE TABLE `jos_comments` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `who_id` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_comments`
--

INSERT INTO `jos_comments` (`id`, `type`, `type_id`, `comment`, `who_id`, `date`) VALUES
(1, 2, 389, 'Jedva cekam', 11000, 1491326260),
(2, 1, 1, 'Ahah', 11000, 1491326568);

-- --------------------------------------------------------

--
-- Table structure for table `jos_druk`
--

CREATE TABLE `jos_druk` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_druk`
--

INSERT INTO `jos_druk` (`id`, `name`, `score`) VALUES
(10999, 'Son_of_a_SIN', 109);

-- --------------------------------------------------------

--
-- Table structure for table `jos_events`
--

CREATE TABLE `jos_events` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  `text` varchar(1500) COLLATE utf8_bin DEFAULT '',
  `location_name` varchar(40) COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `time` smallint(6) NOT NULL DEFAULT '32767',
  `forum_link` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `feautured` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_events`
--

INSERT INTO `jos_events` (`id`, `name`, `text`, `location_name`, `date`, `time`, `forum_link`, `user_id`, `image`, `feautured`) VALUES
(389, 'Rodjendansko slavlje', '[i]Dolaze Keba i Kebra[/i]:w00t:', 'Aut', '2017-04-04', 0, '', 11000, '149132624311000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jos_events_attend`
--

CREATE TABLE `jos_events_attend` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_events_attend`
--

INSERT INTO `jos_events_attend` (`event_id`, `user_id`) VALUES
(389, 11000);

-- --------------------------------------------------------

--
-- Table structure for table `jos_fb_users`
--

CREATE TABLE `jos_fb_users` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `uhits` int(11) DEFAULT '0',
  `karma_time` int(11) DEFAULT '0',
  `friends` int(11) DEFAULT '0',
  `blogs` int(11) DEFAULT '0',
  `mood` int(11) DEFAULT '0',
  `love` int(11) NOT NULL DEFAULT '0',
  `music` int(11) NOT NULL DEFAULT '0',
  `lover_id` int(11) DEFAULT NULL,
  `signature` tinytext COLLATE utf8_bin,
  `posts` int(11) DEFAULT '0',
  `karma` int(11) DEFAULT '0',
  `personalText` text COLLATE utf8_bin,
  `location` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `YIM` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `MSN` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `SKYPE` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `GTALK` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `whatsup` varchar(240) COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_fb_users`
--

INSERT INTO `jos_fb_users` (`userid`, `uhits`, `karma_time`, `friends`, `blogs`, `mood`, `love`, `music`, `lover_id`, `signature`, `posts`, `karma`, `personalText`, `location`, `YIM`, `MSN`, `SKYPE`, `GTALK`, `whatsup`) VALUES
(72, 0, 0, 0, 0, 4, 1, 2722, 0, 'I wish I made elitesecurity... But instead, I made extrafull... Code/database design is ok, but people and lack of money are...', 0, 0, '[center]\r\n[size=200%]Ne menjam username(korisnicko ime) vise. Prelazimo na drugaciji nacin raspoznavanja(pogledaj naslovnu). Takodje nema brisanja profila osim u ekstremnim situacijama. Smaraci cibe![/size]\r\n\r\n[i][size=120%]"Bigger than [color=red]Jesus[/color], smaller than [color=maroon]pals"[/color][/size][/i] [size=75%]this sentence is copyrighted© by crewo[/size]\r\n[/center]', 'somewhere near nowhere', '', 'LONG_FAT_COCK_IN_YOUR_MOUTH', 'BUREK_P(T)ICA', '', 'I am the king of the sub kong'),
(11000, 1, 0, 1, 1, 20, 2, 0, 10999, '', 1, 0, '', 'Titov Vrbas', '', '', '', '', NULL),
(10999, 1, 0, 1, 0, 19, 0, 532, 11000, 'This is sparta!', 1, 0, '[i]Hahahaa[/i]', '', '', '', '', '', 'Cita spisak');

-- --------------------------------------------------------

--
-- Table structure for table `jos_banned_ip`
--

CREATE TABLE `jos_banned_ip` (
  `user_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM;

--
-- Table structure for table `jos_user_token`
--

CREATE TABLE `jos_user_token` (
  `user_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0, -- 0 = remember me
  `code` varchar(40) NOT NULL,
  `expired` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY(user_id, type)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `jos_forum_cats`
--

CREATE TABLE `jos_forum_cats` (
  `id` int(11) NOT NULL,
  `group` int(11) NOT NULL DEFAULT '0',
  `name` tinytext COLLATE utf8_bin NOT NULL,
  `icon` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `moderators` text COLLATE utf8_bin,
  `description` text COLLATE utf8_bin,
  `numTopics` int(11) NOT NULL DEFAULT '0',
  `numPosts` int(11) NOT NULL DEFAULT '0',
  `last_topic_id` int(11) NOT NULL DEFAULT '0',
  `creator` int(11) DEFAULT '0',
  `time_created` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_forum_cats`
--

INSERT INTO `jos_forum_cats` (`id`, `group`, `name`, `icon`, `moderators`, `description`, `numTopics`, `numPosts`, `last_topic_id`, `creator`, `time_created`) VALUES
(1, 0, 'Grad, dešavanja, izlasci & provod', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,424-Milivoj87,,,,,169-SoN_Of_SiN', 'Najbolja mesta za izlaske, noćni(a bogami i dnevni) život. Najava žurki/dešavanja, utisci sa prošlih dešavanja...', 81, 1592, 91, 0, 0),
(2, 0, 'Kultura, literatura, obrazovanje, umetnost', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,169-SoN_Of_SiN,,,,,573-Pantera,,,,,89-BOSS', '(NE)Kultura mladih,  savremena-pop kultura i njene nus-pojave...Materijal za čitanje, preporuke za obavezno štivo i šta svakako zaobići, (ne)obrazovanje, sve vrste umetnosti...', 26, 970, 68, 0, 1),
(3, 0, 'Muzika i film ', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,169-SoN_Of_SiN', 'Koji muzički pravac i grupe su najbolji, pljuvačina po izvođačima koje ne podnosite, music advocacy; Filmovi koje ste gledali...', 115, 5892, 2656, 0, 2),
(4, 0, 'Sport & automobili', NULL, '67-YUBA,,,,,424-Milivoj87,,,,,72-crewce,,,,,68-Bojan', 'Sport & Automobili - Fudbal, košarka, odbojka, tenis, kriket + par još neizmišljenih sportova :) Automoto sport i automobili uopšte', 73, 1948, 2281, 0, 3),
(5, 0, 'Zabava & fun stuff', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,166-Danijel,,,,,169-SoN_Of_SiN', 'Kvizovi i igre raznih vrsta(ovde mećite kaladont i slično). Linkovi, smešni video, zanimljivosti, strange - but true...', 113, 8955, 312, 0, 5),
(6, 0, 'Ljubav & sex', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,141-*IvaniCa`92*,,,,,244-*Lenaaa_xD', 'Ljubav, veze, problemi, saveti, sex...', 106, 4138, 288, 0, 6),
(7, 0, 'Brbljaonica', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce,,,,,244-*Lenaaa_xD,,,,,573-Pantera,,,,,701-IvanaRULS!', 'Sve ono što ne upada u prethodno navedene kategorije...+ Najbolje ribe/tipovi, diskutovanje o lokalnim facama, herojima, anti-herojima, kazanovama, femmes fatales i pijandurama...', 169, 7697, 2496, 0, 9),
(8, 0, 'Informatika, video igre & aplikativni software', NULL, '67-YUBA,,,,,72-crewce,,,,,68-Bojan,,,,,1299-extraempty', 'Sve u vezi računara i IT industrije, novi programi, igre, problemi/kvarovi pri korišćenju računara...', 25, 677, 2117, 0, 4),
(9, 0, 'Extrafull', NULL, '67-YUBA,,,,,68-Bojan,,,,,72-crewce', 'Sve vezano za extrafull.com, prijava bugova, pomoć, mišljenja & kritike...', 12, 374, 2, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `jos_forum_posts`
--

CREATE TABLE `jos_forum_posts` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `who_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin DEFAULT '',
  `subject` varchar(120) COLLATE utf8_bin DEFAULT '',
  `message` text COLLATE utf8_bin,
  `time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_forum_posts`
--

INSERT INTO `jos_forum_posts` (`id`, `tid`, `who_id`, `username`, `subject`, `message`, `time`) VALUES
(1, 1, 11000, 'FuckLoveGhetoQueen', 'Hej!', '[b]Pozdrav![/b] :evillaugh:', 1491325564),
(2, 1, 10999, 'Son_of_a_SIN', 'RE:Hej!', '[quote=FuckLoveGhetoQueen][b]Pozdrav![/b] :evillaugh:[/quote]\r\nLako je tako :)', 1491325711);

-- --------------------------------------------------------

--
-- Table structure for table `jos_forum_topics`
--

CREATE TABLE `jos_forum_topics` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `last_id` int(11) NOT NULL,
  `last_userid` int(11) NOT NULL,
  `last_username` varchar(50) COLLATE utf8_bin NOT NULL,
  `sticky` tinyint(4) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  `subject` varchar(200) COLLATE utf8_bin NOT NULL,
  `view` int(11) DEFAULT '0',
  `replies` int(11) DEFAULT '0',
  `locked` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_forum_topics`
--

INSERT INTO `jos_forum_topics` (`id`, `cid`, `last_id`, `last_userid`, `last_username`, `sticky`, `time`, `subject`, `view`, `replies`, `locked`) VALUES
(1, 1, 2, 10999, 'Son_of_a_SIN', 0, 1491325711, 'Hej!', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jos_jim`
--

CREATE TABLE `jos_jim` (
  `id` int(10) UNSIGNED NOT NULL,
  `who_id` int(11) DEFAULT NULL,
  `from_id` int(11) DEFAULT NULL,
  `outbox` smallint(1) NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `readstate` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Poruka',
  `message` text COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_jim`
--

INSERT INTO `jos_jim` (`id`, `who_id`, `from_id`, `outbox`, `date`, `readstate`, `subject`, `message`) VALUES
(390025, 10999, 11000, 1, '2017-04-04 18:47:43', 1, 'Cao', 'Kako si?');

-- --------------------------------------------------------

--
-- Table structure for table `jos_lovers`
--

CREATE TABLE `jos_lovers` (
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `vote_count` int(11) NOT NULL DEFAULT '0',
  `vote_sum` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_lovers`
--

INSERT INTO `jos_lovers` (`id1`, `id2`, `time`, `vote_count`, `vote_sum`) VALUES
(10999, 11000, 1491326117, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_comments`
--

CREATE TABLE `jos_members_comments` (
  `id` int(11) NOT NULL,
  `who_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `from_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_members_comments`
--

INSERT INTO `jos_members_comments` (`id`, `who_id`, `comment`, `date`, `from_id`) VALUES
(2046020, 11000, 'Kako si? dobro sam!', '2017-04-04 19:08:10', 10999);

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_descs`
--

CREATE TABLE `jos_members_descs` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `txt` text COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_members_descs`
--

INSERT INTO `jos_members_descs` (`who_id`, `from_id`, `txt`) VALUES
(11000, 10999, 'Super je i tako to');

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_friends`
--

CREATE TABLE `jos_members_friends` (
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '65535'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_members_friends`
--

INSERT INTO `jos_members_friends` (`id1`, `id2`, `status`, `position`) VALUES
(10999, 11000, 1, 65535),
(11000, 10999, 1, 65535);

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_karma`
--

CREATE TABLE `jos_members_karma` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `vote` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_locations`
--

CREATE TABLE `jos_members_locations` (
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_members_locations`
--

INSERT INTO `jos_members_locations` (`location_id`, `user_id`) VALUES
(10, 10999);

-- --------------------------------------------------------

--
-- Table structure for table `jos_members_visit`
--

CREATE TABLE `jos_members_visit` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_members_visit`
--

INSERT INTO `jos_members_visit` (`who_id`, `from_id`, `time`) VALUES
(10999, 11000, 1491324448),
(11000, 10999, 1491325718);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_categories`
--

CREATE TABLE `jos_photo_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_bin NOT NULL,
  `p_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_categories`
--

INSERT INTO `jos_photo_categories` (`id`, `name`, `p_id`) VALUES
(1, 'Noćni život', 0),
(2, 'Dnevni život', 0),
(3, 'Venčanja', 0),
(4, 'Maturanti', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_comments`
--

CREATE TABLE `jos_photo_comments` (
  `id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_comments`
--

INSERT INTO `jos_photo_comments` (`id`, `image_id`, `user_id`, `comment`, `datetime`, `published`) VALUES
(91599, 54079, 72, 'Hah, great picture man', '2017-03-19 20:36:46', 72),
(91600, 54081, 72, 'hahaha', '2017-04-04 19:26:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_events`
--

CREATE TABLE `jos_photo_events` (
  `id` int(11) NOT NULL,
  `c_id` int(11) DEFAULT NULL,
  `l_id` int(11) NOT NULL,
  `a_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `file_name` varchar(128) COLLATE utf8_bin DEFAULT '',
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `image_count` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `options` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_events`
--

INSERT INTO `jos_photo_events` (`id`, `c_id`, `l_id`, `a_id`, `date`, `file_name`, `name`, `description`, `image_count`, `published`, `options`) VALUES
(5692, 0, 0, 72, '2017-03-19 20:27:07', '1489951627p1.png', 'Test', 'Lepa galerija', 1, 2, 2),
(5693, 0, 0, 11000, '2017-04-04 19:16:27', '1491326187p1.jpg', 'Sistematski', 'Pregled', 1, 2, 2),
(5694, 2, 16, 72, '2007-11-22 00:00:00', '1491326752p2.jpg', 'Misfits mania', 'Ledilo', 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_favourites`
--

CREATE TABLE `jos_photo_favourites` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_favourites`
--

INSERT INTO `jos_photo_favourites` (`image_id`, `user_id`) VALUES
(54079, 72);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_images`
--

CREATE TABLE `jos_photo_images` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `file_name` varchar(128) COLLATE utf8_bin NOT NULL,
  `name` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '',
  `number_of_views` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `private` int(11) NOT NULL DEFAULT '0',
  `voteSum` int(11) NOT NULL DEFAULT '0',
  `voteCnt` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_images`
--

INSERT INTO `jos_photo_images` (`id`, `event_id`, `file_name`, `name`, `number_of_views`, `comments`, `private`, `voteSum`, `voteCnt`, `time`) VALUES
(54079, 5692, '1489951627p1.png', 'Diamond texture', 4, 1, 72, 5, 1, 1489951627),
(54080, 5693, '1491326187p1.jpg', 'Moja macka', 1, 0, 11000, 0, 0, 1491326187),
(54081, 5694, '1491326744p1.jpg', 'dar', 1, 1, 0, 0, 0, 1491326744),
(54082, 5694, '1491326752p2.jpg', '', 2, 0, 0, 0, 0, 1491326752);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_locations`
--

CREATE TABLE `jos_photo_locations` (
  `id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_bin NOT NULL,
  `address` varchar(40) COLLATE utf8_bin NOT NULL,
  `hasPictures` tinyint(1) DEFAULT '0',
  `user_id` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_locations`
--

INSERT INTO `jos_photo_locations` (`id`, `name`, `address`, `hasPictures`, `user_id`) VALUES
(1, 'Galerija', 'Sombor', 1, 0),
(2, 'In', 'Sombor', 1, 0),
(3, 'Kundalini', 'Sombor', 1, 0),
(4, 'Polet', 'Sombor', 1, 0),
(5, 'Star', 'Sombor', 1, 0),
(6, 'Stari Hrast', 'Sombor', 1, 0),
(7, 'Žad', 'Sombor', 1, 0),
(9, 'Sunčani Sat', 'Sombor', 1, 0),
(10, 'Aut', 'Sombor', 1, 0),
(11, 'Klub', 'Sombor', 1, 0),
(12, 'Kafane...', 'Sombor', 1, 0),
(13, 'Plus', 'Sombor', 1, 0),
(14, 'Grad...', 'Sombor', 1, 0),
(15, 'Rebel', 'Sombor', 1, 0),
(16, 'Kazablanka', 'Sombor', 1, 0),
(17, 'Eden', 'Sombor', 1, 0),
(18, 'Des Arts', 'Sombor', 1, 0),
(19, 'Dolar', 'Sombor', 1, 0),
(20, 'Mi Da Mi', 'Sombor', 1, 0),
(21, 'Bistro Palma ', 'Sombor', 1, 0),
(22, 'Eden-Stapar', 'Stapar', 1, 0),
(23, 'Ž&SS cafe Stapar', 'Stapar', 1, 0),
(24, 'Cafebar Pajcin-Stapar', 'Stapar', 1, 0),
(25, 'Leone - Sivac', 'Sivac', 1, 0),
(26, 'Modena - Sivac', 'Sivac', 1, 0),
(27, 'Fokus - Sivac', 'Sivac', 1, 0),
(28, 'Blef - Apatin', 'Apatin', 1, 0),
(29, 'CHE - Apatin', 'Apatin', 1, 0),
(30, 'Oktagon', 'Apatin', 1, 0),
(34, 'Play off', 'Sivac', 1, 0),
(31, 'Tropicana', 'Kljajićevo', 1, 0),
(32, 'Tref', 'Apatin', 1, 0),
(35, 'Cafe bar Ippon', 'Mite Popovica 8', 0, 0),
(36, 'Club Azuli', 'Sombor', 1, 0),
(37, 'Mach', 'Sivac', 1, 0),
(39, 'Zebra', 'Rade Koncara 16 Sombor', 0, 0),
(40, 'Caffe Bar Hedonist (bivši Che)', 'Ulica Mirna br. 6', 0, 0),
(41, 'Vinski podrum', 'Sombor', 1, 0),
(42, 'De Sol', 'Sombor', 1, 0),
(43, 'club Di Lago', 'Crvenka', 1, 0),
(44, 'Uno fratello', 'Stanišic', 1, 0),
(45, 'Zeleni Otok', 'Batina / Croatia', 1, 0),
(47, 'Basta Doma Omladine', 'Sombor', 0, 0),
(87, 'Backa Topola', 'Backa Topola', 0, 0),
(49, 'Kabare', '', 0, 0),
(50, 'Gakovo', '', 0, 0),
(51, 'AKVARIJUM', '', 0, 0),
(52, 'Svakodnevnica...', '', 1, 0),
(54, 'PLAVA RUZA', 'Apatin, Dunavska obala bb', 0, 0),
(90, 'caffe NICKY', 'CONOPLJA', 0, 0),
(88, 'Tvrđava', 'Gakovo', 0, 0),
(57, 'Stanisic', '', 0, 0),
(58, 'Kljajicevo', '', 0, 0),
(59, 'Stapar', 'Stapar', 0, 0),
(89, 'Diskoteka Tvrdjava', 'Gakovo', 0, 0),
(62, 'Hotel InterNacion (ex Sloboda)', '', 0, 0),
(64, 'Svetozar Miletić', '', 0, 0),
(84, 'Novi Sad', 'Novi Sad', 1, 0),
(66, 'RASTINA', 'RASTINA', 0, 0),
(71, 'Club Loreto', 'SOMBOR', 0, 0),
(97, 'Dalmatinski podrum', 'Zmaj Jovina', 0, 0),
(96, 'caffe bar "PADRINO"', 'SONJE MARINKOVIC 31', 0, 0),
(92, 'Bermudski Trougao - Apatin', 'Kruzni Nasip - Apatin', 0, 0),
(85, 'Pitzerija-Gakovo', '', 0, 0),
(95, '"Narodni" Bioskop Sombor', '', 0, 0),
(94, 'Viver - Backa Palanka', '', 0, 0),
(82, 'Conoplja', '', 0, 0),
(101, 'DISKOTEKA GODIMENT', 'ODZACI', 0, 0),
(118, 'Djele', 'Gakovo', 0, 0),
(119, 'Skaut', 'XII vojvođanske udarne brigade', 0, 0),
(107, 'caffe bar "XXL"', '', 0, 0),
(108, 'Damit', 'Apatin', 0, 0),
(121, '"Bjelivuk":)', '', 0, 0),
(126, 'Kikinda Pub - Kikinda', 'Kikinda...', 0, 0),
(131, 'Piccolina', 'Avrama Mrazovica', 0, 0),
(267, 'Caffe bar La Minut', 'Sombor', 0, 0),
(144, 'cafe bar "MISIJA"', 'Sombor', 0, 0),
(145, 'Kiss', 'Apatin', 0, 0),
(152, 'Alexandro', '1 pasaz', 0, 0),
(181, 'Majestic', 'Stanisic', 0, 0),
(192, 'diskoteka \'\'M\'\' Odzaci', 'Odzaci', 0, 0),
(194, 'Kleopatra', 'Odzaci', 0, 0),
(195, 'pizzeria \'\'Leonardo\'\'', 'Odzaci', 0, 0),
(266, 'Borsalino', 'Sombor', 1, 0),
(200, 'Caffe bar DON', 'Srp.Miletic', 0, 0),
(202, 'Kiss-Ap', 'Apatin', 0, 0),
(204, 'Chelzi', '', 0, 0),
(242, 'SONTA', '', 0, 0),
(268, 'Rock caffe BooM', 'Odzaci', 0, 6979),
(271, 'Paladijum', 'Citaonicka ......', 0, 8458),
(272, 'caffe club TIME - Stapar', '', 0, 610),
(273, 'Tvrdjawa-Kula', 'Kula', 0, 3588),
(275, 'Q bar - Subotica', 'Subotica', 0, 2497),
(276, 'Dzentlmen\'s pub - Subotica', '', 0, 2497),
(277, 'Kafana Sremica', '', 0, 5904),
(278, '"Crno-Beli"', 'Petra Drapsina', 0, 91),
(301, 'Stanisic', 'Stanisic', 0, 5214),
(280, '"alexandro"', 'Kralja Petra I Sombor', 0, 8956),
(300, 'Disco bar "Opera"', 'Sombor', 0, 8918),
(282, 'boss', 'Su', 0, 8049),
(283, 'La Fiesta', '', 0, 1581),
(285, 'Beograd...', 'Beograd...', 0, 8496),
(299, 'caffe-bar De Luxe', 'Branka Copica 8', 0, 4138),
(289, 'B.MONOSTOR....', '', 0, 4221),
(290, 'Skiper-Odzaci', '', 0, 2135),
(292, 'Bodjosev Lad', 'GOGE', 0, 8101),
(298, 'Kaffe bar "Nella"', 'Stanisic', 0, 8918),
(294, '"Mirna Dolina"', '', 0, 2739),
(355, 'Skazi', '', 0, 5111),
(302, 'Pizzeria Mama Mia', '', 0, 2559),
(303, 'Palma', 'SOMBOR', 0, 2021),
(304, 'INTER', 'Bezdan', 0, 9032),
(306, 'Bumerang', 'Backi Monostor', 0, 3787),
(307, 'pizzeria "M"', 'Backi Monostor', 0, 3787),
(370, 'N&D caffe bar', 'Flosbergerova 6', 0, 896),
(311, 'HEXAGON', '', 0, 3391),
(315, 'B.MONOSTOR....', 'Backi Monostor', 0, 4221),
(363, 'ART-Apatin', 'Apatin', 0, 7487),
(361, '***KLADIONICE***', '', 0, 4099),
(360, 'Target', '', 0, 1471),
(359, 'BRANKOVINA', 'brankova kuca!!!', 0, 6777),
(334, 'Novi Sad-Sistem', '', 0, 9067),
(374, 'Stir*', 'So', 0, 1503),
(372, 'PICERIJA"CAMINI"-ODZACI', 'SOMBORSKA 14', 0, 8827),
(383, 'Brankovina', 'Apatin', 0, 9225),
(392, 'Caffe bar QUATTRO', '', 0, 6758),
(407, 'Palacinkarnica Dva Brata', '', 0, 2838),
(411, 'tajm', 'Stapar', 0, 9265),
(428, 'Opera', '', 0, 260),
(429, '*ElBrAc0*', 'Staparski put', 0, 4134),
(431, 'CLUB MONZA', 'SIVAC', 0, 89),
(432, 'MONACO', 'APATIN', 0, 8653),
(438, 'Lionn Conoplja', 'Marka Oreskovica 49.', 0, 7724),
(441, 'The GodFather', '', 0, 7224),
(442, 'Bar Baltazar Novi Sad', '', 0, 4630),
(446, 'Quatro', '', 0, 101),
(447, 'Novi Dolar', '', 0, 1581),
(449, 'Mirna noc', 'Batinska', 0, 9391),
(456, 'Teatro, Mistique', 'Beograd', 0, 9377),
(459, 'club Tango', 'Apatin', 0, 3791),
(467, 'Mala Piccolina', 'Laze Kostica', 0, 1625),
(472, 'Dalmatinski podrum', 'zmaj jovina', 0, 457);

-- --------------------------------------------------------

--
-- Table structure for table `jos_photo_votes`
--

CREATE TABLE `jos_photo_votes` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL DEFAULT '5'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_photo_votes`
--

INSERT INTO `jos_photo_votes` (`image_id`, `user_id`, `grade`) VALUES
(54079, 72, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jos_request`
--

CREATE TABLE `jos_request` (
  `id` int(11) NOT NULL,
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `desc` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `jos_sessions`
--

CREATE TABLE `jos_sessions` (
  `id` varchar(200) COLLATE utf8_bin NOT NULL,
  `time` int(10) UNSIGNED DEFAULT NULL,
  `first_access` int(10) UNSIGNED DEFAULT NULL,
  `data` text COLLATE utf8_bin,
  `userid` int(11) DEFAULT '0',
  `username` varchar(40) COLLATE utf8_bin DEFAULT '',
  `params` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jos_sessions`
--

INSERT INTO `jos_sessions` (`id`, `time`, `first_access`, `data`, `userid`, `username`, `params`) VALUES
('ibcceu58t35uf016lsr7b0g6o7', 1491326781, 1491326706, 'O:4:"User":9:{s:2:"id";s:2:"72";s:8:"username";s:6:"crewce";s:3:"gid";s:2:"21";s:4:"name";s:11:"Igor Crevar";s:6:"params";s:0:"";s:5:"block";s:1:"0";s:13:"lastvisitDate";s:19:"2017-04-04 19:03:08";s:2:"ip";s:3:"::1";s:8:"redirect";s:3:"yes";}', 72, 'crewce', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jos_users`
--

CREATE TABLE `jos_users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `gid` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `avatar` varchar(50) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `birthdate` date DEFAULT NULL,
  `lover_id` int(11) NOT NULL DEFAULT '0',
  `registerDate` datetime DEFAULT NULL,
  `lastvisitDate` datetime DEFAULT NULL,
  `activation` varchar(100) DEFAULT NULL,
  `params` text NOT NULL,
  `IP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_users`
--

INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `block`, `gid`, `avatar`, `gender`, `birthdate`, `lover_id`, `registerDate`, `lastvisitDate`, `activation`, `params`, `IP`) VALUES
(72, 'Igor Crevar', 'crewce', 'crewce@hotmail.com', '28996108f521f93e3caf238f30dd9836:TLciUwxr66MdE2akGiSPlS8iDu4r9G5z', 0, 21, NULL, 1, '1982-03-29', 0, '2007-12-21 00:00:00', '2017-04-04 19:25:08', '', '', '::1'),
(11000, 'Fuck Love!', 'FuckLoveGhetoQueen', 'FuckLoveGhetoQueen@hotgmail.com', 'e1c15b06e2fe9f5d4c5c50b05b21e825:XkKuDkJFwrdTRNpRrWLAiVYXs7Dt8nX5', 0, 18, '11000t5512.jpg', 2, '1987-03-03', 0, NULL, '2017-04-04 19:25:06', NULL, '', '::1'),
(10999, 'Legenda', 'Son_of_a_SIN', 'son_of_a_sin@gmailhot.com', '2eaf755f7885e398eb2eee5a18080035:YnUbmLncpH0kGE2OczA4iAYgxvE5Qatg', 0, 18, '10999t5749.jpg', 1, '1992-04-04', 0, NULL, '2017-04-04 19:15:06', NULL, '11000', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `jos_votes`
--

CREATE TABLE `jos_votes` (
  `type` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `who_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jos_votes`
--

INSERT INTO `jos_votes` (`type`, `type_id`, `who_id`, `grade`) VALUES
(1, 1, 11000, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jos_blocks`
--
ALTER TABLE `jos_blocks`
  ADD PRIMARY KEY (`who_id`,`from_id`);

--
-- Indexes for table `jos_blogs`
--
ALTER TABLE `jos_blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `who_id` (`who_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `jos_changes`
--
ALTER TABLE `jos_changes`
  ADD PRIMARY KEY (`who_id`,`what`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `jos_comments`
--
ALTER TABLE `jos_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `what` (`type`,`type_id`);

--
-- Indexes for table `jos_druk`
--
ALTER TABLE `jos_druk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jos_events`
--
ALTER TABLE `jos_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `jos_events_attend`
--
ALTER TABLE `jos_events_attend`
  ADD PRIMARY KEY (`event_id`,`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `jos_fb_users`
--
ALTER TABLE `jos_fb_users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `jos_forum_cats`
--
ALTER TABLE `jos_forum_cats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group` (`group`),
  ADD KEY `creator` (`creator`),
  ADD KEY `tc` (`time_created`);

--
-- Indexes for table `jos_forum_posts`
--
ALTER TABLE `jos_forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`),
  ADD KEY `time` (`time`),
  ADD KEY `who_id` (`who_id`);

--
-- Indexes for table `jos_forum_topics`
--
ALTER TABLE `jos_forum_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`),
  ADD KEY `time_sticky` (`sticky`,`time`);

--
-- Indexes for table `jos_jim`
--
ALTER TABLE `jos_jim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `who_id` (`who_id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `jos_lovers`
--
ALTER TABLE `jos_lovers`
  ADD PRIMARY KEY (`id1`,`id2`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `jos_members_comments`
--
ALTER TABLE `jos_members_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `who_id` (`who_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `jos_members_descs`
--
ALTER TABLE `jos_members_descs`
  ADD PRIMARY KEY (`who_id`,`from_id`),
  ADD KEY `who_id` (`who_id`);

--
-- Indexes for table `jos_members_friends`
--
ALTER TABLE `jos_members_friends`
  ADD PRIMARY KEY (`id1`,`id2`);

--
-- Indexes for table `jos_members_karma`
--
ALTER TABLE `jos_members_karma`
  ADD PRIMARY KEY (`who_id`,`from_id`);

--
-- Indexes for table `jos_members_locations`
--
ALTER TABLE `jos_members_locations`
  ADD PRIMARY KEY (`location_id`,`user_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jos_members_visit`
--
ALTER TABLE `jos_members_visit`
  ADD PRIMARY KEY (`who_id`,`from_id`);

--
-- Indexes for table `jos_photo_categories`
--
ALTER TABLE `jos_photo_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jos_photo_comments`
--
ALTER TABLE `jos_photo_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image` (`image_id`);

--
-- Indexes for table `jos_photo_events`
--
ALTER TABLE `jos_photo_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_party` (`date`),
  ADD KEY `a_id` (`a_id`,`published`);

--
-- Indexes for table `jos_photo_favourites`
--
ALTER TABLE `jos_photo_favourites`
  ADD PRIMARY KEY (`image_id`,`user_id`);

--
-- Indexes for table `jos_photo_images`
--
ALTER TABLE `jos_photo_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `jos_photo_locations`
--
ALTER TABLE `jos_photo_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jos_photo_votes`
--
ALTER TABLE `jos_photo_votes`
  ADD PRIMARY KEY (`image_id`,`user_id`);

--
-- Indexes for table `jos_request`
--
ALTER TABLE `jos_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jos_sessions`
--
ALTER TABLE `jos_sessions`
  ADD PRIMARY KEY (`id`(64)),
  ADD KEY `userid` (`userid`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `jos_users`
--
ALTER TABLE `jos_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`(255)),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `jos_votes`
--
ALTER TABLE `jos_votes`
  ADD PRIMARY KEY (`type`,`type_id`,`who_id`),
  ADD KEY `type` (`type`,`type_id`),
  ADD KEY `who_id` (`who_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jos_blogs`
--
ALTER TABLE `jos_blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jos_comments`
--
ALTER TABLE `jos_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `jos_events`
--
ALTER TABLE `jos_events`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;
--
-- AUTO_INCREMENT for table `jos_forum_cats`
--
ALTER TABLE `jos_forum_cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `jos_forum_topics`
--
ALTER TABLE `jos_forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jos_jim`
--
ALTER TABLE `jos_jim`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390026;
--
-- AUTO_INCREMENT for table `jos_members_comments`
--
ALTER TABLE `jos_members_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2046021;
--
-- AUTO_INCREMENT for table `jos_photo_categories`
--
ALTER TABLE `jos_photo_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jos_photo_comments`
--
ALTER TABLE `jos_photo_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91601;
--
-- AUTO_INCREMENT for table `jos_photo_events`
--
ALTER TABLE `jos_photo_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5695;
--
-- AUTO_INCREMENT for table `jos_photo_images`
--
ALTER TABLE `jos_photo_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54083;
--
-- AUTO_INCREMENT for table `jos_photo_locations`
--
ALTER TABLE `jos_photo_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;
--
-- AUTO_INCREMENT for table `jos_request`
--
ALTER TABLE `jos_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `jos_users`
--
ALTER TABLE `jos_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11001;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
