<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Filters {

    public $allowed_filters = [];
    public $allowed_order_by = [];
    public $allowed_search_by = [];
    public $allowed_results_per_page = [];

    public $filters = [];
    public $filters_types = [];
    public $search = '';
    public $search_by = '';
    public $order_by = '';
    public $order_type = '';
    public $results_per_page = 25;

    public $get = [];
    public $has_applied_filters = false;

    private $is_processed = false;

    public function __construct($allowed_filters = [], $allowed_search_by = [], $allowed_order_by = [], $allowed_results_per_page = [], $filters_types = []) {

        $this->allowed_filters = $allowed_filters;
        $this->filters_types = $filters_types;
        $this->allowed_order_by = $allowed_order_by;
        $this->allowed_search_by = $allowed_search_by;
        $this->allowed_results_per_page = empty($allowed_results_per_page) ? [10, 25, 50, 100, 250, 500, 1000] : $allowed_results_per_page;

    }

    public function process() {

        /* Filters */
        foreach($this->allowed_filters as $filter) {

            if(isset($_GET[$filter]) && $_GET[$filter] != '') {
                $this->filters[$filter] = query_clean($_GET[$filter]);
                $this->get[$filter] = $_GET[$filter];
            }

        }

        /* Search */
        if(count($this->allowed_search_by) && isset($_GET['search']) && isset($_GET['search_by']) && in_array($_GET['search_by'], $this->allowed_search_by)) {

            $_GET['search'] = query_clean($_GET['search']);
            $_GET['search_by'] = query_clean($_GET['search_by']);

            $this->search = $_GET['search'];
            $this->search_by = $_GET['search_by'];

            $this->get['search'] = $_GET['search'];
            $this->get['search_by'] = $_GET['search_by'];
        }

        /* Order by */
        if(count($this->allowed_order_by) && isset($_GET['order_by']) && in_array($_GET['order_by'], $this->allowed_order_by)) {

            $_GET['order_by'] = query_clean($_GET['order_by']);
            $order_type = isset($_GET['order_type']) && in_array($_GET['order_type'], ['ASC', 'DESC']) ? query_clean($_GET['order_type']) : 'ASC';

            $this->order_by = $_GET['order_by'];
            $this->order_type = $order_type;

            $this->get['order_by'] = $_GET['order_by'];
            $this->get['order_type'] = $_GET['order_type'];
        }

        /* Results per page */
        if(isset($_GET['results_per_page']) && in_array($_GET['results_per_page'], $this->allowed_results_per_page)) {
            $this->results_per_page = (int) $_GET['results_per_page'];
            $this->get['results_per_page'] = $_GET['results_per_page'];
        }

        if(count($this->get)) $this->has_applied_filters = true;

    }

    public function get_sql_where($table_prefix = null) {
        if(!$this->is_processed) $this->process();

        $where = '';

        $table_prefix = $table_prefix ? "`{$table_prefix}`." : null;

        /* Filters */
        foreach($this->filters as $key => $value) {
            if(isset($this->filters_types[$key])) {
                switch($this->filters_types[$key]) {
                    case 'json_contains':
                        /* Only allow numbers for array json searching */
                        $value = (int) $value;
                        $where .= " AND JSON_CONTAINS(COALESCE(NULLIF({$table_prefix}`{$key}`, ''), '[]'), '{$value}', '$')";
                        break;
                }
            } else {
                /* Classic */
                $where .= " AND {$table_prefix}`{$key}` = '{$value}'";
            }
        }

        /* Search */
        if($this->search && $this->search_by) {
            $where .= " AND {$table_prefix}`{$this->search_by}` LIKE '%{$this->search}%'";
        }

        return $where;
    }

    public function get_sql_order_by($table_prefix = null) {
        if(!$this->is_processed) $this->process();

        $order_by = '';

        $table_prefix = $table_prefix ? "`{$table_prefix}`." : null;

        /* Order By */
        if($this->order_by && $this->order_type) {
            $order_by .= " ORDER BY {$table_prefix}`{$this->order_by}` {$this->order_type}";
        }

        return $order_by;
    }

    public function get_results_per_page() {
        return $this->results_per_page;
    }

    public function get_get() {
        $get = [];

        foreach($this->get as $key => $value) {
            $get[] = $key . '=' . $value;
        }

        return implode('&', $get);
    }

    public function set_default_order_by($order_by, $order_type) {

        if(!in_array($order_type, ['ASC', 'DESC'])) {
            $order_type = 'DESC';
        }
        
        if(!$order_by && count($this->allowed_order_by)) {
            $order_by = reset($this->allowed_order_by);
        }

        $this->order_by = $order_by;
        $this->order_type = $order_type;
    }

    public function set_default_results_per_page($results_per_page) {

        if(!$results_per_page) {
            $results_per_page = 25;
        }

        $this->results_per_page = $results_per_page;
    }
}
