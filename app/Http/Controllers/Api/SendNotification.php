<?php


namespace App\Http\Controllers\Api;

use Pusher\Pusher;
use GuzzleHttp\Client;
use JWTAuth;

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

    public static function sendBot($chat_id, $text)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot925882756:AAEt3HsNT_PWsK_bYFzhFqXZUaq34Ayiz0c/sendMessage?chat_id=$chat_id&text=$text";
        $response = $client->request('POST', $url);
        $code = $response->getStatusCode();
    }

    public static function getUserId()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $id = $user['id'];
        return $id;
    }
}
