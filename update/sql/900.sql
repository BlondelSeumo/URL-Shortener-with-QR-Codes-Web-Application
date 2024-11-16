UPDATE `settings` SET `value` = '{\"version\":\"9.0.0\", \"code\":\"900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('opengraph', '');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('announcements', '{"id":"","content":"","show_logged_in":"","show_logged_out":""}');
