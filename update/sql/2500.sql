UPDATE `settings` SET `value` = '{\"version\":\"25.0.0\", \"code\":\"2500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add source varchar(32) null;

-- SEPARATOR --

update users set source = 'direct';
