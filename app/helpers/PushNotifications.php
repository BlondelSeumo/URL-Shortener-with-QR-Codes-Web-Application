<?php

namespace Altum\Helpers;

class PushNotifications {

    public static function send($content, $push_subscriber) {
        if(!\Altum\Plugin::is_active('push-notifications') || !settings()->push_notifications->is_enabled) {
            return true;
        }

        /* Prepare the web push */
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:hey@example.com',
                'publicKey' => settings()->push_notifications->public_key,
                'privateKey' => settings()->push_notifications->private_key,
            ],
        ];

        $web_push = new \Minishlink\WebPush\WebPush($auth);

        /* Set subscriber data */
        $subscriber = [
            'endpoint' => $push_subscriber->endpoint,
            'expirationTime' => null,
            'keys' => json_decode($push_subscriber->keys, true)
        ];

        /* Send the web push */
        if(settings()->push_notifications->icon) {
            $content['icon'] = \Altum\Uploads::get_full_url('push_notifications_icon') .  settings()->push_notifications->icon;
            $content['badge'] = \Altum\Uploads::get_full_url('push_notifications_icon') .  settings()->push_notifications->icon;
        }

        $report = $web_push->sendOneNotification(
            \Minishlink\WebPush\Subscription::create($subscriber),
            json_encode($content),
            ['TTL' => 5000]
        );

        /* Unsubscribe if push failed */
        return $report->getResponse()->getStatusCode() == 410 ? false : true;
    }

}
