<?php

namespace App\Helpers;

class TurnstileHelper
{
    public static function verify(string $responseToken): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret' => \App\Config\Config::getKey('TURNSTILE_SECRET'),
                'response' => $responseToken,
                'remoteip' => $ip,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);

        return (bool) $response->success;
    }
}
