CREATE TABLE IF NOT EXISTS `#__vmvendor_vendorratings` (
  `vendor_rating_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `percent` int(3) NOT NULL DEFAULT '0',
  `voter_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_rating_id`),
  UNIQUE KEY (`vendor_rating_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;