<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Sitemap extends Controller {

    public function index() {

        /* Set the header as xml so the browser can read it properly */
        header('Content-Type: text/xml');

        /* How many external users per sitemap page */
        $pagination = 5000;

        $page = isset($this->params[0]) ? $this->params[0] : null;

        /* Different answers for different parts */
        switch($page) {

            /* Sitemap index */
            case null:

                /* Get the total amount of links */
                $total_links = database()->query("
                    SELECT
                        COUNT(`links`.`link_id`) AS `total` 
                    FROM 
                        `links`
                    LEFT JOIN
                        `users` ON `links`.`user_id` = `users`.`user_id`
                    WHERE
                        `users`.`status` = 1
                        AND `links`.`is_enabled` = 1
                        AND `links`.`type` = 'biolink'
                        AND `links`.`domain_id` = 0
                  ")->fetch_object()->total ?? 0;

                /* Calculate the needed sitemaps */
                $total_sitemaps = 1 + ceil((int) $total_links / $pagination);

                /* Main View */
                $data = [
                    'total_sitemaps' => $total_sitemaps
                ];

                $view = new \Altum\View('sitemap/sitemap_index', (array) $this);

                break;

            /* Output base pages like the homepage, register..etc*/
            case 1:

                /* Get all pages & categories */
                if(settings()->content->pages_is_enabled) {
                    $pages = db()->where('type', 'internal')->where('is_published', 1)->get('pages', null, ['url', 'language']);
                    $pages_categories = db()->get('pages_categories', null, ['url', 'language']);
                }

                /* Get all blog posts & blog categories */
                if(settings()->content->blog_is_enabled) {
                    $blog_posts = db()->where('is_published', 1)->get('blog_posts', null, ['url', 'language']);
                    $blog_posts_categories = db()->get('blog_posts_categories', null, ['url', 'language']);
                }

                /* Main View */
                $data = [
                    'pages' => $pages ?? null,
                    'pages_categories' => $pages_categories ?? null,
                    'blog_posts' => $blog_posts ?? [],
                    'blog_posts_categories' => $blog_posts_categories ?? null,
                ];

                $view = new \Altum\View('sitemap/sitemap_1', (array) $this);

                break;

            /* Output only indexed external users */
            default:

                $limit_start = ($page - 2) * $pagination;

                /* Get the external users list */
                $links_result = database()->query("
                    SELECT
                        `links`.`url`,
                        `links`.`datetime`
                    FROM 
                        `links`
                    LEFT JOIN
                        `users` ON `links`.`user_id` = `users`.`user_id`
                    WHERE
                        `users`.`status` = 1
                        AND `links`.`is_enabled` = 1
                        AND `links`.`type` = 'biolink'
                        AND `links`.`domain_id` = 0
                    LIMIT 
                        {$limit_start}, {$pagination}
                ");

                /* Main View */
                $data = [
                    'links_result' => $links_result
                ];

                $view = new \Altum\View('sitemap/sitemap_x', (array) $this);

                break;

        }


        echo $view->run($data);

        die();
    }

}
