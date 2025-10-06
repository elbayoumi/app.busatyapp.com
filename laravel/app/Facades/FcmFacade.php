<?php
namespace App\Facades;

use App\Services\Firebase\FcmService;
use Illuminate\Support\Facades\Facade;

class FcmFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FcmService::class; // The name used in the service container
    }
}
