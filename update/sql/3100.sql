UPDATE `settings` SET `value` = '{\"version\":\"31.0.0\", \"code\":\"3100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('mercadopago', '{}');

-- SEPARATOR --

CREATE PROCEDURE `altum`()
BEGIN

   	IF
        (SELECT COUNT(`value`) FROM `settings` WHERE `key` = 'aix') = 1
	THEN
        alter table images add variants_ids text null after project_id;

        alter table images add artist varchar(128) null after image;

        alter table images add lighting varchar(128) null after image;

        alter table images add style varchar(128) null after image;
        alter table images add mood varchar(128) null after image;

        alter table users add aix_transcriptions_current_month bigint unsigned default 0 after source;

        CREATE TABLE `transcriptions` (
        `transcription_id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int DEFAULT NULL,
        `project_id` int DEFAULT NULL,
        `name` varchar(64) DEFAULT NULL,
        `input` text,
        `content` text,
        `words` int unsigned DEFAULT NULL,
        `language` varchar(32) DEFAULT NULL,
        `settings` text,
        `datetime` datetime DEFAULT NULL,
        `last_datetime` datetime DEFAULT NULL,
        PRIMARY KEY (`transcription_id`),
        KEY `user_id` (`user_id`),
        KEY `project_id` (`project_id`),
        CONSTRAINT `transcriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `transcriptions_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
END IF;

END;

-- SEPARATOR --

call altum;

-- SEPARATOR --

drop procedure altum;
