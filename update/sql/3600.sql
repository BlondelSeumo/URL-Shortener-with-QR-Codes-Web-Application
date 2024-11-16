UPDATE `settings` SET `value` = '{\"version\":\"36.0.0\", \"code\":\"3600\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('languages', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_search_widget_is_enabled":false,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}');

-- SEPARATOR --

CREATE PROCEDURE `altum`()
BEGIN

IF
(SELECT COUNT(`value`) FROM `settings` WHERE `key` = 'aix') = 1
THEN

INSERT INTO `templates_categories` (`template_category_id`, `name`, `settings`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1111, 'Developers', '{\"translations\":{\"english\":{\"name\":\"Developers\"}}}', 'fas fa-code', '💻', '#DB00FF', '#FCE9FF', 1, 1, '2023-04-19 20:00:55', NULL);


INSERT INTO `templates` (`template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1111, 'PHP snippet', 'You are a PHP programmer, answer the following request with a PHP snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"PHP snippet\",\"description\":\"Generate PHP code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that connects to a MySQL database in procedural style\",\"help\":\"Ask the AI what PHP code you want to receive \\/ get help with.\"}}}}}', 'fab fa-php', 0, 1, 1, '2023-04-19 20:18:43', NULL),
(1111, 'SQL query', 'You are a SQL programmer, answer the following request with an SQL query:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"SQL query\",\"description\":\"Generate helpful SQL queries with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested query\",\"placeholder\":\"Code that calculates the average from 3 columns\",\"help\":\"Ask the AI what SQL query you want to receive \\/ get help with.\"}}}}}', 'fas fa-database', 0, 1, 1, '2023-04-19 21:06:04', '2023-04-19 21:10:50'),
(1111, 'JS snippet', 'You are a JS programmer, answer the following request with a JS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"JS snippet\",\"description\":\"Generate quick & helpful Javascript code snippets.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that helps trigger and catch custom events\",\"help\":\"Ask the AI what JS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-js', 0, 0, 1, '2023-04-19 21:31:37', NULL),
(1111, 'HTML snippet', 'You are a HTML programmer, answer the following request with a HTML snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"HTML snippet\",\"description\":\"Generate simple and fast HTML pieces of code.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a blank HTML page\",\"help\":\"Ask the AI what HTML code you want to receive \\/ get help with.\"}}}}}', 'fab fa-html5', 0, 0, 1, '2023-04-19 22:00:58', NULL),
(1111, 'CSS snippet', 'You are a CSS programmer, answer the following request with a CSS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"CSS snippet\",\"description\":\"Generate CSS classes & code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a gradient background class\",\"help\":\"Ask the AI what CSS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-css3', 0, 0, 1, '2023-04-19 22:03:16', NULL),
(1111, 'Python snippet', 'You are a python programmer, answer the following request with a python snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"Python snippet\",\"description\":\"Generate Python code pieces with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that sends an external HTTP request\",\"help\":\"Ask the AI what Python code you want to receive \\/ get help with.\"}}}}}', 'fab fa-python', 0, 0, 1, '2023-04-19 22:05:03', NULL);

alter table users add aix_documents_current_month bigint unsigned default 0 after aix_words_current_month;

alter table chats add chat_assistant_id bigint unsigned null after user_id;

CREATE TABLE `chats_assistants` (
`chat_assistant_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`prompt` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`image` varchar(404) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int DEFAULT NULL,
`total_usage` bigint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_assistant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chats_assistants` (`chat_assistant_id`, `name`, `prompt`, `settings`, `image`, `order`, `total_usage`, `is_enabled`, `last_datetime`, `datetime`) VALUES (1, 'General Assistant', 'You are a general assistant that can help with anything.', '{\"translations\":{\"english\":{\"name\":\"General Assistant\",\"description\":\"I can help you with any general task or question.\"}}}', 'de618ff8b13d6aa0b7df3b91b16cb452.png', 0, 0, 1, null, NOW());

alter table chats add constraint chats_chats_assistants_chat_assistant_id_fk foreign key (chat_assistant_id) references chats_assistants (chat_assistant_id) on update cascade on delete cascade;

update chats set chat_assistant_id = 1;

INSERT IGNORE INTO `templates` (`template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, 'Quote generator', 'Generate a random quote on the following topic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Quote generator\",\"description\":\"Get random quotes based on the topic you wish.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Motivational\",\"help\":\"Input the type of quote you wish to generate.\"}}}}}', 'fas fa-bolt', 1, 1, 1, '2023-03-28 20:32:15', '2023-05-13 21:08:06'),
(3, 'LinkedIn post', 'Generate a LinkedIn post based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"LinkedIn post\",\"description\":\"Generate a great LinkedIn post based on text or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-linkedin', 22, 0, 1, '2023-05-13 19:41:14', NULL),
(3, 'Twitter thread generator', 'Generate a full Twitter thread based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Twitter thread generator\",\"description\":\"Generate a full thread based on any topic or idea.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-twitter', 23, 0, 1, '2023-05-13 19:49:32', NULL),
(3, 'Pinterest caption', 'Generate a Pinterest caption for a pin based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Pinterest caption\",\"description\":\"Generate a caption for your pins based on your keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-pinterest', 24, 0, 1, '2023-05-13 20:40:38', NULL),
(3, 'TikTok video caption', 'Generate a TikTok video caption based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"TikTok video caption\",\"description\":\"Generate quick & trending captions for your TikTok content with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-tiktok', 25, 0, 1, '2023-05-13 20:42:07', NULL),
(3, 'TikTok video idea', 'Generate a random TikTok video idea in the following niche: {niche}', '{\"translations\":{\"english\":{\"name\":\"TikTok video idea\",\"description\":\"Generate quick & trending video idea your TikTok account.\"}},\"inputs\":{\"niche\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Niche or Category\",\"placeholder\":\"Breakdance tutorials, Interior design principles, Places to visit in New York\",\"help\":\"Input the niche of the idea that you want to get.\"}}}}}', 'fab fa-tiktok', 26, 0, 1, '2023-05-13 20:55:57', '2023-05-13 21:04:22'),
(1, 'Song lyrics', 'Generate song lyrics based the following:\r\n\r\nGenre: {genre}\r\n\r\nTopic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Song lyrics\",\"description\":\"Generate high quality lyrics based for any genre.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Heartbreak, Love, Motivational, Dynamic\",\"help\":\"Input the topic of the lyrics you wish to generate.\"}}},\"genre\":{\"icon\":\"fas fa-music\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Genre\",\"placeholder\":\"Rap, Hip Hop, Pop, Rock\",\"help\":\"Input the genre of the lyrics you wish to generate.\"}}}}}', 'fas fa-music', 2, 1, 1, '2023-05-13 21:09:05', '2023-05-13 21:12:38'),
(1, 'Joke generator', 'Generate a random funny joke on the following topic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Joke generator\",\"description\":\"Get random and funny jokes based on the topic you wish.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Edgy, Cringe, Modern, Dark humor\",\"help\":\"Input the type of joke you wish to generate.\"}}}}}', 'fas fa-laugh-beam', 2, 0, 1, '2023-05-13 21:17:22', '2023-05-13 21:18:55'),
(2, 'Welcome email', 'Write a welcome email subject and body &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Welcome email\",\"description\":\"Generate great engaging emails for your new users.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"OpenAI\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our web platform helps users get start with AI with ease.\",\"help\":\"\"}}}}}', 'fas fa-envelope-open', 23, 1, 1, '2023-05-14 09:54:39', '2023-05-14 10:59:45'),
(2, 'Outreach email', 'Write a cold outreach email subject and body &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Outreach email\",\"description\":\"Generate great emails for cold outreach to get more leads.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"OpenAI\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our web platform helps users get start with AI with ease.\",\"help\":\"\"}}}}}', 'fas fa-envelope', 24, 0, 1, '2023-05-14 10:56:37', '2023-05-14 10:59:51'),
(2, 'Facebook advertisement', 'Generate a Facebook ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Facebook advertisement\",\"description\":\"Generate Facebook optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-facebook', 25, 0, 1, '2023-05-14 11:29:22', '2023-05-14 11:39:04'),
(2, 'Google advertisement', 'Generate a Google ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Google advertisement\",\"description\":\"Generate Google optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-google', 26, 0, 1, '2023-05-14 11:39:14', '2023-05-14 11:39:51'),
(2, 'LinkedIn advertisement', 'Generate a LinkedIn ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"LinkedIn advertisement\",\"description\":\"Generate LinkedIn optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-linkedin', 27, 0, 1, '2023-05-14 11:40:12', '2023-05-14 11:40:37');


alter table images add api varchar(64) null after settings;

UPDATE `images` SET `api` = 'openai_dall_e';

END IF;

END;

-- SEPARATOR --

call altum;

-- SEPARATOR --

drop procedure altum;
