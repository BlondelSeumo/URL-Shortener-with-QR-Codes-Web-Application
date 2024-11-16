<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Plugin {
    public static $plugins = [];

    public static function initialize() {

        /* Check for plugin availability */
        foreach(require APP_PATH . 'includes/plugins.php' as $plugin) {
            $plugin_directory = PLUGINS_PATH . $plugin . '/';
            $config_exists = false;
            $config_type = null;

            if(!file_exists($plugin_directory)) {
                continue;
            }

            /* Make sure the plugin has a config.json file */
            if(file_exists($plugin_directory . 'config.php')) {
                $config_exists = true;
                $config_type = 'php';

                /* Parse the config.php file */
                $config = include $plugin_directory . 'config.php';
            }

            if(!$config_exists && file_exists($plugin_directory . 'config.json')) {
                $config_exists = true;
                $config_type = 'json';

                /* Parse the config.json file */
                $config = json_decode(file_get_contents($plugin_directory . 'config.json'));

                /* Make sure the json has been parsed properly */
                if(is_null($config)) {
                    continue;
                }
            }

            /* Make sure the config file has the required props */
            if(!isset($config->plugin_id, $config->name, $config->description, $config->version, $config->url, $config->author, $config->author_url, $config->status, $config->avatar_style, $config->icon)) {
                continue;
            }

            if(!isset($config->actions)) {
                $config->actions = true;
            }

            /* Get the plugin status */
            if(file_exists($plugin_directory . 'settings.json')) {
                $config->settings = json_decode(file_get_contents($plugin_directory . 'settings.json'));
                $config->status = $config->settings->status;
            }

            /* Save the route to the plugin */
            $config->path = $plugin_directory;

            /* Save the plugin */
            self::$plugins[$config->plugin_id] = $config;

            /* Load the init file */
            if(($config->status == 1 || $config->status == 'active') && file_exists($config->path . 'init.php')) {
                require_once $config->path . 'init.php';
            }

        }

    }

    public static function get($plugin_id) {
        return self::$plugins[$plugin_id] ?? null;
    }

    /* Plugin status = 1 */
    public static function is_active($plugin_id) {
        return self::get($plugin_id) && (self::get($plugin_id)->status === 1  || self::get($plugin_id)->status == 'active');
    }

    /* Plugin status = 0 */
    public static function is_installed($plugin_id) {
        return self::get($plugin_id) && (self::get($plugin_id)->status === 0 || self::get($plugin_id)->status == 'installed');
    }

    /* Plugin status = -1 */
    public static function is_uninstalled($plugin_id) {
        return self::get($plugin_id) && (self::get($plugin_id)->status === -1 || self::get($plugin_id)->status == 'uninstalled');
    }

    /* Plugin status = -2 */
    public static function is_inexistent($plugin_id) {
        return self::get($plugin_id) && (self::get($plugin_id)->status === -2 || self::get($plugin_id)->status == 'inexistent');
    }

    public static function save_status($plugin_id, $new_status) {
        /* Enable the plugin from the config file */
        $new_settings = \Altum\Plugin::get($plugin_id)->settings ? clone \Altum\Plugin::get($plugin_id)->settings : (object) [];
        $new_settings->status = $new_status;

        /* Save the new config file */
        $settings_saved = file_put_contents(\Altum\Plugin::get($plugin_id)->path . 'settings.json', json_encode($new_settings));
        chmod(\Altum\Plugin::get($plugin_id)->path . 'settings.json', 0777);

        return (bool) $settings_saved;
    }

}
