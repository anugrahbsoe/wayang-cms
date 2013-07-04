SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
CREATE TABLE IF NOT EXISTS `wy_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(255) NOT NULL,
  `category_visible` varchar(5) NOT NULL,
  `category_date_add` datetime NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
INSERT INTO `wy_category` (`category_id`, `category_title`, `category_visible`, `category_date_add`) VALUES
(1, 'General', 'True', '2013-03-23 00:54:08');
CREATE TABLE IF NOT EXISTS `wy_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_name` varchar(255) NOT NULL,
  `comment_email` varchar(255) NOT NULL,
  `comment_url` varchar(255) NOT NULL,
  `comment_content` longtext NOT NULL,
  `comment_user_agent` text NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ipaddres` varchar(16) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_login_log` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `login_username` varchar(255) NOT NULL,
  `login_ipaddress` varchar(16) NOT NULL,
  `login_user_agent` varchar(255) NOT NULL,
  `login_date` datetime NOT NULL,
  `login_status` varchar(15) NOT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_date_add` datetime NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
INSERT INTO `wy_module` (`module_id`, `module_name`, `module_date_add`) VALUES
(1, 'contact', '2013-03-18 05:18:18');
CREATE TABLE IF NOT EXISTS `wy_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL,
  `page_visible` varchar(5) NOT NULL,
  `page_module` varchar(255) DEFAULT NULL,
  `page_content` longtext,
  `page_date_add` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
INSERT INTO `wy_page`(`page_id`, `page_name`, `page_visible`, `page_module`, `page_content`, `page_date_add`) VALUES (1,'Home','True','','',CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS `wy_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(255) NOT NULL,
  `post_category` varchar(255) NOT NULL,
  `post_content` longtext NOT NULL,
  `post_tag` varchar(255) DEFAULT NULL,
  `post_user` varchar(255) NOT NULL,
  `post_date` datetime NOT NULL,
  `post_ip` varchar(16) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_site_profile` (
  `site_name` varchar(255) NOT NULL,
  `site_tag_line` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `use_widget` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_full_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_level` varchar(20) NOT NULL,
  `user_date_register` datetime NOT NULL,
  `user_ipaddress_register` varchar(16) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_visitor` (
  `visitor_ipaddress` varchar(16) NOT NULL,
  `visitor_user_agent` varchar(255) NOT NULL,
  `visitor_date` date NOT NULL,
  `visitor_online` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visitor_online_status` int(2) NOT NULL,
  PRIMARY KEY (`visitor_ipaddress`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `wy_widget` (
  `widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_title` varchar(255) NOT NULL,
  `widget_visible` varchar(5) NOT NULL,
  `widget_module` varchar(255) NOT NULL,
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
INSERT INTO `wy_widget` (`widget_id`, `widget_title`, `widget_visible`, `widget_module`) VALUES
(1, 'Recent Post', 'True', 'recent_post'),
(2, 'Recent Comment', 'True', 'recent_comment'),
(3, 'Category', 'True', 'category'),
(4, 'Author', 'True', 'author'),
(5, 'Traffic Visitor', 'True', 'visitor');
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
