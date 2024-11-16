<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Language {
    /* Selected language */
    public static $name;
    public static $code;
    public static $status;

    /* Available languages found in the /app/languages directory */
    public static $languages = [];
    public static $languages_ordered = [];

    /* Enabled languages, for easy reference */
    /* $name => $code */
    public static $active_languages = [];

    /* Defaults */
    public static $main_name = 'english';
    public static $default_name;
    public static $default_code;

    /* Languages directory path */
    public static $path = APP_PATH . 'languages/';

    public static function initialize() {

        /* Determine all the languages available in the directory */
        foreach(glob(self::$path . '*.php') as $file_path) {
            $file_path_exploded = explode('/', $file_path);
            $file_name = str_replace('.php', '', trim(end($file_path_exploded)));

            if($file_name == 'english#en#active' || $file_name == 'english#en#inactive') {
                continue;
            }

            /* Parse file details */
            $file_name_exploded = explode('#', $file_name);

            $language = [
                'name' => $file_name_exploded[0],
                'code' => $file_name_exploded[1],
                'status' => settings()->languages->{$file_name_exploded[0]}->status ?? $file_name_exploded[2] ?? 'active',
                'content' => null,
                'order' => settings()->languages->{$file_name_exploded[0]}->order ?? 1,
                'language_flag' => settings()->languages->{$file_name_exploded[0]}->language_flag ?? '',
            ];

            self::$languages[$language['name']] = $language;

            if($language['status'] == 'active') {
                self::$active_languages[$language['name']] = $language['code'];
            }
        }

        /* Sort by order */
        self::$languages_ordered = self::$languages;
        usort(self::$languages_ordered, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

    }

    public static function get($name = null) {

        if(!$name) {
            $name = self::$name;

            /* Check if we already processed the language file */
            if(isset(self::$languages[$name]['content'])) {
                return self::$languages[$name]['content'];
            }
        }

        /* Make sure we have access to the requested language */
        if(!array_key_exists($name, self::$languages)) {
            /* Try and use the default one if available */
            if(array_key_exists(self::$default_name, self::$languages)) {
                $name = self::$default_name;
            }
            /* Try and use the main one if available */
            if(array_key_exists(self::$main_name, self::$languages)) {
                $name = self::$main_name;
            } else {
                die('The language system is missing the current selected language, the default language, and the main language. Fallback is not successful.');
            }
        }

        /* Check if we already processed the language file */
        if(isset(self::$languages[$name]['content'])) {
            return self::$languages[$name]['content'];
        }

        /* Include the language file */
        if(file_exists(self::$path . $name . '#' . self::$languages[$name]['code'] . '.php')) {
            self::$languages[$name]['content'] = require self::$path . $name . '#' . self::$languages[$name]['code'] . '.php';
        } else {
            self::$languages[$name]['content'] = require self::$path . $name . '#' . self::$languages[$name]['code'] . '#' . self::$languages[$name]['status'] . '.php';
        }

        /* Check the language file */
        if(is_null(self::$languages[$name]['content'])) {
            die('language.corrupted=Restore the original language file.');
        }

        /* Include the admin language file if needed */
        if(\Altum\Router::$path == 'admin') {
            if(file_exists(self::$path . 'admin/' . $name . '#' . self::$languages[$name]['code'] . '.php')) {
                $admin_language = require self::$path . 'admin/' . $name . '#' . self::$languages[$name]['code'] . '.php';
            } else {
                $admin_language = require self::$path . 'admin/' . $name . '#' . self::$languages[$name]['code'] . '#' . self::$languages[$name]['status'] . '.php';
            }

            /* Merge */
            self::$languages[$name]['content'] = self::$languages[$name]['content'] + $admin_language;
        }

        return self::$languages[$name]['content'];
    }

    public static function set_by_name($name) {

        if(array_key_exists($name, self::$languages)) {
            self::$name = self::$languages[$name]['name'];
            self::$code = self::$languages[$name]['code'];
            self::$status = self::$languages[$name]['status'];
        }

    }

    public static function set_by_code($code) {

        if($name = array_search($code, self::$active_languages)) {
            self::$name = self::$languages[$name]['name'];
            self::$code = self::$languages[$name]['code'];
            self::$status = self::$languages[$name]['status'];
        }

    }

    public static function set_default_by_name($name) {
        if(isset(self::$languages[$name])) {
            self::$default_name = self::$languages[$name]['name'];
            self::$default_code = self::$languages[$name]['code'];
        } else {
            self::$default_name = self::$languages[self::$main_name]['name'];
            self::$default_code = self::$languages[self::$main_name]['code'];
        }

        if(!isset(self::$name)) {
            self::$name = self::$languages[self::$default_name]['name'];
            self::$code = self::$languages[self::$default_name]['code'];
        }

    }
}
