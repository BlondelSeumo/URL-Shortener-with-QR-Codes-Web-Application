<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Language;
use Altum\Meta;
use Altum\Title;

class Pages extends Controller {

    public function index() {

        if(!settings()->content->pages_is_enabled) {
            redirect('not-found');
        }

        /* Check if the category url is set */
        $pages_category_url = isset($this->params[0]) ? query_clean($this->params[0]) : null;
        $language = Language::$name;

        /* If the category url is set, get it*/
        if($pages_category_url) {

            /* Pages category index */
            $pages_category_query = "SELECT * FROM `pages_categories` WHERE (`url` = '{$pages_category_url}' AND `language` = '{$language}') OR (`url` = '{$pages_category_url}' AND `language` IS NULL)";
            $pages_category = $pages_category_url ? \Altum\Cache::cache_function_result('pages_category?hash=' . md5($pages_category_query), 'pages_categories', function() use ($pages_category_query) {
                return database()->query($pages_category_query)->fetch_object() ?? null;
            }) : null;

            /* Redirect to pages if the category is not found */
            if(!$pages_category) {
                redirect('not-found');
            }

            /* Get the pages for this category */
            $pages_result_query = "
                SELECT `url`, `title`, `description`, `total_views`, `type`, `language` 
                FROM `pages` 
                WHERE `pages_category_id` = {$pages_category->pages_category_id} AND (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 
                ORDER BY `total_views` DESC
            ";

            $pages = \Altum\Cache::cache_function_result('pages?hash=' . md5($pages_result_query), 'pages', function() use ($pages_result_query) {
                $pages_result = database()->query($pages_result_query);

                /* Iterate over the blog posts */
                $pages = [];

                while($row = $pages_result->fetch_object()) {
                    $pages[] = $row;
                }

                return $pages;
            });

            /* Prepare the view */
            $data = [
                'pages_category' => $pages_category,
                'pages' => $pages
            ];

            $view = new \Altum\View('pages/pages_category', (array) $this);

            /* Set a custom title */
            Title::set($pages_category->title);

            /* Meta */
            Meta::set_canonical_url();
            Meta::set_description($pages_category->description);

            /* Disable automated link language alternate */
            Meta::set_link_alternate(false);

        } else {

            /* Pages index */

            /* Get the popular pages */
            $popular_pages_result_query = "SELECT `url`, `title`, `description`, `total_views`, `type`, `language` FROM `pages` WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 ORDER BY `total_views` DESC LIMIT 6";

            $popular_pages = settings()->content->pages_popular_widget_is_enabled ? \Altum\Cache::cache_function_result('pages?hash=' . md5($popular_pages_result_query), 'pages', function() use ($popular_pages_result_query) {

                $pages_result = database()->query($popular_pages_result_query);

                /* Iterate over the blog posts */
                $popular_pages = [];

                while($row = $pages_result->fetch_object()) {
                    $popular_pages[] = $row;
                }

                return $popular_pages;
            }) : [];


            /* Get all the pages categories */
            $pages_categories_result_query = "
                SELECT 
                    `pages_categories`.`url`,
                    `pages_categories`.`title`,
                    `pages_categories`.`icon`,
                    `pages_categories`.`language`,
                    COUNT(`pages`.`page_id`) AS `total_pages`
                FROM `pages_categories`
                LEFT JOIN `pages` ON `pages`.`pages_category_id` = `pages_categories`.`pages_category_id`
                WHERE (`pages_categories`.`language` = '{$language}' OR `pages_categories`.`language` IS NULL)
                GROUP BY `pages_categories`.`pages_category_id`
                ORDER BY `pages_categories`.`order` ASC
            ";

            $pages_categories = \Altum\Cache::cache_function_result('pages?hash=' . md5($pages_categories_result_query), 'pages_categories', function() use ($pages_categories_result_query) {
                $pages_result = database()->query($pages_categories_result_query);

                /* Iterate over the blog posts */
                $pages_categories = [];

                while($row = $pages_result->fetch_object()) {
                    $pages_categories[] = $row;
                }

                return $pages_categories;
            });

            /* Meta */
            Meta::set_canonical_url();

            /* Prepare the view */
            $data = [
                'popular_pages' => $popular_pages,
                'pages_categories' => $pages_categories
            ];

            $view = new \Altum\View('pages/index', (array) $this);
        }

        $this->add_view_content('content', $view->run($data));

    }

}
