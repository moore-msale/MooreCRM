<?php


namespace App\Http\Controllers\Api;

use Pusher\Pusher;
use GuzzleHttp\Client;

class SendNotification
{
    public static function sendPusher($text)
    {
        $options = array(
            'cluster' => 'ap2',
            'useTLS' => false
        );
        $pusher = new Pusher('88d923159c99cff242b3', '5a9d09dd60899d5e54fc',
            '886463', $options);
        $data['message'] = $text;
        $pusher->trigger('my-channel', 'my-event', $data);
    }

    public static function sendBot($name)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot925882756:AAEt3HsNT_PWsK_bYFzhFqXZUaq34Ayiz0c/sendMessage?chat_id=160868894&text=\"$name\"";
        $response = $client->request('POST', $url);
        $code = $response->getStatusCode();
    }
}
