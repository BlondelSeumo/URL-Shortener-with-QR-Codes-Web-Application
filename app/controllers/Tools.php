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
use Altum\Meta;
use Altum\Title;
use MaxMind\Db\Reader;

class Tools extends Controller {

    public function index() {

        if(!settings()->tools->is_enabled) {
            redirect('not-found');
        }

        if(settings()->tools->access == 'users') {
            \Altum\Authentication::guard();
        }

        $tools = require APP_PATH . 'includes/tools/tools.php';
        $tools_usage = (new \Altum\Models\Tools())->get_tools_usage();

        /* Prepare the view */
        $data = [
            'tools' => $tools,
            'tools_usage' => $tools_usage,
        ];

        $view = new \Altum\View('tools/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    private function initiate() {
        require_once APP_PATH . 'helpers/LoremIpsum.php';
        require_once APP_PATH . 'helpers/Parsedown.php';

        if(!settings()->tools->is_enabled) {
            redirect('not-found');
        }

        if(settings()->tools->access == 'users') {
            \Altum\Authentication::guard();
        }

        if(!settings()->tools->available_tools->{\Altum\Router::$method}) {
            redirect('tools');
        }

        /* Add a new view to the page */
        $cookie_name = 't_statistics_' . \Altum\Router::$method;
        if(!isset($_COOKIE[$cookie_name])) {
            setcookie($cookie_name, (int) true, time()+60*60*24*1);
            db()->onDuplicate(['total_views'], 'id');
            db()->insert('tools_usage', [
                'tool_id' => \Altum\Router::$method,
                'total_views' => db()->inc(),
            ]);
        }

        $tools_usage = (new \Altum\Models\Tools())->get_tools_usage();

        /* Popular tools View */
        $view = new \Altum\View('tools/popular_tools', (array) $this);
        $this->add_view_content('popular_tools', $view->run([
            'tools_usage' => $tools_usage,
            'tools' => require APP_PATH . 'includes/tools/tools.php',
        ]));

        /* Similar tools View */
        $view = new \Altum\View('tools/similar_tools', (array) $this);
        $this->add_view_content('similar_tools', $view->run([
            'tools_usage' => $tools_usage,
            'tool' => \Altum\Router::$method,
            'tools' => require APP_PATH . 'includes/tools/tools.php',
        ]));

        /* Extra content View */
        $view = new \Altum\View('tools/extra_content', (array) $this);
        $this->add_view_content('extra_content', $view->run());

        /* Meta & title */
        Title::set(sprintf(l('tools.tool_title'), l('tools.' . \Altum\Router::$method . '.name')));
        Meta::set_description(l('tools.' . \Altum\Router::$method . '.description'));
        Meta::set_keywords(l('tools.' . \Altum\Router::$method . '.meta_keywords'));
    }

    public function dns_lookup() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['host'] = input_clean($_POST['host']);

            if(filter_var($_POST['host'], FILTER_VALIDATE_URL)) {
                $_POST['host'] = parse_url($_POST['host'], PHP_URL_HOST);
            }

            /* Check for any errors */
            $required_fields = ['host'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $data['result'] = [];

            foreach([DNS_A, DNS_AAAA, DNS_CNAME, DNS_MX, DNS_NS, DNS_TXT, DNS_SOA, DNS_CAA] as $dns_type) {
                $dns_records = @dns_get_record($_POST['host'] . '.', $dns_type);

                if($dns_records) {
                    foreach($dns_records as $dns_record) {
                        if(!isset($data['result'][$dns_record['type']])) {
                            $data['result'][$dns_record['type']] = [$dns_record];
                        } else {
                            $data['result'][$dns_record['type']][] = $dns_record;
                        }
                    }
                }
            }

            if(empty($data['result'])) {
                Alerts::add_field_error('host', l('tools.dns_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                // :)
            }
        }

        $values = [
            'host' => $_POST['host'] ?? '',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/dns_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ip_lookup() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['ip'] = input_clean($_POST['ip']);

            /* Check for any errors */
            $required_fields = ['ip'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
                Alerts::add_field_error('ip', l('tools.ip_lookup.error_message'));
            }

            try {
                $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get($_POST['ip']);
            } catch(\Exception $exception) {
                Alerts::add_field_error('ip', l('tools.ip_lookup.error_message'));
            }

            if(!$maxmind) {
                Alerts::add_field_error('ip', l('tools.ip_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = $maxmind;
            }
        }

        $values = [
            'ip' => $_POST['ip'] ?? get_ip(),
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ip_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function reverse_ip_lookup() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['ip'] = input_clean($_POST['ip']);

            /* Check for any errors */
            $required_fields = ['ip'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
                Alerts::add_field_error('ip', l('tools.reverse_ip_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = gethostbyaddr($_POST['ip']);
            }
        }

        $values = [
            'ip' => $_POST['ip'] ?? get_ip(),
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/reverse_ip_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ssl_lookup() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['host'] = trim(query_clean($_POST['host']));
            $_POST['port'] = (int) $_POST['port'];

            if(filter_var($_POST['host'], FILTER_VALIDATE_URL)) {
                $_POST['host'] = parse_url($_POST['host'], PHP_URL_HOST);
            }

            /* Check for any errors */
            $required_fields = ['host'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for an SSL certificate */
            $certificate = get_website_certificate('https://' . $_POST['host'], $_POST['port']);

            if(!$certificate) {
                Alerts::add_field_error('host', l('tools.ssl_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $certificate;

            }
        }

        $values = [
            'host' => $_POST['host'] ?? '',
            'port' => $_POST['port'] ?? 443,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ssl_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function whois_lookup() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['domain_name'] = input_clean($_POST['domain_name']);

            if(filter_var($_POST['domain_name'], FILTER_VALIDATE_URL)) {
                $_POST['domain_name'] = parse_url($_POST['domain_name'], PHP_URL_HOST);
            }

            /* Check for any errors */
            $required_fields = ['domain_name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            try {
                $get_whois = \Iodev\Whois\Factory::get()->createWhois();
                $whois_info = $get_whois->loadDomainInfo($_POST['domain_name']);
            } catch (\Exception $e) {
                Alerts::add_field_error('domain_name', l('tools.whois_lookup.error_message'));
            }

            $whois = isset($whois_info) && $whois_info ? [
                'start_datetime' => $whois_info->creationDate ? (new \DateTime())->setTimestamp($whois_info->creationDate)->format('Y-m-d H:i:s') : null,
                'updated_datetime' => $whois_info->updatedDate ? (new \DateTime())->setTimestamp($whois_info->updatedDate)->format('Y-m-d H:i:s') : null,
                'end_datetime' => $whois_info->expirationDate ? (new \DateTime())->setTimestamp($whois_info->expirationDate)->format('Y-m-d H:i:s') : null,
                'registrar' => $whois_info->registrar,
                'nameservers' => $whois_info->nameServers,
            ] : [];

            if(empty($whois)) {
                Alerts::add_field_error('domain_name', l('tools.whois_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $whois;

            }
        }

        $values = [
            'domain_name' => $_POST['domain_name'] ?? '',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/whois_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ping() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['type'] = in_array($_POST['type'], ['website', 'ping', 'port']) ? input_clean($_POST['type']) : 'website';
            $_POST['target'] = input_clean($_POST['target']);
            $_POST['port'] = isset($_POST['port']) ? (int) $_POST['port'] : 0;

            /* Check for any errors */
            $required_fields = ['target'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

//            if(empty($whois)) {
//                Alerts::add_field_error('domain_name', l('tools.whois_lookup.error_message'));
//            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $target = (new \StdClass());
                $target->type = $_POST['type'];
                $target->target = $_POST['target'];
                $target->port = $_POST['port'] ?? 0;
                $target->ping_servers_ids = [1];
                $target->settings = (new \StdClass());
                $target->settings->timeout_seconds = 5;
                $target->settings->request_method = 'get';
                $target->settings->request_basic_auth_username = '';
                $target->settings->request_basic_auth_password = '';
                $target->settings->request_headers = [];
                $target->settings->response_status_code = 200;

                $check = ping($target);

                $data['result'] = $check;

            }
        }

        $values = [
            'type' => $_POST['type'] ?? '',
            'target' => $_POST['target'] ?? '',
            'port' => $_POST['port'] ?? '',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ping', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function md5_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = md5($_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/md5_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function md2_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('md2', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/md2_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function md4_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('md4', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/md4_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function whirlpool_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('whirlpool', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/whirlpool_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha1_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha1', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha1_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha224_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha224', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha224_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha256_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha256', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha256_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha384_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha384', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha384_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha512_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha512', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha512_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha512_224_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha512/224', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha512_224_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha512_256_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha512/256', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha512_256_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha3_224_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha3-224', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha3_224_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha3_256_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha3-256', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha3_256_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha3_384_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha3-384', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha3_384_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sha3_512_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = hash('sha3-512', $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sha3_512_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function base64_encoder() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = base64_encode($_POST['content']);

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/base64_encoder', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function base64_decoder() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = base64_decode($_POST['content']);

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/base64_decoder', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function base64_to_image() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $_POST['content'];

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/base64_to_image', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function image_to_base64() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            /* Check for any errors */
            $required_fields = [];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Image uploads */
            $image = !empty($_FILES['image']['name']);

            /* Check for any errors on the logo image */
            if($image) {
                $image_file_name = $_FILES['image']['name'];
                $image_file_extension = explode('.', $image_file_name);
                $image_file_extension = mb_strtolower(end($image_file_extension));
                $image_file_temp = $_FILES['image']['tmp_name'];

                if($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), get_max_upload()));
                }

                if($_FILES['image']['error'] && $_FILES['image']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($image_file_extension, ['gif', 'png', 'jpg', 'jpeg', 'svg'])) {
                    Alerts::add_error(l('global.error_message.invalid_file_type'));
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                    $data['result'] = base64_encode(file_get_contents($image_file_temp));
                }
            }

        }

        $values = [
            'image' => $_POST['image'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/image_to_base64', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function url_encoder() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = urlencode($_POST['content']);

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/url_encoder', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function url_decoder() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = urldecode($_POST['content']);

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/url_decoder', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function lorem_ipsum_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['amount'] = (int) $_POST['amount'];
            $_POST['type'] = in_array($_POST['type'], ['paragraphs', 'sentences', 'words']) ? $_POST['type'] : 'paragraphs';

            /* Check for any errors */
            $required_fields = ['amount'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $lipsum = new \joshtronic\LoremIpsum();

                switch($_POST['type']) {
                    case 'paragraphs':
                        $data['result'] = $lipsum->paragraphs($_POST['amount']);
                        break;

                    case 'sentences':
                        $data['result'] = $lipsum->sentences($_POST['amount']);
                        break;

                    case 'words':
                        $data['result'] = $lipsum->words($_POST['amount']);
                        break;
                }

            }
        }

        $values = [
            'amount' => $_POST['amount'] ?? 1,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/lorem_ipsum_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function markdown_to_html() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['markdown'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $parsedown = new \Parsedown();
                $data['result'] = $parsedown->text($_POST['markdown']);

            }
        }

        $values = [
            'markdown' => $_POST['markdown'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/markdown_to_html', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function case_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);
            $_POST['type'] = in_array($_POST['type'], ['lowercase', 'uppercase', 'sentencecase', 'camelcase', 'pascalcase', 'capitalcase', 'constantcase', 'dotcase', 'snakecase', 'paramcase']) ? $_POST['type'] : 'lowercase';

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($_POST['type']) {
                    case 'lowercase':
                        $data['result'] = mb_strtolower($_POST['text']);
                        break;

                    case 'uppercase':
                        $data['result'] = mb_strtoupper($_POST['text']);
                        break;

                    case 'sentencecase':
                        $data['result'] = ucfirst(mb_strtolower($_POST['text']));
                        break;

                    case 'camelcase':
                        $words = explode(' ', $_POST['text']);

                        $pascalcase_words = array_map(function($word) {
                            return ucfirst($word);
                        }, $words);

                        $pascalcase = implode($pascalcase_words);

                        $data['result'] = lcfirst($pascalcase);
                        break;

                    case 'pascalcase':
                        $words = explode(' ', string_filter_alphanumeric($_POST['text']));

                        $pascalcase_words = array_map(function($word) {
                            return ucfirst($word);
                        }, $words);

                        $pascalcase = implode($pascalcase_words);

                        $data['result'] = $pascalcase;
                        break;

                    case 'capitalcase':
                        $data['result'] = ucwords($_POST['text']);
                        break;

                    case 'constantcase':
                        $data['result'] = mb_strtoupper(str_replace(' ', '_', trim(string_filter_alphanumeric($_POST['text']))));
                        break;

                    case 'dotcase':
                        $data['result'] = mb_strtolower(str_replace(' ', '.', trim(string_filter_alphanumeric($_POST['text']))));
                        break;

                    case 'snakecase':
                        $data['result'] = mb_strtolower(str_replace(' ', '_', trim(string_filter_alphanumeric($_POST['text']))));
                        break;

                    case 'paramcase':
                        $data['result'] = mb_strtolower(str_replace(' ', '-', trim(string_filter_alphanumeric($_POST['text']))));
                        break;
                }


            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/case_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function random_number_generator() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['minimum'] = (int) $_POST['minimum'];
            $_POST['maximum'] = (int) $_POST['maximum'];

            /* Check for any errors */
            if($_POST['minimum'] > $_POST['maximum']) {
                $_POST['minimum'] = 0;
                $_POST['maximum'] = 100;
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = rand($_POST['minimum'], $_POST['maximum']);

            }
        }

        $values = [
            'minimum' => $_POST['minimum'] ?? 0,
            'maximum' => $_POST['maximum'] ?? 100,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/random_number_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function uuid_v4_generator() {
        $this->initiate();

        $data = [];

        /* Generate UUID */
        $data['result'] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/uuid_v4_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bcrypt_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = password_hash($_POST['text'], PASSWORD_DEFAULT);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bcrypt_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function password_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['characters'] = (int) mb_substr($_POST['characters'], 0, 2048);
            $_POST['numbers'] = (int) isset($_POST['numbers']);
            $_POST['symbols'] = (int) isset($_POST['symbols']);
            $_POST['lowercase'] = (int) isset($_POST['lowercase']);
            $_POST['uppercase'] = (int) isset($_POST['uppercase']);

            /* Check for any errors */
            $required_fields = ['characters'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $available_characters = '';

                if($_POST['numbers']) $available_characters .= '0123456789';
                if($_POST['symbols']) $available_characters .= '!@#$%^&*()_+=-[],./\\\'<>?:"|{}';
                if($_POST['lowercase']) $available_characters .= 'abcdefghijklmnopqrstuvwxyz';
                if($_POST['uppercase']) $available_characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $available_characters = str_split($available_characters);
                shuffle($available_characters);

                $password = '';

                for($i = 1; $i <= $_POST['characters']; $i++) {
                    $password .= $available_characters[array_rand($available_characters)];
                }

                $data['result'] = $password;

            }
        }

        $values = [
            'characters' => $_POST['characters'] ?? 8,
            'numbers' => $_POST['numbers'] ?? true,
            'symbols' => $_POST['symbols'] ?? true,
            'lowercase' => $_POST['lowercase'] ?? true,
            'uppercase' => $_POST['uppercase'] ?? true,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/password_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function password_strength_checker() {
        $this->initiate();

        $data = [];

        $values = [
            'password' => $_POST['password'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/password_strength_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function slug_generator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                mb_internal_encoding('utf-8');

                /* Replace all non words characters with the specified $delimiter */
                $string = preg_replace('/[^\p{L}\d-]+/u', '-', $_POST['text']);

                /* Check for double $delimiters and remove them so it only will be 1 delimiter */
                $string = preg_replace('/-+/u', '-', $string);

                /* Remove the $delimiter character from the start and the end of the string */
                $string = trim($string, '-');

                /* lowercase */
                $string = mb_strtolower($string);

                $data['result'] = $string;

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/slug_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function html_minifier() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['html'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $htmldoc = new \hexydec\html\htmldoc();
            $htmldoc_load = $htmldoc->load($_POST['html']);

            if(!$htmldoc_load) {
                Alerts::add_field_error('css', l('tools.html_minifier.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $htmldoc->minify();
                $data['result'] = $htmldoc->save() ?? null;

                $data['html_characters'] = mb_strlen($_POST['html']);
                $data['minified_html_characters'] = mb_strlen($data['result']);
            }
        }

        $values = [
            'html' => $_POST['html'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/html_minifier', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function css_minifier() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['css'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $cssdoc = new \hexydec\css\cssdoc();
            $cssdoc_load = $cssdoc->load($_POST['css']);

            if(!$cssdoc_load) {
                Alerts::add_field_error('css', l('tools.css_minifier.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $cssdoc->minify();
                $data['result'] = $cssdoc->save() ?? null;

                $data['css_characters'] = mb_strlen($_POST['css']);
                $data['minified_css_characters'] = mb_strlen($data['result']);
            }
        }

        $values = [
            'css' => $_POST['css'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/css_minifier', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function js_minifier() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['js'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $jsdoc = new \hexydec\jslite\jslite();
            $jsdoc_load = $jsdoc->load($_POST['js']);

            if(!$jsdoc_load) {
                Alerts::add_field_error('js', l('tools.js_minifier.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $jsdoc->minify();
                $data['result'] = $jsdoc->compile() ?? null;

                $data['js_characters'] = mb_strlen($_POST['js']);
                $data['minified_js_characters'] = mb_strlen($data['result']);
            }
        }

        $values = [
            'js' => $_POST['js'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/js_minifier', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function user_agent_parser() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['user_agent'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $whichbrowser = new \WhichBrowser\Parser($_POST['user_agent']);

                $data['result']['browser_name'] = $whichbrowser->browser->name ?? null;
                $data['result']['browser_version'] = $whichbrowser->browser->version->value ?? null;
                $data['result']['os_name'] = $whichbrowser->os->name ?? null;
                $data['result']['os_version'] = $whichbrowser->os->version->value ?? null;
                $data['result']['device_type'] = $whichbrowser->device->type ?? null;

            }
        }

        $values = [
            'user_agent' => $_POST['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/user_agent_parser', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function website_hosting_checker() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['host'] = input_clean($_POST['host']);

            if(filter_var($_POST['host'], FILTER_VALIDATE_URL)) {
                $_POST['host'] = parse_url($_POST['host'], PHP_URL_HOST);
            }

            /* Check for any errors */
            $required_fields = ['host'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Get ip of host */
            $host_ip = gethostbyname($_POST['host']);

            /* Check via ip-api */
            $response = \Unirest\Request::get('http://ip-api.com/json/' . $host_ip);

            if(empty($response->raw_body) || $response->body->status == 'fail') {
                Alerts::add_field_error('host', l('tools.website_hosting_checker.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $response->body;

            }
        }

        $values = [
            'host' => $_POST['host'] ?? '',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/website_hosting_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function file_mime_type_checker() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/file_mime_type_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gravatar_checker() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            /* Check for any errors */
            $required_fields = ['email'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = [];

                foreach(['mp', 'identicon', 'monsterid', 'wavatar', 'retro', 'robohash', 'blank'] as $key) {
                    $data['result'][$key] = get_gravatar($_POST['email'], 2056, $key);
                }

            }
        }

        $values = [
            'email' => $_POST['email'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gravatar_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


    public function list_randomizer() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $result = array_filter(preg_split('/\r\n|\r|\n/', $_POST['text']));
                array_map('input_clean', $result);
                shuffle($result);
                $data['result'] = implode("\r\n", $result);
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/list_randomizer', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function reverse_words() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $array = explode(' ', $_POST['text']);
                $data['result'] = implode(' ', array_reverse($array));
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/reverse_words', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function reverse_letters() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = strrev($_POST['text']);
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/reverse_letters', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function emojis_remover() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $emojis = ['1F600','1F603','1F604','1F601','1F606','1F605','1F923','1F602','1F642','1F643','1F609','1F60A','1F607','1F970','1F60D','1F929','1F618','1F617','263A','1F61A','1F619','1F60B','1F61B','1F61C','1F92A','1F61D','1F911','1F917','1F92D','1F92B','1F914','1F910','1F928','1F610','1F611','1F636','1F60F','1F612','1F644','1F62C','1F925','1F60C','1F614','1F62A','1F924','1F634','1F637','1F912','1F915','1F922','1F92E','1F927','1F975','1F976','1F974','1F635','1F92F','1F920','1F973','1F60E','1F913','1F9D0','1F615','1F61F','1F641','2639','1F62E','1F62F','1F632','1F633','1F97A','1F626','1F627','1F628','1F630','1F625','1F622','1F62D','1F631','1F616','1F623','1F61E','1F613','1F629','1F62B','1F971','1F624','1F621','1F620','1F92C','1F608','1F47F','1F480','2620','1F4A9','1F921','1F479','1F47A','1F47B','1F47D','1F47E','1F916','1F63A','1F638','1F639','1F63B','1F63C','1F63D','1F640','1F63F','1F63E','1F648','1F649','1F64A','1F48B','1F48C','1F498','1F49D','1F496','1F497','1F493','1F49E','1F495','1F49F','2763','1F494','2764','1F9E1','1F49B','1F49A','1F499','1F49C','1F90E','1F5A4','1F90D','1F4AF','1F4A2','1F4A5','1F4AB','1F4A6','1F4A8','1F573','1F4A3','1F4AC','1F441','FE0F','200D','1F5E8','FE0F','1F5E8','1F5EF','1F4AD','1F4A4','1F44B','1F91A','1F590','270B','1F596','1F44C','1F90F','270C','1F91E','1F91F','1F918','1F919','1F448','1F449','1F446','1F595','1F447','261D','1F44D','1F44E','270A','1F44A','1F91B','1F91C','1F44F','1F64C','1F450','1F932','1F91D','1F64F','270D','1F485','1F933','1F4AA','1F9BE','1F9BF','1F9B5','1F9B6','1F442','1F9BB','1F443','1F9E0','1F9B7','1F9B4','1F440','1F441','1F445','1F444','1F476','1F9D2','1F466','1F467','1F9D1','1F471','1F468','1F9D4','1F471','200D','2642','FE0F','1F468','200D','1F9B0','1F468','200D','1F9B1','1F468','200D','1F9B3','1F468','200D','1F9B2','1F469','1F471','200D','2640','FE0F','1F469','200D','1F9B0','1F469','200D','1F9B1','1F469','200D','1F9B3','1F469','200D','1F9B2','1F9D3','1F474','1F475','1F64D','1F64D','200D','2642','FE0F','1F64D','200D','2640','FE0F','1F64E','1F64E','200D','2642','FE0F','1F64E','200D','2640','FE0F','1F645','1F645','200D','2642','FE0F','1F645','200D','2640','FE0F','1F646','1F646','200D','2642','FE0F','1F646','200D','2640','FE0F','1F481','1F481','200D','2642','FE0F','1F481','200D','2640','FE0F','1F64B','1F64B','200D','2642','FE0F','1F64B','200D','2640','FE0F','1F9CF','1F9CF','200D','2642','FE0F','1F9CF','200D','2640','FE0F','1F647','1F647','200D','2642','FE0F','1F647','200D','2640','FE0F','1F926','1F926','200D','2642','FE0F','1F926','200D','2640','FE0F','1F937','1F937','200D','2642','FE0F','1F937','200D','2640','FE0F','1F468','200D','2695','FE0F','1F469','200D','2695','FE0F','1F468','200D','1F393','1F469','200D','1F393','1F468','200D','1F3EB','1F469','200D','1F3EB','1F468','200D','2696','FE0F','1F469','200D','2696','FE0F','1F468','200D','1F33E','1F469','200D','1F33E','1F468','200D','1F373','1F469','200D','1F373','1F468','200D','1F527','1F469','200D','1F527','1F468','200D','1F3ED','1F469','200D','1F3ED','1F468','200D','1F4BC','1F469','200D','1F4BC','1F468','200D','1F52C','1F469','200D','1F52C','1F468','200D','1F4BB','1F469','200D','1F4BB','1F468','200D','1F3A4','1F469','200D','1F3A4','1F468','200D','1F3A8','1F469','200D','1F3A8','1F468','200D','2708','FE0F','1F469','200D','2708','FE0F','1F468','200D','1F680','1F469','200D','1F680','1F468','200D','1F692','1F469','200D','1F692','1F46E','1F46E','200D','2642','FE0F','1F46E','200D','2640','FE0F','1F575','1F575','FE0F','200D','2642','FE0F','1F575','FE0F','200D','2640','FE0F','1F482','1F482','200D','2642','FE0F','1F482','200D','2640','FE0F','1F477','1F477','200D','2642','FE0F','1F477','200D','2640','FE0F','1F934','1F478','1F473','1F473','200D','2642','FE0F','1F473','200D','2640','FE0F','1F472','1F9D5','1F935','1F470','1F930','1F931','1F47C','1F385','1F936','1F9B8','1F9B8','200D','2642','FE0F','1F9B8','200D','2640','FE0F','1F9B9','1F9B9','200D','2642','FE0F','1F9B9','200D','2640','FE0F','1F9D9','1F9D9','200D','2642','FE0F','1F9D9','200D','2640','FE0F','1F9DA','1F9DA','200D','2642','FE0F','1F9DA','200D','2640','FE0F','1F9DB','1F9DB','200D','2642','FE0F','1F9DB','200D','2640','FE0F','1F9DC','1F9DC','200D','2642','FE0F','1F9DC','200D','2640','FE0F','1F9DD','1F9DD','200D','2642','FE0F','1F9DD','200D','2640','FE0F','1F9DE','1F9DE','200D','2642','FE0F','1F9DE','200D','2640','FE0F','1F9DF','1F9DF','200D','2642','FE0F','1F9DF','200D','2640','FE0F','1F486','1F486','200D','2642','FE0F','1F486','200D','2640','FE0F','1F487','1F487','200D','2642','FE0F','1F487','200D','2640','FE0F','1F6B6','1F6B6','200D','2642','FE0F','1F6B6','200D','2640','FE0F','1F9CD','1F9CD','200D','2642','FE0F','1F9CD','200D','2640','FE0F','1F9CE','1F9CE','200D','2642','FE0F','1F9CE','200D','2640','FE0F','1F468','200D','1F9AF','1F469','200D','1F9AF','1F468','200D','1F9BC','1F469','200D','1F9BC','1F468','200D','1F9BD','1F469','200D','1F9BD','1F3C3','1F3C3','200D','2642','FE0F','1F3C3','200D','2640','FE0F','1F483','1F57A','1F574','1F46F','1F46F','200D','2642','FE0F','1F46F','200D','2640','FE0F','1F9D6','1F9D6','200D','2642','FE0F','1F9D6','200D','2640','FE0F','1F9D7','1F9D7','200D','2642','FE0F','1F9D7','200D','2640','FE0F','1F93A','1F3C7','26F7','1F3C2','1F3CC','1F3CC','FE0F','200D','2642','FE0F','1F3CC','FE0F','200D','2640','FE0F','1F3C4','1F3C4','200D','2642','FE0F','1F3C4','200D','2640','FE0F','1F6A3','1F6A3','200D','2642','FE0F','1F6A3','200D','2640','FE0F','1F3CA','1F3CA','200D','2642','FE0F','1F3CA','200D','2640','FE0F','26F9','26F9','FE0F','200D','2642','FE0F','26F9','FE0F','200D','2640','FE0F','1F3CB','1F3CB','FE0F','200D','2642','FE0F','1F3CB','FE0F','200D','2640','FE0F','1F6B4','1F6B4','200D','2642','FE0F','1F6B4','200D','2640','FE0F','1F6B5','1F6B5','200D','2642','FE0F','1F6B5','200D','2640','FE0F','1F938','1F938','200D','2642','FE0F','1F938','200D','2640','FE0F','1F93C','1F93C','200D','2642','FE0F','1F93C','200D','2640','FE0F','1F93D','1F93D','200D','2642','FE0F','1F93D','200D','2640','FE0F','1F93E','1F93E','200D','2642','FE0F','1F93E','200D','2640','FE0F','1F939','1F939','200D','2642','FE0F','1F939','200D','2640','FE0F','1F9D8','1F9D8','200D','2642','FE0F','1F9D8','200D','2640','FE0F','1F6C0','1F6CC','1F9D1','200D','1F91D','200D','1F9D1','1F46D','1F46B','1F46C','1F48F','1F469','200D','2764','FE0F','200D','1F48B','200D','1F468','1F468','200D','2764','FE0F','200D','1F48B','200D','1F468','1F469','200D','2764','FE0F','200D','1F48B','200D','1F469','1F491','1F469','200D','2764','FE0F','200D','1F468','1F468','200D','2764','FE0F','200D','1F468','1F469','200D','2764','FE0F','200D','1F469','1F46A','1F468','200D','1F469','200D','1F466','1F468','200D','1F469','200D','1F467','1F468','200D','1F469','200D','1F467','200D','1F466','1F468','200D','1F469','200D','1F466','200D','1F466','1F468','200D','1F469','200D','1F467','200D','1F467','1F468','200D','1F468','200D','1F466','1F468','200D','1F468','200D','1F467','1F468','200D','1F468','200D','1F467','200D','1F466','1F468','200D','1F468','200D','1F466','200D','1F466','1F468','200D','1F468','200D','1F467','200D','1F467','1F469','200D','1F469','200D','1F466','1F469','200D','1F469','200D','1F467','1F469','200D','1F469','200D','1F467','200D','1F466','1F469','200D','1F469','200D','1F466','200D','1F466','1F469','200D','1F469','200D','1F467','200D','1F467','1F468','200D','1F466','1F468','200D','1F466','200D','1F466','1F468','200D','1F467','1F468','200D','1F467','200D','1F466','1F468','200D','1F467','200D','1F467','1F469','200D','1F466','1F469','200D','1F466','200D','1F466','1F469','200D','1F467','1F469','200D','1F467','200D','1F466','1F469','200D','1F467','200D','1F467','1F5E3','1F464','1F465','1F463','1F9B0','1F9B1','1F9B3','1F9B2','1F435','1F412','1F98D','1F9A7','1F436','1F415','1F9AE','1F415','200D','1F9BA','1F429','1F43A','1F98A','1F99D','1F431','1F408','1F981','1F42F','1F405','1F406','1F434','1F40E','1F984','1F993','1F98C','1F42E','1F402','1F403','1F404','1F437','1F416','1F417','1F43D','1F40F','1F411','1F410','1F42A','1F42B','1F999','1F992','1F418','1F98F','1F99B','1F42D','1F401','1F400','1F439','1F430','1F407','1F43F','1F994','1F987','1F43B','1F428','1F43C','1F9A5','1F9A6','1F9A8','1F998','1F9A1','1F43E','1F983','1F414','1F413','1F423','1F424','1F425','1F426','1F427','1F54A','1F985','1F986','1F9A2','1F989','1F9A9','1F99A','1F99C','1F438','1F40A','1F422','1F98E','1F40D','1F432','1F409','1F995','1F996','1F433','1F40B','1F42C','1F41F','1F420','1F421','1F988','1F419','1F41A','1F40C','1F98B','1F41B','1F41C','1F41D','1F41E','1F997','1F577','1F578','1F982','1F99F','1F9A0','1F490','1F338','1F4AE','1F3F5','1F339','1F940','1F33A','1F33B','1F33C','1F337','1F331','1F332','1F333','1F334','1F335','1F33E','1F33F','2618','1F340','1F341','1F342','1F343','1F347','1F348','1F349','1F34A','1F34B','1F34C','1F34D','1F96D','1F34E','1F34F','1F350','1F351','1F352','1F353','1F95D','1F345','1F965','1F951','1F346','1F954','1F955','1F33D','1F336','1F952','1F96C','1F966','1F9C4','1F9C5','1F344','1F95C','1F330','1F35E','1F950','1F956','1F968','1F96F','1F95E','1F9C7','1F9C0','1F356','1F357','1F969','1F953','1F354','1F35F','1F355','1F32D','1F96A','1F32E','1F32F','1F959','1F9C6','1F95A','1F373','1F958','1F372','1F963','1F957','1F37F','1F9C8','1F9C2','1F96B','1F371','1F358','1F359','1F35A','1F35B','1F35C','1F35D','1F360','1F362','1F363','1F364','1F365','1F96E','1F361','1F95F','1F960','1F961','1F980','1F99E','1F990','1F991','1F9AA','1F366','1F367','1F368','1F369','1F36A','1F382','1F370','1F9C1','1F967','1F36B','1F36C','1F36D','1F36E','1F36F','1F37C','1F95B','2615','1F375','1F376','1F37E','1F377','1F378','1F379','1F37A','1F37B','1F942','1F943','1F964','1F9C3','1F9C9','1F9CA','1F962','1F37D','1F374','1F944','1F52A','1F3FA','1F30D','1F30E','1F30F','1F310','1F5FA','1F5FE','1F9ED','1F3D4','26F0','1F30B','1F5FB','1F3D5','1F3D6','1F3DC','1F3DD','1F3DE','1F3DF','1F3DB','1F3D7','1F9F1','1F3D8','1F3DA','1F3E0','1F3E1','1F3E2','1F3E3','1F3E4','1F3E5','1F3E6','1F3E8','1F3E9','1F3EA','1F3EB','1F3EC','1F3ED','1F3EF','1F3F0','1F492','1F5FC','1F5FD','26EA','1F54C','1F6D5','1F54D','26E9','1F54B','26F2','26FA','1F301','1F303','1F3D9','1F304','1F305','1F306','1F307','1F309','2668','1F3A0','1F3A1','1F3A2','1F488','1F3AA','1F682','1F683','1F684','1F685','1F686','1F687','1F688','1F689','1F68A','1F69D','1F69E','1F68B','1F68C','1F68D','1F68E','1F690','1F691','1F692','1F693','1F694','1F695','1F696','1F697','1F698','1F699','1F69A','1F69B','1F69C','1F3CE','1F3CD','1F6F5','1F9BD','1F9BC','1F6FA','1F6B2','1F6F4','1F6F9','1F68F','1F6E3','1F6E4','1F6E2','26FD','1F6A8','1F6A5','1F6A6','1F6D1','1F6A7','2693','26F5','1F6F6','1F6A4','1F6F3','26F4','1F6E5','1F6A2','2708','1F6E9','1F6EB','1F6EC','1FA82','1F4BA','1F681','1F69F','1F6A0','1F6A1','1F6F0','1F680','1F6F8','1F6CE','1F9F3','231B','23F3','231A','23F0','23F1','23F2','1F570','1F55B','1F567','1F550','1F55C','1F551','1F55D','1F552','1F55E','1F553','1F55F','1F554','1F560','1F555','1F561','1F556','1F562','1F557','1F563','1F558','1F564','1F559','1F565','1F55A','1F566','1F311','1F312','1F313','1F314','1F315','1F316','1F317','1F318','1F319','1F31A','1F31B','1F31C','1F321','2600','1F31D','1F31E','1FA90','2B50','1F31F','1F320','1F30C','2601','26C5','26C8','1F324','1F325','1F326','1F327','1F328','1F329','1F32A','1F32B','1F32C','1F300','1F308','1F302','2602','2614','26F1','26A1','2744','2603','26C4','2604','1F525','1F4A7','1F30A','1F383','1F384','1F386','1F387','1F9E8','2728','1F388','1F389','1F38A','1F38B','1F38D','1F38E','1F38F','1F390','1F391','1F9E7','1F380','1F381','1F397','1F39F','1F3AB','1F396','1F3C6','1F3C5','1F947','1F948','1F949','26BD','26BE','1F94E','1F3C0','1F3D0','1F3C8','1F3C9','1F3BE','1F94F','1F3B3','1F3CF','1F3D1','1F3D2','1F94D','1F3D3','1F3F8','1F94A','1F94B','1F945','26F3','26F8','1F3A3','1F93F','1F3BD','1F3BF','1F6F7','1F94C','1F3AF','1FA80','1FA81','1F3B1','1F52E','1F9FF','1F3AE','1F579','1F3B0','1F3B2','1F9E9','1F9F8','2660','2665','2666','2663','265F','1F0CF','1F004','1F3B4','1F3AD','1F5BC','1F3A8','1F9F5','1F9F6','1F453','1F576','1F97D','1F97C','1F9BA','1F454','1F455','1F456','1F9E3','1F9E4','1F9E5','1F9E6','1F457','1F458','1F97B','1FA71','1FA72','1FA73','1F459','1F45A','1F45B','1F45C','1F45D','1F6CD','1F392','1F45E','1F45F','1F97E','1F97F','1F460','1F461','1FA70','1F462','1F451','1F452','1F3A9','1F393','1F9E2','26D1','1F4FF','1F484','1F48D','1F48E','1F507','1F508','1F509','1F50A','1F4E2','1F4E3','1F4EF','1F514','1F515','1F3BC','1F3B5','1F3B6','1F399','1F39A','1F39B','1F3A4','1F3A7','1F4FB','1F3B7','1F3B8','1F3B9','1F3BA','1F3BB','1FA95','1F941','1F4F1','1F4F2','260E','1F4DE','1F4DF','1F4E0','1F50B','1F50C','1F4BB','1F5A5','1F5A8','2328','1F5B1','1F5B2','1F4BD','1F4BE','1F4BF','1F4C0','1F9EE','1F3A5','1F39E','1F4FD','1F3AC','1F4FA','1F4F7','1F4F8','1F4F9','1F4FC','1F50D','1F50E','1F56F','1F4A1','1F526','1F3EE','1FA94','1F4D4','1F4D5','1F4D6','1F4D7','1F4D8','1F4D9','1F4DA','1F4D3','1F4D2','1F4C3','1F4DC','1F4C4','1F4F0','1F5DE','1F4D1','1F516','1F3F7','1F4B0','1F4B4','1F4B5','1F4B6','1F4B7','1F4B8','1F4B3','1F9FE','1F4B9','1F4B1','1F4B2','2709','1F4E7','1F4E8','1F4E9','1F4E4','1F4E5','1F4E6','1F4EB','1F4EA','1F4EC','1F4ED','1F4EE','1F5F3','270F','2712','1F58B','1F58A','1F58C','1F58D','1F4DD','1F4BC','1F4C1','1F4C2','1F5C2','1F4C5','1F4C6','1F5D2','1F5D3','1F4C7','1F4C8','1F4C9','1F4CA','1F4CB','1F4CC','1F4CD','1F4CE','1F587','1F4CF','1F4D0','2702','1F5C3','1F5C4','1F5D1','1F512','1F513','1F50F','1F510','1F511','1F5DD','1F528','1FA93','26CF','2692','1F6E0','1F5E1','2694','1F52B','1F3F9','1F6E1','1F527','1F529','2699','1F5DC','2696','1F9AF','1F517','26D3','1F9F0','1F9F2','2697','1F9EA','1F9EB','1F9EC','1F52C','1F52D','1F4E1','1F489','1FA78','1F48A','1FA79','1FA7A','1F6AA','1F6CF','1F6CB','1FA91','1F6BD','1F6BF','1F6C1','1FA92','1F9F4','1F9F7','1F9F9','1F9FA','1F9FB','1F9FC','1F9FD','1F9EF','1F6D2','1F6AC','26B0','26B1','1F5FF','1F3E7','1F6AE','1F6B0','267F','1F6B9','1F6BA','1F6BB','1F6BC','1F6BE','1F6C2','1F6C3','1F6C4','1F6C5','26A0','1F6B8','26D4','1F6AB','1F6B3','1F6AD','1F6AF','1F6B1','1F6B7','1F4F5','1F51E','2622','2623','2B06','2197','27A1','2198','2B07','2199','2B05','2196','2195','2194','21A9','21AA','2934','2935','1F503','1F504','1F519','1F51A','1F51B','1F51C','1F51D','1F6D0','269B','1F549','2721','2638','262F','271D','2626','262A','262E','1F54E','1F52F','2648','2649','264A','264B','264C','264D','264E','264F','2650','2651','2652','2653','26CE','1F500','1F501','1F502','25B6','23E9','23ED','23EF','25C0','23EA','23EE','1F53C','23EB','1F53D','23EC','23F8','23F9','23FA','23CF','1F3A6','1F505','1F506','1F4F6','1F4F3','1F4F4','2640','2642','2695','267E','267B','269C','1F531','1F4DB','1F530','2B55','2705','2611','2714','2716','274C','274E','2795','2796','2797','27B0','27BF','303D','2733','2734','2747','203C','2049','2753','2754','2755','2757','3030','00A9','00AE','2122','0023','FE0F','20E3','002A','FE0F','20E3','0030','FE0F','20E3','0031','FE0F','20E3','0032','FE0F','20E3','0033','FE0F','20E3','0034','FE0F','20E3','0035','FE0F','20E3','0036','FE0F','20E3','0037','FE0F','20E3','0038','FE0F','20E3','0039','FE0F','20E3','1F51F','1F520','1F521','1F522','1F523','1F524','1F170','1F18E','1F171','1F191','1F192','1F193','2139','1F194','24C2','1F195','1F196','1F17E','1F197','1F17F','1F198','1F199','1F19A','1F201','1F202','1F237','1F236','1F22F','1F250','1F239','1F21A','1F232','1F251','1F238','1F234','1F233','3297','3299','1F23A','1F235','1F534','1F7E0','1F7E1','1F7E2','1F535','1F7E3','1F7E4','26AB','26AA','1F7E5','1F7E7','1F7E8','1F7E9','1F7E6','1F7EA','1F7EB','2B1B','2B1C','25FC','25FB','25FE','25FD','25AA','25AB','1F536','1F537','1F538','1F539','1F53A','1F53B','1F4A0','1F518','1F533','1F532','1F3C1','1F6A9','1F38C','1F3F4','1F3F3','1F3F3','FE0F','200D','1F308','1F3F4','200D','2620','FE0F','1F1E6','1F1E8','1F1E6','1F1E9','1F1E6','1F1EA','1F1E6','1F1EB','1F1E6','1F1EC','1F1E6','1F1EE','1F1E6','1F1F1','1F1E6','1F1F2','1F1E6','1F1F4','1F1E6','1F1F6','1F1E6','1F1F7','1F1E6','1F1F8','1F1E6','1F1F9','1F1E6','1F1FA','1F1E6','1F1FC','1F1E6','1F1FD','1F1E6','1F1FF','1F1E7','1F1E6','1F1E7','1F1E7','1F1E7','1F1E9','1F1E7','1F1EA','1F1E7','1F1EB','1F1E7','1F1EC','1F1E7','1F1ED','1F1E7','1F1EE','1F1E7','1F1EF','1F1E7','1F1F1','1F1E7','1F1F2','1F1E7','1F1F3','1F1E7','1F1F4','1F1E7','1F1F6','1F1E7','1F1F7','1F1E7','1F1F8','1F1E7','1F1F9','1F1E7','1F1FB','1F1E7','1F1FC','1F1E7','1F1FE','1F1E7','1F1FF','1F1E8','1F1E6','1F1E8','1F1E8','1F1E8','1F1E9','1F1E8','1F1EB','1F1E8','1F1EC','1F1E8','1F1ED','1F1E8','1F1EE','1F1E8','1F1F0','1F1E8','1F1F1','1F1E8','1F1F2','1F1E8','1F1F3','1F1E8','1F1F4','1F1E8','1F1F5','1F1E8','1F1F7','1F1E8','1F1FA','1F1E8','1F1FB','1F1E8','1F1FC','1F1E8','1F1FD','1F1E8','1F1FE','1F1E8','1F1FF','1F1E9','1F1EA','1F1E9','1F1EC','1F1E9','1F1EF','1F1E9','1F1F0','1F1E9','1F1F2','1F1E9','1F1F4','1F1E9','1F1FF','1F1EA','1F1E6','1F1EA','1F1E8','1F1EA','1F1EA','1F1EA','1F1EC','1F1EA','1F1ED','1F1EA','1F1F7','1F1EA','1F1F8','1F1EA','1F1F9','1F1EA','1F1FA','1F1EB','1F1EE','1F1EB','1F1EF','1F1EB','1F1F0','1F1EB','1F1F2','1F1EB','1F1F4','1F1EB','1F1F7','1F1EC','1F1E6','1F1EC','1F1E7','1F1EC','1F1E9','1F1EC','1F1EA','1F1EC','1F1EB','1F1EC','1F1EC','1F1EC','1F1ED','1F1EC','1F1EE','1F1EC','1F1F1','1F1EC','1F1F2','1F1EC','1F1F3','1F1EC','1F1F5','1F1EC','1F1F6','1F1EC','1F1F7','1F1EC','1F1F8','1F1EC','1F1F9','1F1EC','1F1FA','1F1EC','1F1FC','1F1EC','1F1FE','1F1ED','1F1F0','1F1ED','1F1F2','1F1ED','1F1F3','1F1ED','1F1F7','1F1ED','1F1F9','1F1ED','1F1FA','1F1EE','1F1E8','1F1EE','1F1E9','1F1EE','1F1EA','1F1EE','1F1F1','1F1EE','1F1F2','1F1EE','1F1F3','1F1EE','1F1F4','1F1EE','1F1F6','1F1EE','1F1F7','1F1EE','1F1F8','1F1EE','1F1F9','1F1EF','1F1EA','1F1EF','1F1F2','1F1EF','1F1F4','1F1EF','1F1F5','1F1F0','1F1EA','1F1F0','1F1EC','1F1F0','1F1ED','1F1F0','1F1EE','1F1F0','1F1F2','1F1F0','1F1F3','1F1F0','1F1F5','1F1F0','1F1F7','1F1F0','1F1FC','1F1F0','1F1FE','1F1F0','1F1FF','1F1F1','1F1E6','1F1F1','1F1E7','1F1F1','1F1E8','1F1F1','1F1EE','1F1F1','1F1F0','1F1F1','1F1F7','1F1F1','1F1F8','1F1F1','1F1F9','1F1F1','1F1FA','1F1F1','1F1FB','1F1F1','1F1FE','1F1F2','1F1E6','1F1F2','1F1E8','1F1F2','1F1E9','1F1F2','1F1EA','1F1F2','1F1EB','1F1F2','1F1EC','1F1F2','1F1ED','1F1F2','1F1F0','1F1F2','1F1F1','1F1F2','1F1F2','1F1F2','1F1F3','1F1F2','1F1F4','1F1F2','1F1F5','1F1F2','1F1F6','1F1F2','1F1F7','1F1F2','1F1F8','1F1F2','1F1F9','1F1F2','1F1FA','1F1F2','1F1FB','1F1F2','1F1FC','1F1F2','1F1FD','1F1F2','1F1FE','1F1F2','1F1FF','1F1F3','1F1E6','1F1F3','1F1E8','1F1F3','1F1EA','1F1F3','1F1EB','1F1F3','1F1EC','1F1F3','1F1EE','1F1F3','1F1F1','1F1F3','1F1F4','1F1F3','1F1F5','1F1F3','1F1F7','1F1F3','1F1FA','1F1F3','1F1FF','1F1F4','1F1F2','1F1F5','1F1E6','1F1F5','1F1EA','1F1F5','1F1EB','1F1F5','1F1EC','1F1F5','1F1ED','1F1F5','1F1F0','1F1F5','1F1F1','1F1F5','1F1F2','1F1F5','1F1F3','1F1F5','1F1F7','1F1F5','1F1F8','1F1F5','1F1F9','1F1F5','1F1FC','1F1F5','1F1FE','1F1F6','1F1E6','1F1F7','1F1EA','1F1F7','1F1F4','1F1F7','1F1F8','1F1F7','1F1FA','1F1F7','1F1FC','1F1F8','1F1E6','1F1F8','1F1E7','1F1F8','1F1E8','1F1F8','1F1E9','1F1F8','1F1EA','1F1F8','1F1EC','1F1F8','1F1ED','1F1F8','1F1EE','1F1F8','1F1EF','1F1F8','1F1F0','1F1F8','1F1F1','1F1F8','1F1F2','1F1F8','1F1F3','1F1F8','1F1F4','1F1F8','1F1F7','1F1F8','1F1F8','1F1F8','1F1F9','1F1F8','1F1FB','1F1F8','1F1FD','1F1F8','1F1FE','1F1F8','1F1FF','1F1F9','1F1E6','1F1F9','1F1E8','1F1F9','1F1E9','1F1F9','1F1EB','1F1F9','1F1EC','1F1F9','1F1ED','1F1F9','1F1EF','1F1F9','1F1F0','1F1F9','1F1F1','1F1F9','1F1F2','1F1F9','1F1F3','1F1F9','1F1F4','1F1F9','1F1F7','1F1F9','1F1F9','1F1F9','1F1FB','1F1F9','1F1FC','1F1F9','1F1FF','1F1FA','1F1E6','1F1FA','1F1EC','1F1FA','1F1F2','1F1FA','1F1F3','1F1FA','1F1F8','1F1FA','1F1FE','1F1FA','1F1FF','1F1FB','1F1E6','1F1FB','1F1E8','1F1FB','1F1EA','1F1FB','1F1EC','1F1FB','1F1EE','1F1FB','1F1F3','1F1FB','1F1FA','1F1FC','1F1EB','1F1FC','1F1F8','1F1FD','1F1F0','1F1FE','1F1EA','1F1FE','1F1F9','1F1FF','1F1E6','1F1FF','1F1F2','1F1FF','1F1FC','1F3F4','E0067','E0062','E0065','E006E','E0067','E007F','1F3F4','E0067','E0062','E0073','E0063','E0074','E007F','1F3F4','E0067','E0062','E0077','E006C','E0073','E007F'];
                $emojis_regex = '\x{' . implode('}\x{', $emojis) . '}';

                /* Replace all the emojis */
                $data['result'] = preg_replace('/[' . $emojis_regex . ']+/u', '', $_POST['text']);
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/emojis_remover', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function reverse_list() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $result = array_filter(preg_split('/\r\n|\r|\n/', $_POST['text']));
                array_map('input_clean', $result);
                $data['result'] = implode("\r\n", array_reverse($result));
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/reverse_list', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function list_alphabetizer() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['type'] = in_array($_POST['type'], ['A-Z', 'Z-A']) ? $_POST['type'] : 'A-Z';

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $result = array_filter(preg_split('/\r\n|\r|\n/', $_POST['text']));
                array_map('input_clean', $result);
                switch ($_POST['type']) {
                    case 'A-Z':
                        sort($result);
                        break;

                    case 'Z-A':
                        rsort($result);
                        break;
                }
                $data['result'] = implode("\r\n", $result);
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'type' => $_POST['type'] ?? 'A-Z',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/list_alphabetizer', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function upside_down_text_generator() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['reverse'] = (int) isset($_POST['reverse']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $table = [
                    'A' => "∀",
                    'B' => "q",
                    'C' => "Ɔ",
                    'E' => "Ǝ",
                    'F' => "Ⅎ",
                    'G' => "פ",
                    'H' => "H",
                    'I' => "I",
                    'J' => "ſ",
                    'L' => "˥",
                    'M' => "W",
                    'N' => "N",
                    'P' => "Ԁ",
                    'R' => "ᴚ",
                    'T' => "⊥",
                    'U' => "∩",
                    'V' => "Λ",
                    'Y' => "⅄",
                    'a' => "ɐ",
                    'b' => "q",
                    'c' => "ɔ",
                    'd' => "p",
                    'e' => "ǝ",
                    'f' => "ɟ",
                    'g' => "ƃ",
                    'h' => "ɥ",
                    'i' => "ᴉ",
                    'j' => "ɾ",
                    'k' => "ʞ",
                    'm' => "ɯ",
                    'n' => "u",
                    'p' => "d",
                    'q' => "b",
                    'r' => "ɹ",
                    't' => "ʇ",
                    'u' => "n",
                    'v' => "ʌ",
                    'w' => "ʍ",
                    'y' => "ʎ",
                    '1' => "Ɩ",
                    '2' => "ᄅ",
                    '3' => "Ɛ",
                    '4' => "ㄣ",
                    '5' => "ϛ",
                    '6' => "9",
                    '7' => "ㄥ",
                    '8' => "8",
                    '9' => "6",
                    '0' => "0",
                    "." => "˙",
                    "," => "'",
                    "'" => ",",
                    '"' => ",,",
                    "`" => ",",
                    "<" => ">",
                    ">" => "<",
                    "∴" => "∵",
                    "&" => "⅋",
                    "_" => "‾",
                    "?" => "¿",
                    "¿" => "?",
                    "!" => "¡",
                    "¡" => "!",
                    "[" => "]",
                    "]" => "[",
                    "(" => ")",
                    ")" => "(",
                    "{" => "}",
                    "}" => "{",
                    ";" => "؛",
                    "‿" => "⁀",
                    "⁅" => "⁆"
                ];

                $text = $_POST['reverse'] ? strrev($_POST['text']) : $_POST['text'];

                $data['result'] = '';
                for($i = 0; $i < mb_strlen($text); $i++) {
                    $character = $text[$i];
                    $data['result'] .= array_key_exists($character, $table) ? $table[$character] : $character;
                }
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'reverse' => $_POST['reverse'] ?? true,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/upside_down_text_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function old_english_text_generator() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $table = [
                    'a' => "𝔞",
                    'b' => "𝔟",
                    'c' => "𝔠",
                    'd' => "𝔡",
                    'e' => "𝔢",
                    'f' => "𝔣",
                    'g' => "𝔤",
                    'h' => "𝔥",
                    'i' => "𝔦",
                    'j' => "𝔧",
                    'k' => "𝔨",
                    'l' => "𝔩",
                    'm' => "𝔪",
                    'n' => "𝔫",
                    'o' => "𝔬",
                    'p' => "𝔭",
                    'q' => "𝔮",
                    'r' => "𝔯",
                    's' => "𝔰",
                    't' => "𝔱",
                    'u' => "𝔲",
                    'v' => "𝔳",
                    'w' => "𝔴",
                    'x' => "𝔵",
                    'y' => "𝔶",
                    'z' => "𝔷",
                    'A' => "𝔄",
                    'B' => "𝔅",
                    'C' => "ℭ",
                    'D' => "𝔇",
                    'E' => "𝔈",
                    'F' => "𝔉",
                    'G' => "𝔊",
                    'H' => "ℌ",
                    'I' => "ℑ",
                    'J' => "𝔍",
                    'K' => "𝔎",
                    'L' => "𝔏",
                    'M' => "𝔐",
                    'N' => "𝔑",
                    'O' => "𝔒",
                    'P' => "𝔓",
                    'Q' => "𝔔",
                    'R' => "ℜ",
                    'S' => "𝔖",
                    'T' => "𝔗",
                    'U' => "𝔘",
                    'V' => "𝔙",
                    'W' => "𝔚",
                    'X' => "𝔛",
                    'Y' => "𝔜",
                    'Z' => "ℨ",
                ];

                $data['result'] = '';
                for($i = 0; $i < mb_strlen($_POST['text']); $i++) {
                    $character = $_POST['text'][$i];
                    $data['result'] .= array_key_exists($character, $table) ? $table[$character] : $character;
                }
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/old_english_text_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function cursive_text_generator() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $table = [
                    '0' => "𝟢",
                    '1' => "𝟣",
                    '2' => "𝟤",
                    '3' => "𝟥",
                    '4' => "𝟦",
                    '5' => "𝟧",
                    '6' => "𝟨",
                    '7' => "𝟩",
                    '8' => "𝟪",
                    '9' => "𝟫",
                    'a' => "𝒶",
                    'b' => "𝒷",
                    'c' => "𝒸",
                    'd' => "𝒹",
                    'e' => "𝑒",
                    'f' => "𝒻",
                    'g' => "𝑔",
                    'h' => "𝒽",
                    'i' => "𝒾",
                    'j' => "𝒿",
                    'k' => "𝓀",
                    'l' => "𝓁",
                    'm' => "𝓂",
                    'n' => "𝓃",
                    'o' => "𝑜",
                    'p' => "𝓅",
                    'q' => "𝓆",
                    'r' => "𝓇",
                    's' => "𝓈",
                    't' => "𝓉",
                    'u' => "𝓊",
                    'v' => "𝓋",
                    'w' => "𝓌",
                    'x' => "𝓍",
                    'y' => "𝓎",
                    'z' => "𝓏",
                    'A' => "𝒜",
                    'B' => "𝐵",
                    'C' => "𝒞",
                    'D' => "𝒟",
                    'E' => "𝐸",
                    'F' => "𝐹",
                    'G' => "𝒢",
                    'H' => "𝐻",
                    'I' => "𝐼",
                    'J' => "𝒥",
                    'K' => "𝒦",
                    'L' => "𝐿",
                    'M' => "𝑀",
                    'N' => "𝒩",
                    'O' => "𝒪",
                    'P' => "𝒫",
                    'Q' => "𝒬",
                    'R' => "𝑅",
                    'S' => "𝒮",
                    'T' => "𝒯",
                    'U' => "𝒰",
                    'V' => "𝒱",
                    'W' => "𝒲",
                    'X' => "𝒳",
                    'Y' => "𝒴",
                    'Z' => "𝒵",
                ];

                $data['result'] = '';
                for($i = 0; $i < mb_strlen($_POST['text']); $i++) {
                    $character = $_POST['text'][$i];
                    $data['result'] .= array_key_exists($character, $table) ? $table[$character] : $character;
                }
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/cursive_text_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function palindrome_checker() {

        $this->initiate();

        $data = [];

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/palindrome_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function character_counter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result']['characters'] = mb_strlen($_POST['text']);
                $data['result']['words'] = str_word_count($_POST['text']);
                $data['result']['lines'] = substr_count($_POST['text'], "\r\n") + 1;;

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/character_counter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function url_parser() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $parsed_url = parse_url($_POST['url']);

                if(isset($parsed_url['query'])) {
                    $query_string_array = explode('&', $parsed_url['query']);
                    $query_array = [];
                    foreach($query_string_array as $query_string_value) {
                        $query_string_value_exploded = explode('=', $query_string_value);
                        $query_array[$query_string_value_exploded[0]] = $query_string_value_exploded[1];
                    }

                    $parsed_url['query_array'] = $query_array;
                }

                $data['result'] = $parsed_url;

            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/url_parser', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function color_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['color'] = input_clean($_POST['color']);

            /* Check for any errors */
            $required_fields = ['color'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $type = null;

            if(mb_substr($_POST['color'], 0, strlen('#')) === '#') {
                $type = 'hex';
            }

            if(mb_substr($_POST['color'], 0, strlen('#')) === '#' && mb_strlen($_POST['color']) > 7) {
                $type = 'hexa';
            }

            foreach(['rgb', 'rgba', 'hsl', 'hsla', 'hsv'] as $color_type) {
                if(mb_substr($_POST['color'], 0, strlen($color_type)) === $color_type) {
                    $type = $color_type;
                }
            }

            if(!$type) {
                Alerts::add_field_error('color', l('tools.color_converter.error_message'));
            } else {
                try {
                    $class = '\OzdemirBurak\Iris\Color\\' . ucfirst($type);
                    $color = new $class($_POST['color']);
                } catch (\Exception $exception) {
                    Alerts::add_field_error('color', l('tools.color_converter.error_message'));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result']['hex'] = $color->toHex();
                $data['result']['hexa'] = $color->toHexa();
                $data['result']['rgb'] = $color->toRgb();
                $data['result']['rgba'] = $color->toRgba();
                $data['result']['hsv'] = $color->toHsv();
                $data['result']['hsl'] = $color->toHsl();
                $data['result']['hsla'] = $color->toHsla();

            }
        }

        $values = [
            'color' => $_POST['color'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/color_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function http_headers_lookup() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            try {
                $response = \Unirest\Request::get($_POST['url']);
            } catch (\Exception $exception) {
                Alerts::add_field_error('url', l('tools.http_headers_lookup.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $response->headers;

            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/http_headers_lookup', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function http2_checker() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            try {
                $response = \Unirest\Request::get($_POST['url']);
            } catch (\Exception $exception) {
                Alerts::add_field_error('url', l('tools.http2_checker.error_message'));
            }

            $curl_info = \Unirest\Request::getInfo();

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $curl_info['http_version'] == 3;

            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/http2_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function brotli_checker() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            try {
                $response = \Unirest\Request::get($_POST['url'], ['Accept-Encoding' => 'br']);
            } catch (\Exception $exception) {
                Alerts::add_field_error('url', l('tools.brotli_checker.error_message'));
            }

            $curl_info = \Unirest\Request::getInfo();

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $is_brotli_enabled = false;

                $response->headers = array_change_key_case($response->headers, CASE_LOWER);

                if(isset($response->headers['content-encoding']) && str_contains($response->headers['content-encoding'], 'br')) {
                    $is_brotli_enabled = true;
                }

                $data['result'] = $is_brotli_enabled;

            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/brotli_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function duplicate_lines_remover() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $lines_array = preg_split('/\r\n|\r|\n/', $_POST['text']);
                $new_lines_array = array_unique($lines_array);

                $data['result']['text'] = implode("\r\n", $new_lines_array);
                $data['result']['lines'] = substr_count($_POST['text'], "\r\n") + 1;
                $data['result']['new_lines'] = count($new_lines_array);
                $data['result']['removed_lines'] = count($lines_array) - count($new_lines_array);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/duplicate_lines_remover', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function text_to_speech() {
        $this->initiate();

        $data = [];

        if(isset($_GET['text']) && isset($_GET['language_code'])) {
            $_GET['text'] = trim(input_clean($_GET['text']));
            $_GET['language_code'] = trim(input_clean($_GET['language_code']));
            $text = rawurlencode(htmlspecialchars($_GET['text']));
            $audio = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $text . '&tl=' . $_GET['language_code']);

            header('Cache-Control: private');
            header('Content-type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3');
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: filename="' . get_slug($_GET['text']) . '.mp3"');
            header('Content-Length: ' . strlen($audio));

            echo $audio;

            die();
        }

        if(!empty($_POST)) {
            $_POST['text'] = trim(input_clean($_POST['text']));
            $_POST['language_code'] = trim(input_clean($_POST['language_code']));

            /* Check for any errors */
            $required_fields = ['text', 'language_code'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = true;
            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'language_code' => $_POST['language_code'] ?? 'en-US',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/text_to_speech', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function idn_punnycode_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_punnycode', 'to_idn']) ? $_POST['type'] : 'to_punnycode';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $_POST['type'] == 'to_punnycode' ? idn_to_ascii($_POST['content']) : idn_to_utf8($_POST['content']);

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/idn_punnycode_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function json_validator_beautifier() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['json'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $data['result'] = json_decode($_POST['json']);

            if(!$data['result']) {
                Alerts::add_field_error('json', l('tools.json_validator_beautifier.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {


            }
        }

        $values = [
            'json' => $_POST['json'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/json_validator_beautifier', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function qr_code_reader() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/qr_code_reader', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function barcode_reader() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/barcode_reader', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function meta_tags_checker() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }


            /* Get the URL source */
            try {
                $response = \Unirest\Request::get($_POST['url']);
            } catch (\Exception $exception) {
                Alerts::add_field_error('url', l('tools.meta_tags_checker.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $doc = new \DOMDocument('1.0', 'UTF-8');
                @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $response->raw_body);

                $meta_tags_array = $doc->getElementsByTagName('meta');
                $meta_tags = [];

                for($i = 0; $i < $meta_tags_array->length; $i++) {
                    $meta_tag = $meta_tags_array->item($i);

                    $meta_tag_key = !empty($meta_tag->getAttribute('name')) ? $meta_tag->getAttribute('name') : $meta_tag->getAttribute('property');

                    if($meta_tag_key) {
                        $meta_tags[$meta_tag_key] = $meta_tag->getAttribute('content');
                    }
                }

                $data['result'] = $meta_tags;
            }
        }

        $values = [
            'url' => $_POST['url'] ?? '',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/meta_tags_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function exif_reader() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/exif_reader', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function color_picker() {

        $this->initiate();

        $data = [];

        $values = [
            'color' => $_POST['color'] ?? '#ffffff',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/color_picker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function sql_beautifier() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['sql'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = (new \Doctrine\SqlFormatter\SqlFormatter())->format($_POST['sql']);
            }
        }

        $values = [
            'sql' => $_POST['sql'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/sql_beautifier', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function html_entity_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['type'] = in_array($_POST['type'], ['encode', 'decode']) ? $_POST['type'] : 'encode';

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $data['result'] = $_POST['type'] == 'encode' ? htmlentities(htmlentities($_POST['text'])) : html_entity_decode($_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/html_entity_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function binary_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_binary', 'to_text']) ? $_POST['type'] : 'to_binary';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                function string_to_binary($string) {
                    $characters = str_split($string);

                    $binary = [];
                    foreach($characters as $character) {
                        $data = unpack('H*', $character);
                        $binary[] = base_convert($data[1], 16, 2);
                    }

                    return implode(' ', $binary);
                }

                function binary_to_string($binary) {
                    $binaries = explode(' ', $binary);

                    $string = null;
                    foreach($binaries as $binary) {
                        $string .= pack('H*', dechex(bindec($binary)));
                    }

                    return $string;
                }

                switch($_POST['type']) {
                    case 'to_binary':
                        $data['result'] = string_to_binary($_POST['content']);
                        break;

                    case 'to_text':
                        $data['result'] = binary_to_string($_POST['content']);
                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/binary_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function hex_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_hex', 'to_text']) ? $_POST['type'] : 'to_hex';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($_POST['type']) {
                    case 'to_hex':
                        $data['result'] = bin2hex($_POST['content']);
                        break;

                    case 'to_text':
                        $data['result'] = hex2bin($_POST['content']);
                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/hex_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ascii_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_ascii', 'to_text']) ? $_POST['type'] : 'to_ascii';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($_POST['type']) {
                    case 'to_ascii':
                        $data['result'] = '';

                        for($i = 0; $i < strlen($_POST['content']); $i++) {
                            $data['result'] .= ord($_POST['content'][$i]) . ' ';
                        }

                        break;

                    case 'to_text':
                        $content = explode(' ', $_POST['content']);
                        $data['result'] = '';

                        foreach($content as $value) {
                            $data['result'] .= chr($value);
                        }

                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ascii_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function decimal_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_decimal', 'to_text']) ? $_POST['type'] : 'to_decimal';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($_POST['type']) {
                    case 'to_decimal':
                        $data['result'] = '';

                        for($i = 0; $i < strlen($_POST['content']); $i++) {
                            $data['result'] .= ord($_POST['content'][$i]) . ' ';
                        }

                        break;

                    case 'to_text':
                        $content = explode(' ', $_POST['content']);
                        $data['result'] = '';

                        foreach($content as $value) {
                            if(is_numeric($value)) {
                                $data['result'] .= chr($value);
                            }
                        }

                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/decimal_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function octal_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = trim(input_clean($_POST['content']));
            $_POST['type'] = in_array($_POST['type'], ['to_octal', 'to_text']) ? $_POST['type'] : 'to_octal';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($_POST['type']) {
                    case 'to_octal':
                        $data['result'] = '';

                        for($i = 0; $i < strlen($_POST['content']); $i++) {
                            $data['result'] .= decoct(ord($_POST['content'][$i])) . ' ';
                        }

                        break;

                    case 'to_text':
                        $content = explode(' ', $_POST['content']);
                        $data['result'] = '';

                        foreach($content as $value) {
                            $data['result'] .= chr(octdec($value));
                        }

                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/octal_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function morse_converter() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['content'] = input_clean($_POST['content']);
            $_POST['type'] = in_array($_POST['type'], ['to_morse', 'to_text']) ? $_POST['type'] : 'to_morse';

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $morse = new \Morse\Text();

                switch($_POST['type']) {
                    case 'to_morse':
                        $data['result'] = $morse->toMorse($_POST['content']);
                        break;

                    case 'to_text':
                        $data['result'] = $morse->fromMorse($_POST['content']);
                        break;
                }

            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/morse_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function number_to_words_converter() {

        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['number'] = (int) $_POST['number'];

            /* Check for any errors */
            $required_fields = ['language'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = \NumberToWords\NumberToWords::transformNumber($_POST['language'], $_POST['number']);
            }
        }

        $values = [
            'number' => $_POST['number'] ?? 125,
            'language' => $_POST['language'] ?? 'en',
            'languages' => [
                'al' => 'Albanian',
                'ar' => 'Arabic',
                'az' => 'Azerbaijani',
                'fr_BE' => 'Belgian French',
                'pt_BR' => 'Brazilian Portuguese',
                'cs' => 'Czech',
                'dk' => 'Danish',
                'nl' => 'Dutch',
                'en' => 'English',
                'et' => 'Estonian',
                'ka' => 'Georgian',
                'de' => 'German',
                'fr' => 'French',
                'hu' => 'Hungarian',
                'id' => 'Indonesian',
                'it' => 'Italian',
                'lt' => 'Lithuanian',
                'lv' => 'Latvian',
                'mk' => 'Macedonian',
                'ms' => 'Malay',
                'fa' => 'Persian',
                'pl' => 'Polish',
                'ro' => 'Romanian',
                'sk' => 'Slovak',
                'es' => 'Spanish',
                'ru' => 'Russian',
                'sv' => 'Swedish',
                'tr' => 'Turkish',
                'tk' => 'Turkmen',
                'ua' => 'Ukrainian',
                'yo' => 'Yoruba',
            ]
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/number_to_words_converter', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function mailto_link_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/mailto_link_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function youtube_thumbnail_downloader() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/', $_POST['url'], $match)) {
                Alerts::add_field_error('url', l('tools.youtube_thumbnail_downloader.invalid_url'));
            }

            $youtube_video_id = $match[1];

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = [];

                foreach(['default', 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault'] as $key) {
                    $data['result'][$key] = sprintf('https://img.youtube.com/vi/%s/%s.jpg', $youtube_video_id, $key);
                }

            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/youtube_thumbnail_downloader', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function safe_url_checker() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = !google_safe_browsing_check($_POST['url'], settings()->links->google_safe_browsing_api_key);
            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/safe_url_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function utm_link_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/utm_link_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function whatsapp_link_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/whatsapp_link_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function youtube_timestamp_link_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/youtube_timestamp_link_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function google_cache_checker() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Get the URL source */
            $url = 'http://webcache.googleusercontent.com/search?hl=en&q=cache:' . urlencode($_POST['url']) . '&strip=0&vwsrc=1';
            try {
                $response = \Unirest\Request::get($url, [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0'
                ]);
            } catch (\Exception $exception) {
                Alerts::add_field_error('url', l('tools.google_cache_checker.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Get details from the google query result */
                preg_match('/It is a snapshot of the page as it appeared on ([^\.]+)\./i', $response->raw_body, $matches);

                $data['result'] = empty($matches) ? false : $matches[1];
            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/google_cache_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function url_redirect_checker() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['url'] = get_url($_POST['url']);

            /* Check for any errors */
            $required_fields = ['url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Save locations of each request */
            $locations = [];

            /* Get the URL source */
            $i = 1;
            $url = $_POST['url'];

            /* Start the requests process */
            do {
                try {
                    \Unirest\Request::curlOpt(CURLOPT_FOLLOWLOCATION, 0);
                    $response = \Unirest\Request::get($url, [
                        'User-Agent' => settings()->main->title . ' ' . url('tools/url_redirect_checker') . '/1.0'
                    ]);

                    $locations[] = [
                        'url' => $url,
                        'status_code' => $response->code,
                        'redirect_to' => $response->headers['Location'] ?? $response->headers['location'] ?? null,
                    ];

                    $i++;
                    $url = $response->headers['Location'] ?? $response->headers['location'] ?? null;
                } catch (\Exception $exception) {
                    Alerts::add_field_error('url', l('tools.url_redirect_checker.error_message'));
                    break;
                }
            } while($i <= 10 && ($response->code == 301 || $response->code == 302));

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = $locations;
            }
        }

        $values = [
            'url' => $_POST['url'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/url_redirect_checker', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function image_optimizer() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['quality'] = (int) $_POST['quality'];
            $_POST['quality'] = $_POST['quality'] < 1 || $_POST['quality'] > 100 ? 75 : $_POST['quality'];

            /* Check for any errors */
            $required_fields = [];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Image uploads */
            $image = !empty($_FILES['image']['name']);

            /* Check for any errors on the logo image */
            if(!$image) {
                Alerts::add_field_error('image', l('global.error_message.empty_field'));
            }

            if($image) {
                $image_file_name = $_FILES['image']['name'];
                $image_file_extension = explode('.', $image_file_name);
                $image_file_extension = mb_strtolower(end($image_file_extension));
                $image_file_temp = $_FILES['image']['tmp_name'];
                $image_file_type = mime_content_type($image_file_temp);

                if($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), get_max_upload()));
                }

                if($_FILES['image']['size'] > 5 * 1000000) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), 5));
                }

                if($_FILES['image']['error'] && $_FILES['image']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($image_file_extension, ['gif', 'png', 'jpg', 'jpeg', 'webp'])) {
                    Alerts::add_field_error('image', l('global.error_message.invalid_file_type'));
                }

                /* Generate new name for image */
                $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                /* Build the request to the API */
                $mime = mime_content_type($image_file_temp);
                $output = new \CURLFile($image_file_temp, $mime, $image_new_name);

                $body = \Unirest\Request\Body::multipart([
                    'files' => $output,
                ]);

                try {
                    $response = \Unirest\Request::post('http://api.resmush.it/?qlty=' . $_POST['quality'], [], $body);
                } catch (\Exception $exception) {
                    Alerts::add_field_error('image', l('tools.image_optimizer.error_message'));
                }

                if(isset($response->body->error)) {
                    Alerts::add_field_error('image', l('tools.image_optimizer.error_message'));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result']['original_file_url'] = $response->body->dest;
                $data['result']['file_url'] = url('tools/view_image?url=' . urlencode($response->body->dest) . '&global_token=' . \Altum\Csrf::get('global_token'));
                $data['result']['original_size'] = $response->body->src_size;
                $data['result']['new_size'] = $response->body->dest_size;
                $data['result']['name'] = $image_new_name;
            }
        }

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 75,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/image_optimizer', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function png_to_jpg() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/png_to_jpg', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function png_to_webp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/png_to_webp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function png_to_bmp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/png_to_bmp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function png_to_gif() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/png_to_gif', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function png_to_ico() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/png_to_ico', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function jpg_to_png() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/jpg_to_png', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function jpg_to_webp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/jpg_to_webp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function jpg_to_gif() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/jpg_to_gif', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function jpg_to_bmp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/jpg_to_bmp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function jpg_to_ico() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/jpg_to_ico', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function webp_to_ico() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/webp_to_ico', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function webp_to_jpg() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/webp_to_jpg', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function webp_to_png() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/webp_to_png', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function webp_to_bmp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/webp_to_bmp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function webp_to_gif() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/webp_to_gif', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bmp_to_ico() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bmp_to_ico', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bmp_to_jpg() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bmp_to_jpg', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bmp_to_png() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bmp_to_png', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bmp_to_webp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bmp_to_webp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bmp_to_gif() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bmp_to_gif', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ico_to_bmp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ico_to_bmp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ico_to_jpg() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ico_to_jpg', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ico_to_png() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ico_to_png', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ico_to_webp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ico_to_webp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function ico_to_gif() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/ico_to_gif', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gif_to_bmp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gif_to_bmp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gif_to_jpg() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gif_to_jpg', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gif_to_png() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gif_to_png', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gif_to_webp() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gif_to_webp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function gif_to_ico() {
        $this->initiate();

        $data = [];

        $values = [
            'image' => $_POST['image'] ?? null,
            'quality' => $_POST['quality'] ?? 85,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/gif_to_ico', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function text_separator() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);
            $_POST['separated_by'] = in_array($_POST['separated_by'], ['new_line', 'space', ';', '-', '|', '.']) ? $_POST['separated_by'] : 'new_line';
            $_POST['separate_by'] = in_array($_POST['separate_by'], ['new_line', 'space', ';', '-', '|', '.']) ? $_POST['separate_by'] : 'space';

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $search_for = $replace_with = '';

                switch($_POST['separated_by']) {
                    case 'new_line':
                        $search_for = "\r\n";
                        break;

                    case 'space':
                        $search_for = " ";
                        break;

                    default:
                        $search_for = $_POST['separated_by'];
                        break;
                }

                switch($_POST['separate_by']) {
                    case 'new_line':
                        $replace_with = "\r\n";
                        break;

                    case 'space':
                        $replace_with = " ";
                        break;

                    default:
                        $replace_with = $_POST['separate_by'];
                        break;
                }

                $data['result'] = str_replace($search_for, $replace_with, $_POST['text']);

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
            'separated_by' => $_POST['separated_by'] ?? 'new_line',
            'separate_by' => $_POST['separate_by'] ?? 'space',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/text_separator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function email_extractor() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
                preg_match_all($pattern, $_POST['text'], $matches);

                $data['result']['count'] = count($matches[0] ?? []);
                $data['result']['emails'] = $matches[0] ?? [];

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/email_extractor', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function url_extractor() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $pattern = '/(http|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/i';
                preg_match_all($pattern, $_POST['text'], $matches);

                $data['result']['count'] = count($matches[0] ?? []);
                $data['result']['urls'] = $matches[0] ?? [];

            }
        }

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/url_extractor', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function text_size_calculator() {
        $this->initiate();

        $data = [];

        $values = [
            'text' => $_POST['text'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/text_size_calculator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function paypal_link_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/paypal_link_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bbcode_to_html() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['bbcode'] = input_clean($_POST['bbcode']);

            /* Check for any errors */
            $required_fields = ['bbcode'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $bbcode = new \ChrisKonnertz\BBCode\BBCode();
                $data['result'] = $bbcode->render($_POST['bbcode']);
            }
        }

        $values = [
            'bbcode' => $_POST['bbcode'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/bbcode_to_html', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function html_tags_remover() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {

            /* Check for any errors */
            $required_fields = ['content'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $data['result'] = strip_tags($_POST['content']);
            }
        }

        $values = [
            'content' => $_POST['content'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/html_tags_remover', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function number_to_roman_numerals() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['number'] = (float) $_POST['number'];

            /* Check for any errors */
            $required_fields = ['number'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                function number_to_roman_numerals($number) {
                    $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
                    $returnValue = '';
                    while ($number > 0) {
                        foreach($map as $roman => $int) {
                            if($number >= $int) {
                                $number -= $int;
                                $returnValue .= $roman;
                                break;
                            }
                        }
                    }
                    return $returnValue;
                }

                $data['result'] = number_to_roman_numerals($_POST['number']);
            }
        }

        $values = [
            'number' => $_POST['number'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/number_to_roman_numerals', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function roman_numerals_to_number() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['roman_numerals'] = input_clean($_POST['roman_numerals']);

            /* Check for any errors */
            $required_fields = ['roman_numerals'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                function roman_numerals_to_number($roman_numerals) {
                    $romans = [
                        'M' => 1000,
                        'CM' => 900,
                        'D' => 500,
                        'CD' => 400,
                        'C' => 100,
                        'XC' => 90,
                        'L' => 50,
                        'XL' => 40,
                        'X' => 10,
                        'IX' => 9,
                        'V' => 5,
                        'IV' => 4,
                        'I' => 1,
                    ];
                    $result = 0;
                    foreach($romans as $key => $value) {
                        while (strpos($roman_numerals, $key) === 0) {
                            $result += $value;
                            $roman_numerals = substr($roman_numerals, strlen($key));
                        }
                    }
                    return $result;
                }

                $data['result'] = roman_numerals_to_number($_POST['roman_numerals']);
            }
        }

        $values = [
            'roman_numerals' => $_POST['roman_numerals'] ?? null,
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/roman_numerals_to_number', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function unix_timestamp_to_date() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/unix_timestamp_to_date', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function date_to_unix_timestamp() {
        $this->initiate();

        $data = [];

        if(!empty($_POST)) {
            $_POST['year'] = (int) $_POST['year'];
            $_POST['month'] = (int) $_POST['month'];
            $_POST['day'] = (int) $_POST['day'];
            $_POST['hour'] = (int) $_POST['hour'];
            $_POST['minute'] = (int) $_POST['minute'];
            $_POST['second'] = (int) $_POST['second'];
            $_POST['timezone']  = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? query_clean($_POST['timezone']) : 'UTC';

            /* Check for any errors */
            $required_fields = ['year', 'month', 'day', 'hour', 'minute', 'second', 'timezone'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $datetime = (new \DateTime())->setTimezone(new \DateTimeZone($_POST['timezone']))->setDate($_POST['year'], $_POST['month'], $_POST['day'])->setTime($_POST['hour'], $_POST['minute'], $_POST['second']);
                $data['result'] = $datetime->getTimestamp();
            }
        }

        $values = [
            'year' => $_POST['year'] ?? date('Y'),
            'month' => $_POST['month'] ?? date('m'),
            'day' => $_POST['day'] ?? date('d'),
            'hour' => $_POST['hour'] ?? date('H'),
            'minute' => $_POST['minute'] ?? date('i'),
            'second' => $_POST['second'] ?? date('s'),
            'timezone' => $_POST['timezone'] ?? 'UTC',
        ];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/date_to_unix_timestamp', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function signature_generator() {
        $this->initiate();

        $data = [];

        $values = [];

        /* Prepare the view */
        $data['values'] = $values;

        $view = new \Altum\View('tools/signature_generator', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function download() {
        if(!isset($_GET['url']) && !isset($_GET['name'])) {
            die();
        }

        if(!\Altum\Csrf::check('global_token')) {
            die();
        }

        $_GET['url'] = get_url(urldecode($_GET['url']));
        $_GET['name'] = get_slug(urldecode($_GET['name']));
        $_GET['mime_content_type'] = urldecode($_GET['mime_content_type'] ?? '');

        $content = file_get_contents($_GET['url']);

        header('Cache-Control: private');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="' . $_GET['name'] . '";');
        header('Content-Length: ' . strlen($content));

        $mime_content_type = @mime_content_type($_GET['url']);
        if($mime_content_type) {
            header('Content-Type: ' . $mime_content_type);
        }
        else if($_GET['mime_content_type']) {
            header('Content-Type: ' . $_GET['mime_content_type']);
        }

        die($content);
    }

    public function view_image() {
        if (!isset($_GET['url'])) {
            die();
        }

        if (!\Altum\Csrf::check('global_token')) {
            die();
        }

        // Sanitize and decode the URL
        $url = get_url(urldecode($_GET['url']));

        /* Make sure to only allow images through the proxy */
        $image_info = getimagesize($url);
        if (!$image_info) {
            die();
        }

        // Set the Content-Type header to the image's MIME type
        header('Content-Type: ' . $image_info['mime']);

        // Fetch and output the image content
        $content = file_get_contents($url);

        if ($content === false) {
            die();
        }

        echo $content;
    }
}
