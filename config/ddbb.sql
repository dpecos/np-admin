-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 02, 2008 at 03:05 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `npadmin_groups`
--

CREATE TABLE IF NOT EXISTS `npadmin_groups` (
  `group_name` varchar(40) NOT NULL,
  `description` varchar(150) default NULL,
  PRIMARY KEY  (`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `npadmin_groups`
--

INSERT INTO `npadmin_groups` (`group_name`, `description`) VALUES
('Administrators', 'NP-Admin administrator users');

-- --------------------------------------------------------

--
-- Table structure for table `npadmin_menus`
--

CREATE TABLE IF NOT EXISTS `npadmin_menus` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  `text` varchar(60) default NULL,
  `url` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `npadmin_menus`
--

INSERT INTO `npadmin_menus` (`id`, `parent_id`, `order`, `text`, `url`) VALUES
(1, 0, 0, 'Menu', 'index.php'),
(2, 0, 1, 'Management', NULL),
(3, 2, 0, 'Users', 'panels/userPanel.php'),
(4, 2, 1, 'Groups', 'panels/groupPanel.php'),
(5, 2, 3, 'Menus', 'panels/menuPanel.php'),
(6, 0, 2, 'Configuration', NULL),
(7, 6, 0, 'NP-Admin settings', 'panels/settingsPanel.php'),
(8, 2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `npadmin_menus_groups`
--

CREATE TABLE IF NOT EXISTS `npadmin_menus_groups` (
  `menu_id` int(11) NOT NULL,
  `group_name` varchar(60) NOT NULL,
  PRIMARY KEY  (`menu_id`,`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `npadmin_menus_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `npadmin_settings`
--

CREATE TABLE IF NOT EXISTS `npadmin_settings` (
  `name` varchar(60) NOT NULL,
  `value` varchar(100) default NULL,
  `default_value` varchar(100) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `npadmin_settings`
--

INSERT INTO `npadmin_settings` (`name`, `value`, `default_value`) VALUES
('BASE_URL', '/~dani/np-admin', '/np-admin'),
('YUI_PATH', 'http://yui.yahooapis.com/2.5.2/build/', 'lib/yui_2.5.2/build/');

-- --------------------------------------------------------

--
-- Table structure for table `npadmin_users`
--

CREATE TABLE IF NOT EXISTS `npadmin_users` (
  `user` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `real_name` varchar(60) default NULL,
  `email` varchar(60) default NULL,
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `npadmin_users`
--

INSERT INTO `npadmin_users` (`user`, `password`, `creation_date`, `real_name`, `email`) VALUES
('admin', 'admin', '2008-07-20 14:18:38', 'NP-Admin main user', '-'),
('dpecos', 'dpecos', '2008-07-31 23:54:05', 'Daniel Pecos', '');

-- --------------------------------------------------------

--
-- Table structure for table `npadmin_users_groups`
--

CREATE TABLE IF NOT EXISTS `npadmin_users_groups` (
  `user` varchar(20) NOT NULL,
  `group_name` varchar(40) NOT NULL,
  PRIMARY KEY  (`user`,`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `npadmin_users_groups`
--

INSERT INTO `npadmin_users_groups` (`user`, `group_name`) VALUES
('admin', 'Administrators');
