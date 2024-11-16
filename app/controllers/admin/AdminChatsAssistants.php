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
use Altum\Models\ChatsAssistants;

class AdminChatsAssistants extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['name', 'total_usage']));
        $filters->set_default_order_by('chat_assistant_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `chats_assistants` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/chats-assistants?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $chats_assistants = [];
        $chats_assistants_result = database()->query("
            SELECT
                *
            FROM
                `chats_assistants`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $chats_assistants_result->fetch_object()) {
            $chats_assistants[] = $row;
        }

        /* Export handler */
        process_export_json($chats_assistants, 'include', ['chat_assistant_id', 'name', 'prompt', 'settings', 'image', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);
        process_export_csv($chats_assistants, 'include', ['chat_assistant_id', 'name', 'prompt', 'image', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'chats_assistants' => $chats_assistants,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/chats-assistants/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $chat_assistant_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template = db()->where('chat_assistant_id', $chat_assistant_id)->getOne('chats_assistants', ['chat_assistant_id', 'name'])) {
            redirect('admin/chats-assistants');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new ChatsAssistants())->delete($chat_assistant_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $template->name . '</strong>'));

        }

        redirect('admin/chats-assistants');
    }

}
