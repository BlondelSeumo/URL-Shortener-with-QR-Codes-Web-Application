UPDATE `settings` SET `value` = '{\"version\":\"23.0.0\", \"code\":\"2300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table links add last_datetime datetime null after is_enabled;

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('support', '{}');
