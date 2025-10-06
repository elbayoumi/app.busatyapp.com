<?php

namespace App\Repositories\Schools\Buses;

use App\Http\Requests\StoreStudentsToBusRequest;

interface BusesInterface
{
    /**
     * Store students to a bus.
     *
     * @param StoreStudentsToBusRequest $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function storeStudentsToBus(StoreStudentsToBusRequest $request);
}
