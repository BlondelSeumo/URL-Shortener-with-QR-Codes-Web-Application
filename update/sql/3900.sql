UPDATE `settings` SET `value` = '{\"version\":\"39.0.0\", \"code\":\"3900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table links add splash_page_id bigint unsigned null after project_id;

-- SEPARATOR --

create index links_splash_page_id_index on links (splash_page_id);

-- SEPARATOR --

CREATE TABLE `splash_pages` (
`splash_page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`link_unlock_seconds` int unsigned DEFAULT '5',
`auto_redirect` tinyint unsigned DEFAULT '0',
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`splash_page_id`),
KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- SEPARATOR --

alter table links add constraint links_splash_pages_splash_page_id_fk foreign key (splash_page_id) references splash_pages (splash_page_id) on update cascade on delete set null;
