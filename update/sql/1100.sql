UPDATE `settings` SET `value` = '{\"version\":\"11.0.0\", \"code\":\"1100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add trial_days int unsigned not null default 0 after lifetime_price;

-- SEPARATOR --

alter table plans add description varchar(256) null after name;

-- SEPARATOR --

alter table plans add color varchar(16) null after taxes_ids;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('twitter', '');

-- SEPARATOR --

alter table users modify password varchar(128) collate utf8_unicode_ci null;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('coinbase', '');

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'plan_trial';
