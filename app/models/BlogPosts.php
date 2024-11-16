<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class BlogPosts extends Model {

    public function get_popular_blog_posts_by_language($language) {

        /* Get the resources */
        $blog_posts = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('blog_posts?type=popular&language=' . $language);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $blog_posts_result = database()->query("
                SELECT * 
                FROM `blog_posts`
                WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1
                ORDER BY `total_views` DESC
                LIMIT 5
            ");
            while($row = $blog_posts_result->fetch_object()) $blog_posts[$row->blog_post_id] = $row;

            cache()->save(
                $cache_instance->set($blog_posts)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('blog_posts')
            );

        } else {

            /* Get cache */
            $blog_posts = $cache_instance->get();

        }

        return $blog_posts;

    }

    public function delete($blog_post_id) {

        $blog_post = db()->where('blog_post_id', $blog_post_id)->getOne('blog_posts', ['blog_post_id', 'image']);

        if(!$blog_post) return;

        \Altum\Uploads::delete_uploaded_file($blog_post->image, 'blog');

        /* Delete the resource */
        db()->where('blog_post_id', $blog_post_id)->delete('blog_posts');

        /* Clear the cache */
        cache()->deleteItemsByTag('blog_posts');

    }

}
