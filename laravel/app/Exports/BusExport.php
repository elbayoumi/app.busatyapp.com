<?php

namespace App\Exports;

use App\Models\bus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BusExport implements FromView
{

    protected $bus;
    
    public function __construct($bus)
    {
        $this->bus = $bus;
    }

    public function view(): View
    {
        return view('dashboard.exports.bus', [
            'bus' =>   $this->bus,
        ]);
    }

}
