<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class BlogPostsCategories extends Model {

    public function get_blog_posts_categories_by_language($language) {

        /* Get the resources */
        $blog_posts_categories = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('blog_posts_categories?language=' . $language);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $blog_posts_categories_result = database()->query("
                SELECT * 
                FROM `blog_posts_categories`
                WHERE `language` = '{$language}' OR `language` IS NULL
                ORDER BY `order` ASC
            ");
            while($row = $blog_posts_categories_result->fetch_object()) $blog_posts_categories[$row->blog_posts_category_id] = $row;

            cache()->save(
                $cache_instance->set($blog_posts_categories)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('blog_posts_categories')
            );

        } else {

            /* Get cache */
            $blog_posts_categories = $cache_instance->get();

        }

        return $blog_posts_categories;

    }

}
