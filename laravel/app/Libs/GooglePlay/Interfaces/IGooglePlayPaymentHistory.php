<?php

namespace App\Libs\GooglePlay\Interfaces;

interface IGooglePlayPaymentHistory
{
    //function getSubscriptionDetails($subscriptionId, $purchaseToken);
    function getSubscriptionDetailsByPurchaseToken($purchaseToken);
}
