CREATE TABLE IF NOT EXISTS `#__vmvendor_comments` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`parent` INT(11)  NOT NULL ,
	`vendor_userid` INT(11)  NOT NULL ,
	`title` VARCHAR(180)  NOT NULL ,
	`comment` TEXT NOT NULL ,
	`lang` VARCHAR(10)  NOT NULL ,
	`created_by` INT(11)  NOT NULL ,
	`created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`ordering` INT(11)  NOT NULL ,
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`reports` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL ,
	PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;