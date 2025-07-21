<?php

namespace App\Services;

use Google_Client;
use Illuminate\Support\Facades\Http;

class PushNotificationService
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->accessToken = $this->getAccessToken();
    }

    protected function getAccessToken()
    {
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setAuthConfig(storage_path('app/firebase/firebase_credentials.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }

    public function sendNotification(array $fcmTokens, string $title, string $body, array $data = [])
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        foreach ($fcmTokens as $token) {
            $payload = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    "data" => $data
                ]
            ];

            Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);
        }
    }
}
