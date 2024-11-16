UPDATE `settings` SET `value` = '{\"version\":\"33.0.0\", \"code\":\"3300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

UPDATE biolinks_blocks SET type = 'tiktok_video' WHERE `type` = 'tiktok';

-- SEPARATOR --

UPDATE biolinks_blocks SET type = 'email_collector' WHERE `type` = 'mail';

-- SEPARATOR --

UPDATE data SET type = 'email_collector' WHERE `type` = 'mail';
