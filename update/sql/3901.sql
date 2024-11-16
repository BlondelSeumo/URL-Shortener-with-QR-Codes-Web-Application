UPDATE `settings` SET `value` = '{\"version\":\"39.0.1\", \"code\":\"3901\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('languages', '{}');
