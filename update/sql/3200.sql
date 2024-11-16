UPDATE `settings` SET `value` = '{\"version\":\"32.0.0\", \"code\":\"3200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table biolinks_themes add `order` int default 0 after is_enabled;

-- SEPARATOR --

CREATE TABLE `biolinks_templates` (
`biolink_template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`link_id` int DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

alter table users add is_newsletter_subscribed tinyint unsigned default 0 null after type;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE PROCEDURE `altum`()
BEGIN

IF
(SELECT COUNT(`value`) FROM `settings` WHERE `key` = 'aix') = 1
THEN

alter table documents add template_id bigint unsigned null after project_id;

CREATE TABLE `templates_categories` (
`template_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(128) DEFAULT NULL,
`settings` text,
`icon` varchar(32) DEFAULT NULL,
`emoji` varchar(32) DEFAULT NULL,
`color` varchar(16) DEFAULT NULL,
`background` varchar(16) DEFAULT NULL,
`order` int DEFAULT NULL,
`is_enabled` tinyint unsigned DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`template_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `templates_categories` (`template_category_id`, `name`, `settings`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, 'Text', '{\"translations\":{\"english\":{\"name\":\"Text\"}}}', 'fas fa-paragraph', '📝', '#14b8a6', '#f0fdfa', 1, 1, '2023-03-25 17:33:19', NULL),
(2, 'Website', '{\"translations\":{\"english\":{\"name\":\"Website\"}}}', 'fas fa-globe', '🌐', '#0ea5e9', '#f0f9ff', 1, 1, '2023-03-25 17:33:19', NULL),
(3, 'Social Media', '{\"translations\":{\"english\":{\"name\":\"Social Media\"}}}', 'fas fa-hashtag', '🕊️', '#3b82f6', '#eff6ff', 1, 1, '2023-03-25 17:33:19', NULL),
(4, 'Others', '{\"translations\":{\"english\":{\"name\":\"Others\"}}}', 'fas fa-fire', '🔥', '#6366f1', '#eef2ff', 1, 1, '2023-03-25 17:33:19', NULL);

CREATE TABLE `templates` (
`template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`template_category_id` bigint unsigned DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`prompt` text,
`settings` text,
`icon` varchar(32) DEFAULT NULL,
`order` int DEFAULT NULL,
`total_usage` bigint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`template_id`),
KEY `template_category_id` (`template_category_id`),
CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `templates` (`template_id`, `template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, 1, 'Summarize', 'Summarize the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Summarize\",\"description\":\"Get a quick summary of a long piece of text, only the important parts.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to summarize\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-align-left', 1, 1, 1, '2023-03-25 23:28:59', NULL),
(2, 1, 'Explain like I am 5', 'Explain & summarize the following text like I am 5: {text}', '{\"translations\":{\"english\":{\"name\":\"Explain like I am 5\",\"description\":\"Get a better understanding on a topic, subject or piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or concept to explain\",\"placeholder\":\"How does a rocket go into space?\",\"help\":null}}}}}', 'fas fa-child', 2, 1, 1, '2023-03-25 23:28:59', NULL),
(3, 1, 'Text spinner/rewriter', 'Rewrite the following text in a different manner: {text}', '{\"translations\":{\"english\":{\"name\":\"Text spinner/rewriter\",\"description\":\"Rewrite a piece of text in another unique way, using different words.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to rewrite\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-sync', 3, 1, 1, '2023-03-25 23:28:59', NULL),
(4, 1, 'Keywords generator', 'Extract important keywords from the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Keywords generator\",\"description\":\"Extract important keywords from a piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-key', 4, 1, 1, '2023-03-25 23:28:59', NULL),
(5, 1, 'Grammar fixer', 'Fix the grammar on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Grammar fixer\",\"description\":\"Make sure your text is written correctly with no spelling or grammar errors.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to grammar fix\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-spell-check', 5, 1, 1, '2023-03-25 23:28:59', NULL),
(6, 1, 'Text to Emoji', 'Transform the following text into emojis: {text}', '{\"translations\":{\"english\":{\"name\":\"Text to Emoji\",\"description\":\"Convert the meaning of a piece of text to fun emojis.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to convert\",\"placeholder\":\"The pirates of the Caribbean\",\"help\":null}}}}}', 'fas fa-smile-wink', 6, 1, 1, '2023-03-25 23:28:59', NULL),
(7, 1, 'Blog Article Idea', 'Write multiple blog article ideas based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea\",\"description\":\"Generate interesting blog article ideas based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":\"Best places to travel as a couple\",\"help\":null}}}}}', 'fas fa-lightbulb', 7, 1, 1, '2023-03-25 23:29:00', NULL),
(8, 1, 'Blog Article Intro', 'Write a good intro for a blog article, based on the title of the blog post: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Intro\",\"description\":\"Generate a creative intro section for your blog article.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title of the blog article\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-keyboard', 8, 1, 1, '2023-03-25 23:29:00', NULL),
(9, 1, 'Blog Article Idea & Outline', 'Write ideas for a blog article title and outline, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea & Outline\",\"description\":\"Generate unlimited blog article ideas and structure with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-blog', 9, 1, 1, '2023-03-25 23:29:00', NULL),
(10, 1, 'Blog Article Section', 'Write a blog sections about \"{title}\" using the \"{keywords}\" keywords', '{\"translations\":{\"english\":{\"name\":\"Blog Article Section\",\"description\":\"Generate a full and unique section/paragraph for your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Best traveling tips and tricks\",\"help\":null}}},\"keywords\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Airport luggage, Car rentals, Quality Airbnb stays\",\"help\":null}}}}}', 'fas fa-rss', 10, 1, 1, '2023-03-25 23:29:00', NULL),
(11, 1, 'Blog Article', 'Write a long article / blog post on \"{title}\" with the \"{keywords}\" keywords and the following sections \"{sections}\"', '{\"translations\":{\"english\":{\"name\":\"Blog Article\",\"description\":\"Generate a simple and creative article / blog post for your website.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Places you must visit in winter\",\"help\":null}}},\"keywords\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Winter, Hotel, Jacuzzi, Spa, Ski\",\"help\":null}}},\"sections\":{\"icon\":\"fas fa-feather\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Sections\",\"placeholder\":\"Austria, Italy, Switzerland\",\"help\":null}}}}}', 'fas fa-feather', 11, 1, 1, '2023-03-25 23:29:00', NULL),
(12, 1, 'Blog Article Outro', 'Write a blog article outro based on the blog title \"{title}\" and the \"{description}\" description', '{\"translations\":{\"english\":{\"name\":\"Blog Article Outro\",\"description\":\"Generate the conclusion section of your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Warm places to visit in December\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Describe what your blog article is about\",\"help\":null}}}}}', 'fas fa-pen-nib', 12, 1, 1, '2023-03-25 23:29:00', NULL),
(13, 1, 'Reviews', 'Write a review or testimonial about \"{name}\" using the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Reviews\",\"description\":\"Generate creative reviews / testimonials for your service or product.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Wandering Agency: Travel with confidence\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"We plan and set up your perfect traveling experience to the most exotic places, from start to finish.\",\"help\":null}}}}}', 'fas fa-star', 13, 1, 1, '2023-03-25 23:29:00', NULL),
(14, 1, 'Translate', 'Translate the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Translate\",\"description\":\"Translate a piece of text to another language with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-language', 14, 1, 1, '2023-03-25 23:29:00', NULL),
(15, 3, 'Social media bio', 'Write a short social media bio profile description based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media bio\",\"description\":\"Generate Twitter, Instagram, TikTok bio for your account.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Yacht traveling, Boat charter, Summer, Sailing\",\"help\":null}}}}}', 'fas fa-share-alt', 15, 1, 1, '2023-03-25 23:29:00', NULL),
(16, 3, 'Social media hashtags', 'Generate hashtags for a social media post based on the following description: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media hashtags\",\"description\":\"Generate hashtags for your social media posts.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract hashtags from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-hashtag', 16, 1, 1, '2023-03-25 23:29:00', NULL),
(17, 3, 'Video Idea', 'Write ideas for a video scenario, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Idea\",\"description\":\"Generate a random video idea based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-video', 17, 1, 1, '2023-03-25 23:29:00', NULL),
(18, 3, 'Video Title', 'Write a video title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Title\",\"description\":\"Generate a catchy video title for your video.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-play', 18, 1, 1, '2023-03-25 23:29:00', NULL),
(19, 3, 'Video Description', 'Write a video description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Description\",\"description\":\"Generate a brief and quality video description.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-film', 19, 1, 1, '2023-03-25 23:29:00', NULL),
(20, 3, 'Tweet generator', 'Generate a tweet based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Tweet generator\",\"description\":\"Generate tweets based on your ideas/topics/keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-twitter', 20, 1, 1, '2023-03-25 23:29:00', NULL),
(21, 3, 'Instagram caption', 'Generate an instagram caption for a post based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Instagram caption\",\"description\":\"Generate an instagram post caption based on text or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-instagram', 21, 1, 1, '2023-03-25 23:29:00', NULL),
(22, 2, 'Website Headline', 'Write a website short headline for the \"{name}\" product with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Website Headline\",\"description\":\"Generate creative, catchy and unique headlines for your website.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Sunset Agents: Best summer destinations\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our blog helps you find and plan your next summer vacation.\",\"help\":null}}}}}', 'fas fa-feather', 22, 1, 1, '2023-03-25 23:29:00', NULL),
(23, 2, 'SEO Title', 'Write an SEO Title for a web page based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Title\",\"description\":\"Generate high quality & SEO ready titles for your web pages.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Traveling, Summer, Beach, Pool\",\"help\":null}}}}}', 'fas fa-heading', 23, 1, 1, '2023-03-25 23:29:00', NULL),
(24, 2, 'SEO Description', 'Write an SEO description, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Description\",\"description\":\"Generate proper descriptions for your web pages to help you rank better\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-pen', 24, 1, 1, '2023-03-25 23:29:00', NULL),
(25, 2, 'SEO Keywords', 'Write SEO meta keywords, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Keywords\",\"description\":\"Extract and generate meaningful and quality keywords for your website.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-file-word', 25, 1, 1, '2023-03-25 23:29:00', NULL),
(26, 2, 'Ad Title', 'Write a short ad title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Title\",\"description\":\"Generate a short & good title copy for any of your ads.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-money-check-alt', 26, 1, 1, '2023-03-25 23:29:00', NULL),
(27, 2, 'Ad Description', 'Write a short ad description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Description\",\"description\":\"Generate the description for an ad campaign.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-th-list', 27, 1, 1, '2023-03-25 23:29:00', NULL),
(28, 4, 'Name generator', 'Generate multiple & relevant product names based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Name generator\",\"description\":\"Generate interesting product names for your project.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-file-signature', 28, 1, 1, '2023-03-25 23:29:00', NULL),
(29, 4, 'Startup ideas', 'Generate multiple & relevant startup business ideas based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Startup ideas\",\"description\":\"Generate startup ideas based on your topic inputs.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-user-tie', 29, 1, 1, '2023-03-25 23:29:00', NULL),
(30, 4, 'Viral ideas', 'Generate a viral idea based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Viral ideas\",\"description\":\"Generate highly viral probability ideas based on your topics or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-bolt', 30, 1, 1, '2023-03-25 23:29:01', NULL),
(31, 4, 'Custom prompt', '{text}', '{\"translations\":{\"english\":{\"name\":\"Custom prompt\",\"description\":\"Ask our AI for anything & he will do it is best to give you quality content.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Question or task\",\"placeholder\":\"What are the top 5 most tourist friendly destinations?\",\"help\":null}}}}}', 'fas fa-star', 31, 1, 1, '2023-03-25 23:29:23', NULL);

alter table documents add constraint documents_templates_template_id_fk foreign key (template_id) references templates (template_id) on update cascade on delete cascade;

alter table documents add template_category_id bigint unsigned null after template_id;

alter table documents add constraint documents_templates_categories_template_category_id_fk foreign key (template_category_id) references templates_categories (template_category_id) on update cascade on delete cascade;

UPDATE documents SET type = 1 WHERE type = 'summarize';

UPDATE documents SET type = 2 WHERE type = 'explain_for_a_kid';

UPDATE documents SET type = 3 WHERE type = 'spinner';

UPDATE documents SET type = 4 WHERE type = 'keywords_generator';

UPDATE documents SET type = 5 WHERE type = 'grammar_fixer';

UPDATE documents SET type = 6 WHERE type = 'text_to_emoji';

UPDATE documents SET type = 7 WHERE type = 'blog_article_idea';

UPDATE documents SET type = 8 WHERE type = 'blog_article_intro';

UPDATE documents SET type = 9 WHERE type = 'blog_article_idea_and_outline';

UPDATE documents SET type = 10 WHERE type = 'blog_article_section';

UPDATE documents SET type = 11 WHERE type = 'blog_article';

UPDATE documents SET type = 12 WHERE type = 'blog_article_outro';

UPDATE documents SET type = 13 WHERE type = 'reviews';

UPDATE documents SET type = 14 WHERE type = 'translate';

UPDATE documents SET type = 15 WHERE type = 'social_bio';

UPDATE documents SET type = 16 WHERE type = 'hashtags';

UPDATE documents SET type = 17 WHERE type = 'video_idea';

UPDATE documents SET type = 18 WHERE type = 'video_title';

UPDATE documents SET type = 19 WHERE type = 'video_description';

UPDATE documents SET type = 20 WHERE type = 'tweet';

UPDATE documents SET type = 21 WHERE type = 'instagram_caption';

UPDATE documents SET type = 22 WHERE type = 'website_headline';

UPDATE documents SET type = 23 WHERE type = 'seo_title';

UPDATE documents SET type = 24 WHERE type = 'seo_description';

UPDATE documents SET type = 25 WHERE type = 'seo_keywords';

UPDATE documents SET type = 26 WHERE type = 'ad_title';

UPDATE documents SET type = 27 WHERE type = 'ad_description';

UPDATE documents SET type = 28 WHERE type = 'name_generator';

UPDATE documents SET type = 29 WHERE type = 'startup_ideas';

UPDATE documents SET type = 30 WHERE type = 'viral_ideas';

UPDATE documents SET type = 31 WHERE type = 'custom';

UPDATE documents SET template_id = 1, template_category_id = 1 WHERE type = '1';

UPDATE documents SET template_id = 2, template_category_id = 1 WHERE type = '2';

UPDATE documents SET template_id = 3, template_category_id = 1 WHERE type = '3';

UPDATE documents SET template_id = 4, template_category_id = 1 WHERE type = '4';

UPDATE documents SET template_id = 5, template_category_id = 1 WHERE type = '5';

UPDATE documents SET template_id = 6, template_category_id = 1 WHERE type = '6';

UPDATE documents SET template_id = 7, template_category_id = 1 WHERE type = '7';

UPDATE documents SET template_id = 8, template_category_id = 1 WHERE type = '8';

UPDATE documents SET template_id = 9, template_category_id = 1 WHERE type = '9';

UPDATE documents SET template_id = 10, template_category_id = 1 WHERE type = '10';

UPDATE documents SET template_id = 11, template_category_id = 1 WHERE type = '11';

UPDATE documents SET template_id = 12, template_category_id = 1 WHERE type = '12';

UPDATE documents SET template_id = 13, template_category_id = 1 WHERE type = '13';

UPDATE documents SET template_id = 14, template_category_id = 1 WHERE type = '14';

UPDATE documents SET template_id = 15, template_category_id = 3 WHERE type = '15';

UPDATE documents SET template_id = 16, template_category_id = 3 WHERE type = '16';

UPDATE documents SET template_id = 17, template_category_id = 3 WHERE type = '17';

UPDATE documents SET template_id = 18, template_category_id = 3 WHERE type = '18';

UPDATE documents SET template_id = 19, template_category_id = 3 WHERE type = '19';

UPDATE documents SET template_id = 20, template_category_id = 3 WHERE type = '20';

UPDATE documents SET template_id = 21, template_category_id = 3 WHERE type = '21';

UPDATE documents SET template_id = 22, template_category_id = 2 WHERE type = '22';

UPDATE documents SET template_id = 23, template_category_id = 2 WHERE type = '23';

UPDATE documents SET template_id = 24, template_category_id = 2 WHERE type = '24';

UPDATE documents SET template_id = 25, template_category_id = 2 WHERE type = '25';

UPDATE documents SET template_id = 26, template_category_id = 2 WHERE type = '26';

UPDATE documents SET template_id = 27, template_category_id = 2 WHERE type = '27';

UPDATE documents SET template_id = 28, template_category_id = 4 WHERE type = '28';

UPDATE documents SET template_id = 29, template_category_id = 4 WHERE type = '29';

UPDATE documents SET template_id = 30, template_category_id = 4 WHERE type = '30';

UPDATE documents SET template_id = 31, template_category_id = 4 WHERE type = '31';

update documents set input = JSON_OBJECT('text', `input`) WHERE NOT JSON_VALID(`input`);

alter table documents add model varchar(64) null after settings;

alter table documents add api_response_time int unsigned null after model;

alter table images add api_response_time int unsigned null after settings;

alter table transcriptions add api_response_time int unsigned null after settings;

alter table users add aix_chats_current_month bigint unsigned default 0 after aix_transcriptions_current_month;

CREATE TABLE `chats` (
`chat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`total_messages` int unsigned DEFAULT '0',
`used_tokens` int unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chats_messages` (
`chat_message_id` int NOT NULL AUTO_INCREMENT,
`chat_id` bigint unsigned DEFAULT NULL,
`user_id` int DEFAULT NULL,
`role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` text COLLATE utf8mb4_unicode_ci,
`model` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_message_id`),
KEY `chat_id` (`chat_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `chats_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `chats_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

END IF;

END;

-- SEPARATOR --

call altum;

-- SEPARATOR --

drop procedure altum;
