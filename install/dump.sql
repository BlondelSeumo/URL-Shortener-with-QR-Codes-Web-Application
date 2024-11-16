CREATE TABLE `users` (
`user_id` int NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`password` varchar(128) DEFAULT NULL,
`name` varchar(64) NOT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`lost_password_code` varchar(32) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
`has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text,
`plan_trial_done` tinyint(4) DEFAULT '0',
`plan_expiry_reminder` tinyint(4) DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint(4) DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`currency` varchar(4) DEFAULT NULL,
`timezone` varchar(32) DEFAULT 'UTC',
`preferences` text,
`extra` text,
`datetime` datetime DEFAULT NULL,
`next_cleanup_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
`ip` varchar(64) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
`source` varchar(32) DEFAULT 'direct',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`, `preferences`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode',1,1,'custom','2030-01-01 12:00:00', '{"url_minimum_characters":1,"url_maximum_characters":256,"additional_domains":[],"custom_url":true,"deep_links":true,"no_ads":true,"removable_branding":true,"custom_branding":true,"statistics":true,"custom_backgrounds":true,"verified":false,"temporary_url_is_enabled":true,"cloaking_is_enabled":true,"app_linking_is_enabled":true,"seo":true,"utm":true,"fonts":true,"password":true,"sensitive_content":true,"leap_link":true,"api_is_enabled":true,"dofollow_is_enabled":true,"biolink_blocks_limit":-1,"projects_limit":-1,"splash_pages_limit":-1,"pixels_limit":-1,"qr_codes_limit":-1,"biolinks_limit":-1,"links_limit":-1,"files_limit":-1,"vcards_limit":-1,"events_limit":-1,"static_limit":-1,"domains_limit":-1,"payment_processors_limit":-1,"signatures_limit":-1,"teams_limit":-1,"team_members_limit":-1,"affiliate_commission_percentage":50,"track_links_retention":365,"custom_css_is_enabled":true,"custom_js_is_enabled":true,"enabled_biolink_blocks":{"link":true,"heading":true,"paragraph":true,"avatar":true,"image":true,"socials":true,"email_collector":true,"threads":true,"soundcloud":true,"spotify":true,"youtube":true,"twitch":true,"vimeo":true,"tiktok_video":true,"paypal":true,"phone_collector":true,"map":true,"applemusic":true,"tidal":true,"anchor":true,"twitter_profile":true,"twitter_tweet":true,"pinterest_profile":true,"instagram_media":true,"snapchat":true,"rss_feed":true,"custom_html":true,"vcard":true,"image_grid":true,"divider":true,"list":true,"alert":true,"tiktok_profile":true,"big_link":true,"faq":true,"typeform":true,"discord":true,"facebook":true,"reddit":true,"audio":true,"video":true,"file":true,"countdown":true,"cta":true,"external_item":true,"share":true,"youtube_feed":true,"timeline":true,"review":true,"image_slider":true,"pdf_document":true,"markdown":true,"rumble":true,"telegram":true,"donation":true,"product":true,"service":true},"documents_model":"gpt-4-1106-preview","documents_per_month_limit":-1,"words_per_month_limit":-1,"images_api":"dall-e-3","images_per_month_limit":-1,"transcriptions_per_month_limit":-1,"transcriptions_file_size_limit":2,"chats_model":"gpt-4-vision-preview","chats_per_month_limit":-1,"chat_messages_per_chat_limit":-1,"chat_image_size_limit":2,"syntheses_api":"openai_audio","syntheses_per_month_limit":-1,"synthesized_characters_per_month_limit":-1,"force_splash_page_on_link":false,"force_splash_page_on_biolink":false,"force_splash_page_on_file":false,"force_splash_page_on_static":false,"force_splash_page_on_vcard":false,"force_splash_page_on_event":false}', NOW(),'',NOW(), '{"default_results_per_page":100,"default_order_type":"DESC","links_default_order_by":"link_id","qr_codes_default_order_by":"qr_code_id","openai_api_key":"","clipdrop_api_key":""}');

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`translations` text NOT NULL,
`prices` text NOT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` longtext NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint(4) NOT NULL,
`order` int(10) unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`),
KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`pages_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`keywords` varchar(256) CHARACTER SET utf8mb4 DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`type` varchar(16) DEFAULT '',
`position` varchar(16) NOT NULL DEFAULT '',
`language` varchar(32) DEFAULT NULL,
`open_in_new_tab` tinyint DEFAULT '1',
`order` int DEFAULT '0',
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
KEY `pages_is_published_index` (`is_published`),
KEY `pages_language_index` (`language`),
CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/66biolinks', 'Built with 66biolinks', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `blog_posts_categories` (
`blog_posts_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_posts_category_id`),
KEY `url` (`url`),
KEY `blog_posts_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `blog_posts` (
`blog_post_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`blog_posts_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`keywords` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image_description` varchar(256) DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`language` varchar(32) DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_post_id`),
KEY `blog_post_id_index` (`blog_post_id`),
KEY `blog_post_url_index` (`url`),
KEY `blog_posts_category_id` (`blog_posts_category_id`),
KEY `blog_posts_is_published_index` (`is_published`),
KEY `blog_posts_language_index` (`language`),
CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_posts_category_id`) REFERENCES `blog_posts_categories` (`blog_posts_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`views` bigint unsigned DEFAULT '0',
`clicks` bigint unsigned DEFAULT '0',
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`broadcast_id` bigint unsigned DEFAULT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`target` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `broadcast_id` (`broadcast_id`),
KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
`internal_notification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`for_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`from_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`is_read` tinyint unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`read_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`internal_notification_id`),
KEY `user_id` (`user_id`),
KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

CREATE TABLE `projects` (
`project_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`name` varchar(64) NOT NULL DEFAULT '',
`color` varchar(16) DEFAULT '#000',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`project_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`)
VALUES
('main', '{"title":"Your title","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","ai_scraping_is_allowed":true,"se_indexing":true,"display_index_plans":true,"display_index_testimonials":true,"display_index_faq":true,"display_index_latest_blog_posts":true,"default_results_per_page":100,"default_order_type":"DESC","auto_language_detection_is_enabled":true,"blog_is_enabled":false,"api_is_enabled":true,"theme_style_change_is_enabled":true,"logo_light":"","logo_dark":"","logo_email":"","opengraph":"","favicon":"","openai_api_key":"","openai_model":"gpt-4o","force_https_is_enabled":false,"broadcasts_statistics_is_enabled":true,"breadcrumbs_is_enabled":true,"display_pagination_when_no_pages":false,"chart_cache":12,"chart_days":30}'),
('languages', '{"english":{"status":"active"}}'),
('users', '{"email_confirmation":false,"welcome_email_is_enabled":false,"register_is_enabled":true,"register_only_social_logins":false,"register_social_login_require_password":false,"register_display_newsletter_checkbox":false,"login_rememberme_checkbox_is_checked":true,"login_rememberme_cookie_days":90,"auto_delete_unconfirmed_users":3,"auto_delete_inactive_users":30,"user_deletion_reminder":0,"blacklisted_domains":[],"blacklisted_countries":[],"login_lockout_is_enabled":true,"login_lockout_max_retries":3,"login_lockout_time":10,"lost_password_lockout_is_enabled":true,"lost_password_lockout_max_retries":3,"lost_password_lockout_time":10,"resend_activation_lockout_is_enabled":true,"resend_activation_lockout_max_retries":3,"resend_activation_lockout_time":10,"register_lockout_is_enabled":true,"register_lockout_max_registrations":3,"register_lockout_time":10}'),
('ads', '{"ad_blocker_detector_is_enabled":true,"ad_blocker_detector_lock_is_enabled":false,"ad_blocker_detector_delay":5,"header":"","footer":"","header_biolink":"","footer_biolink":"","header_splash":"","footer_splash":""}'),
('captcha', '{"type":"basic","recaptcha_public_key":"","recaptcha_private_key":"","login_is_enabled":0,"register_is_enabled":0,"lost_password_is_enabled":0,"resend_activation_is_enabled":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{"emails":"","new_user":false,"delete_user":false,"new_payment":false,"new_domain":false,"new_affiliate_withdrawal":false,"contact":false}'),
('internal_notifications', '{"users_is_enabled":true,"admins_is_enabled":true,"new_user":true,"delete_user":true,"new_newsletter_subscriber":true,"new_payment":true,"new_affiliate_withdrawal":true}'),
('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_search_widget_is_enabled":false,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}'),
('sso', '{"is_enabled":true,"display_menu_items":true,"websites":{}}'),
('facebook', '{"is_enabled":false,"app_id":"","app_secret":""}'),
('google', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('twitter', '{"is_enabled":false,"consumer_api_key":"","consumer_api_secret":""}'),
('discord', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('linkedin', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('microsoft', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('plan_custom', '{"plan_id":"custom","name":"Custom","description":"Contact us for enterprise pricing.","price":"Custom","custom_button_url":"mailto:sample@example.com","color":null,"status":2,"settings":{}}'),
('plan_free', '{"plan_id":"free","name":"Free","days":null,"status":1,"settings":{"additional_global_domains":true,"custom_url":true,"deep_links":true,"no_ads":true,"removable_branding":true,"custom_branding":true,"custom_colored_links":true,"statistics":true,"custom_backgrounds":true,"verified":true,"temporary_url_is_enabled":true,"seo":true,"utm":true,"socials":true,"fonts":true,"password":true,"sensitive_content":true,"leap_link":true,"api_is_enabled":true,"affiliate_is_enabled":true,"projects_limit":10,"pixels_limit":10,"biolinks_limit":15,"links_limit":25,"domains_limit":1,"enabled_biolink_blocks":{"link":true,"text":true,"image":true,"mail":true,"soundcloud":true,"spotify":true,"youtube":true,"twitch":true,"vimeo":true,"tiktok":true,"applemusic":true,"tidal":true,"anchor":true,"twitter_tweet":true,"instagram_media":true,"rss_feed":true,"custom_html":true,"vcard":true,"image_grid":true,"divider":true}}}'),
('payment', '{"is_enabled":false,"type":"both","default_payment_frequency":"monthly","currencies":{"USD":{"code":"USD","symbol":"$","default_payment_processor":"offline_payment"}},"default_currency":"USD","codes_is_enabled":true,"taxes_and_billing_is_enabled":true,"invoice_is_enabled":true,"user_plan_expiry_reminder":0,"user_plan_expiry_checker_is_enabled":0,"currency_exchange_api_key":""}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{"is_enabled":true,"instructions":"Your offline/manual payment instructions go here, which the user will see when paying via this method.","proof_size_limit":2}'),
('coinbase', '{"is_enabled":false,"api_key":"","webhook_secret":"","currencies":["USD"]}'),
('payu', '{"is_enabled":false,"mode":"sandbox","merchant_pos_id":"","signature_key":"","oauth_client_id":"","oauth_client_secret":"","currencies":["USD"]}'),
('iyzico', '{"is_enabled":false,"mode":"live","api_key":"","secret_key":"","currencies":["USD"]}'),
('paystack', '{"is_enabled":false,"public_key":"","secret_key":"","currencies":["USD"]}'),
('razorpay', '{"is_enabled":false,"key_id":"","key_secret":"","webhook_secret":"","currencies":["USD"]}'),
('mollie', '{"is_enabled":false,"api_key":""}'),
('yookassa', '{"is_enabled":false,"shop_id":"","secret_key":""}'),
('crypto_com', '{"is_enabled":false,"publishable_key":"","secret_key":"","webhook_secret":""}'),
('paddle', '{"is_enabled":false,"mode":"sandbox","vendor_id":"","api_key":"","public_key":"","currencies":["USD"]}'),
('mercadopago', '{"is_enabled":false,"access_token":"","currencies":["USD"]}'),
('midtrans', '{"is_enabled":false,"server_key":"","mode":"sandbox","currencies":["USD"]}'),
('flutterwave', '{"is_enabled":false,"secret_key":"","currencies":["USD"]}'),
('smtp', '{"from_name":"AltumCode","from":"","reply_to_name":"","reply_to":"","cc":"","bcc":"","host":"","encryption":"tls","port":"","auth":0,"username":"","password":"","display_socials":false,"company_details":""}'),
('custom', '{"body_content":"","head_js":"","head_css":"","head_js_biolink":"","head_css_biolink":"","body_content_biolink":"","head_js_splash_page":"","head_css_splash_page":"","body_content_splash_page":""}'),
('theme', '{"light_is_enabled": false, "dark_is_enabled": false}'),
('socials', '{"threads":"","youtube":"","facebook":"","x":"","instagram":"","tiktok":"","linkedin":"","whatsapp":"","email":""}'),
('announcements', '{"guests_is_enabled":0,"guests_id":"035cc337f6de075434bc24807b7ad9af","guests_content":"","guests_text_color":"#000000","guests_background_color":"#000000","users_is_enabled":0,"users_id":"035cc337f6de075434bc24807b7ad9af","users_content":"","users_text_color":"#000000","users_background_color":"#000000","translations":{"english":{"guests_content":"","users_content":""}}}'),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{"user_new":"","user_delete":"","payment_new":"","code_redeemed":"","contact":"","cron_start":"","cron_end":"","domain_new":"","domain_update":""}'),
('cookie_consent', '{"is_enabled":false,"logging_is_enabled":false,"necessary_is_enabled":true,"analytics_is_enabled":true,"targeting_is_enabled":true,"layout":"bar","position_y":"middle","position_x":"center"}'),
('links', '{"available_biolink_blocks":{"link":true,"heading":true,"paragraph":true,"avatar":true,"image":true,"socials":true,"email_collector":true,"threads":true,"soundcloud":true,"spotify":true,"youtube":true,"twitch":true,"vimeo":true,"tiktok_video":true,"paypal":true,"phone_collector":true,"contact_collector":true,"map":true,"applemusic":true,"tidal":true,"anchor":true,"twitter_profile":true,"twitter_tweet":true,"twitter_video":true,"pinterest_profile":true,"instagram_media":true,"snapchat":true,"rss_feed":true,"custom_html":true,"vcard":true,"image_grid":true,"divider":true,"list":true,"alert":true,"tiktok_profile":true,"vk_video":true,"big_link":true,"faq":true,"typeform":true,"discord":true,"facebook":true,"reddit":true,"audio":true,"video":true,"iframe":true,"file":true,"countdown":true,"cta":true,"external_item":true,"share":true,"youtube_feed":true,"timeline":true,"review":true,"image_slider":true,"pdf_document":true,"markdown":true,"rumble":true,"telegram":true,"donation":true,"product":true,"service":true},"example_url":"","random_url_length":5,"branding":"66biolinks by AltumCode","shortener_is_enabled":1,"biolinks_is_enabled":1,"biolinks_templates_is_enabled":1,"biolinks_themes_is_enabled":"on","biolinks_new_blocks_position":"bottom","biolinks_default_active_tab":"settings","default_biolink_theme_id":null,"default_biolink_template_id":null,"files_is_enabled":1,"vcards_is_enabled":1,"events_is_enabled":1,"static_is_enabled":1,"pixels_is_enabled":1,"splash_page_is_enabled":1,"splash_page_auto_redirect":1,"splash_page_link_unlock_seconds":3,"directory_is_enabled":1,"directory_access":"everyone","directory_display":"all","domains_is_enabled":1,"additional_domains_is_enabled":1,"main_domain_is_enabled":1,"domains_custom_main_ip":"","blacklisted_domains":[],"blacklisted_keywords":[],"google_safe_browsing_is_enabled":0,"google_safe_browsing_api_key":"","google_static_maps_is_enabled":0,"google_static_maps_api_key":"","avatar_size_limit":2,"background_size_limit":2,"favicon_size_limit":2,"seo_image_size_limit":2,"thumbnail_image_size_limit":2,"image_size_limit":2,"audio_size_limit":2,"video_size_limit":2,"file_size_limit":2,"product_file_size_limit":2,"static_size_limit":2}'),
('codes', '{"qr_codes_is_enabled":1,"logo_size_limit":1,"background_size_limit":1,"available_qr_codes":{"text":true,"url":true,"phone":true,"sms":true,"email":true,"whatsapp":true,"facetime":true,"location":true,"wifi":true,"event":true,"vcard":true,"crypto":true,"paypal":true,"upi":true,"epc":true,"pix":true},"qr_codes_branding_logo":"","qr_codes_default_image":""}'),
('tools', '{"is_enabled":1,"access":"everyone","available_tools":{"dns_lookup":true,"ip_lookup":true,"reverse_ip_lookup":true,"ssl_lookup":true,"whois_lookup":true,"ping":true,"http_headers_lookup":true,"http2_checker":true,"brotli_checker":true,"safe_url_checker":true,"google_cache_checker":true,"url_redirect_checker":true,"password_strength_checker":true,"meta_tags_checker":true,"website_hosting_checker":true,"file_mime_type_checker":true,"gravatar_checker":true,"text_separator":true,"email_extractor":true,"url_extractor":true,"text_size_calculator":true,"duplicate_lines_remover":true,"text_to_speech":true,"idn_punnycode_converter":true,"case_converter":true,"character_counter":true,"list_randomizer":true,"reverse_words":true,"reverse_letters":true,"emojis_remover":true,"reverse_list":true,"list_alphabetizer":true,"upside_down_text_generator":true,"old_english_text_generator":true,"cursive_text_generator":true,"palindrome_checker":true,"base64_encoder":true,"base64_decoder":true,"base64_to_image":true,"image_to_base64":true,"url_encoder":true,"url_decoder":true,"color_converter":true,"binary_converter":true,"hex_converter":true,"ascii_converter":true,"decimal_converter":true,"octal_converter":true,"morse_converter":true,"number_to_words_converter":true,"paypal_link_generator":true,"signature_generator":true,"mailto_link_generator":true,"utm_link_generator":true,"whatsapp_link_generator":true,"youtube_timestamp_link_generator":true,"slug_generator":true,"lorem_ipsum_generator":true,"password_generator":true,"random_number_generator":true,"uuid_v4_generator":true,"bcrypt_generator":true,"md2_generator":true,"md4_generator":true,"md5_generator":true,"whirlpool_generator":true,"sha1_generator":true,"sha224_generator":true,"sha256_generator":true,"sha384_generator":true,"sha512_generator":true,"sha512_224_generator":true,"sha512_256_generator":true,"sha3_224_generator":true,"sha3_256_generator":true,"sha3_384_generator":true,"sha3_512_generator":true,"html_minifier":true,"css_minifier":true,"js_minifier":true,"json_validator_beautifier":true,"sql_beautifier":true,"html_entity_converter":true,"bbcode_to_html":true,"markdown_to_html":true,"html_tags_remover":true,"user_agent_parser":true,"url_parser":true,"image_optimizer":true,"png_to_jpg":true,"png_to_webp":true,"png_to_bmp":true,"png_to_gif":true,"png_to_ico":true,"jpg_to_png":true,"jpg_to_webp":true,"jpg_to_gif":true,"jpg_to_ico":true,"jpg_to_bmp":true,"webp_to_jpg":true,"webp_to_gif":true,"webp_to_png":true,"webp_to_bmp":true,"webp_to_ico":true,"bmp_to_jpg":true,"bmp_to_gif":true,"bmp_to_png":true,"bmp_to_webp":true,"bmp_to_ico":true,"ico_to_jpg":true,"ico_to_gif":true,"ico_to_png":true,"ico_to_webp":true,"ico_to_bmp":true,"gif_to_jpg":true,"gif_to_ico":true,"gif_to_png":true,"gif_to_webp":true,"gif_to_bmp":true,"youtube_thumbnail_downloader":true,"qr_code_reader":true,"barcode_reader":true,"exif_reader":true,"color_picker":true,"unix_timestamp_to_date":true,"date_to_unix_timestamp":true},"extra_content_is_enabled":true,"share_is_enabled":true,"views_is_enabled":true,"similar_widget_is_enabled":true,"popular_widget_is_enabled":true}'),
('license', '{\"license\":\"weadown.com\",\"type\":\"Extended License\"}'),
('product_info', '{\"version\":\"51.0.0\", \"code\":\"5100\"}');

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

CREATE TABLE `biolinks_themes` (
`biolink_theme_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL,
`settings` text,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`order` int DEFAULT '0',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`biolink_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `biolinks_themes` (`biolink_theme_id`, `name`, `settings`, `is_enabled`, `order`, `last_datetime`, `datetime`) VALUES
(1, 'Paris', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"zero\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#FFFFFFC9\",\"background_color\":\"#0000004A\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 1, NULL, '2024-09-07 16:36:29'),
(2, 'Tokyo', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"ten\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#000000B3\",\"description_color\":\"#ffffff\",\"background_color\":\"#FFFFFF\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"round\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 2, NULL, '2024-09-07 16:36:29'),
(3, 'Sydney', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"thirteen\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#ffffff\",\"background_color\":\"#21007ABD\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"straight\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 3, NULL, '2024-09-07 16:36:29'),
(4, 'London', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"four\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"trebuchet_ms\",\"font_size\":\"16\",\"background_blur\":1,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#F2F2F2\",\"background_color\":\"#94008B30\",\"border_width\":\"2\",\"border_color\":\"#6700601C\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"3\",\"border_shadow_offset_y\":\"3\",\"border_shadow_blur\":\"15\",\"border_shadow_spread\":\"2\",\"border_shadow_color\":\"#88888800\"}}', 1, 4, NULL, '2024-09-07 16:36:29'),
(5, 'Antalya', '{\"biolink\":{\"background_type\":\"preset_abstract\",\"background\":\"seven\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"montserrat\",\"font_size\":\"16\",\"background_blur\":5,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#00644B\",\"description_color\":\"#00AC8B\",\"background_color\":\"#2CFFD5\",\"border_width\":\"0\",\"border_color\":\"#67006000\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"10\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#3DFFB359\"}}', 1, 5, NULL, '2024-09-07 16:36:29');

-- SEPARATOR --

CREATE TABLE `links` (
`link_id` int NOT NULL AUTO_INCREMENT,
`project_id` int DEFAULT NULL,
`splash_page_id` bigint unsigned DEFAULT NULL,
`user_id` int NOT NULL,
`biolink_theme_id` int DEFAULT NULL,
`domain_id` int DEFAULT '0',
`pixels_ids` text,
`type` varchar(32) NOT NULL DEFAULT '',
`subtype` varchar(32) DEFAULT NULL,
`url` varchar(256) NOT NULL DEFAULT '',
`location_url` varchar(2048) DEFAULT NULL,
`clicks` int NOT NULL DEFAULT '0',
`settings` text,
`start_date` datetime DEFAULT NULL,
`end_date` datetime DEFAULT NULL,
`is_verified` tinyint DEFAULT '0',
`is_enabled` tinyint NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`link_id`),
KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
KEY `url` (`url`),
KEY `links_subtype_index` (`subtype`),
KEY `links_type_index` (`type`),
KEY `links_biolinks_themes_biolink_theme_id_fk` (`biolink_theme_id`),
KEY `links_splash_page_id_index` (`splash_page_id`),
CONSTRAINT `links_biolinks_themes_biolink_theme_id_fk` FOREIGN KEY (`biolink_theme_id`) REFERENCES `biolinks_themes` (`biolink_theme_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `links_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `links_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `links_splash_pages_splash_page_id_fk` FOREIGN KEY (`splash_page_id`) REFERENCES `splash_pages` (`splash_page_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `biolinks_templates` (
`biolink_template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`link_id` int DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`total_usage` bigint unsigned DEFAULT '0',
`order` int DEFAULT '0',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`biolink_template_id`),
KEY `link_id` (`link_id`),
CONSTRAINT `biolinks_templates_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `track_links` (
`id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`link_id` int DEFAULT NULL,
`biolink_block_id` int DEFAULT NULL,
`project_id` int DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(128) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`utm_source` varchar(128) DEFAULT NULL,
`utm_medium` varchar(128) DEFAULT NULL,
`utm_campaign` varchar(128) DEFAULT NULL,
`is_unique` tinyint(4) DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `link_id` (`link_id`),
KEY `track_links_date_index` (`datetime`),
KEY `track_links_project_id_index` (`project_id`),
KEY `track_links_users_user_id_fk` (`user_id`),
KEY `track_links_biolink_block_id_index` (`biolink_block_id`),
CONSTRAINT `track_links_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `track_links_links_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `links` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `track_links_projects_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `track_links_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

CREATE TABLE `biolinks_blocks` (
`biolink_block_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`link_id` int DEFAULT NULL,
`type` varchar(32) NOT NULL DEFAULT '',
`location_url` varchar(512) DEFAULT NULL,
`clicks` int NOT NULL DEFAULT '0',
`settings` text,
`order` int NOT NULL DEFAULT '0',
`start_date` datetime DEFAULT NULL,
`end_date` datetime DEFAULT NULL,
`is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`biolink_block_id`),
KEY `user_id` (`user_id`),
KEY `links_type_index` (`type`),
KEY `links_links_link_id_fk` (`link_id`),
CONSTRAINT `biolinks_blocks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `biolinks_blocks_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `pixels` (
`pixel_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`type` varchar(64) NOT NULL,
`name` varchar(64) NOT NULL,
`pixel` varchar(64) NOT NULL,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`pixel_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `pixels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `domains` (
`domain_id` int NOT NULL AUTO_INCREMENT,
`link_id` int DEFAULT NULL,
`user_id` int DEFAULT NULL,
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(128) NOT NULL DEFAULT '',
`custom_index_url` varchar(256) DEFAULT NULL,
`custom_not_found_url` varchar(256) DEFAULT NULL,
`type` tinyint(11) DEFAULT '1',
`is_enabled` tinyint(4) DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`domain_id`),
KEY `user_id` (`user_id`),
KEY `host` (`host`),
KEY `type` (`type`),
KEY `domains_links_link_id_fk` (`link_id`),
CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `domains_links_link_id_fk` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `data` (
`datum_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`biolink_block_id` int DEFAULT NULL,
`link_id` int DEFAULT NULL,
`project_id` int DEFAULT NULL,
`user_id` int NOT NULL,
`type` varchar(32) DEFAULT NULL,
`data` text,
`datetime` datetime NOT NULL,
PRIMARY KEY (`datum_id`),
UNIQUE KEY `datum_id` (`datum_id`),
KEY `link_id` (`link_id`),
KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
KEY `biolink_block_id` (`biolink_block_id`),
CONSTRAINT `data_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_4` FOREIGN KEY (`biolink_block_id`) REFERENCES `biolinks_blocks` (`biolink_block_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `qr_codes` (
`qr_code_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`project_id` int DEFAULT NULL,
`link_id` int DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`qr_code_logo` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`qr_code_background` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`qr_code_foreground` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`qr_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`embedded_data` text COLLATE utf8mb4_unicode_ci,
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`qr_code_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
KEY `qr_codes_links_link_id_fk` (`link_id`),
CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `qr_codes_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `qr_codes_links_link_id_fk` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `links` (`link_id`, `project_id`, `user_id`, `domain_id`, `pixels_ids`, `type`, `url`, `location_url`, `clicks`, `settings`, `start_date`, `end_date`, `is_verified`, `is_enabled`, `datetime`) VALUES (1, NULL, 1, 0, '[]', 'biolink',  'example', NULL, 0, '{\"verified_location\":\"top\",\"background_type\":\"preset\",\"background\":\"six\",\"favicon\":null,\"text_color\":\"#fff\",\"display_branding\":true,\"branding\":{\"name\":\"\",\"url\":\"\"},\"seo\":{\"block\":false,\"title\":\"\",\"meta_description\":\"\",\"image\":\"\"},\"utm\":{\"medium\":\"\",\"source\":\"\"},\"font\":\"arial\",\"font_size\":16,\"password\":null,\"sensitive_content\":false,\"leap_link\":\"\"}', NULL, NULL, 1, 1, '2021-12-20 18:05:36');

-- SEPARATOR --

INSERT INTO `biolinks_blocks` (`user_id`, `link_id`, `type`, `location_url`, `clicks`, `settings`, `order`, `start_date`, `end_date`, `is_enabled`, `datetime`) VALUES (1, 1, 'heading', NULL, 0, '{\"heading_type\":\"h1\",\"text\":\"Example page\",\"text_color\":\"white\"}', 0, NULL, NULL, 1, '2021-12-20 18:05:52');

-- SEPARATOR --

INSERT INTO `biolinks_blocks` (`user_id`, `link_id`, `type`, `location_url`, `clicks`, `settings`, `order`, `start_date`, `end_date`, `is_enabled`, `datetime`) VALUES (1, 1, 'paragraph', NULL, 0, '{\"text\":\"This is an example description.\",\"text_color\":\"white\"}', 1, NULL, NULL, 1, '2021-12-20 18:06:09');

-- SEPARATOR --

CREATE TABLE `tools_usage` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tool_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE KEY `tools_usage_tool_id_idx` (`tool_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `codes` (
  `code_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `discount` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `redeemed` int(11) NOT NULL DEFAULT 0,
  `plans_ids` text DEFAULT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_id` int(10) UNSIGNED DEFAULT NULL,
  `processor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `total_amount_default_currency` float DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `redeemed_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `taxes` (
  `tax_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `value_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('inclusive','exclusive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` enum('personal','business','both') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

ALTER TABLE `codes`
  ADD PRIMARY KEY (`code_id`),
  ADD KEY `type` (`type`),
  ADD KEY `code` (`code`);

-- SEPARATOR --

ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);
  
-- SEPARATOR --

ALTER TABLE `redeemed_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_id` (`code_id`),
  ADD KEY `user_id` (`user_id`);

-- SEPARATOR --

ALTER TABLE `taxes`
  ADD PRIMARY KEY (`tax_id`);
  
-- SEPARATOR --

ALTER TABLE `codes`
  MODIFY `code_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- SEPARATOR --

ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- SEPARATOR --

ALTER TABLE `redeemed_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- SEPARATOR --

ALTER TABLE `taxes`
  MODIFY `tax_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;