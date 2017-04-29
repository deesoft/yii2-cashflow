<?php

namespace rest\classes;

use Yii;
use yii\web\Response;
use yii\base\Behavior;
use app\models\ar\Client;

/**
 * Description of FilterResponse
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class FilterResponse extends Behavior
{
    public $responseMessage;

    public function events()
    {
        return[
            Response::EVENT_BEFORE_SEND => 'beforeSend'
        ];
    }

    public function beforeSend($event)
    {
        /* @var $response Response */
        $response = $event->sender;
        if ($response->format != Response::FORMAT_JSON) {
            return;
        }
        /* @var $client Client */
        $user = Yii::$app->user;
        $client = $user->getClient();
        $data = $response->data;
        $isSuccess = $response->isSuccessful;
        $message = [
            'code' => $response->statusCode,
            'message' => !$isSuccess && isset($data['message']) ? $data['message'] : $response->statusText,
        ];
        $response->statusCode = 200;
        if ($client) {
            $logged = !$user->getIsGuest();
            $auth = [
                'isLoggedIn' => $logged,
                'token' => $user->getToken(),
                'hasNotiveKey' => !empty($client->notive_key),
                'client_id' => $client->id,
            ];
            if ($logged) {
                $auth = array_merge($auth, [
                    'user_id' => $user->id,
                    'name' => $user->username,
                    'fullname' => $user->fullname,
                    'photoUrl' => $user->avatarUrl,
                    'emailAddress' => $user->email,
                    'phoneNumber' => null,
                ]);
            }

            $response->data = [
                'auth' => $auth,
                'returnMessage' => $message,
            ];
        } else {
            $response->data = [
                'auth' => new \stdClass(),
                'returnMessage' => $message,
            ];
        }
        $response->data[$isSuccess ? 'data' : 'errorResponse'] = $data;
    }
}
