UPDATE `settings` SET `value` = '{\"version\":\"42.0.0\", \"code\":\"4200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('sso', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('iyzico', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('midtrans', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('flutterwave', '{}');

-- SEPARATOR --

alter table plans add prices text null after description;

-- SEPARATOR --

update plans set prices = '{}';

-- SEPARATOR --

alter table users add currency varchar(4) null after language;

-- SEPARATOR --

CREATE PROCEDURE `altum`()
BEGIN

IF
(SELECT COUNT(`value`) FROM `settings` WHERE `key` = 'aix') = 1
THEN

alter table syntheses add format varchar(16) default 'mp3' after language;
alter table chats_assistants modify prompt varchar(2048) default null;

END IF;

END;

-- EXTENDED SEPARATOR --

alter table payments add total_amount_default_currency float null after total_amount;

-- SEPARATOR --

update payments set total_amount_default_currency = total_amount;
