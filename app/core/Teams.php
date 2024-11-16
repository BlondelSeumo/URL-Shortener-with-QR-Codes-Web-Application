<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

use Altum\Models\User;

class Teams {
    public static $team = null;
    public static $team_member = null;
    public static $team_user = null;

    public static function initialize() {
        if(isset($_SESSION['team_id']) && \Altum\Plugin::is_active('teams')) {
            /* Get requested team */
            self::$team = (new \Altum\Models\Teams())->get_team_by_team_id($_SESSION['team_id']);

            if(self::$team) {
                /* Get team member */
                self::$team_member = (new \Altum\Models\TeamsMembers())->get_team_member_by_team_id_and_user_id(self::$team->team_id, \Altum\Authentication::$user_id);

                if(self::$team_member) {
                    self::$team_member->access = json_decode(self::$team_member->access);
                }
            }
        }
    }

    public static function delegate_access() {
        if(!self::$team || !self::$team_member) {
            return false;
        }

        /* Get team owner user */
        self::$team_user = (new User())->get_user_by_user_id(self::$team->user_id);

        return self::$team_user;
    }

    public static function is_delegated() {
        return self::$team && self::$team_member;
    }

    public static function has_access($access_level = null) {
        if(!self::$team || !self::$team_member) {
            /* Return true as there is no team or team member set */
            return true;
        }

        return self::$team_member->access->{$access_level};
    }
}
