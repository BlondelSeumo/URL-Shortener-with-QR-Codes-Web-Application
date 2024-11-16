<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\models;

class Chats extends Model {

    public function delete($chat_id) {

        if(!$chat = db()->where('chat_id', $chat_id)->getOne('chats', ['user_id', 'chat_id',])) {
            return;
        }

        $result = database()->query("SELECT `image` FROM `chats_messages` WHERE `chat_id` = {$chat->chat_id}");
        while($row = $result->fetch_object()) {
            \Altum\Uploads::delete_uploaded_file($row->image, 'chats_images');
        }

        /* Delete from database */
        db()->where('chat_id', $chat_id)->delete('chats');
    }

}
