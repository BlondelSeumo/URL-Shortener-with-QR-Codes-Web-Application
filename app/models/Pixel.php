<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Pixel extends Model {

    public function get_pixels($user_id) {
        if(!settings()->links->pixels_is_enabled) return [];

        /* Get the user pixels */
        $pixels = [];

        /* Try to check if the user exists via the cache */
        $cache_instance = cache()->getItem('pixels?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $pixels_result = database()->query("SELECT * FROM `pixels` WHERE `user_id` = {$user_id}");
            while($row = $pixels_result->fetch_object()) $pixels[$row->pixel_id] = $row;

            cache()->save(
                $cache_instance->set($pixels)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)->addTag('pixels?user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $pixels = $cache_instance->get();

        }

        return $pixels;

    }

    public function get_pixels_by_pixels_ids_and_user_id($pixels_ids, $user_id) {
        if(!settings()->links->pixels_is_enabled) return [];

        if(empty($pixels_ids)) return [];

        $pixels_ids_plain = implode(',', $pixels_ids);

        /* Get the user pixels */
        $pixels = [];

        /* Try to check if the user exists via the cache */
        $cache_instance = cache()->getItem('pixels?pixels_ids_plain=' . $pixels_ids_plain);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $pixels_result = database()->query("SELECT * FROM `pixels` WHERE `pixel_id` IN({$pixels_ids_plain})");
            while($row = $pixels_result->fetch_object()) $pixels[$row->pixel_id] = $row;

            cache()->save(
                $cache_instance->set($pixels)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)->addTag('pixels?user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $pixels = $cache_instance->get();

        }

        return $pixels;

    }
}
