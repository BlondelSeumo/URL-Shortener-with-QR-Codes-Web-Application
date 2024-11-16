UPDATE `settings` SET `value` = '{\"version\":\"10.0.0\", \"code\":\"1000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users_logs change date datetime datetime null;

-- SEPARATOR --

alter table users_logs drop column public;

-- SEPARATOR --

alter table users_logs add device_type varchar(16) null after ip;

-- SEPARATOR --

alter table users_logs add country_code varchar(8) null after device_type;

-- SEPARATOR --

alter table users_logs add os_name varchar(16) null after device_type;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('google', '');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('main', '');
