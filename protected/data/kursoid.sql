CREATE TABLE `site_bank_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `currency` int(10) DEFAULT NULL,
  `buy` varchar(6) DEFAULT NULL,
  `sale` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14683 DEFAULT CHARSET=utf8

CREATE TABLE `site_bank` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `site_bank_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `source_alias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38357 DEFAULT CHARSET=utf8

CREATE TABLE `site_bank_branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) DEFAULT NULL,
  `longtitude` varchar(16) DEFAULT NULL,
  `latitude` varchar(16) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `source_alias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92292 DEFAULT CHARSET=utf8

CREATE TABLE `site_bank_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `source_alias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33554 DEFAULT CHARSET=latin1