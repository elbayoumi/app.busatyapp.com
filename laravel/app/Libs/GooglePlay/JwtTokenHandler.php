<?php

namespace App\Libs\GooglePlay;
use App\Libs\GooglePlay\Config\GooglePlayAuthConfig;
use App\Libs\GooglePlay\Interfaces\IJwtTokenHandler;
use \Firebase\JWT\JWT;
use Google\Exception;
use Google_Client;
class JwtTokenHandler implements IJwtTokenHandler
{
    private const GOOGLE_PLAY_JWT_EXPIRY = 720000;
    private const GOOGLE_PLAY_AUTH_URL = "https://www.googleapis.com/oauth2/v4/token";
    private const GOOGLE_PLAY_AUTH_SCOPE = "https://www.googleapis.com/auth/androidpublisher";
    private  $authConfig;

    public function __construct(GooglePlayAuthConfig $authConfig)
    {
        $this->authConfig = $authConfig;
    }

    /**
     * @throws Exception
     */
    function generateToken(): string
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->authConfig->getAuthFilePath());
        $client->addScope(self::GOOGLE_PLAY_AUTH_SCOPE);
        $token = $client->fetchAccessTokenWithAssertion();

        $now_seconds = time();
        $payload = array(
            "iat" => $now_seconds,
            "exp" => $now_seconds + self::GOOGLE_PLAY_JWT_EXPIRY,  // Set expiration time
            "aud" => self::GOOGLE_PLAY_AUTH_URL,
            "iss" => $client->getClientId(),
            "scope" => self::GOOGLE_PLAY_AUTH_SCOPE  // Replace with the exact scope for Publisher ID
        );

        return JWT::encode($payload, $token['access_token'], 'HS256');
    }
}
