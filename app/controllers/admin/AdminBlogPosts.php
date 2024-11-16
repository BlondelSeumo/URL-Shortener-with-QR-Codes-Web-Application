<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Models\BlogPosts;

class AdminBlogPosts extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['blog_posts_category_id', 'is_published'], ['title', 'description', 'keywords', 'url'], ['datetime', 'last_datetime']));
        $filters->set_default_order_by('blog_post_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `blog_posts` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/blog-posts?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $blog_posts = [];
        $blog_posts_result = database()->query("
            SELECT
                *
            FROM
                `blog_posts`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $blog_posts_result->fetch_object()) {
            $blog_posts[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get all blog posts categories */
        $blog_posts_categories = [];
        $blog_posts_result = database()->query("SELECT `blog_posts_category_id`, `title` FROM `blog_posts_categories`");
        while($row = $blog_posts_result->fetch_object()) {
            $blog_posts_categories[$row->blog_posts_category_id] = $row;
        }

        /* Main View */
        $data = [
            'blog_posts' => $blog_posts,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters,
            'blog_posts_categories' => $blog_posts_categories,
        ];

        $view = new \Altum\View('admin/blog-posts/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/blog-posts');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/blog-posts');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/blog-posts');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        (new BlogPosts())->delete($id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/blog-posts');
    }

    public function delete() {

        $blog_post_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$blog_post = db()->where('blog_post_id', $blog_post_id)->getOne('blog_posts', ['blog_post_id', 'title'])) {
            redirect('admin/blog-posts');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            (new BlogPosts())->delete($blog_post->blog_post_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $blog_post->title . '</strong>'));

        }

        redirect('admin/blog-posts');
    }

}
