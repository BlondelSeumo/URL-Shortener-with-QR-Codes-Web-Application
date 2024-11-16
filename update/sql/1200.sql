UPDATE `settings` SET `value` = '{\"version\":\"12.0.0\", \"code\":\"1200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add codes_ids text null after taxes_ids;

-- SEPARATOR --

update plans set codes_ids = '[]';

-- SEPARATOR --

alter table plans change date datetime datetime not null;

-- SEPARATOR --

alter table users change date datetime datetime null;

-- SEPARATOR --

alter table pages change date datetime datetime null;

-- SEPARATOR --

alter table pages change last_date last_datetime datetime null;

-- SEPARATOR --

alter table users add payment_processor varchar(16) null after payment_subscription_id;

-- SEPARATOR --

update users set payment_processor = SUBSTRING_INDEX(`payment_subscription_id`, '###', 1) where payment_subscription_id <> '';

-- SEPARATOR --

update users set `payment_subscription_id` = SUBSTRING_INDEX(`payment_subscription_id`, '###', -1);

-- SEPARATOR --

alter table users modify email varchar(320) collate utf8_unicode_ci not null;

-- SEPARATOR --

alter table users modify name varchar(64) not null;

-- SEPARATOR --

alter table users add payment_total_amount float null after payment_processor;

-- SEPARATOR --

alter table users add payment_currency varchar(4) null after payment_total_amount;

-- SEPARATOR --

alter table users drop column facebook_id;

-- SEPARATOR --

alter table users modify type tinyint default 0 not null;

-- SEPARATOR --

alter table users change active status tinyint default 0 not null;

-- SEPARATOR --

alter table users modify last_user_agent varchar(1024) collate utf8_unicode_ci null;

-- SEPARATOR --

CREATE TABLE `data` (
`datum_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`biolink_block_id` int DEFAULT NULL,
`link_id` int DEFAULT NULL,
`project_id` int DEFAULT NULL,
`user_id` int NOT NULL,
`type` varchar(32) DEFAULT NULL,
`data` text,
`datetime` datetime NOT NULL,
PRIMARY KEY (`datum_id`),
UNIQUE KEY `datum_id` (`datum_id`),
KEY `link_id` (`link_id`),
KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
KEY `biolink_block_id` (`biolink_block_id`),
CONSTRAINT `data_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_4` FOREIGN KEY (`biolink_block_id`) REFERENCES `biolinks_blocks` (`biolink_block_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `qr_codes` (
`qr_code_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`project_id` int DEFAULT NULL,
`name` varchar(64) NOT NULL,
`type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`qr_code_logo` varchar(64) DEFAULT NULL,
`qr_code` varchar(64) NOT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`qr_code_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `qr_codes_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- EXTENDED SEPARATOR --

alter table taxes drop column internal_name;

-- SEPARATOR --

alter table codes add name varchar(64) null after code_id;

-- SEPARATOR --

alter table codes change date datetime datetime not null;

-- SEPARATOR --

alter table codes drop foreign key codes_ibfk_1;

-- SEPARATOR --

alter table codes drop column plan_id;

-- SEPARATOR --

alter table payments add business text null after billing;

-- SEPARATOR --

update payments set business = (select `value` FROM `settings` WHERE `key` = 'business');

-- SEPARATOR --

alter table payments change date datetime datetime null;

-- SEPARATOR --

alter table redeemed_codes change date datetime datetime not null;

-- SEPARATOR --

alter table payments drop column subscription_id;

-- SEPARATOR --

alter table payments drop column payer_id;

-- SEPARATOR --

alter table payments add plan text null after name;

