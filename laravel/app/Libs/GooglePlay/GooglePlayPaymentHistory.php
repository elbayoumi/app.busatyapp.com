<?php

namespace App\Libs\GooglePlay;

use App\Libs\GooglePlay\Config\GooglePlayAuthConfig;
use App\Libs\GooglePlay\Interfaces\IGooglePlayPaymentHistory;
use App\Libs\GooglePlay\Interfaces\IJwtTokenHandler;
use Google\Exception;
use Google_Client;
use Google_Service_AndroidPublisher;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GooglePlayPaymentHistory implements IGooglePlayPaymentHistory
{
    private const GOOGLE_PACKAGE_NAME = "com.busaty.parent";
    private const SUBSCRIPTION_ID = "monthly_subscription";
    private const GOOGLE_BASE_URL =
        "https://www.googleapis.com/androidpublisher/v3/applications/".self::GOOGLE_PACKAGE_NAME."/purchases/subscriptions/".self::SUBSCRIPTION_ID."/tokens";

    private const GOOGLE_PLAY_AUTH_SCOPE = "https://www.googleapis.com/auth/androidpublisher";

    private  $jwtTokenHandler;
    /**
     * @var GooglePlayAuthConfig
     */
    private $authConfig;

    public function __construct(IJwtTokenHandler $jwtTokenHandler, GooglePlayAuthConfig $authConfig)
    {
        $this->jwtTokenHandler = $jwtTokenHandler;
        $this->authConfig = $authConfig;
    }

    /**
     * @throws Exception
     */
    function getSubscriptionDetailsByPurchaseToken($purchaseToken)
    {

        $client = new Google_Client();
        $client->setAuthConfig($this->authConfig->getAuthFilePath());
        $client->addScope(self::GOOGLE_PLAY_AUTH_SCOPE);

        $client->authorize();

        $service = new Google_Service_AndroidPublisher($client);

        $purchase = $service->purchases_subscriptions
            ->get(self::GOOGLE_PACKAGE_NAME, self::SUBSCRIPTION_ID, $purchaseToken);

        dd($purchase);

    }
}
