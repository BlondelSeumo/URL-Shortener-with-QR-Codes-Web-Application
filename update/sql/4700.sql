UPDATE `settings` SET `value` = '{\"version\":\"47.0.0\", \"code\":\"4700\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table qr_codes add link_id int null after project_id;

-- SEPARATOR --

alter table qr_codes add constraint qr_codes_links_link_id_fk foreign key (link_id) references links (link_id);

-- SEPARATOR --

alter table qr_codes add qr_code_foreground varchar(40) null after qr_code_logo;

-- SEPARATOR --

alter table users add extra text null after preferences;

