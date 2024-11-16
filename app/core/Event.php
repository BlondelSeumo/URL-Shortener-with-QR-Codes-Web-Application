<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Event {
    /* For events */
    public static $callbacks = [];

    /* For extra content, such as javascript */
    public static $content = [];

    public static function bind($event, Callable $function) {
        if(empty(self::$callbacks[$event]) || !is_array(self::$callbacks[$event])){
            self::$callbacks[$event] = [];
        }

        self::$callbacks[$event][] = $function;
    }

    public static function trigger() {
        $args = func_get_args();
        $event = $args[0];
        unset($args[0]);

        if(isset(self::$callbacks[$event])) {
            foreach(self::$callbacks[$event] as $func) {
                call_user_func_array($func, $args);
            }
        }
    }

    public static function exists_content_type($type) {
        return isset(self::$content[$type]);
    }

    public static function exists_content_type_key($type, $key) {
        return self::exists_content_type($type) && isset(self::$content[$type][$key]);
    }

    public static function add_content($content, $type, $key = null) {

        if(!isset(self::$content[$type])) {
            self::$content[$type] = [];
        }

        if(isset($key)) {
            self::$content[$type][$key] = $content;
        } else {
            self::$content[$type][] = $content;
        }

    }

    public static function get_content($type) {

        $full_content = '';

        if(isset(self::$content[$type])) {
            foreach(self::$content[$type] as $key => $value) {

                $full_content .= $value;

            }
        }

        return $full_content;

    }
}
