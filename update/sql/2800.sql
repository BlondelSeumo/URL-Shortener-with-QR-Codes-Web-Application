UPDATE `settings` SET `value` = '{\"version\":\"28.0.0\", \"code\":\"2800\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table pages add is_published tinyint default 1 null AFTER `total_views`;

-- SEPARATOR --

alter table pages add keywords varchar(256) null AFTER `description`;

-- SEPARATOR --

create index pages_is_published_index on pages (is_published);

-- SEPARATOR --

create index pages_language_index on pages (language);

-- SEPARATOR --

drop index pages_url_language_index on pages;

-- SEPARATOR --

alter table blog_posts add is_published tinyint default 1 null AFTER `total_views`;

-- SEPARATOR --

alter table blog_posts add keywords varchar(256) null AFTER `description`;

-- SEPARATOR --

drop index blog_post_url_language_index on blog_posts;

-- SEPARATOR --

create index blog_posts_is_published_index on blog_posts (is_published);

-- SEPARATOR --

create index blog_posts_language_index on blog_posts (language);

