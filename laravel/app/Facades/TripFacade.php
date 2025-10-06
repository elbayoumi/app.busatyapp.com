<?php
namespace App\Facades;

use App\Services\TripService;
use Illuminate\Support\Facades\Facade;

class TripFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TripService::class; // The name used in the service container
    }
}
