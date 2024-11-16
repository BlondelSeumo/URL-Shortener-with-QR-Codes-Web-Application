<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Link extends Model {

    public function get_full_links_by_user_id($user_id) {

        /* Get the user links */
        $links = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('links?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $links_result = database()->query("SELECT `links`.*, `domains`.`scheme`, `domains`.`host`, `domains`.`link_id` as `domain_link_id` FROM `links` LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id` WHERE `links`.`user_id` = {$user_id}");
            while($row = $links_result->fetch_object()) {
                $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . ($row->domain_link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;
                $links[$row->link_id] = $row;
            }

            cache()->save(
                $cache_instance->set($links)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $links = $cache_instance->get();

        }

        return $links;

    }

    public function delete($link_id) {

        if(!$link = db()->where('link_id', $link_id)->getOne('links', ['user_id', 'link_id', 'biolink_theme_id', 'type', 'settings'])) {
            return;
        }

        /* Process to delete the stored files of the vcard avatar */
        if($link->type == 'vcard') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->vcard_avatar, 'avatars');
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'file') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->file, 'files');
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'biolink') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->favicon, 'favicons');
            \Altum\Uploads::delete_uploaded_file($link->settings->seo->image, 'block_images');

            if($link->settings->background_type == 'image' && !$link->biolink_theme_id) {
                \Altum\Uploads::delete_uploaded_file($link->settings->background, 'backgrounds');
            }

            /* Get all the available biolink blocks and iterate over them to delete the stored images */
            $result = database()->query("SELECT `biolink_block_id` FROM `biolinks_blocks` WHERE `link_id` = {$link->link_id}");
            while($row = $result->fetch_object()) {

                (new \Altum\Models\BiolinkBlock())->delete($row->biolink_block_id);

            }
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'static') {
            $link->settings = json_decode($link->settings ?? '');

            /* Clear the already existing folder and contents */
            remove_directory_and_contents(\Altum\Uploads::get_full_path('static') . $link->settings->static_folder);
        }

        /* Delete from database */
        db()->where('link_id', $link_id)->delete('links');

        /* Clear the cache */
        cache()->deleteItem($link->type . '_links_total?user_id=' . $link->user_id);
        cache()->deleteItem('links_total?user_id=' . $link->user_id);
        cache()->deleteItem('links?user_id=' . $link->user_id);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $link->link_id);
        cache()->deleteItemsByTag('link_id=' . $link->link_id);

    }
}
