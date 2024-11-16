UPDATE `settings` SET `value` = '{\"version\":\"16.0.0\", \"code\":\"1600\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

CREATE TABLE `biolinks_themes` (
`biolink_theme_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`biolink_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table links add biolink_theme_id int null after user_id;

-- SEPARATOR --

alter table links add constraint links_biolinks_themes_biolink_theme_id_fk foreign key (biolink_theme_id) references biolinks_themes (biolink_theme_id) on update cascade on delete set null;


-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('crypto_com', '{}');
