UPDATE `settings` SET `value` = '{\"version\":\"48.0.0\", \"code\":\"4800\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table biolinks_blocks add last_datetime datetime null;

-- SEPARATOR --

alter table users add next_cleanup_datetime datetime default CURRENT_TIMESTAMP null after datetime;

