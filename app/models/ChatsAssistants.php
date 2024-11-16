<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\models;

class ChatsAssistants extends Model {

    public function get_chats_assistants() {

        /* Get the user projects */
        $chats_assistants = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('chats_assistants');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $chats_assistants_result = database()->query("SELECT * FROM `chats_assistants` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $chats_assistants_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $chats_assistants[$row->chat_assistant_id] = $row;
            }

            cache()->save(
                $cache_instance->set($chats_assistants)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $chats_assistants = $cache_instance->get();

        }

        return $chats_assistants;

    }

    public function delete($chat_assistant_id) {

        $chat_assistant = db()->where('chat_assistant_id', $chat_assistant_id)->getOne('chats_assistants', ['chat_assistant_id', 'image']);

        if(!$chat_assistant) return;

        \Altum\Uploads::delete_uploaded_file($chat_assistant->image, 'chats_assistants');

        /* Delete the resource */
        db()->where('chat_assistant_id', $chat_assistant_id)->delete('chats_assistants');

        /* Clear the cache */
        cache()->deleteItemsByTag('chats_assistants');

    }

}
