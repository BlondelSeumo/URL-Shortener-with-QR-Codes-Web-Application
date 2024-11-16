UPDATE `settings` SET `value` = '{\"version\":\"40.0.0\", \"code\":\"4000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('microsoft', '{}');

-- SEPARATOR --

alter table pages add icon varchar(32) null after description;

-- EXTENDED SEPARATOR --

alter table redeemed_codes add type varchar(16) null after user_id;

-- SEPARATOR --

update redeemed_codes left join codes on `redeemed_codes`.`code_id` = `codes`.`code_id` set `redeemed_codes`.`type` = `codes`.`type`;
