UPDATE `settings` SET `value` = '{\"version\":\"46.0.0\", \"code\":\"4600\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

CREATE TABLE `tools_usage` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tool_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE KEY `tools_usage_tool_id_idx` (`tool_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table plans add translations text null after description;

-- SEPARATOR --

alter table plans drop column monthly_price;

-- SEPARATOR --

alter table plans drop column annual_price;

-- SEPARATOR --

alter table plans drop column lifetime_price;

-- SEPARATOR --

alter table users modify plan_settings longtext null;

-- SEPARATOR --

alter table plans modify settings longtext not null;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('codes', '{}');
