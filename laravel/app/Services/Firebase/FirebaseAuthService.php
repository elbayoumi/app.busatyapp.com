<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthService
{
    protected Auth $auth;

    // public function __construct()
    // {
    //     $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
    //     $this->auth = $factory->createAuth();
    // }
    public function __construct()
    {
        $credentialsPath = config('services.firebase.credentials_file');

        if (!file_exists($credentialsPath)) {
            return;
        }

        $factory = (new Factory)->withServiceAccount($credentialsPath);
        $this->auth = $factory->createAuth();
    }


    public function verifyIdToken(string $idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken->claims()->get('email');
        } catch (FailedToVerifyToken $e) {
            throw new \Exception("Invalid token", 401);
        }
    }

    public function getUserInfo(string $idToken)
    {
        $verifiedToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedToken->claims()->get('sub');
        return $this->auth->getUser($uid);
    }
}
