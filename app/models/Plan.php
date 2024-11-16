<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Plan extends Model {

    public function get_plan_by_id($plan_id) {

        switch($plan_id) {

            case 'guest':

                return settings()->plan_guest;

                break;

            case 'free':

                return settings()->plan_free;

                break;

            case 'custom':

                return settings()->plan_custom;

                break;

            default:

                if(CACHE) {
                    $plans = self::get_plans();
                    $plan = $plans[$plan_id] ?? null;

                    if(!$plan) {
                        return settings()->plan_custom;
                    }

                } else {
                    $plan = db()->where('plan_id', $plan_id)->getOne('plans');

                    if(!$plan) {
                        return settings()->plan_custom;
                    }

                    $plan->settings = json_decode($plan->settings ?? '');
                $plan->translations = json_decode($plan->translations ?? '');
                    $plan->prices = json_decode($plan->prices);
                }

                return $plan;

                break;

        }

    }

    public function get_plan_taxes_by_taxes_ids($taxes_ids) {

        $taxes_ids = json_decode($taxes_ids);

        if(empty($taxes_ids)) {
            return null;
        }

        $taxes_ids = implode(',', $taxes_ids);

        $taxes = [];

        $result = database()->query("SELECT * FROM `taxes` WHERE `tax_id` IN ({$taxes_ids})");

        while($row = $result->fetch_object()) {

            /* Country */
            $row->countries = json_decode($row->countries);

            $taxes[] = $row;

        }

        return $taxes;
    }

    public function get_plans() {

        $data = [];

        $cache_instance = cache()->getItem('plans');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {
            $result = database()->query("SELECT * FROM `plans` ORDER BY `order`");

            while($row = $result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $row->translations = json_decode($row->translations ?? '');
                $row->prices = json_decode($row->prices);
                $data[$row->plan_id] = $row;
            }

            cache()->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS));

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

}
