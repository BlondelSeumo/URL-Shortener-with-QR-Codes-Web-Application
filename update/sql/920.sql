UPDATE `settings` SET `value` = '{\"version\":\"9.2.0\", \"code\":\"920\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

ALTER TABLE `users` ADD `plan_expiry_reminder` TINYINT NOT NULL DEFAULT '0' AFTER `plan_trial_done`;

-- SEPARATOR --

alter table users add referral_key varchar(32) null after payment_subscription_id;

-- SEPARATOR --

alter table users add referred_by varchar(32) null after referral_key;

-- SEPARATOR --

alter table users add referred_by_has_converted tinyint default 0 null after referred_by;

-- SEPARATOR --

UPDATE users SET referral_key = MD5(CONCAT(NOW(), `email`, `date`));
