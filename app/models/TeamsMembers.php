<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class TeamsMembers extends Model {

    public function get_team_member_by_team_id_and_user_id($team_id, $user_id) {

        /* Get the team member */
        $team_member = null;

        /* Try to check if the resource exists via the cache */
        $cache_instance = cache()->getItem('team_member?team_id=' . $team_id . '&user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $team_member = db()->where('team_id', $team_id)->where('user_id', $user_id)->getOne('teams_members');

            if($team_member) {
                cache()->save(
                    $cache_instance->set($team_member)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $team_member->user_id)->addTag('team_id=' . $team_member->team_id)
                );
            }

        } else {

            /* Get cache */
            $team_member = $cache_instance->get();

        }

        return $team_member;

    }

}
