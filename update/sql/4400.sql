UPDATE `settings` SET `value` = '{\"version\":\"44.0.0\", \"code\":\"4400\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

-- X --
alter table users add currency varchar(4) null after language;

-- SEPARATOR --

-- X --
alter table plans drop codes_ids;

-- EXTENDED SEPARATOR --

-- X --
alter table codes modify days int unsigned null;

-- SEPARATOR --

-- X --
alter table codes modify discount int unsigned not null;

-- SEPARATOR --

-- X --
alter table codes modify quantity int unsigned default 1 not null;

-- SEPARATOR --

-- X --
alter table codes modify redeemed int unsigned default 0 not null;

-- SEPARATOR --

-- X --
alter table codes add plans_ids text null after redeemed;

