UPDATE `settings` SET `value` = '{\"version\":\"26.0.0\", \"code\":\"2600\"}' WHERE `key` = 'product_info';

-- EXTENDED SEPARATOR --

alter table taxes modify value float null;
