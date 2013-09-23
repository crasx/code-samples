-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 24, 2010 at 01:28 AM
-- Server version: 5.1.41
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cj_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `min`, `max`) VALUES
(1, 'Curiosity', 0, 100),
(2, 'Sufficient data', 0, 100),
(3, 'Creativity', 0, 100),
(4, 'Neatness', 0, 100),
(6, 'Hypothesis', 0, 200),
(7, 'Conclusion', 0, 200),
(8, 'Purpose', 0, 200),
(9, 'Procedure', 0, 200),
(10, 'Accuracy', 0, 100);

-- --------------------------------------------------------

--
-- Table structure for table `checked`
--

CREATE TABLE IF NOT EXISTS `checked` (
  `competition` int(11) NOT NULL,
  `contestant` int(11) NOT NULL,
  `line` tinyint(1) NOT NULL,
  `called` tinyint(1) NOT NULL,
  PRIMARY KEY (`competition`,`contestant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `checked`
--


-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE IF NOT EXISTS `competitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `registration` tinyint(1) NOT NULL DEFAULT '-1',
  `judging` tinyint(1) NOT NULL DEFAULT '-1',
  `mc` tinyint(1) NOT NULL DEFAULT '-1',
  `rss` tinyint(1) NOT NULL DEFAULT '-1',
  `rssR` int(11) NOT NULL DEFAULT '-1',
  `group` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`id`, `order`, `name`, `registration`, `judging`, `mc`, `rss`, `rssR`, `group`, `public`, `description`) VALUES
(6, 1, 'The science fair', 1, 1, 1, -1, -1, 1, 0, 'Main event');

-- --------------------------------------------------------

--
-- Table structure for table `competition_categories`
--

CREATE TABLE IF NOT EXISTS `competition_categories` (
  `competition` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  UNIQUE KEY `competition` (`competition`,`category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competition_categories`
--

INSERT INTO `competition_categories` (`competition`, `category`) VALUES
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `competition_custom`
--

CREATE TABLE IF NOT EXISTS `competition_custom` (
  `competition` int(11) NOT NULL,
  `custom` int(11) NOT NULL,
  UNIQUE KEY `competition` (`competition`,`custom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competition_custom`
--

INSERT INTO `competition_custom` (`competition`, `custom`) VALUES
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `competition_groups`
--

CREATE TABLE IF NOT EXISTS `competition_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `register` tinyint(1) NOT NULL,
  `judge` tinyint(1) NOT NULL,
  `mc` tinyint(1) NOT NULL DEFAULT '0',
  `rss` tinyint(1) NOT NULL,
  `rssR` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `competition_groups`
--

INSERT INTO `competition_groups` (`id`, `name`, `register`, `judge`, `mc`, `rss`, `rssR`, `public`) VALUES
(1, 'Monday', 1, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(128) NOT NULL,
  `value` varchar(128) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`key`, `value`) VALUES
('dbversion', '3'),
('name', 'Science fair'),
('scriptversion', '1');

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

CREATE TABLE IF NOT EXISTS `contestants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `registered` int(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`id`, `name`, `registered`) VALUES
(1, 'Michael', 2010),
(2, 'Ashley', 2010),
(3, 'James', 2010);

-- --------------------------------------------------------

--
-- Table structure for table `custom`
--

CREATE TABLE IF NOT EXISTS `custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `mc` tinyint(1) NOT NULL DEFAULT '0',
  `judge` tinyint(1) NOT NULL DEFAULT '0',
  `rss` tinyint(1) NOT NULL DEFAULT '0',
  `rssR` int(10) NOT NULL DEFAULT '0',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `custom`
--

INSERT INTO `custom` (`id`, `name`, `required`, `mc`, `judge`, `rss`, `rssR`, `public`) VALUES
(1, 'Question', 1, 1, 1, 1, 0, 0),
(2, 'Hypothesis', 1, 1, 1, 1, 0, 0),
(3, 'Procedure', 1, 1, 1, 1, 0, 0),
(4, 'Research', 1, 1, 1, 1, 0, 0),
(5, 'Conclusion', 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_values`
--

CREATE TABLE IF NOT EXISTS `custom_values` (
  `contestant` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `val` varchar(512) NOT NULL,
  PRIMARY KEY (`contestant`,`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `custom_values`
--

INSERT INTO `custom_values` (`contestant`, `field`, `val`) VALUES
(3, 5, 'After one week, both the plants with vitamins where in better shape. This proves that vitamins help a plants growth.'),
(3, 3, 'First, take all the pots and label them. Label them with letters A, B, C, and D. Pot A and B are the ones that you will not be adding any types of vitamins too. Pot C is where you will be putting the first type of vitamin so label it with Vitamin A. Pot D is where you will be putting the second type of vitamin so label it with Vitamin B. Put all four pots in an area with sufficient sunlight. Water all the plants daily. When watering samples C and D, make a mixture of the vitamin and the water and then water'),
(3, 4, 'Much research has been done to see if vitamins affect the growth of a plant. Many farmers use fertilizer to grow their crops so questions have rose on whether or not vitamins help plants grow. We are going to make a couple of experiments that show whether or not vitamins affect the growth of a plant.'),
(3, 2, 'We think that the vitamins will help the plants growth since they help people.'),
(3, 1, 'Will vitamins affect the growth of a plant?'),
(2, 5, ' Adding some Sulfuric Acid as electrolyte will increase conductivity of water and creation of Hydrogen and Oxigen gases.'),
(2, 3, 'Fillup Â¼ of beaker with clear watter, secure two test tubes filled with water in the beaker in a way that test tubes are up-side down over the beaker. Mount the wires or electrodes that you have prepared and then connect the electricity. Check the produced hydrogen and oxygen gasses in five minutes. Repeat the test with different electrodes and different amounts of electrolytes and record the results in the table below. You may want to repeat the experiment with different electrods. '),
(2, 4, 'Electrolisis is Chemical change, especially decomposition, produced in an electrolyte by an electric current. Electrolytes dissolve by dissociation. That is when the molecules of the substance break down into charged particles called ions. An ion with a negative charge is called an anion because it is drawn through the solution to the positive charge on the anode. A particle with a positive charge is called a cation. It moves through the solution to the cathode. '),
(2, 2, 'Adding some Sulfuric Acid as electrolyte will increase conductivity of water and creation of Hydrogen and Oxigen gases.'),
(2, 1, 'How can you perform Electrolysis of water to produce Hydrogen and Oxygen?'),
(1, 5, 'In conclusion, Mylanta antacid (extra strength) had a pH closest to 7.) When reacted with the 0.05 M HCI concentration for Trial 1 and Rolaids (extra strength) had a pH closest to 7.0 for Trial 2.'),
(1, 3, 'Five brands of antacid were mixed with 0.05 M HCI, 0.10 HCI, and 0.15 M HCI. Then the pH of the resulting solutions was tested with a pH meter and pH paper. The solution with a pH closest to neutral pH of 7.0 is the most effective antacid.'),
(1, 4, 'There was some disagreement with the results that we confounded. As seen in the dendogram, the sturgeon is represented as more closely related to the rest of the bony fishes than that of the Trout. But, likewise, the trout is more closely related to the bony fishes than that of the Sturgeon, which obviously shows some incongruities. Also, the buffaloes are shown grouping with the carps and not of the suckers from which they are traditionally placed. The other results from our tests are shown in agreement wi'),
(1, 2, 'The solution with a pH closest to neutral pH of 7.0 is the most effective antacid. '),
(1, 1, 'The purpose of this experiment is to determine which antacid works best at neutralizing hydrochloric acid (HCI), which is found in the stomach. ');

-- --------------------------------------------------------

--
-- Table structure for table `registers`
--

CREATE TABLE IF NOT EXISTS `registers` (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `contestant` int(11) NOT NULL,
  `competition` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `contestant_2` (`contestant`,`competition`),
  KEY `competition` (`competition`),
  KEY `contestant` (`contestant`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `registers`
--

INSERT INTO `registers` (`id`, `contestant`, `competition`, `number`, `enabled`) VALUES
(1, 3, 6, 1, 1),
(2, 2, 6, 2, 1),
(3, 1, 6, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rss`
--

CREATE TABLE IF NOT EXISTS `rss` (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `date` int(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rss`
--


-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE IF NOT EXISTS `scores` (
  `competition` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `judge` int(11) NOT NULL,
  `contestant` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  UNIQUE KEY `competition` (`competition`,`category`,`judge`,`contestant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scores`
--


-- --------------------------------------------------------

--
-- Table structure for table `style`
--

CREATE TABLE IF NOT EXISTS `style` (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `style` varchar(64) NOT NULL,
  `name` varchar(256) NOT NULL,
  `file` varchar(256) NOT NULL,
  `disablefile` tinyint(1) NOT NULL,
  `color` varchar(6) NOT NULL,
  `disablecolor` tinyint(1) NOT NULL,
  `text` varchar(6) NOT NULL,
  `disabletext` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `style`
--

INSERT INTO `style` (`id`, `style`, `name`, `file`, `disablefile`, `color`, `disablecolor`, `text`, `disabletext`) VALUES
(1, 'css', 'logo', 'img/logo.gif', 0, '', 1, '', 1),
(2, 'css', 'body', 'img/bg.jpg', 0, '009900', 0, 'ffffff', 0),
(5, 'css', 'Menu fill', '0.png', 0, '', 1, '000000', 0),
(6, 'css', 'a', '', 1, '', 1, 'ff0000', 0),
(7, 'css', 'a:hover', '', 1, '', 1, 'ff0000', 0),
(8, 'css', 'Menu fill hover', '', 1, '', 1, 'ff6600', 0),
(9, 'iphone', 'body', '', 1, 'e2c486', 0, '000000', 0),
(10, 'iphone', 'li', '', 1, 'cfa54a', 0, '000000', 1),
(11, 'iphone', 'td', '', 1, 'cfa54a', 0, '000000', 1),
(12, 'iphone', 'a', '', 1, '', 1, '0000ff', 0),
(13, 'iphone', 'a hover', '', 1, 'ff6600', 0, '000000', 0),
(14, 'iphone', 'a holder', '', 1, 'cfa54b', 0, '000000', 0),
(15, 'css', 'main', '', 1, '0000ff', 0, '00ff00', 0),
(17, 'css', 'inner', '', 1, 'ff0000', 0, '0000ff', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` tinyint(1) NOT NULL,
  `register` tinyint(1) NOT NULL,
  `mc` tinyint(1) NOT NULL,
  `judge` tinyint(1) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `admin`, `register`, `mc`, `judge`, `name`, `username`, `contact`, `password`) VALUES
(1, 1, 1, 1, 1, 'Admin', 'admin', '', '21232f297a57a5a743894a0e4a801fc3');
