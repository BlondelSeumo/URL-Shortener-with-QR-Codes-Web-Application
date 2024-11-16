UPDATE `settings` SET `value` = '{\"version\":\"49.0.0\", \"code\":\"4900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table domains add link_id int collate utf8mb4_unicode_ci null after domain_id;

-- SEPARATOR --

alter table domains add constraint domains_links_link_id_fk foreign key (link_id) references links (link_id) on update cascade on delete set null;

-- SEPARATOR --

alter table blog_posts add image_description varchar(256) null after description;

-- SEPARATOR --

alter table biolinks_themes drop column image;

-- SEPARATOR --

alter table biolinks_templates drop column image;

-- SEPARATOR --

INSERT INTO `biolinks_themes` (`name`, `settings`, `is_enabled`, `order`, `last_datetime`, `datetime`) VALUES
('Paris', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"zero\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#FFFFFFC9\",\"background_color\":\"#0000004A\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 1, NULL, '2024-09-07 16:36:29'),
('Tokyo', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"ten\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#000000B3\",\"description_color\":\"#ffffff\",\"background_color\":\"#FFFFFF\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"round\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 2, NULL, '2024-09-07 16:36:29'),
('Sydney', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"thirteen\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#ffffff\",\"background_color\":\"#21007ABD\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"straight\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"}}', 1, 3, NULL, '2024-09-07 16:36:29'),
('London', '{\"biolink\":{\"background_type\":\"preset\",\"background\":\"four\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"trebuchet_ms\",\"font_size\":\"16\",\"background_blur\":1,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#F2F2F2\",\"background_color\":\"#94008B30\",\"border_width\":\"2\",\"border_color\":\"#6700601C\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"3\",\"border_shadow_offset_y\":\"3\",\"border_shadow_blur\":\"15\",\"border_shadow_spread\":\"2\",\"border_shadow_color\":\"#88888800\"}}', 1, 4, NULL, '2024-09-07 16:36:29'),
('Antalya', '{\"biolink\":{\"background_type\":\"preset_abstract\",\"background\":\"seven\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"montserrat\",\"font_size\":\"16\",\"background_blur\":5,\"background_brightness\":100},\"biolink_block\":{\"text_color\":\"#00644B\",\"description_color\":\"#00AC8B\",\"background_color\":\"#2CFFD5\",\"border_width\":\"0\",\"border_color\":\"#67006000\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"10\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#3DFFB359\"}}', 1, 5, NULL, '2024-09-07 16:36:29');


