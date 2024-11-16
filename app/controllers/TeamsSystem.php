<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class TeamsSystem extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Get data about the teams */
        $total_teams = db()->where('user_id', $this->user->user_id)->getValue('teams', 'count(*)');
        $total_teams_member = db()->where('user_id', $this->user->user_id)->orWhere('user_email', $this->user->email)->getValue('teams_members', 'count(*)');

        /* Prepare the view */
        $data = [
            'total_teams' => $total_teams,
            'total_teams_member' => $total_teams_member,
        ];

        $view = new \Altum\View('teams-system/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
