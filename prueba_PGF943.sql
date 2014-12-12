-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-12-2014 a las 21:12:03
-- Versión del servidor: 5.5.40-cll
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prueba_PGF943`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_campaigns`
--

CREATE TABLE IF NOT EXISTS `fbshare_campaigns` (
  `campaignid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(100) unsigned NOT NULL,
  `campaignname` varchar(200) NOT NULL,
  `accountid` int(100) unsigned NOT NULL,
  `listid` int(100) unsigned NOT NULL,
  `messagespostedon` int(100) NOT NULL DEFAULT '0',
  `howtopostmessages` int(1) unsigned NOT NULL DEFAULT '0',
  `is_campaign_started` int(1) unsigned NOT NULL DEFAULT '0',
  `campaign_enabled` int(1) unsigned NOT NULL DEFAULT '1',
  `is_campaign_finished` int(1) unsigned NOT NULL DEFAULT '0',
  `createdon` datetime NOT NULL,
  `campaign_run` int(10) unsigned NOT NULL DEFAULT '0',
  `campaign_run_specific_day` date NOT NULL,
  `campaign_run_day` int(10) unsigned NOT NULL DEFAULT '1',
  `campaign_run_month_day` int(10) unsigned NOT NULL DEFAULT '1',
  `campaign_run_minutes_post_x_messages` int(5) unsigned NOT NULL DEFAULT '10',
  `campaign_run_day_post_x_messages` int(5) NOT NULL DEFAULT '1',
  `campaign_run_messages_to_post_minutes` int(10) unsigned NOT NULL DEFAULT '10',
  `campaign_run_messages_to_post_every_hour` int(10) unsigned NOT NULL DEFAULT '10',
  `campaign_run_messages_to_post_every_day` int(10) unsigned NOT NULL DEFAULT '10',
  `campaign_run_messages_to_post_every_week` int(10) unsigned NOT NULL DEFAULT '10',
  `campaign_repeat_type` int(10) unsigned NOT NULL DEFAULT '0',
  `campaign_last_time_run` datetime NOT NULL,
  `totalmessagespostedinthiscampaign` int(100) unsigned NOT NULL DEFAULT '0',
  `totalmessagesposted` int(100) unsigned NOT NULL DEFAULT '0',
  `howmanytimesthecampaignrun` int(5) unsigned NOT NULL DEFAULT '0',
  `posted_temp` int(5) unsigned NOT NULL DEFAULT '0',
  `isgroup` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_campaigns_messages`
--

CREATE TABLE IF NOT EXISTS `fbshare_campaigns_messages` (
  `messageid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `originalmessageid` int(100) unsigned NOT NULL DEFAULT '0',
  `originallistid` int(100) unsigned NOT NULL DEFAULT '0',
  `campaignid` int(100) unsigned NOT NULL,
  `userid` int(100) unsigned NOT NULL,
  `message` text NOT NULL,
  `lastpostedon` datetime NOT NULL,
  `nroftimesposted` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4969 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_fbaccounts`
--

CREATE TABLE IF NOT EXISTS `fbshare_fbaccounts` (
  `accountid` int(40) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(40) unsigned NOT NULL,
  `username` varchar(80) NOT NULL,
  `fb_description` varchar(250) NOT NULL,
  `fb_email` varchar(250) NOT NULL,
  `fb_password` varchar(250) NOT NULL,
  `fb_accountid` varchar(250) NOT NULL,
  `fb_username` varchar(250) NOT NULL,
  `accountstatus` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_fbpages`
--

CREATE TABLE IF NOT EXISTS `fbshare_fbpages` (
  `pageid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(100) unsigned NOT NULL,
  `accountid` int(100) unsigned NOT NULL,
  `fbpagedescription` varchar(250) NOT NULL,
  `fbpageurl` varchar(250) NOT NULL,
  `isgroup` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4106 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_general_settings`
--

CREATE TABLE IF NOT EXISTS `fbshare_general_settings` (
  `settingid` int(150) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(40) unsigned NOT NULL,
  `cron_nr_posts` varchar(100) NOT NULL,
  `cron_pause_between_posts` varchar(100) NOT NULL DEFAULT '0.2',
  `cron_send_notifications` int(10) unsigned NOT NULL DEFAULT '0',
  `cron_send_notifications_to` varchar(250) NOT NULL,
  `appid` varchar(255) NOT NULL,
  `appsecret` varchar(255) NOT NULL,
  PRIMARY KEY (`settingid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `fbshare_general_settings`
--

INSERT INTO `fbshare_general_settings` (`settingid`, `userid`, `cron_nr_posts`, `cron_pause_between_posts`, `cron_send_notifications`, `cron_send_notifications_to`, `appid`, `appsecret`) VALUES
(1, 10000, '15', '5', 0, 'anjimgo@gmail.com', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_group_campaigns`
--

CREATE TABLE IF NOT EXISTS `fbshare_group_campaigns` (
  `groupcampaignid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(100) NOT NULL,
  `campaignid` int(100) unsigned NOT NULL,
  `accountid` int(100) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`groupcampaignid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1537 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_logs`
--

CREATE TABLE IF NOT EXISTS `fbshare_logs` (
  `logid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(100) unsigned NOT NULL,
  `campaignid` int(100) unsigned NOT NULL,
  `logtext` text NOT NULL,
  `loggedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99710 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_messagelists`
--

CREATE TABLE IF NOT EXISTS `fbshare_messagelists` (
  `listid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(100) unsigned NOT NULL,
  `listname` varchar(100) NOT NULL,
  `listdescription` varchar(250) NOT NULL,
  PRIMARY KEY (`listid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_messages`
--

CREATE TABLE IF NOT EXISTS `fbshare_messages` (
  `messageid` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `listid` int(100) unsigned NOT NULL,
  `userid` int(100) unsigned NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8326 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbshare_users`
--

CREATE TABLE IF NOT EXISTS `fbshare_users` (
  `userid` int(40) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '',
  `userpassword` varchar(80) NOT NULL DEFAULT '',
  `useremailaddress` varchar(250) NOT NULL,
  `userfullname` varchar(250) NOT NULL,
  `usertype` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10001 ;

--
-- Volcado de datos para la tabla `fbshare_users`
--

INSERT INTO `fbshare_users` (`userid`, `username`, `userpassword`, `useremailaddress`, `userfullname`, `usertype`) VALUES
(10000, 'admin', 'c893bad68927b457dbed39460e6afd62', 'anjimgo@gmail.com', 'admin', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
