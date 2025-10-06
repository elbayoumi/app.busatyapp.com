<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Libs\GooglePlay\Interfaces\IGooglePlayPaymentHistory;
use App\Libs\GooglePlay\Interfaces\IJwtTokenHandler;
use App\Models\Absence;
use App\Models\Attendant;
use App\Models\Bus;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\My_Parent;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    // private IGooglePlayPaymentHistory $googlePlayPaymentHistory;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IGooglePlayPaymentHistory $googlePlayPaymentHistory)
    {

        $this->middleware('auth:web');
        // $this->googlePlayPaymentHistory = $googlePlayPaymentHistory;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

    // $absence = Absence::with('bus.attendants.fcmTokens')->first();

    // // استخراج فقط fcm_token من كل سجل
    // $fcmTokens = $absence->bus->attendants->pluck('fcmTokens.*.fcm_token')->flatten()->toArray();

    // dd($fcmTokens);


        return view('dashboard.home',[
            'School_count' => School::count(),
            'Student_count' => Student::count(),
            'Attendant_count' => Attendant::count(),
            'bus_count' => Bus::count(),
            'My_Parent_count' => My_Parent::count(),
            'admins_count' => Staff::count(),
            'Classroom_count' => Classroom::count(),
            'Grade_coun' => Grade::count(),
            'Schools' => latest(new School),
            'students' => latest(new Student),
            'buses' => latest(new Bus),
            'admins' => latest(new Attendant, ['type'=> 'admins']),
            'drivers' => latest(new Attendant, ['type'=> 'drivers']),
            'parents' => latest(new My_Parent),



        ]);
    }

}


