UPDATE `settings` SET `value` = '{\"version\":\"29.0.0\", \"code\":\"2900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add city_name varchar(32) null after country;

-- SEPARATOR --

alter table users add continent_code varchar(8) null after city_name;

-- SEPARATOR --

alter table users_logs add city_name varchar(32) null after country_code;

-- SEPARATOR --

alter table users_logs add continent_code varchar(8) null after city_name;
