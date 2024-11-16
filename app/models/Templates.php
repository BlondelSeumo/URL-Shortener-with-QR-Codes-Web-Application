<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Templates extends Model {

    public function get_templates() {
        if(!settings()->links->biolinks_templates_is_enabled) return [];

        /* Get the user projects */
        $templates = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('templates');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $templates_result = database()->query("SELECT * FROM `templates` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $templates_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $templates[$row->template_id] = $row;
            }

            cache()->save(
                $cache_instance->set($templates)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $templates = $cache_instance->get();

        }

        return $templates;

    }

}
