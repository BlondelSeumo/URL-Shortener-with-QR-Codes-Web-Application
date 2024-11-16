UPDATE `settings` SET `value` = '{\"version\":\"43.0.0\", \"code\":\"4300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

-- X --
call altum;

-- SEPARATOR --

-- X --
drop procedure altum;

-- SEPARATOR --

-- X --
alter table users add preferences text after timezone;

-- EXTENDED SEPARATOR --

-- X --
update payments set total_amount_default_currency = total_amount;


