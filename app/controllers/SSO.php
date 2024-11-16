<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

class SSO extends Controller {

    public function index() {
        redirect('not-found');
    }

    public function switch() {

        \Altum\Authentication::guard();

        $to = isset($_GET['to']) && array_key_exists($_GET['to'], (array) settings()->sso->websites) ? input_clean($_GET['to']) : null;
        $redirect = isset($_GET['redirect']) ? input_clean($_GET['redirect']) : 'dashboard';

        if(!$to) {
            redirect();
        }

        $website = settings()->sso->websites->{$to};

        $response = \Unirest\Request::post(
            $website->url . 'admin-api/sso/login',
            ['Authorization' => 'Bearer ' . $website->api_key],
            \Unirest\Request\Body::form([
                'email' => $this->user->email,
                'name' => $this->user->name,
                'redirect' => $redirect,
            ])
        );

        /* Check against errors */
        if($response->code >= 400) {
            \Altum\Alerts::add_error($response->body->errors[0]->title);
            redirect('dashboard');
        }

        /* Redirect */
        header('Location: ' . $response->body->data->url); die();

    }

}
