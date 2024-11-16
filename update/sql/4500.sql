UPDATE `settings` SET `value` = '{\"version\":\"45.0.0\", \"code\":\"4500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table qr_codes add qr_code_background varchar(40) null after qr_code_logo;

-- SEPARATOR --

UPDATE `settings` SET `value` = '{"light_is_enabled": false, "dark_is_enabled": false}' WHERE `key` = 'theme';

