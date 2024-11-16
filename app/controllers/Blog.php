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
use Altum\Models\BlogPosts;
use Altum\Models\BlogPostsCategories;
use Altum\Title;

class Blog extends Controller {

    public function index() {

        if(!settings()->content->blog_is_enabled) {
            redirect('not-found');
        }

        $language = Language::$name;

        /* Blog RSS */
        if(isset($this->params[0]) && $this->params[0] == 'feed') {
            /* Set the header as xml so the browser can read it properly */
            header('Content-Type: text/xml');

            $blog_posts = db()->where('is_published', 1)->get('blog_posts', null, ['title', 'description', 'url', 'language', 'datetime']);

            /* Prepare the view */
            $data = [
                'blog_posts' => $blog_posts
            ];

            $view = new \Altum\View('blog/blog_rss', (array) $this);

            echo $view->run($data);

            die();
        }

        /* Blog post */
        if(isset($this->params[0]) && $this->params[0] != 'category') {
            $url = query_clean($this->params[0]);

            $blog_post_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE ((`url` = '{$url}' AND `language` = '{$language}') OR (`url` = '{$url}' AND `language` IS NULL)) AND `is_published` = 1
            ";
            $blog_post = \Altum\Cache::cache_function_result('blog_post?hash=' . md5($blog_post_query), 'blog_posts', function() use ($blog_post_query) {
                return database()->query($blog_post_query)->fetch_object() ?? null;
            });

            if(!$blog_post) {
                redirect('not-found');
            }

            /* Transform content if needed */
            $blog_post->content = json_decode($blog_post->content) ? convert_editorjs_json_to_html($blog_post->content) : nl2br($blog_post->content);

            /* Get the blog post category */
            $blog_posts_category = \Altum\Cache::cache_function_result('blog_posts_category?hash=' . md5($blog_post->blog_posts_category_id ?? ''), 'blog_posts_categories', function() use ($blog_post) {
                return $blog_post->blog_posts_category_id ? db()->where('blog_posts_category_id', $blog_post->blog_posts_category_id)->getOne('blog_posts_categories') : null;
            });

            /* Add a new view to the post */
            $cookie_name = 'blog_post_view_' . $blog_post->blog_post_id;
            if(!isset($_COOKIE[$cookie_name])) {
                db()->where('blog_post_id', $blog_post->blog_post_id)->update('blog_posts', ['total_views' => db()->inc()]);
                setcookie($cookie_name, (int) true, time()+60*60*24*1);
            }

            /* Set a custom title */
            Title::set(sprintf(l('blog.blog_post.title'), $blog_post->title));

            /* Meta */
            Meta::set_canonical_url();
            Meta::set_description($blog_post->description);
            Meta::set_keywords($blog_post->keywords);
            if($blog_post->image) {
                Meta::set_social_url(url(\Altum\Router::$original_request));
                Meta::set_social_image(\Altum\Uploads::get_full_url('blog') . $blog_post->image);
            }

            /* Disable automated link language alternate */
            Meta::set_link_alternate(false);

            /* Get all the categories */
            $blog_posts_categories = settings()->content->blog_categories_widget_is_enabled ? (new BlogPostsCategories())->get_blog_posts_categories_by_language($language) : [];

            /* Get popular posts */
            $blog_posts_popular = settings()->content->blog_popular_widget_is_enabled ? (new BlogPosts())->get_popular_blog_posts_by_language($language) : [];

            /* Prepare the view */
            $data = [
                'blog_post' => $blog_post,
                'blog_posts_category' => $blog_posts_category,
                'blog_posts_categories' => $blog_posts_categories,
                'blog_posts_popular' => $blog_posts_popular,
            ];

            $view = new \Altum\View('blog/blog_post', (array) $this);

            $this->add_view_content('content', $view->run($data));
        }

        /* Blog category */
        else if(isset($this->params[0], $this->params[1]) && $this->params[0] == 'category') {
            $url = query_clean($this->params[1]);

            $blog_posts_category_query = "
                SELECT * 
                FROM `blog_posts_categories`
                WHERE (`url` = '{$url}' AND `language` = '{$language}') OR (`url` = '{$url}' AND `language` IS NULL)
                ORDER BY `language` DESC
            ";
            $blog_posts_category = \Altum\Cache::cache_function_result('blog_posts_category?hash=' . md5($blog_posts_category_query), 'blog_posts_categories', function() use ($blog_posts_category_query) {
                return database()->query($blog_posts_category_query)->fetch_object() ?? null;
            });

            if(!$blog_posts_category) {
                redirect('not-found');
            }

            /* Get the posts */
            /* Prepare the filtering system */
            $filters = (new \Altum\Filters());
            $filters->set_default_order_by('datetime', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
            $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

            /* Prepare the paginator */
            $total_rows_query = "SELECT COUNT(*) AS `total` FROM `blog_posts` WHERE `blog_posts_category_id` = {$blog_posts_category->blog_posts_category_id} AND (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 {$filters->get_sql_where()}";
            $total_rows = \Altum\Cache::cache_function_result('blog_posts_count?hash=' . md5($total_rows_query), 'blog_posts', function() use ($total_rows_query) {
                return database()->query($total_rows_query)->fetch_object()->total ?? 0;
            });
            $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('blog/category/' . $blog_posts_category->url . '?' . $filters->get_get() . '&page=%d')));

            /* Blog posts query */
            $blog_posts_result_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE `blog_posts_category_id` = {$blog_posts_category->blog_posts_category_id} AND (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                {$paginator->get_sql_limit()}
            ";

            $blog_posts = \Altum\Cache::cache_function_result('blog_posts?hash=' . md5($blog_posts_result_query), 'blog_posts', function() use ($blog_posts_result_query) {
                $blog_posts_result = database()->query($blog_posts_result_query);

                /* Iterate over the blog posts */
                $blog_posts = [];

                while($row = $blog_posts_result->fetch_object()) {
                    /* Transform content if needed */
                    $row->content = json_decode($row->content) ? convert_editorjs_json_to_html($row->content) : nl2br($row->content);

                    $blog_posts[] = $row;
                }

                return $blog_posts;
            });

            /* Prepare the pagination view */
            $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

            /* Get all the categories */
            $blog_posts_categories = settings()->content->blog_categories_widget_is_enabled ? (new BlogPostsCategories())->get_blog_posts_categories_by_language($language) : [];

            /* Get popular posts */
            $blog_posts_popular = settings()->content->blog_popular_widget_is_enabled ? (new BlogPosts())->get_popular_blog_posts_by_language($language) : [];

            /* Set a custom title */
            Title::set(sprintf(l('blog.blog_posts_category.title'), $blog_posts_category->title));

            /* Meta */
            Meta::set_canonical_url();
            Meta::set_description($blog_posts_category->description);

            /* Disable automated link language alternate */
            Meta::set_link_alternate(false);

            /* Prepare the view */
            $data = [
                'blog_posts_category' => $blog_posts_category,
                'blog_posts' => $blog_posts,
                'pagination' => $pagination,
                'blog_posts_categories' => $blog_posts_categories,
                'blog_posts_popular' => $blog_posts_popular,
            ];

            $view = new \Altum\View('blog/blog_posts_category', (array) $this);

            $this->add_view_content('content', $view->run($data));
        }

        /* Blog index */
        else {

            /* Get the posts */
            /* Prepare the filtering system */
            $filters = (new \Altum\Filters([], ['title']));
            $filters->set_default_order_by('datetime', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
            $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

            /* Prepare the paginator */
            $total_rows_query = "SELECT COUNT(*) AS `total` FROM `blog_posts` WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 {$filters->get_sql_where()}";
            $total_rows = \Altum\Cache::cache_function_result('blog_posts_count?hash=' . md5($total_rows_query), 'blog_posts', function() use ($total_rows_query) {
                return database()->query($total_rows_query)->fetch_object()->total ?? 0;
            });
            $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('blog?' . $filters->get_get() . '&page=%d')));

            /* Blog posts query */
            $blog_posts_result_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                {$paginator->get_sql_limit()}
            ";

            $blog_posts = \Altum\Cache::cache_function_result('blog_posts?hash=' . md5($blog_posts_result_query), 'blog_posts', function() use ($blog_posts_result_query) {
                $blog_posts_result = database()->query($blog_posts_result_query);

                /* Iterate over the blog posts */
                $blog_posts = [];

                while($row = $blog_posts_result->fetch_object()) {
                    /* Transform content if needed */
                    $row->content = json_decode($row->content) ? convert_editorjs_json_to_html($row->content) : nl2br($row->content);

                    $blog_posts[] = $row;
                }

                return $blog_posts;
            });

            /* Prepare the pagination view */
            $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

            /* Get all the categories */
            $blog_posts_categories = settings()->content->blog_categories_widget_is_enabled ? (new BlogPostsCategories())->get_blog_posts_categories_by_language($language) : [];

            /* Get popular posts */
            $blog_posts_popular = settings()->content->blog_popular_widget_is_enabled ? (new BlogPosts())->get_popular_blog_posts_by_language($language) : [];

            if(!empty($_GET['search'])) {
                /* Set a custom title */
                Title::set(sprintf(l('blog.title_search'), input_clean($_GET['search'])));

                /* Meta */
                Meta::set_robots('noindex');
            }

            /* Prepare the view */
            $data = [
                'blog_posts' => $blog_posts,
                'pagination' => $pagination,
                'filters' => $filters,
                'blog_posts_categories' => $blog_posts_categories,
                'blog_posts_popular' => $blog_posts_popular,
            ];

            $view = new \Altum\View('blog/index', (array) $this);

            $this->add_view_content('content', $view->run($data));
        }
    }

}
