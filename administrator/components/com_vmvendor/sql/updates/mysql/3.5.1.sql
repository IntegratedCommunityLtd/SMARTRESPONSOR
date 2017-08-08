ALTER TABLE `#__vmvendor_vendoraddress` ADD `state_name` VARCHAR(180) AFTER `virtuemart_state_id`;
ALTER TABLE `#__vmvendor_vendoraddress` ADD `country_name` VARCHAR(180) AFTER `virtuemart_country_id`;
ALTER TABLE `#__vmvendor_vendoraddress` ADD `latitude` float(10,6)	NOT NULL DEFAULT '255' AFTER `country_name`;
ALTER TABLE `#__vmvendor_vendoraddress` ADD `longitude` float(10,6)	NOT NULL DEFAULT '255' AFTER `latitude`;