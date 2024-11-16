UPDATE `settings` SET `value` = '{\"version\":\"20.0.0\", \"code\":\"2000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table pages_categories add language varchar(32) null;

-- SEPARATOR --

alter table pages add language varchar(32) null after position;

-- SEPARATOR --

create index pages_url_language_index on pages (url, language);

-- SEPARATOR --

create index pages_categories_url_language_index on pages_categories (url, language);

-- SEPARATOR --

alter table users add anti_phishing_code varchar(8) null after twofa_secret;
