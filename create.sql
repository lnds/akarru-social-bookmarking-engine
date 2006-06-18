-- MySQL dump 10.9
--
-- Host: localhost    Database: blog_memes
-- ------------------------------------------------------
-- Server version	4.1.16-standard

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `debate`
--

DROP TABLE IF EXISTS `debate`;
CREATE TABLE `debate` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `position` int(11) default NULL,
  `post_id` int(11) default NULL,
  `comment` text,
  `date_posted` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_debate_meme` (`post_id`),
  KEY `idx_debate_user` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `post_cats`
--

DROP TABLE IF EXISTS `post_cats`;
CREATE TABLE `post_cats` (
  `cat_title` varchar(100) default NULL,
  `cat_desc` text,
  `ID` int(11) NOT NULL auto_increment,
  `lang` char(2) default 'es',
  `feed` text,
  `disabled` tinyint(1) default '0',
  PRIMARY KEY  (`ID`),
  KEY `idx_cat_title` (`cat_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `post_comments`
--

DROP TABLE IF EXISTS `post_comments`;
CREATE TABLE `post_comments` (
  `title` varchar(200) default NULL,
  `content` text,
  `user_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `date_posted` int(11) default NULL,
  `ID` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`ID`),
  KEY `idx_post_comments` (`post_id`),
  KEY `idx_user_comment` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `post_votes`
--

DROP TABLE IF EXISTS `post_votes`;
CREATE TABLE `post_votes` (
  `post_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `date_voted` int(11) default NULL,
  `ID` int(11) NOT NULL auto_increment,
  `ip_address` varchar(15) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `idx_post_votes_ip` (`ip_address`),
  KEY `idx_post_votes_user_id` (`user_id`),
  KEY `idx_post_votes_post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `title` varchar(200) default NULL,
  `content` text,
  `date_posted` int(11) default NULL,
  `category` int(11) default NULL,
  `url` varchar(200) default NULL,
  `submitted_user_id` int(11) default NULL,
  `ID` int(11) NOT NULL auto_increment,
  `trackback` varchar(200) default NULL,
  `date_promo` int(11) default NULL,
  `lang` varchar(2) default 'es',
  `clicks` int(11) default '0',
  `rank` int(11) default '0',
  `votes` int(11) NOT NULL default '0',
  `is_micro_content` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `icon` varchar(200) default NULL,
  `debate_0` int(11) default '0',
  `debate_neg` int(11) default '0',
  `debate_pos` int(11) default '0',
  `allows_debates` int(11) NOT NULL default '0',
  `promoted` int(11) NOT NULL default '0',
  `disabled` tinyint(1) default '0',
  `social_clicks` int(11) default '0',
  `views` int(11) NOT NULL default '0',
  `shares` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `idx_post_dates` (`date_posted`),
  KEY `idx_post_submiters` (`submitted_user_id`),
  KEY `idx_post_cats` (`category`),
  KEY `idx_post_url` (`url`),
  KEY `idx_promo` (`date_promo`),
  KEY `post_lang` (`lang`),
  KEY `idx_post_rank` (`rank`),
  KEY `idx_post_votes` (`votes`),
  KEY `idx_prooted` (`promoted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `ID` int(11) NOT NULL auto_increment,
  `tag` varchar(40) default NULL,
  `lang` char(2) default 'es',
  PRIMARY KEY  (`ID`),
  KEY `idx_tags` (`tag`),
  KEY `idx_tags_lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tags_posts`
--

DROP TABLE IF EXISTS `tags_posts`;
CREATE TABLE `tags_posts` (
  `tag_id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tag_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tags_user`
--

DROP TABLE IF EXISTS `tags_user`;
CREATE TABLE `tags_user` (
  `tag_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tag_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(100) default NULL,
  `password` varchar(32) default NULL,
  `email` varchar(200) default NULL,
  `join_date` int(11) default NULL,
  `admin` enum('0','1') default NULL,
  `ID` int(11) NOT NULL auto_increment,
  `website` varchar(200) default NULL,
  `blog` varchar(200) default NULL,
  `fullname` varchar(200) default NULL,
  `persistent_session` varchar(64) default NULL,
  `gravatar` varchar(32) default NULL,
  `strong_pass` blob,
  `send_newsletter` tinyint(1) default '1',
  `track_comments` tinyint(1) default '1',
  PRIMARY KEY  (`ID`),
  KEY `idx_username` (`username`),
  KEY `idx_user_email` (`email`),
  KEY `idx_persist_session` (`persistent_session`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `users_profiles`
--

DROP TABLE IF EXISTS `users_profiles`;
CREATE TABLE `users_profiles` (
  `user_id` int(11) default NULL,
  `full_name` varchar(100) default NULL,
  `website` varchar(200) default NULL,
  `blogsite` varchar(200) default NULL,
  `preferred_lang` char(2) default NULL,
  `id` int(11) NOT NULL auto_increment,
  `avatar` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

