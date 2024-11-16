UPDATE `settings` SET `value` = '{\"version\":\"41.0.0\", \"code\":\"4100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add os_name varchar(16) null after city_name;

-- SEPARATOR --

alter table users add browser_name varchar(32) null after city_name;

-- SEPARATOR --

alter table users add browser_language varchar(32) null after city_name;

-- SEPARATOR --

alter table users add device_type varchar(16) null after city_name;

-- SEPARATOR --

alter table users drop column last_user_agent;

-- SEPARATOR --

alter table users_logs add browser_name varchar(32) null;

-- SEPARATOR --

alter table users_logs add browser_language varchar(32) null;

-- SEPARATOR --

alter table qr_codes add embedded_data text null after settings;

-- SEPARATOR --

CREATE PROCEDURE `altum`()
BEGIN

IF
(SELECT COUNT(`value`) FROM `settings` WHERE `key` = 'aix') = 1
THEN

    update images set api = 'dall_e_2' where api = 'dall-e-2';

    alter table chats_messages add image varchar(40) null after content;

    CREATE TABLE `syntheses` (
    `synthesis_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int DEFAULT NULL,
    `project_id` int DEFAULT NULL,
    `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `input` text COLLATE utf8mb4_unicode_ci,
    `file` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `language` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `voice_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `voice_engine` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `voice_gender` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `settings` text COLLATE utf8mb4_unicode_ci,
    `characters` int unsigned DEFAULT '0',
    `api_response_time` int unsigned DEFAULT NULL,
    `datetime` datetime DEFAULT NULL,
    `last_datetime` datetime DEFAULT NULL,
    PRIMARY KEY (`synthesis_id`),
    KEY `user_id` (`user_id`),
    KEY `project_id` (`project_id`),
    CONSTRAINT `syntheses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `syntheses_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    alter table users add aix_syntheses_current_month bigint unsigned default 0 after source;

    alter table users add aix_synthesized_characters_current_month bigint unsigned default 0 after source;

END IF;

END;

-- SEPARATOR --

call altum;

-- SEPARATOR --

drop procedure altum;
