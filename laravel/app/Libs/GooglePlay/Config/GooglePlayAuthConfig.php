<?php

namespace App\Libs\GooglePlay\Config;

class GooglePlayAuthConfig
{
    private  $clientEmail;
    private  $authFilePath;

    public function __construct($clientEmail, $authFilePath)
    {
        $this->setClientEmail($clientEmail);
        $this->setAuthFilePath($authFilePath);
    }

    public function getAuthFilePath(): string
    {
        return $this->authFilePath;
    }

    public function setAuthFilePath(string $authFilePath): void
    {
        $this->authFilePath = $authFilePath;
    }

    public function getClientEmail(): string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): void
    {
        $this->clientEmail = $clientEmail;
    }
}
