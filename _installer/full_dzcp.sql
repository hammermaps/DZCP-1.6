-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 07. Mrz 2026 um 03:21
-- Server-Version: 10.6.23-MariaDB-0ubuntu0.22.04.1
-- PHP-Version: 8.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `dzcp`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_acomments`
--

CREATE TABLE `dzcp_acomments` (
  `id` int(10) NOT NULL,
  `artikel` int(10) NOT NULL DEFAULT 0,
  `nick` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `reg` int(5) NOT NULL DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `editby` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_artikel`
--

CREATE TABLE `dzcp_artikel` (
  `id` int(10) NOT NULL,
  `autor` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `kat` int(2) NOT NULL DEFAULT 0,
  `titel` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url1` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `link2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url2` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `link3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url3` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `public` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_artikel`
--

INSERT INTO `dzcp_artikel` (`id`, `autor`, `datum`, `kat`, `titel`, `text`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `public`) VALUES
(1, '1', 1772846710, 1, 'Testartikel', '<p>Hier k&ouml;nnte dein Artikel stehen!</p>\r\n<p> </p>', '', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_awards`
--

CREATE TABLE `dzcp_awards` (
  `id` int(5) NOT NULL,
  `squad` int(11) NOT NULL DEFAULT 0,
  `date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `postdate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `place` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `prize` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_away`
--

CREATE TABLE `dzcp_away` (
  `id` int(5) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `titel` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start` int(20) NOT NULL DEFAULT 0,
  `end` int(20) NOT NULL DEFAULT 0,
  `date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastedit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_clankasse`
--

CREATE TABLE `dzcp_clankasse` (
  `id` int(20) NOT NULL,
  `datum` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `member` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `transaktion` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pm` int(1) NOT NULL DEFAULT 0,
  `betrag` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_clankasse_kats`
--

CREATE TABLE `dzcp_clankasse_kats` (
  `id` int(5) NOT NULL,
  `kat` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_clankasse_kats`
--

INSERT INTO `dzcp_clankasse_kats` (`id`, `kat`) VALUES
(1, 'Servermiete'),
(2, 'Serverbeitrag');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_clankasse_payed`
--

CREATE TABLE `dzcp_clankasse_payed` (
  `id` int(5) NOT NULL,
  `user` int(5) NOT NULL DEFAULT 0,
  `payed` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_clanwars`
--

CREATE TABLE `dzcp_clanwars` (
  `id` int(5) NOT NULL,
  `squad_id` int(11) NOT NULL DEFAULT 0,
  `gametype` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `gcountry` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'de',
  `matchadmins` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `lineup` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `glineup` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `clantag` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `gegner` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `xonx` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `liga` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `punkte` int(5) NOT NULL DEFAULT 0,
  `gpunkte` int(5) NOT NULL DEFAULT 0,
  `maps` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `serverip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `servername` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `serverpwd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `bericht` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `top` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_clanwars`
--

INSERT INTO `dzcp_clanwars` (`id`, `squad_id`, `gametype`, `gcountry`, `matchadmins`, `lineup`, `glineup`, `datum`, `clantag`, `gegner`, `url`, `xonx`, `liga`, `punkte`, `gpunkte`, `maps`, `serverip`, `servername`, `serverpwd`, `bericht`, `top`) VALUES
(1, 1, '', 'de', '', '', '', 1772845710, 'DZCP', 'deV!L`z Clanportal', 'https://www.dzcp.de', '5on5', 'DZCP', 0, 21, 'de_dzcp', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_clanwar_players`
--

CREATE TABLE `dzcp_clanwar_players` (
  `cwid` int(5) NOT NULL DEFAULT 0,
  `member` int(5) NOT NULL DEFAULT 0,
  `status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_config`
--

CREATE TABLE `dzcp_config` (
  `id` int(1) NOT NULL DEFAULT 1,
  `upicsize` int(5) NOT NULL DEFAULT 100,
  `gallery` int(5) NOT NULL DEFAULT 4,
  `m_usergb` int(5) NOT NULL DEFAULT 10,
  `m_clanwars` int(5) NOT NULL DEFAULT 10,
  `maxshoutarchiv` int(5) NOT NULL DEFAULT 20,
  `m_clankasse` int(5) NOT NULL DEFAULT 20,
  `m_awards` int(5) NOT NULL DEFAULT 15,
  `m_userlist` int(5) NOT NULL DEFAULT 40,
  `m_banned` int(5) NOT NULL DEFAULT 40,
  `m_membermap` int(5) NOT NULL DEFAULT 10,
  `maxwidth` int(4) NOT NULL DEFAULT 400,
  `shout_max_zeichen` int(5) NOT NULL DEFAULT 100,
  `l_servernavi` int(5) NOT NULL DEFAULT 22,
  `m_adminnews` int(5) NOT NULL DEFAULT 20,
  `m_shout` int(5) NOT NULL DEFAULT 10,
  `m_comments` int(5) NOT NULL DEFAULT 10,
  `m_archivnews` int(5) NOT NULL DEFAULT 30,
  `m_gb` int(5) NOT NULL DEFAULT 10,
  `m_fthreads` int(5) NOT NULL DEFAULT 20,
  `m_fposts` int(5) NOT NULL DEFAULT 10,
  `m_news` int(5) NOT NULL DEFAULT 5,
  `f_forum` int(5) NOT NULL DEFAULT 20,
  `l_shoutnick` int(5) NOT NULL DEFAULT 20,
  `f_gb` int(5) NOT NULL DEFAULT 20,
  `f_membergb` int(5) NOT NULL DEFAULT 20,
  `f_shout` int(5) NOT NULL DEFAULT 20,
  `f_newscom` int(5) NOT NULL DEFAULT 20,
  `f_cwcom` int(5) NOT NULL DEFAULT 20,
  `f_artikelcom` int(5) NOT NULL DEFAULT 20,
  `l_newsadmin` int(5) NOT NULL DEFAULT 20,
  `l_shouttext` int(5) NOT NULL DEFAULT 22,
  `l_newsarchiv` int(5) NOT NULL DEFAULT 20,
  `l_forumtopic` int(5) NOT NULL DEFAULT 20,
  `l_forumsubtopic` int(5) NOT NULL DEFAULT 20,
  `l_clanwars` int(5) NOT NULL DEFAULT 30,
  `m_gallerypics` int(5) NOT NULL DEFAULT 5,
  `m_lnews` int(5) NOT NULL DEFAULT 6,
  `m_topdl` int(5) NOT NULL DEFAULT 5,
  `m_ftopics` int(5) NOT NULL DEFAULT 6,
  `m_lwars` int(5) NOT NULL DEFAULT 6,
  `m_nwars` int(5) NOT NULL DEFAULT 6,
  `l_topdl` int(5) NOT NULL DEFAULT 20,
  `l_ftopics` int(5) NOT NULL DEFAULT 28,
  `l_lnews` int(5) NOT NULL DEFAULT 22,
  `l_lwars` int(5) NOT NULL DEFAULT 12,
  `l_nwars` int(5) NOT NULL DEFAULT 12,
  `l_lreg` int(5) NOT NULL DEFAULT 12,
  `m_lreg` int(5) NOT NULL DEFAULT 5,
  `m_artikel` int(5) NOT NULL DEFAULT 15,
  `m_cwcomments` int(5) NOT NULL DEFAULT 10,
  `m_adminartikel` int(5) NOT NULL DEFAULT 15,
  `securelogin` int(1) NOT NULL DEFAULT 0,
  `allowhover` int(1) NOT NULL DEFAULT 1,
  `teamrow` int(1) NOT NULL DEFAULT 3,
  `l_lartikel` int(1) NOT NULL DEFAULT 18,
  `m_lartikel` int(1) NOT NULL DEFAULT 5,
  `l_team` int(5) NOT NULL DEFAULT 7,
  `m_events` int(5) NOT NULL DEFAULT 5,
  `m_away` int(5) NOT NULL DEFAULT 10,
  `cache_teamspeak` int(10) NOT NULL DEFAULT 30,
  `cache_server` int(10) NOT NULL DEFAULT 30,
  `direct_refresh` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_config`
--

INSERT INTO `dzcp_config` (`id`, `upicsize`, `gallery`, `m_usergb`, `m_clanwars`, `maxshoutarchiv`, `m_clankasse`, `m_awards`, `m_userlist`, `m_banned`, `m_membermap`, `maxwidth`, `shout_max_zeichen`, `l_servernavi`, `m_adminnews`, `m_shout`, `m_comments`, `m_archivnews`, `m_gb`, `m_fthreads`, `m_fposts`, `m_news`, `f_forum`, `l_shoutnick`, `f_gb`, `f_membergb`, `f_shout`, `f_newscom`, `f_cwcom`, `f_artikelcom`, `l_newsadmin`, `l_shouttext`, `l_newsarchiv`, `l_forumtopic`, `l_forumsubtopic`, `l_clanwars`, `m_gallerypics`, `m_lnews`, `m_topdl`, `m_ftopics`, `m_lwars`, `m_nwars`, `l_topdl`, `l_ftopics`, `l_lnews`, `l_lwars`, `l_nwars`, `l_lreg`, `m_lreg`, `m_artikel`, `m_cwcomments`, `m_adminartikel`, `securelogin`, `allowhover`, `teamrow`, `l_lartikel`, `m_lartikel`, `l_team`, `m_events`, `m_away`, `cache_teamspeak`, `cache_server`, `direct_refresh`) VALUES
(1, 100, 4, 10, 10, 20, 20, 15, 40, 40, 10, 500, 100, 22, 20, 10, 10, 30, 10, 20, 10, 5, 20, 20, 20, 20, 20, 20, 20, 20, 20, 22, 20, 20, 20, 30, 5, 6, 5, 6, 6, 6, 20, 28, 22, 12, 12, 12, 5, 15, 10, 15, 0, 1, 3, 18, 5, 7, 5, 10, 30, 30, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_counter`
--

CREATE TABLE `dzcp_counter` (
  `id` int(5) NOT NULL,
  `visitors` int(20) NOT NULL DEFAULT 0,
  `today` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `maxonline` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_counter`
--

INSERT INTO `dzcp_counter` (`id`, `visitors`, `today`, `maxonline`) VALUES
(1, 1, '7.3.2026', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_counter_ips`
--

CREATE TABLE `dzcp_counter_ips` (
  `id` int(10) NOT NULL,
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `datum` int(20) NOT NULL DEFAULT 0,
  `agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_counter_ips`
--

INSERT INTO `dzcp_counter_ips` (`id`, `ip`, `datum`, `agent`) VALUES
(1, '127.0.0.1', 1772847299, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 \"Not:A-Brand\";v=\"99\", \"Brave\";v=\"145\", \"Chromium\";v=\"145\" ');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_counter_whoison`
--

CREATE TABLE `dzcp_counter_whoison` (
  `id` int(50) NOT NULL,
  `ip` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `online` int(20) NOT NULL DEFAULT 0,
  `whereami` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_counter_whoison`
--

INSERT INTO `dzcp_counter_whoison` (`id`, `ip`, `online`, `whereami`, `login`) VALUES
(52, '0.0.0.0', 1772854592, 'News', 0),
(64, '127.0.0.1', 1772855391, 'News', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_cw_comments`
--

CREATE TABLE `dzcp_cw_comments` (
  `id` int(10) NOT NULL,
  `cw` int(10) NOT NULL DEFAULT 0,
  `nick` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `reg` int(5) NOT NULL DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `editby` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_downloads`
--

CREATE TABLE `dzcp_downloads` (
  `id` int(11) NOT NULL,
  `download` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `beschreibung` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `hits` int(50) NOT NULL DEFAULT 0,
  `kat` int(5) NOT NULL DEFAULT 0,
  `date` int(20) NOT NULL DEFAULT 0,
  `last_dl` int(20) NOT NULL DEFAULT 0,
  `intern` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_downloads`
--

INSERT INTO `dzcp_downloads` (`id`, `download`, `url`, `beschreibung`, `hits`, `kat`, `date`, `last_dl`, `intern`) VALUES
(1, 'Testdownload', 'http://www.url.de/test.zip', '<p>Das ist ein Testdownload</p>', 0, 1, 1772846305, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_download_kat`
--

CREATE TABLE `dzcp_download_kat` (
  `id` int(11) NOT NULL,
  `name` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_download_kat`
--

INSERT INTO `dzcp_download_kat` (`id`, `name`) VALUES
(1, 'Downloads'),
(2, 'Demos'),
(3, 'Stuff');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_dsgvo`
--

CREATE TABLE `dzcp_dsgvo` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `info_tag` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `text_tag` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `persid` int(1) NOT NULL DEFAULT 0,
  `show` int(1) NOT NULL DEFAULT 0,
  `lock_show` int(1) NOT NULL DEFAULT 0,
  `for_dsgvo` int(1) NOT NULL DEFAULT 1,
  `for_dsgvo_ak` int(1) NOT NULL DEFAULT 0,
  `sort` int(3) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_dsgvo`
--

INSERT INTO `dzcp_dsgvo` (`id`, `title`, `info_tag`, `text_tag`, `persid`, `show`, `lock_show`, `for_dsgvo`, `for_dsgvo_ak`, `sort`) VALUES
(1, '_dsgvo_base_title_001', '_dsgvo_base_001', '_dsgvo_base_text_001', 0, 1, 0, 1, 0, 1),
(2, '_dsgvo_base_title_002', '_dsgvo_base_002', '_dsgvo_base_text_002', 0, 1, 0, 1, 1, 2),
(3, '_dsgvo_base_title_003', '_dsgvo_base_003', '_dsgvo_base_text_003', 0, 1, 0, 1, 1, 3),
(4, '_dsgvo_base_title_004', '_dsgvo_base_004', '_dsgvo_base_text_004', 0, 1, 0, 1, 1, 4),
(5, '_dsgvo_base_title_005', '_dsgvo_base_005', '_dsgvo_base_text_005', 0, 1, 0, 1, 1, 5),
(6, '_dsgvo_base_title_006', '_dsgvo_base_006', '_dsgvo_base_text_006', 0, 1, 0, 1, 1, 6),
(7, '_dsgvo_base_title_007', '_dsgvo_base_007', '_dsgvo_base_text_007', 1, 1, 0, 1, 1, 7),
(8, '_dsgvo_base_title_008', '_dsgvo_base_008', '_dsgvo_base_text_008', 2, 0, 0, 1, 1, 8),
(9, '_dsgvo_base_title_009', '_dsgvo_base_009', '_dsgvo_base_text_009', 0, 1, 0, 1, 1, 9),
(10, '_dsgvo_base_title_010', '_dsgvo_base_010', '_dsgvo_base_text_010', 0, 1, 0, 1, 1, 10),
(11, '_dsgvo_base_title_011', '_dsgvo_base_011', '_dsgvo_base_text_011', 0, 1, 0, 1, 1, 11),
(12, '_dsgvo_base_title_012', '_dsgvo_base_012', '_dsgvo_base_text_012', 0, 1, 0, 1, 1, 12),
(13, '_dsgvo_base_title_013', '_dsgvo_base_013', '_dsgvo_base_text_013', 0, 1, 0, 1, 1, 13),
(14, '_dsgvo_base_title_014', '_dsgvo_base_014', '_dsgvo_base_text_014', 0, 0, 0, 1, 1, 14),
(15, '_dsgvo_base_title_015', '_dsgvo_base_015', '_dsgvo_base_text_015', 0, 1, 0, 1, 1, 15),
(16, '_dsgvo_base_title_016', '_dsgvo_base_016', '_dsgvo_base_text_016', 0, 1, 0, 1, 1, 16),
(17, '_dsgvo_base_title_017', '_dsgvo_base_017', '_dsgvo_base_text_017', 0, 1, 0, 1, 1, 17),
(18, '_dsgvo_base_title_018', '_dsgvo_base_018', '_dsgvo_base_text_018', 0, 1, 0, 1, 1, 18),
(19, '_dsgvo_base_title_019', '_dsgvo_base_019', '_dsgvo_base_text_019', 0, 0, 0, 1, 1, 19),
(20, '_dsgvo_base_title_020', '_dsgvo_base_020', '_dsgvo_base_text_020', 0, 0, 0, 1, 1, 20),
(21, '_dsgvo_base_title_021', '_dsgvo_base_021', '_dsgvo_base_text_021', 0, 0, 0, 1, 1, 21),
(22, '_dsgvo_base_title_022', '_dsgvo_base_022', '_dsgvo_base_text_022', 0, 0, 0, 1, 1, 22),
(23, '_dsgvo_base_title_023', '_dsgvo_base_023', '_dsgvo_base_text_023', 0, 0, 0, 1, 1, 23),
(24, '_dsgvo_base_title_024', '_dsgvo_base_024', '_dsgvo_base_text_024', 0, 0, 0, 1, 1, 24),
(25, '_dsgvo_base_title_025', '_dsgvo_base_025', '_dsgvo_base_text_025', 0, 0, 0, 1, 1, 25),
(26, '_dsgvo_base_title_026', '_dsgvo_base_026', '_dsgvo_base_text_026', 0, 0, 0, 1, 1, 26),
(27, '_dsgvo_base_title_027', '_dsgvo_base_027', '_dsgvo_base_text_027', 0, 0, 0, 1, 1, 27),
(28, '_dsgvo_base_title_028', '_dsgvo_base_028', '_dsgvo_base_text_028', 0, 0, 0, 1, 1, 28),
(29, '_dsgvo_base_title_029', '_dsgvo_base_029', '_dsgvo_base_text_029', 0, 1, 0, 1, 1, 29),
(30, '_dsgvo_base_title_030', '_dsgvo_base_030', '_dsgvo_base_text_030', 0, 0, 0, 1, 1, 30),
(31, '_dsgvo_base_title_031', '_dsgvo_base_031', '_dsgvo_base_text_031', 0, 0, 0, 1, 1, 31),
(32, '_dsgvo_base_title_032', '_dsgvo_base_032', '_dsgvo_base_text_032', 0, 0, 0, 1, 1, 32),
(33, '_dsgvo_base_title_033', '_dsgvo_base_033', '_dsgvo_base_text_033', 0, 1, 0, 1, 1, 33),
(34, '_dsgvo_base_title_034', '_dsgvo_base_034', '_dsgvo_base_text_034', 0, 1, 0, 1, 1, 34),
(35, '_dsgvo_base_title_035', '_dsgvo_base_035', '_dsgvo_base_text_035', 0, 1, 0, 1, 1, 35),
(36, '_dsgvo_base_title_036', '_dsgvo_base_036', '_dsgvo_base_text_036', 0, 1, 0, 1, 1, 36),
(37, '_dsgvo_base_title_037', '_dsgvo_base_037', '_dsgvo_base_text_037', 0, 1, 0, 1, 1, 37),
(38, '_dsgvo_base_title_201', '_dsgvo_base_201', '_dsgvo_base_text_201', 0, 0, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_dsgvo_log`
--

CREATE TABLE `dzcp_dsgvo_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0.0.0.0',
  `date` int(11) NOT NULL DEFAULT 0,
  `agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_dsgvo_log`
--

INSERT INTO `dzcp_dsgvo_log` (`id`, `uid`, `ip`, `date`, `agent`) VALUES
(1, 1, '127.0.0.1', 1772847704, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_dsgvo_pers`
--

CREATE TABLE `dzcp_dsgvo_pers` (
  `id` int(5) NOT NULL,
  `organisation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `titel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `first_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `place` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `e-mail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_dsgvo_pers`
--

INSERT INTO `dzcp_dsgvo_pers` (`id`, `organisation`, `titel`, `first_name`, `last_name`, `address`, `zip_code`, `place`, `country`, `e-mail`, `phone`, `website`) VALUES
(1, 'Muster Unternehmen', 'Herr', 'Max', 'Mustermann', 'Eisenweg. 1', '123456', 'Musterdorf', 'Germany', 'max.mustermann@musterdorf.de', '049 12345 67891234566', 'http://www.deineUrl.de'),
(2, 'Muster Unternehmen', 'Herr', 'Max', 'Mustermann', 'Eisenweg. 1', '123456', 'Musterdorf', 'Germany', 'max.mustermann@musterdorf.de', '049 12345 67891234566', 'http://www.deineUrl.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_events`
--

CREATE TABLE `dzcp_events` (
  `id` int(5) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `event` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_events`
--

INSERT INTO `dzcp_events` (`id`, `datum`, `title`, `event`) VALUES
(1, 1772847410, 'Testevent', '<p>Das ist nur ein Testevent! :)</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_forumkats`
--

CREATE TABLE `dzcp_forumkats` (
  `id` int(10) NOT NULL,
  `kid` int(10) NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `intern` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_forumkats`
--

INSERT INTO `dzcp_forumkats` (`id`, `kid`, `name`, `intern`) VALUES
(1, 1, 'Hauptforum', 0),
(2, 2, 'OFFtopic', 0),
(3, 3, 'Clanforum', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_forumposts`
--

CREATE TABLE `dzcp_forumposts` (
  `id` int(10) NOT NULL,
  `kid` int(10) NOT NULL DEFAULT 0,
  `sid` int(10) NOT NULL DEFAULT 0,
  `date` int(20) NOT NULL DEFAULT 0,
  `nick` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `reg` int(1) NOT NULL DEFAULT 0,
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `edited` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_forumsubkats`
--

CREATE TABLE `dzcp_forumsubkats` (
  `id` int(10) NOT NULL,
  `sid` int(10) NOT NULL DEFAULT 0,
  `kattopic` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `subtopic` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pos` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_forumsubkats`
--

INSERT INTO `dzcp_forumsubkats` (`id`, `sid`, `kattopic`, `subtopic`, `pos`) VALUES
(1, 1, 'Allgemein', 'Allgemeines...', 1),
(2, 1, 'Homepage', 'Kritiken/Anregungen/Bugs', 2),
(3, 1, 'Server', 'Serverseitige Themen...', 3),
(4, 1, 'Spam', 'Spamt die Bude voll ;)', 4),
(5, 2, 'Sonstiges', '', 5),
(6, 2, 'OFFtopic', '', 6),
(7, 3, 'internes Forum', 'interne Angelegenheiten', 7),
(8, 3, 'Server intern', 'interne Serverangelegenheiten', 8),
(9, 3, 'War Forum', 'Alles &uuml;ber und rundum Clanwars', 9);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_forumthreads`
--

CREATE TABLE `dzcp_forumthreads` (
  `id` int(10) NOT NULL,
  `kid` int(10) NOT NULL DEFAULT 0,
  `t_date` int(20) NOT NULL DEFAULT 0,
  `topic` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `subtopic` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `t_nick` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `t_reg` int(11) NOT NULL DEFAULT 0,
  `t_email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `t_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hits` int(10) NOT NULL DEFAULT 0,
  `first` int(1) NOT NULL DEFAULT 0,
  `lp` int(20) NOT NULL DEFAULT 0,
  `sticky` int(1) NOT NULL DEFAULT 0,
  `closed` int(1) NOT NULL DEFAULT 0,
  `global` int(1) NOT NULL DEFAULT 0,
  `edited` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `t_hp` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `vote` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `dsgvo` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_f_abo`
--

CREATE TABLE `dzcp_f_abo` (
  `id` int(10) NOT NULL,
  `fid` int(10) NOT NULL,
  `datum` int(20) NOT NULL,
  `user` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_f_access`
--

CREATE TABLE `dzcp_f_access` (
  `id` int(11) NOT NULL,
  `user` int(10) NOT NULL DEFAULT 0,
  `pos` int(1) NOT NULL,
  `forum` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_gallery`
--

CREATE TABLE `dzcp_gallery` (
  `id` int(5) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `kat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `intern` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_gallery`
--

INSERT INTO `dzcp_gallery` (`id`, `datum`, `kat`, `beschreibung`, `intern`) VALUES
(1, 1772846711, 'Testgalerie', '<p>Das ist die erste Testgalerie.</p>\r\n<p>Hier seht ihr ein paar Bilder die eigentlich nur als Platzhalter dienen :)</p>', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_gb`
--

CREATE TABLE `dzcp_gb` (
  `id` int(5) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `nick` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(130) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reg` int(1) NOT NULL DEFAULT 0,
  `nachricht` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `editby` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_glossar`
--

CREATE TABLE `dzcp_glossar` (
  `id` int(11) NOT NULL,
  `word` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `glossar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_glossar`
--

INSERT INTO `dzcp_glossar` (`id`, `word`, `glossar`) VALUES
(1, 'DZCP', '<p>deV!L`z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.</p>\r\n<p>Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_ipcheck`
--

CREATE TABLE `dzcp_ipcheck` (
  `id` int(11) NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT 0,
  `what` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `time` int(20) NOT NULL DEFAULT 0,
  `created` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_ipcheck`
--

INSERT INTO `dzcp_ipcheck` (`id`, `ip`, `user_id`, `what`, `time`, `created`) VALUES
(1, '127.0.0.1', 1, 'login(1)', 1772847704, 1772847704);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_links`
--

CREATE TABLE `dzcp_links` (
  `id` int(5) NOT NULL,
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `text` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `banner` int(1) NOT NULL DEFAULT 0,
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hits` int(50) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_links`
--

INSERT INTO `dzcp_links` (`id`, `url`, `text`, `banner`, `beschreibung`, `hits`) VALUES
(1, 'https://www.dzcp.de', 'https://www.dzcp.de/banner/dzcp.gif', 1, 'deV!L`z Clanportal', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_linkus`
--

CREATE TABLE `dzcp_linkus` (
  `id` int(5) NOT NULL,
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `text` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `banner` int(1) NOT NULL DEFAULT 0,
  `beschreibung` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_linkus`
--

INSERT INTO `dzcp_linkus` (`id`, `url`, `text`, `banner`, `beschreibung`) VALUES
(1, 'https://www.dzcp.de', 'https://www.dzcp.de/banner/button.gif', 1, 'deV!L`z Clanportal');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_messages`
--

CREATE TABLE `dzcp_messages` (
  `id` int(5) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `von` int(11) NOT NULL DEFAULT 0,
  `an` int(11) NOT NULL DEFAULT 0,
  `see_u` int(1) NOT NULL DEFAULT 0,
  `page` int(1) NOT NULL DEFAULT 0,
  `titel` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nachricht` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `see` int(1) NOT NULL DEFAULT 0,
  `readed` int(1) NOT NULL DEFAULT 0,
  `sendmail` int(1) DEFAULT 0,
  `sendnews` int(1) NOT NULL DEFAULT 0,
  `senduser` int(5) NOT NULL DEFAULT 0,
  `sendnewsuser` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_navi`
--

CREATE TABLE `dzcp_navi` (
  `id` int(11) NOT NULL,
  `pos` int(20) NOT NULL DEFAULT 0,
  `kat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `shown` int(1) NOT NULL DEFAULT 0,
  `name` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `target` int(1) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `internal` int(1) NOT NULL DEFAULT 0,
  `wichtig` int(1) NOT NULL DEFAULT 0,
  `editor` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_navi`
--

INSERT INTO `dzcp_navi` (`id`, `pos`, `kat`, `shown`, `name`, `url`, `target`, `type`, `internal`, `wichtig`, `editor`) VALUES
(1, 1, 'nav_main', 1, '_news_', '../news/', 0, 1, 0, 0, 0),
(2, 2, 'nav_main', 1, '_newsarchiv_', '../news/?action=archiv', 0, 1, 0, 0, 0),
(3, 3, 'nav_main', 1, '_artikel_', '../artikel/', 0, 1, 0, 0, 0),
(4, 4, 'nav_main', 1, '_forum_', '../forum/', 0, 1, 0, 0, 0),
(5, 5, 'nav_main', 1, '_gb_', '../gb/', 0, 1, 0, 0, 0),
(6, 1, 'nav_server', 1, '_server_', '../server/', 0, 1, 0, 0, 0),
(7, 6, 'nav_main', 1, '_kalender_', '../kalender/', 0, 1, 0, 0, 0),
(8, 7, 'nav_main', 1, '_votes_', '../votes/', 0, 1, 0, 0, 0),
(9, 8, 'nav_main', 1, '_links_', '../links/', 0, 1, 0, 0, 0),
(10, 9, 'nav_main', 1, '_sponsoren_', '../sponsors/', 0, 1, 0, 0, 0),
(11, 10, 'nav_main', 1, '_downloads_', '../downloads/', 0, 1, 0, 0, 0),
(12, 11, 'nav_main', 1, '_userlist_', '../user/?action=userlist', 0, 1, 0, 0, 0),
(13, 1, 'nav_clan', 1, '_squads_', '../squads/', 0, 1, 0, 0, 0),
(14, 3, 'nav_clan', 1, '_cw_', '../clanwars/', 0, 1, 0, 0, 0),
(15, 4, 'nav_clan', 1, '_awards_', '../awards/', 0, 1, 0, 0, 0),
(16, 5, 'nav_clan', 1, '_rankings_', '../rankings/', 0, 1, 0, 0, 0),
(17, 2, 'nav_server', 1, '_serverlist_', '../serverliste/', 0, 1, 0, 0, 0),
(18, 3, 'nav_server', 1, '_ts_', '../teamspeak/', 0, 1, 0, 0, 0),
(20, 2, 'nav_misc', 1, '_galerie_', '../gallery/', 0, 1, 0, 0, 0),
(21, 3, 'nav_misc', 1, '_kontakt_', '../contact/', 0, 1, 0, 0, 0),
(22, 4, 'nav_misc', 1, '_joinus_', '../contact/?action=joinus', 0, 1, 0, 0, 0),
(23, 5, 'nav_misc', 1, '_fightus_', '../contact/?action=fightus', 0, 1, 0, 0, 0),
(24, 6, 'nav_misc', 1, '_linkus_', '../linkus/', 0, 1, 0, 0, 0),
(25, 7, 'nav_misc', 1, '_stats_', '../stats/', 0, 1, 0, 0, 0),
(26, 8, 'nav_misc', 1, '_impressum_', '../impressum/', 0, 1, 0, 0, 0),
(27, 1, 'nav_admin', 1, '_admin_', '../admin/', 0, 1, 1, 1, 0),
(28, 1, 'nav_user', 1, '_lobby_', '../user/?action=userlobby', 0, 1, 0, 0, 0),
(29, 2, 'nav_user', 1, '_nachrichten_', '../user/?action=msg', 0, 1, 0, 0, 0),
(30, 3, 'nav_user', 1, '_buddys_', '../user/?action=buddys', 0, 1, 0, 0, 0),
(31, 4, 'nav_user', 1, '_edit_profile_', '../user/?action=editprofile', 0, 1, 0, 0, 0),
(32, 5, 'nav_user', 1, '_logout_', '../user/?action=logout', 0, 1, 0, 1, 0),
(34, 1, 'nav_member', 1, '_clankasse_', '../clankasse/', 0, 1, 0, 0, 0),
(35, 2, 'nav_member', 1, '_taktiken_', '../taktik/', 0, 1, 0, 0, 0),
(37, 2, 'nav_clan', 1, '_membermap_', '../membermap/', 0, 1, 0, 0, 0),
(38, 12, 'nav_main', 1, '_glossar_', '../glossar/', 0, 1, 0, 0, 0),
(39, 0, 'nav_main', 1, '_news_send_', '../news/send.php', 0, 1, 0, 0, 0),
(40, 1, 'nav_trial', 1, '_awaycal_', '../away/', 0, 2, 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_navi_kats`
--

CREATE TABLE `dzcp_navi_kats` (
  `id` int(10) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `placeholder` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_navi_kats`
--

INSERT INTO `dzcp_navi_kats` (`id`, `name`, `placeholder`, `level`) VALUES
(1, 'Clan Navigation', 'nav_clan', 0),
(2, 'Main Navigation', 'nav_main', 0),
(3, 'Server Navigation', 'nav_server', 0),
(4, 'Misc Navigation', 'nav_misc', 0),
(5, 'Trial Navigation', 'nav_trial', 2),
(6, 'Admin Navigation', 'nav_admin', 4),
(7, 'User Navigation', 'nav_user', 1),
(8, 'Member Navigation', 'nav_member', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_news`
--

CREATE TABLE `dzcp_news` (
  `id` int(10) NOT NULL,
  `autor` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `kat` int(2) NOT NULL DEFAULT 0,
  `sticky` int(20) NOT NULL DEFAULT 0,
  `titel` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `intern` int(1) NOT NULL DEFAULT 0,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `klapplink` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `klapptext` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url1` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `link2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url2` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `link3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url3` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `viewed` int(10) NOT NULL DEFAULT 0,
  `public` int(1) NOT NULL DEFAULT 0,
  `timeshift` int(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_news`
--

INSERT INTO `dzcp_news` (`id`, `autor`, `datum`, `kat`, `sticky`, `titel`, `intern`, `text`, `klapplink`, `klapptext`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `viewed`, `public`, `timeshift`) VALUES
(1, '1', 1772846591, 1, 0, 'deV!L`z Clanportal', 0, '<p>deV!L`z Clanportal wurde erfolgreich installiert!</p><p>Bei Fragen oder Problemen kannst du gerne das Forum unter <a href=\"https://www.dzcp.de/\" target=\"_blank\">www.dzcp.de</a> kontaktieren.</p><p>Mehr Designtemplates und Modifikationen findest du unter <a href=\"http://www.templatebar.de/\" target=\"_blank\" title=\"Templates, Designs &amp; Modifikationen\">www.templatebar.de</a>.</p><p><br /></p><p>Viel Spass mit dem DZCP w&uuml;nscht dir das Team von www.dzcp.de.</p>', '', '', 'www.dzcp.de', 'https://www.dzcp.de', 'TEMPLATEbar.de', 'http://www.templatebar.de', '', '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_newscomments`
--

CREATE TABLE `dzcp_newscomments` (
  `id` int(10) NOT NULL,
  `news` int(10) NOT NULL DEFAULT 0,
  `nick` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `datum` int(20) NOT NULL DEFAULT 0,
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `reg` int(5) NOT NULL DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `editby` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_newskat`
--

CREATE TABLE `dzcp_newskat` (
  `id` int(5) NOT NULL,
  `katimg` tinytext CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kategorie` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_newskat`
--

INSERT INTO `dzcp_newskat` (`id`, `katimg`, `kategorie`) VALUES
(1, 'hp.jpg', 'Homepage');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_partners`
--

CREATE TABLE `dzcp_partners` (
  `id` int(5) NOT NULL,
  `link` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `banner` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `textlink` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_partners`
--

INSERT INTO `dzcp_partners` (`id`, `link`, `banner`, `textlink`) VALUES
(1, 'http://www.hogibo.net', 'hogibo.gif', 0),
(2, 'https://www.dzcp.de', 'dzcp.gif', 0),
(3, 'https://www.dzcp.de', 'dzcp.de', 1),
(4, 'http://www.hogibo.net', 'Webspace', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_permissions`
--

CREATE TABLE `dzcp_permissions` (
  `id` int(5) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `pos` int(1) NOT NULL DEFAULT 0,
  `positions` int(1) NOT NULL DEFAULT 0,
  `intforum` int(1) NOT NULL DEFAULT 0,
  `clankasse` int(1) NOT NULL DEFAULT 0,
  `clanwars` int(1) NOT NULL DEFAULT 0,
  `clear` int(1) NOT NULL DEFAULT 0,
  `config` int(1) DEFAULT 0,
  `shoutbox` int(1) NOT NULL DEFAULT 0,
  `serverliste` int(1) NOT NULL DEFAULT 0,
  `editusers` int(1) NOT NULL DEFAULT 0,
  `edittactics` int(1) NOT NULL DEFAULT 0,
  `editsquads` int(1) NOT NULL DEFAULT 0,
  `editserver` int(1) NOT NULL DEFAULT 0,
  `editkalender` int(1) NOT NULL DEFAULT 0,
  `news` int(1) NOT NULL DEFAULT 0,
  `gb` int(1) NOT NULL DEFAULT 0,
  `partners` int(1) NOT NULL DEFAULT 0,
  `profile` int(1) NOT NULL DEFAULT 0,
  `protocol` int(1) NOT NULL DEFAULT 0,
  `forum` int(1) NOT NULL DEFAULT 0,
  `forumkats` int(1) NOT NULL DEFAULT 0,
  `votes` int(1) NOT NULL DEFAULT 0,
  `gallery` int(1) NOT NULL DEFAULT 0,
  `votesadmin` int(1) NOT NULL DEFAULT 0,
  `links` int(1) NOT NULL DEFAULT 0,
  `downloads` int(1) NOT NULL DEFAULT 0,
  `newsletter` int(1) NOT NULL DEFAULT 0,
  `intnews` int(1) NOT NULL DEFAULT 0,
  `impressum` int(1) NOT NULL DEFAULT 0,
  `rankings` int(1) NOT NULL DEFAULT 0,
  `contact` int(1) NOT NULL DEFAULT 0,
  `joinus` int(1) NOT NULL DEFAULT 0,
  `awards` int(1) NOT NULL DEFAULT 0,
  `artikel` int(1) NOT NULL DEFAULT 0,
  `backup` int(1) NOT NULL DEFAULT 0,
  `receivecws` int(1) NOT NULL DEFAULT 0,
  `editor` int(1) NOT NULL DEFAULT 0,
  `glossar` int(1) NOT NULL DEFAULT 0,
  `gs_showpw` int(1) NOT NULL DEFAULT 0,
  `slideshow` int(1) NOT NULL DEFAULT 0,
  `smileys` int(1) NOT NULL DEFAULT 0,
  `support` int(1) NOT NULL DEFAULT 0,
  `galleryintern` int(1) NOT NULL DEFAULT 0,
  `dlintern` int(1) NOT NULL DEFAULT 0,
  `datenschutz` int(1) NOT NULL DEFAULT 0,
  `addoncheck` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_permissions`
--

INSERT INTO `dzcp_permissions` (`id`, `user`, `pos`, `positions`, `intforum`, `clankasse`, `clanwars`, `clear`, `config`, `shoutbox`, `serverliste`, `editusers`, `edittactics`, `editsquads`, `editserver`, `editkalender`, `news`, `gb`, `partners`, `profile`, `protocol`, `forum`, `forumkats`, `votes`, `gallery`, `votesadmin`, `links`, `downloads`, `newsletter`, `intnews`, `impressum`, `rankings`, `contact`, `joinus`, `awards`, `artikel`, `backup`, `receivecws`, `editor`, `glossar`, `gs_showpw`, `slideshow`, `smileys`, `support`, `galleryintern`, `dlintern`, `datenschutz`, `addoncheck`) VALUES
(1, 1, 0, 0, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_positions`
--

CREATE TABLE `dzcp_positions` (
  `id` int(2) NOT NULL,
  `pid` int(2) NOT NULL DEFAULT 0,
  `position` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nletter` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_positions`
--

INSERT INTO `dzcp_positions` (`id`, `pid`, `position`, `nletter`) VALUES
(1, 1, 'Leader', 0),
(2, 2, 'Co-Leader', 0),
(3, 3, 'Webmaster', 0),
(4, 4, 'Member', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_profile`
--

CREATE TABLE `dzcp_profile` (
  `id` int(5) UNSIGNED NOT NULL,
  `kid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feldname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `type` int(5) NOT NULL DEFAULT 1,
  `shown` int(5) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_profile`
--

INSERT INTO `dzcp_profile` (`id`, `kid`, `name`, `feldname`, `type`, `shown`) VALUES
(2, 1, '_job_', 'job', 1, 1),
(3, 1, '_hobbys_', 'hobbys', 1, 1),
(4, 1, '_motto_', 'motto', 1, 1),
(5, 2, '_exclans_', 'ex', 1, 1),
(8, 4, '_drink_', 'drink', 1, 1),
(9, 4, '_essen_', 'essen', 1, 1),
(10, 4, '_film_', 'film', 1, 1),
(11, 4, '_musik_', 'musik', 1, 1),
(12, 4, '_song_', 'song', 1, 1),
(13, 4, '_buch_', 'buch', 1, 1),
(14, 4, '_autor_', 'autor', 1, 1),
(15, 4, '_person_', 'person', 1, 1),
(16, 4, '_sport_', 'sport', 1, 1),
(17, 4, '_sportler_', 'sportler', 1, 1),
(18, 4, '_auto_', 'auto', 1, 1),
(19, 4, '_game_', 'game', 1, 1),
(20, 4, '_favoclan_', 'favoclan', 1, 1),
(21, 4, '_spieler_', 'spieler', 1, 1),
(22, 4, '_map_', 'map', 1, 1),
(23, 4, '_waffe_', 'waffe', 1, 1),
(24, 5, '_system_', 'os', 1, 1),
(25, 5, '_board_', 'board', 1, 1),
(26, 5, '_cpu_', 'cpu', 1, 1),
(27, 5, '_ram_', 'ram', 1, 1),
(28, 5, '_graka_', 'graka', 1, 1),
(29, 5, '_hdd_', 'hdd', 1, 1),
(30, 5, '_monitor_', 'monitor', 1, 1),
(31, 5, '_maus_', 'maus', 1, 1),
(32, 5, '_mauspad_', 'mauspad', 1, 1),
(33, 5, '_headset_', 'headset', 1, 1),
(34, 5, '_inet_', 'inet', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_rankings`
--

CREATE TABLE `dzcp_rankings` (
  `id` int(5) NOT NULL,
  `league` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastranking` int(10) NOT NULL DEFAULT 0,
  `rank` int(10) NOT NULL,
  `squad` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `postdate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_server`
--

CREATE TABLE `dzcp_server` (
  `id` int(5) NOT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `shown` int(1) NOT NULL DEFAULT 1,
  `navi` int(1) NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `port` int(10) NOT NULL DEFAULT 0,
  `pwd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `game` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `qport` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_server`
--

INSERT INTO `dzcp_server` (`id`, `status`, `shown`, `navi`, `name`, `ip`, `port`, `pwd`, `game`, `qport`) VALUES
(1, 'bf2', 1, 1, 'Battlefield-Basis.de II von Hogibo.net', '80.190.178.115', 9260, '', 'bf2.gif', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_serverliste`
--

CREATE TABLE `dzcp_serverliste` (
  `id` int(20) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `clanname` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `clanurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `port` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pwd` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `checked` int(1) NOT NULL DEFAULT 0,
  `slots` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_serverliste`
--

INSERT INTO `dzcp_serverliste` (`id`, `datum`, `clanname`, `clanurl`, `ip`, `port`, `pwd`, `checked`, `slots`) VALUES
(1, 1772846711, '[-tHu-] teamHanau', 'http://www.thu-clan.de', '82.98.216.10', '27015', '', 1, '17');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_settings`
--

CREATE TABLE `dzcp_settings` (
  `id` int(1) NOT NULL DEFAULT 1,
  `clanname` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reg_forum` int(1) NOT NULL DEFAULT 1,
  `reg_cwcomments` int(1) NOT NULL DEFAULT 1,
  `counter_start` int(10) NOT NULL DEFAULT 0,
  `reg_dl` int(1) NOT NULL DEFAULT 1,
  `reg_artikel` int(1) NOT NULL DEFAULT 1,
  `reg_newscomments` int(1) NOT NULL DEFAULT 1,
  `tmpdir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'version1.6',
  `wmodus` int(1) NOT NULL DEFAULT 0,
  `iban` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `bic` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `badwords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pagetitel` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `i_domain` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'www.deineUrl.de',
  `i_autor` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `k_nr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '123456789',
  `k_inhaber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Max Mustermann',
  `k_blz` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '123456789',
  `k_bank` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Musterbank',
  `k_waehrung` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '&euro;',
  `language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'deutsch',
  `domain` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'localhost',
  `regcode` int(1) NOT NULL DEFAULT 1,
  `ts_ip` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `mailfrom` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'info@localhost',
  `ts_port` int(10) NOT NULL DEFAULT 0,
  `ts_sport` int(10) NOT NULL DEFAULT 0,
  `ts_customicon` int(1) NOT NULL DEFAULT 1,
  `ts_showchannel` int(1) NOT NULL DEFAULT 0,
  `ts_width` int(10) NOT NULL DEFAULT 0,
  `eml_reg_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `eml_pwd_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `eml_nletter_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `eml_reg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_pwd` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_nletter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reg_shout` int(1) NOT NULL DEFAULT 1,
  `gmaps_who` int(1) NOT NULL DEFAULT 1,
  `prev` int(3) NOT NULL DEFAULT 0,
  `eml_fabo_npost_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_fabo_tedit_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_fabo_pedit_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_pn_subj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_fabo_npost` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_fabo_tedit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_fabo_pedit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eml_pn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `k_vwz` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `double_post` int(1) NOT NULL DEFAULT 1,
  `forum_vote` int(1) NOT NULL DEFAULT 1,
  `gb_activ` int(1) NOT NULL DEFAULT 1,
  `urls_linked` int(1) NOT NULL DEFAULT 1,
  `steam_api_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `db_optimize` int(20) NOT NULL DEFAULT 0,
  `last_conjob` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_settings`
--

INSERT INTO `dzcp_settings` (`id`, `clanname`, `reg_forum`, `reg_cwcomments`, `counter_start`, `reg_dl`, `reg_artikel`, `reg_newscomments`, `tmpdir`, `wmodus`, `iban`, `bic`, `badwords`, `pagetitel`, `i_domain`, `i_autor`, `k_nr`, `k_inhaber`, `k_blz`, `k_bank`, `k_waehrung`, `language`, `domain`, `regcode`, `ts_ip`, `mailfrom`, `ts_port`, `ts_sport`, `ts_customicon`, `ts_showchannel`, `ts_width`, `eml_reg_subj`, `eml_pwd_subj`, `eml_nletter_subj`, `eml_reg`, `eml_pwd`, `eml_nletter`, `reg_shout`, `gmaps_who`, `prev`, `eml_fabo_npost_subj`, `eml_fabo_tedit_subj`, `eml_fabo_pedit_subj`, `eml_pn_subj`, `eml_fabo_npost`, `eml_fabo_tedit`, `eml_fabo_pedit`, `eml_pn`, `k_vwz`, `double_post`, `forum_vote`, `gb_activ`, `urls_linked`, `steam_api_key`, `db_optimize`, `last_conjob`) VALUES
(1, 'Dein Clanname hier!', 1, 1, 0, 1, 1, 1, 'version1.6', 0, '', '', 'arsch,Arsch,arschloch,Arschloch,hure,Hure', 'Dein Seitentitel hier!', 'www.deineUrl.de', 'Max Mustermann', '123456789', 'Max Mustermann', '123456789', 'Musterbank', '&euro;', 'deutsch', 'localhost', 1, '80.190.204.164', 'info@localhost', 7000, 10011, 1, 0, 0, 'Deine Registrierung', 'Deine Zugangsdaten', 'Newsletter', 'Du hast dich erfolgreich auf unserer Seite registriert!\r\nDeine Logindaten lauten:\r\n\r\n\r\n\r\n##########\r\n\r\nLoginname: [user]\r\n\r\nPasswort: [pwd]\r\n\r\n##########\r\n\r\n\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 'Ein neues Passwort wurde f&uuml;r deinen Account generiert!\r\n\r\n\r\n\r\n#########\r\n\r\nLogin-Name: [user]\r\n\r\nPasswort: [pwd]\r\n\r\n#########\r\n\r\n\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', '[text]\r\n\r\n\r\n\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 1, 1, 661, 'Neuer Beitrag auf abonniertes Thema im [titel]', 'Thread auf abonniertes Thema im [titel] wurde editiert', 'Beitrag auf abonniertes Thema im [titel] wurde editiert', 'Neue PN auf [domain]', 'Hallo [nick],<br />\r\n<br />\r\n[postuser] hat auf das Thema: [topic] auf der Website: &#34;[titel]&#34; geantwortet.<br />\r\n<br />\r\nDen neuen Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/&euro;action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/&euro;action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden Text geschrieben:<br />\r\n-<br />\r\n[text]<br />\r\n-<br />\r\n<br />\r\nViele Gr&uuml;&szlig;e,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 'Hallo [nick],<br />\r\n         <br />\r\nDer Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/&euro;action=showthread&id=[id]&#34;>http://[domain]/forum/&euro;action=showthread&id=[id]</a><br />\r\n         <br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n-<br />\r\n[text]<br />\r\n-<br />\r\n         <br />\r\nViele Gr&uuml;&szlig;e,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 'Hallo [nick],<br />\r\n<br />\r\nEin Beitrag im Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/&euro;action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/&euro;action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n-<br />\r\n[text]<br />\r\n-<br />\r\n<br />\r\nViele Gr&uuml;&szlig;e,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', '-<br />\r\n<br />\r\nHallo [nick],<br />\r\n<br />\r\nDu hast eine neue Nachricht in deinem Postfach.<br />\r\n<br />\r\nTitel: [titel]<br />\r\n<br />\r\n<a href=&#34;http://[domain]/user/index.php&euro;action=msg&#34;>Zum Nachrichten-Center</a><br />\r\n<br />\r\nVG<br />\r\n<br />\r\n[clan]<br />\r\n<br />\r\n-', '', 1, 1, 1, 1, '', 1773105927, 1772853649);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_shoutbox`
--

CREATE TABLE `dzcp_shoutbox` (
  `id` int(11) NOT NULL,
  `datum` int(30) NOT NULL DEFAULT 0,
  `nick` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_shoutbox`
--

INSERT INTO `dzcp_shoutbox` (`id`, `datum`, `nick`, `email`, `text`, `ip`) VALUES
(1, 1772846711, 'deV!L', 'webmaster@dzcp.de', 'Viel Gl&uuml;ck und Erfolg mit eurem Clan!', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_sites`
--

CREATE TABLE `dzcp_sites` (
  `id` int(5) NOT NULL,
  `titel` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `html` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_slideshow`
--

CREATE TABLE `dzcp_slideshow` (
  `id` int(11) NOT NULL,
  `pos` int(5) NOT NULL DEFAULT 0,
  `bez` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `showbez` int(1) NOT NULL DEFAULT 1,
  `desc` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `target` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_sponsoren`
--

CREATE TABLE `dzcp_sponsoren` (
  `id` int(5) NOT NULL,
  `name` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `site` int(1) NOT NULL DEFAULT 0,
  `send` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slink` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner` int(1) NOT NULL DEFAULT 0,
  `bend` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `blink` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `box` int(1) NOT NULL DEFAULT 0,
  `xend` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `xlink` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pos` int(5) NOT NULL,
  `hits` int(50) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_sponsoren`
--

INSERT INTO `dzcp_sponsoren` (`id`, `name`, `link`, `beschreibung`, `site`, `send`, `slink`, `banner`, `bend`, `blink`, `box`, `xend`, `xlink`, `pos`, `hits`) VALUES
(1, 'DZCP', 'https://www.dzcp.de', '<p>deV!L\'z Clanportal, das CMS for Online-Clans!</p>', 0, '', '', 0, '', '', 1, 'gif', '', 7, 0),
(2, 'DZCP Rotationsbanner', 'https://www.dzcp.de', '<p>deV!L`z Clanportal</p>', 0, '', '', 1, '', 'https://www.dzcp.de/banner/dzcp.gif', 0, '', '', 5, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_squads`
--

CREATE TABLE `dzcp_squads` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `game` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `icon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pos` int(1) NOT NULL DEFAULT 0,
  `shown` int(1) NOT NULL DEFAULT 0,
  `navi` int(1) NOT NULL DEFAULT 1,
  `status` int(1) NOT NULL DEFAULT 1,
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `team_show` int(1) NOT NULL DEFAULT 1,
  `team_joinus` int(1) NOT NULL DEFAULT 1,
  `team_fightus` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_squads`
--

INSERT INTO `dzcp_squads` (`id`, `name`, `game`, `icon`, `pos`, `shown`, `navi`, `status`, `beschreibung`, `team_show`, `team_joinus`, `team_fightus`) VALUES
(1, 'Testsquad', 'Counter-Strike', 'cs.gif', 1, 1, 1, 1, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_squaduser`
--

CREATE TABLE `dzcp_squaduser` (
  `id` int(5) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `squad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_squaduser`
--

INSERT INTO `dzcp_squaduser` (`id`, `user`, `squad`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_taktiken`
--

CREATE TABLE `dzcp_taktiken` (
  `id` int(10) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `map` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `spart` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `standardt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sparct` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `standardct` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `autor` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_userbuddys`
--

CREATE TABLE `dzcp_userbuddys` (
  `id` int(10) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `buddy` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_usergallery`
--

CREATE TABLE `dzcp_usergallery` (
  `id` int(5) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pic` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_usergb`
--

CREATE TABLE `dzcp_usergb` (
  `id` int(5) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `datum` int(20) NOT NULL DEFAULT 0,
  `nick` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `reg` int(1) NOT NULL DEFAULT 0,
  `nachricht` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `editby` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_userposis`
--

CREATE TABLE `dzcp_userposis` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `posi` int(5) NOT NULL DEFAULT 0,
  `squad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_users`
--

CREATE TABLE `dzcp_users` (
  `id` int(11) NOT NULL,
  `user` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nick` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pwd_md5` int(1) NOT NULL DEFAULT 1,
  `sessid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pkey` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'de',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `regdatum` int(20) NOT NULL DEFAULT 0,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `steamid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `battlenetid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `originid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `skypename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `psnid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `xboxid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `level` int(2) NOT NULL DEFAULT 0,
  `dsgvo_lock` int(1) NOT NULL DEFAULT 1,
  `banned` int(1) NOT NULL DEFAULT 0,
  `rlname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `city` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `geolocation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` int(1) NOT NULL DEFAULT 0,
  `bday` int(11) NOT NULL DEFAULT 0,
  `hobbys` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `motto` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hp` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `cpu` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ram` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `monitor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `maus` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `mauspad` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `headset` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `board` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `os` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `graka` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `hdd` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `inet` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `signatur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position` int(2) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1,
  `ex` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `job` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `time` int(20) NOT NULL DEFAULT 0,
  `listck` int(1) NOT NULL DEFAULT 0,
  `online` int(1) NOT NULL DEFAULT 0,
  `nletter` int(1) NOT NULL DEFAULT 1,
  `whereami` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `drink` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `essen` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `film` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `musik` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `song` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `buch` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `autor` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `person` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sport` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sportler` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `auto` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `game` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `favoclan` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `spieler` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `map` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `waffe` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `rasse` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url2` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `url3` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pnmail` int(1) NOT NULL DEFAULT 1,
  `perm_gallery` int(1) NOT NULL DEFAULT 0,
  `perm_gb` int(1) NOT NULL DEFAULT 1,
  `show` int(1) NOT NULL DEFAULT 4,
  `language` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_users`
--

INSERT INTO `dzcp_users` (`id`, `user`, `nick`, `pwd`, `pwd_md5`, `sessid`, `pkey`, `country`, `ip`, `regdatum`, `email`, `steamid`, `battlenetid`, `originid`, `skypename`, `psnid`, `xboxid`, `level`, `dsgvo_lock`, `banned`, `rlname`, `city`, `geolocation`, `sex`, `bday`, `hobbys`, `motto`, `hp`, `cpu`, `ram`, `monitor`, `maus`, `mauspad`, `headset`, `board`, `os`, `graka`, `hdd`, `inet`, `signatur`, `position`, `status`, `ex`, `job`, `time`, `listck`, `online`, `nletter`, `whereami`, `drink`, `essen`, `film`, `musik`, `song`, `buch`, `autor`, `person`, `sport`, `sportler`, `auto`, `game`, `favoclan`, `spieler`, `map`, `waffe`, `rasse`, `url2`, `url3`, `beschreibung`, `pnmail`, `perm_gallery`, `perm_gb`, `show`, `language`) VALUES
(1, 'masterbee', 'Test', '$2y$12$7Fh3/i2s3kO0RYBZ1kY3e.4ec4bw2p8xYqBk7e2qAY1YcqyOHEKte', 0, '0bef8f96e99a4563554148819a0a82ff', '', 'de', '127.0.0.1', 1772846711, 'lbrucksch@hammermaps.de', '', '', '', '', '', '', 4, 0, 0, '', '', NULL, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, 1, 1, '', '', 1772852434, 0, 1, 1, 'News', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, 1, 0, 1, 4, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_userstats`
--

CREATE TABLE `dzcp_userstats` (
  `id` int(5) NOT NULL,
  `user` int(11) NOT NULL DEFAULT 0,
  `logins` int(100) NOT NULL DEFAULT 0,
  `writtenmsg` int(10) NOT NULL DEFAULT 0,
  `lastvisit` int(20) NOT NULL DEFAULT 0,
  `hits` int(249) NOT NULL DEFAULT 0,
  `votes` int(5) NOT NULL DEFAULT 0,
  `profilhits` int(20) NOT NULL DEFAULT 0,
  `forumposts` int(5) NOT NULL DEFAULT 0,
  `cws` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_userstats`
--

INSERT INTO `dzcp_userstats` (`id`, `user`, `logins`, `writtenmsg`, `lastvisit`, `hits`, `votes`, `profilhits`, `forumposts`, `cws`) VALUES
(1, 1, 1, 0, 0, 85, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_votes`
--

CREATE TABLE `dzcp_votes` (
  `id` int(11) NOT NULL,
  `datum` int(20) NOT NULL DEFAULT 0,
  `titel` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `intern` int(1) NOT NULL DEFAULT 0,
  `menu` int(1) NOT NULL DEFAULT 0,
  `closed` int(1) NOT NULL DEFAULT 0,
  `von` int(10) NOT NULL DEFAULT 0,
  `forum` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_votes`
--

INSERT INTO `dzcp_votes` (`id`, `datum`, `titel`, `intern`, `menu`, `closed`, `von`, `forum`) VALUES
(1, 1772846512, 'Wie findet ihr unsere Seite?', 0, 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dzcp_vote_results`
--

CREATE TABLE `dzcp_vote_results` (
  `id` int(11) NOT NULL,
  `vid` int(11) NOT NULL DEFAULT 0,
  `what` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sel` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `stimmen` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dzcp_vote_results`
--

INSERT INTO `dzcp_vote_results` (`id`, `vid`, `what`, `sel`, `stimmen`) VALUES
(1, 1, 'a1', 'Gut', 0),
(2, 1, 'a2', 'Schlecht', 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dzcp_acomments`
--
ALTER TABLE `dzcp_acomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acomments_artikel` (`artikel`),
  ADD KEY `idx_acomments_datum` (`datum`),
  ADD KEY `idx_acomments_reg` (`reg`);

--
-- Indizes für die Tabelle `dzcp_artikel`
--
ALTER TABLE `dzcp_artikel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_artikel_kat` (`kat`),
  ADD KEY `idx_artikel_autor` (`autor`),
  ADD KEY `idx_artikel_public` (`public`);

--
-- Indizes für die Tabelle `dzcp_awards`
--
ALTER TABLE `dzcp_awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_awards_squad` (`squad`);

--
-- Indizes für die Tabelle `dzcp_away`
--
ALTER TABLE `dzcp_away`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_away_userid` (`userid`),
  ADD KEY `idx_away_start` (`start`),
  ADD KEY `idx_away_end` (`end`);

--
-- Indizes für die Tabelle `dzcp_clankasse`
--
ALTER TABLE `dzcp_clankasse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clankasse_pm` (`pm`);

--
-- Indizes für die Tabelle `dzcp_clankasse_kats`
--
ALTER TABLE `dzcp_clankasse_kats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_clankasse_payed`
--
ALTER TABLE `dzcp_clankasse_payed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_c_payed_user` (`user`);

--
-- Indizes für die Tabelle `dzcp_clanwars`
--
ALTER TABLE `dzcp_clanwars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cw_squad_id` (`squad_id`),
  ADD KEY `idx_cw_datum` (`datum`),
  ADD KEY `idx_cw_top` (`top`);

--
-- Indizes für die Tabelle `dzcp_clanwar_players`
--
ALTER TABLE `dzcp_clanwar_players`
  ADD KEY `idx_cw_player_cwid` (`cwid`),
  ADD KEY `idx_cw_player_member` (`member`),
  ADD KEY `idx_cw_player_status` (`status`);

--
-- Indizes für die Tabelle `dzcp_config`
--
ALTER TABLE `dzcp_config`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `dzcp_counter`
--
ALTER TABLE `dzcp_counter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `today` (`today`);

--
-- Indizes für die Tabelle `dzcp_counter_ips`
--
ALTER TABLE `dzcp_counter_ips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `idx_c_ips_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_counter_whoison`
--
ALTER TABLE `dzcp_counter_whoison`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip` (`ip`),
  ADD KEY `idx_c_who_online` (`online`),
  ADD KEY `idx_c_who_login` (`login`);

--
-- Indizes für die Tabelle `dzcp_cw_comments`
--
ALTER TABLE `dzcp_cw_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cw_comments_cw` (`cw`),
  ADD KEY `idx_cw_comments_datum` (`datum`),
  ADD KEY `idx_cw_comments_reg` (`reg`);

--
-- Indizes für die Tabelle `dzcp_downloads`
--
ALTER TABLE `dzcp_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_downloads_kat` (`kat`),
  ADD KEY `idx_downloads_hits` (`hits`),
  ADD KEY `idx_downloads_intern` (`intern`);

--
-- Indizes für die Tabelle `dzcp_download_kat`
--
ALTER TABLE `dzcp_download_kat`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_dsgvo`
--
ALTER TABLE `dzcp_dsgvo`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_dsgvo_log`
--
ALTER TABLE `dzcp_dsgvo_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `idx_dsgvo_log_date` (`date`);

--
-- Indizes für die Tabelle `dzcp_dsgvo_pers`
--
ALTER TABLE `dzcp_dsgvo_pers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_events`
--
ALTER TABLE `dzcp_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_events_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_forumkats`
--
ALTER TABLE `dzcp_forumkats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_f_kats_kid` (`kid`),
  ADD KEY `idx_f_kats_intern` (`intern`);

--
-- Indizes für die Tabelle `dzcp_forumposts`
--
ALTER TABLE `dzcp_forumposts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `date` (`date`),
  ADD KEY `idx_f_posts_kid` (`kid`),
  ADD KEY `idx_f_posts_reg` (`reg`);

--
-- Indizes für die Tabelle `dzcp_forumsubkats`
--
ALTER TABLE `dzcp_forumsubkats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_f_skats_sid` (`sid`),
  ADD KEY `idx_f_skats_pos` (`pos`);

--
-- Indizes für die Tabelle `dzcp_forumthreads`
--
ALTER TABLE `dzcp_forumthreads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kid` (`kid`),
  ADD KEY `lp` (`lp`),
  ADD KEY `topic` (`topic`),
  ADD KEY `first` (`first`),
  ADD KEY `idx_f_threads_sticky` (`sticky`),
  ADD KEY `idx_f_threads_closed` (`closed`),
  ADD KEY `idx_f_threads_global` (`global`),
  ADD KEY `idx_f_threads_t_reg` (`t_reg`);

--
-- Indizes für die Tabelle `dzcp_f_abo`
--
ALTER TABLE `dzcp_f_abo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_f_abo_fid` (`fid`),
  ADD KEY `idx_f_abo_user` (`user`),
  ADD KEY `idx_f_abo_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_f_access`
--
ALTER TABLE `dzcp_f_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `forum` (`forum`);

--
-- Indizes für die Tabelle `dzcp_gallery`
--
ALTER TABLE `dzcp_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gallery_datum` (`datum`),
  ADD KEY `idx_gallery_intern` (`intern`);

--
-- Indizes für die Tabelle `dzcp_gb`
--
ALTER TABLE `dzcp_gb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gb_datum` (`datum`),
  ADD KEY `idx_gb_reg` (`reg`),
  ADD KEY `idx_gb_public` (`public`);

--
-- Indizes für die Tabelle `dzcp_glossar`
--
ALTER TABLE `dzcp_glossar`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_ipcheck`
--
ALTER TABLE `dzcp_ipcheck`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `what` (`what`),
  ADD KEY `idx_ipcheck_user_id` (`user_id`),
  ADD KEY `idx_ipcheck_time` (`time`);

--
-- Indizes für die Tabelle `dzcp_links`
--
ALTER TABLE `dzcp_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_links_banner` (`banner`),
  ADD KEY `idx_links_hits` (`hits`);

--
-- Indizes für die Tabelle `dzcp_linkus`
--
ALTER TABLE `dzcp_linkus`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_messages`
--
ALTER TABLE `dzcp_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `an` (`an`),
  ADD KEY `idx_msg_von` (`von`),
  ADD KEY `idx_msg_datum` (`datum`),
  ADD KEY `idx_msg_see` (`see`),
  ADD KEY `idx_msg_sendnews` (`sendnews`),
  ADD KEY `idx_msg_senduser` (`senduser`);

--
-- Indizes für die Tabelle `dzcp_navi`
--
ALTER TABLE `dzcp_navi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`),
  ADD KEY `idx_navi_kat` (`kat`),
  ADD KEY `idx_navi_shown` (`shown`),
  ADD KEY `idx_navi_internal` (`internal`),
  ADD KEY `idx_navi_type` (`type`),
  ADD KEY `idx_navi_pos` (`pos`);

--
-- Indizes für die Tabelle `dzcp_navi_kats`
--
ALTER TABLE `dzcp_navi_kats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_news`
--
ALTER TABLE `dzcp_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_news_kat` (`kat`),
  ADD KEY `idx_news_autor` (`autor`),
  ADD KEY `idx_news_sticky` (`sticky`),
  ADD KEY `idx_news_intern` (`intern`),
  ADD KEY `idx_news_public` (`public`),
  ADD KEY `idx_news_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_newscomments`
--
ALTER TABLE `dzcp_newscomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_newscomments_news` (`news`),
  ADD KEY `idx_newscomments_datum` (`datum`),
  ADD KEY `idx_newscomments_reg` (`reg`);

--
-- Indizes für die Tabelle `dzcp_newskat`
--
ALTER TABLE `dzcp_newskat`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_partners`
--
ALTER TABLE `dzcp_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_permissions`
--
ALTER TABLE `dzcp_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_permissions_user` (`user`),
  ADD KEY `idx_permissions_pos` (`pos`);

--
-- Indizes für die Tabelle `dzcp_positions`
--
ALTER TABLE `dzcp_positions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_profile`
--
ALTER TABLE `dzcp_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_rankings`
--
ALTER TABLE `dzcp_rankings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rankings_squad` (`squad`),
  ADD KEY `idx_rankings_postdate` (`postdate`);

--
-- Indizes für die Tabelle `dzcp_server`
--
ALTER TABLE `dzcp_server`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_serverliste`
--
ALTER TABLE `dzcp_serverliste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_serverliste_checked` (`checked`),
  ADD KEY `idx_serverliste_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_settings`
--
ALTER TABLE `dzcp_settings`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `dzcp_shoutbox`
--
ALTER TABLE `dzcp_shoutbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shout_datum` (`datum`);

--
-- Indizes für die Tabelle `dzcp_sites`
--
ALTER TABLE `dzcp_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_slideshow`
--
ALTER TABLE `dzcp_slideshow`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dzcp_sponsoren`
--
ALTER TABLE `dzcp_sponsoren`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sponsoren_pos` (`pos`),
  ADD KEY `idx_sponsoren_hits` (`hits`);

--
-- Indizes für die Tabelle `dzcp_squads`
--
ALTER TABLE `dzcp_squads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_squads_shown` (`shown`),
  ADD KEY `idx_squads_status` (`status`),
  ADD KEY `idx_squads_team_show` (`team_show`);

--
-- Indizes für die Tabelle `dzcp_squaduser`
--
ALTER TABLE `dzcp_squaduser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_squaduser_user` (`user`),
  ADD KEY `idx_squaduser_squad` (`squad`);

--
-- Indizes für die Tabelle `dzcp_taktiken`
--
ALTER TABLE `dzcp_taktiken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_taktik_autor` (`autor`),
  ADD KEY `idx_taktik_datum` (`datum`),
  ADD KEY `idx_taktik_map` (`map`);

--
-- Indizes für die Tabelle `dzcp_userbuddys`
--
ALTER TABLE `dzcp_userbuddys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_buddys_user` (`user`),
  ADD KEY `idx_buddys_buddy` (`buddy`);

--
-- Indizes für die Tabelle `dzcp_usergallery`
--
ALTER TABLE `dzcp_usergallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usergallery_user` (`user`);

--
-- Indizes für die Tabelle `dzcp_usergb`
--
ALTER TABLE `dzcp_usergb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usergb_user` (`user`),
  ADD KEY `idx_usergb_datum` (`datum`),
  ADD KEY `idx_usergb_reg` (`reg`);

--
-- Indizes für die Tabelle `dzcp_userposis`
--
ALTER TABLE `dzcp_userposis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `squad` (`squad`),
  ADD KEY `idx_userpos_posi` (`posi`);

--
-- Indizes für die Tabelle `dzcp_users`
--
ALTER TABLE `dzcp_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pwd` (`pwd`),
  ADD KEY `time` (`time`),
  ADD KEY `bday` (`bday`),
  ADD KEY `user` (`user`),
  ADD KEY `idx_users_level` (`level`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_banned` (`banned`),
  ADD KEY `idx_users_regdatum` (`regdatum`),
  ADD KEY `idx_users_online` (`online`),
  ADD KEY `idx_users_country` (`country`),
  ADD KEY `idx_users_nick` (`nick`(50)),
  ADD KEY `idx_users_email` (`email`(100));

--
-- Indizes für die Tabelle `dzcp_userstats`
--
ALTER TABLE `dzcp_userstats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userstats_user` (`user`),
  ADD KEY `idx_userstats_lastvisit` (`lastvisit`);

--
-- Indizes für die Tabelle `dzcp_votes`
--
ALTER TABLE `dzcp_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_votes_datum` (`datum`),
  ADD KEY `idx_votes_closed` (`closed`),
  ADD KEY `idx_votes_intern` (`intern`),
  ADD KEY `idx_votes_menu` (`menu`),
  ADD KEY `idx_votes_von` (`von`);

--
-- Indizes für die Tabelle `dzcp_vote_results`
--
ALTER TABLE `dzcp_vote_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vote_results_vid` (`vid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `dzcp_acomments`
--
ALTER TABLE `dzcp_acomments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_artikel`
--
ALTER TABLE `dzcp_artikel`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_awards`
--
ALTER TABLE `dzcp_awards`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_away`
--
ALTER TABLE `dzcp_away`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_clankasse`
--
ALTER TABLE `dzcp_clankasse`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_clankasse_kats`
--
ALTER TABLE `dzcp_clankasse_kats`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `dzcp_clankasse_payed`
--
ALTER TABLE `dzcp_clankasse_payed`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_clanwars`
--
ALTER TABLE `dzcp_clanwars`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_counter`
--
ALTER TABLE `dzcp_counter`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_counter_ips`
--
ALTER TABLE `dzcp_counter_ips`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_counter_whoison`
--
ALTER TABLE `dzcp_counter_whoison`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT für Tabelle `dzcp_cw_comments`
--
ALTER TABLE `dzcp_cw_comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_downloads`
--
ALTER TABLE `dzcp_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_download_kat`
--
ALTER TABLE `dzcp_download_kat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `dzcp_dsgvo_log`
--
ALTER TABLE `dzcp_dsgvo_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_dsgvo_pers`
--
ALTER TABLE `dzcp_dsgvo_pers`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `dzcp_events`
--
ALTER TABLE `dzcp_events`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_forumkats`
--
ALTER TABLE `dzcp_forumkats`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `dzcp_forumposts`
--
ALTER TABLE `dzcp_forumposts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_forumsubkats`
--
ALTER TABLE `dzcp_forumsubkats`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `dzcp_forumthreads`
--
ALTER TABLE `dzcp_forumthreads`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_f_abo`
--
ALTER TABLE `dzcp_f_abo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_f_access`
--
ALTER TABLE `dzcp_f_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_gallery`
--
ALTER TABLE `dzcp_gallery`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_gb`
--
ALTER TABLE `dzcp_gb`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_glossar`
--
ALTER TABLE `dzcp_glossar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_ipcheck`
--
ALTER TABLE `dzcp_ipcheck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_links`
--
ALTER TABLE `dzcp_links`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_linkus`
--
ALTER TABLE `dzcp_linkus`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_messages`
--
ALTER TABLE `dzcp_messages`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_navi`
--
ALTER TABLE `dzcp_navi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT für Tabelle `dzcp_navi_kats`
--
ALTER TABLE `dzcp_navi_kats`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `dzcp_news`
--
ALTER TABLE `dzcp_news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_newscomments`
--
ALTER TABLE `dzcp_newscomments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_newskat`
--
ALTER TABLE `dzcp_newskat`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_partners`
--
ALTER TABLE `dzcp_partners`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `dzcp_permissions`
--
ALTER TABLE `dzcp_permissions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_positions`
--
ALTER TABLE `dzcp_positions`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `dzcp_profile`
--
ALTER TABLE `dzcp_profile`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `dzcp_rankings`
--
ALTER TABLE `dzcp_rankings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_server`
--
ALTER TABLE `dzcp_server`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_serverliste`
--
ALTER TABLE `dzcp_serverliste`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_shoutbox`
--
ALTER TABLE `dzcp_shoutbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_sites`
--
ALTER TABLE `dzcp_sites`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_slideshow`
--
ALTER TABLE `dzcp_slideshow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_sponsoren`
--
ALTER TABLE `dzcp_sponsoren`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `dzcp_squads`
--
ALTER TABLE `dzcp_squads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_squaduser`
--
ALTER TABLE `dzcp_squaduser`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_taktiken`
--
ALTER TABLE `dzcp_taktiken`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_userbuddys`
--
ALTER TABLE `dzcp_userbuddys`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_usergallery`
--
ALTER TABLE `dzcp_usergallery`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_usergb`
--
ALTER TABLE `dzcp_usergb`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_userposis`
--
ALTER TABLE `dzcp_userposis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dzcp_users`
--
ALTER TABLE `dzcp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_userstats`
--
ALTER TABLE `dzcp_userstats`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_votes`
--
ALTER TABLE `dzcp_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `dzcp_vote_results`
--
ALTER TABLE `dzcp_vote_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
