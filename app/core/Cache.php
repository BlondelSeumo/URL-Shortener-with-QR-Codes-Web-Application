<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

/* Simple wrapper for phpFastCache */

class Cache {
    public static $adapter;

    public static function initialize($force_enable = false) {

        $driver = $force_enable ? 'Files' : (CACHE ? 'Files' : 'Devnull');

        /* Cache adapter for phpFastCache */
        if($driver == 'Files') {
            $config = new \Phpfastcache\Drivers\Files\Config([
                'securityKey' => PRODUCT_KEY,
                'path' => UPLOADS_PATH . 'cache',
                'preventCacheSlams' => true,
                'cacheSlamsTimeout' => 20,
                'secureFileManipulation' => true
            ]);
        } else {
            $config = new \Phpfastcache\Config\Config([
                'path' => UPLOADS_PATH . 'cache',
            ]);
        }

        \Phpfastcache\CacheManager::setDefaultConfig($config);

        self::$adapter = \Phpfastcache\CacheManager::getInstance($driver);
    }

    public static function cache_function_result($key, $tag, $function_to_cache, $cached_seconds = CACHE_DEFAULT_SECONDS) {
        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem($key);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $result = $function_to_cache();

            $cache_item = $cache_instance->set($result)->expiresAfter($cached_seconds);

            if($tag) {
                if(is_array($tag)) {
                    foreach($tag as $tag_key) $cache_item->addTag($tag_key);
                } else {
                    $cache_item->addTag($tag);
                }
            }

            cache()->save($cache_item);

        } else {

            /* Get cache */
            $result = $cache_instance->get();

        }

        return $result;
    }

}
