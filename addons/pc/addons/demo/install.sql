CREATE TABLE `#DB_PRE#pc_demo` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL,
  `content` text NOT NULL,
  `posttime` char(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;