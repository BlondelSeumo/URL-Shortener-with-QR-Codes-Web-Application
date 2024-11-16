<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Settings {
    public static $settings = null;

    public static function initialize() {

        self::$settings = (new \Altum\Models\Settings())->get();

    }

    public static function get() {
        return self::$settings;
    }
}
