<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Settings extends Model {

    public function get() {

        $cache_instance = cache()->getItem('settings');

        /* Set cache if not existing */
        if(!$cache_instance->get()) {

            $result = database()->query("SELECT * FROM `settings`");
            $data = new \StdClass();

            while($row = $result->fetch_object()) {

                /* Put the value in a variable so we can check if its json or not */
                $value = json_decode($row->value);

                $data->{$row->key} = is_null($value) ? $row->value : $value;

            }

            cache()->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS));

        } else {

            /* Get cache */
            $data = $cache_instance->get('settings');

        }

        /* Define some stuff from the database */
        if(!defined('PRODUCT_VERSION')) define('PRODUCT_VERSION', $data->product_info->version);
        if(!defined('PRODUCT_CODE')) define('PRODUCT_CODE', $data->product_info->code);

        /* Set the full url for assets */
        $assets_url = SITE_URL . ASSETS_URL_PATH;
        $uploads_url = SITE_URL . UPLOADS_URL_PATH;

        if(\Altum\Plugin::is_active('offload')) {
            if(!empty($data->offload->assets_url)) {
                $assets_url = $data->offload->assets_url;
            }

            if(!empty($data->offload->uploads_url)) {
                $uploads_url = $data->offload->uploads_url;
            }

            /* CDN */
            if(!empty($data->offload->cdn_assets_url)) {
                $assets_url = $data->offload->cdn_assets_url . ASSETS_URL_PATH;
            }

            if(!empty($data->offload->cdn_uploads_url)) {
                $uploads_url = $data->offload->cdn_uploads_url . UPLOADS_URL_PATH;
            }
        }

        define('ASSETS_FULL_URL', $assets_url);
        define('UPLOADS_FULL_URL', $uploads_url);

        return $data;
    }

}
