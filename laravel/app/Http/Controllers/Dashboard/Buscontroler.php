<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\BusExport;
use App\Http\Controllers\Controller;
use App\Models\Attendant;
use App\Models\Bus;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Validation\Rule;

class Buscontroler extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:super|buses-list'])->only(['index']);
        $this->middleware(['permission:super|buses-show'])->only(['show']);
        $this->middleware(['permission:super|buses-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|buses-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|buses-destroy'])->only(['destroy']);
    }





    public function index(Request $r)
    {


        $text = isset($r->text) && $r->text != '' ? $r->text : null;
        $bus_number = isset($r->bus_number) && $r->bus_number != '' ? $r->bus_number : null;
        $school_id = isset($r->school_id) && $r->school_id != '' ? $r->school_id : null;


        $all_buses = Bus::query();
        if ($text != null) {
            $all_buses = $all_buses->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r) {
                    return $query->where('name', 'like', "%$r->text%");

                });
            });
        }
        if ($bus_number != null) {
            $all_buses = $all_buses->where(function ($q) use ($r) {
                return $q->when($r->bus_number, function ($query) use ($r) {
                    return $query->where('id', $r->bus_number,);

                });
            });
        }

        if ($school_id != null) {
            $all_buses = $all_buses->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id,);

                });
            });
        }



        $all_buses = $all_buses->orderBy('id', 'desc')->paginate(10);


        return view('dashboard.buses.index', [
            'all_buses' => $all_buses,
            'schools' => School::get(),

        ]);
    }

    public function create()
    {
        $school =  School::get();
        return view('dashboard.buses.create', [
            'school'   => $school,
        ]);
    }


    public function store(Request $r)
    {

            $r->validate([
                'name'                          => 'required|max:255',
                'school_id'                     =>  'required',
                'notes'                         => 'required|max:255',
                'car_number'                    => 'required|max:255',

            ]);

            $School = School::find($r->school_id);

            if ($School  != null) {

            $bus  = new Bus();
                $bus->name                      = $r->name;
                $bus->school_id                 = $r->school_id;
                $bus->notes                     = $r->notes;
                $bus->car_number                = $r->car_number;
                $bus->save();

                return redirect()->route('dashboard.buses.index')->with('success', 'تم اضافة بيانات الباص بنجاح');
            }

            return redirect()->back()->withErrors(['error' => 'Something was wrong']);
    }




    public function show($id)
    {
        $buses = Bus::FindOrFail($id);
        $school =  School::get();
        return view('dashboard.buses.show', [
            'buses'   => $buses,
            'school'   => $school,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $buses = Bus::FindOrFail($id);
        $school =  School::where('id', $buses->school_id)->first();

        return view('dashboard.buses.edit', [
            'buses'   => $buses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, $id)
    {

        $bus = Bus::FindOrFail($id);
        $rules = [
            'name'                          => 'required|max:255',
            'notes'                         => 'required|max:255',
            'car_number'                    => 'required|max:255',
        ];

        $r->validate($rules);
            $bus->name                      = $r->name;
            $bus->notes                     = $r->notes;
            $bus->car_number                = $r->car_number;
            $bus->save();
            return redirect()->route('dashboard.buses.index')->with('success', 'تم تعديل بيانات الباص بنجاح');

    }


    public function destroy($id)
    {
        $bus = Bus::FindOrFail($id);
        $bus->delete();
        return redirect()->back()->with('success', 'تم حذف البيانات  بنجاح');
    }

    public function getBus($id)
    {
        return Bus::where("school_id", $id)->pluck("name", "id");
    }

    public function print_data($id)
    {
        $bus = Bus::where("id", $id)->first();
        return view('dashboard.buses.print', [
            'bus' => $bus,
        ]);
    }

    public function bus_export($id)
    {
        $bus = Bus::where("id", $id)->first();
        return Excel::download(new BusExport($bus), 'باص'.'-'.$bus->name.'-'.date('Y-m-d').'.xlsx');

    }

    public function bus_export_pdf($id)
    {
        $bus = Bus::where("id", $id)->first();
        // dd($data['bus'][0]['id']);
        $pdf = PDF::loadView('dashboard.buses.pdf', [
            'bus' => $bus,
        ]);

        return $pdf->download($bus->name.'.pdf');
    }
}
