UPDATE `settings` SET `value` = '{\"version\":\"15.0.0\", \"code\":\"1500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('users', '{}');

-- SEPARATOR --

alter table pages add editor varchar(16) null after description;

-- SEPARATOR --

alter table links add is_verified tinyint default 0 null after end_date;

-- SEPARATOR --

alter table links modify location_url varchar(2048) collate utf8mb4_unicode_ci null;

-- SEPARATOR --

UPDATE `settings` SET `value` = '{"email_confirmation":true,"register_is_enabled":true,"auto_delete_inactive_users":0,"user_deletion_reminder":0,"blacklisted_domains":[]}' WHERE `key` = 'users';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'email_confirmation';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'register_is_enabled';

-- SEPARATOR --

UPDATE `settings` SET `value` = '{"title":"Replace me","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","se_indexing":true,"default_results_per_page":25,"default_order_type":"DESC"}' WHERE `key` = 'main';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'title';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_language';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_timezone';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_theme_style';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'index_url';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'terms_and_conditions_url';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'privacy_policy_url';
