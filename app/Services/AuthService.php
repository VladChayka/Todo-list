<?php

namespace App\Services;

use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;
use function config;


class AuthService

{
    const DEFAULT_REFRESH_TOKEN_CONFIG = 'app.api_refresh_token_expired_in_days';

    public function getRefreshTokenExpiresAt(string $refreshTokenConfig = self::DEFAULT_REFRESH_TOKEN_CONFIG)
    {
        $refreshTokenExpiresAfter = config($refreshTokenConfig);
        return ($refreshTokenExpiresAfter * 86400) - 30;
    }

    public function getTokenAndRefreshToken(string $email, string $password)
    {
        $oClient = OClient::where('password_client', 1)->first();

        $http = new Client();

        $response = $http->request('POST', config('app.local_app_url') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scopes' => []
            ],
        ]);

        $result = json_decode((string)$response->getBody(), true);



        $result['refreshTokenExpiresAt'] = $this->getRefreshTokenExpiresAt();

        return $result;
    }
}
