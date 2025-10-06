<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Bus;
use App\Models\My_Parent;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:super|addresses-list'])->only(['index']);
        $this->middleware(['permission:super|addresses-show'])->only(['show']);
        $this->middleware(['permission:super|addresses-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|addresses-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|addresses-destroy'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {

        $Addresses = Address::query()->with('students');
        switch(!empty($r->text )){
            case true:
                $Addresses = $Addresses->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->whereHas('students', function ($e) use ($r) {
                            $e->where('name', 'like', "%$r->text%");
                        });

                    });
                });
                break;
        }
        switch(!empty($r->school_id )){
            case true:
                $Addresses = $Addresses->where(function ($q) use ($r) {
                    return $q->when($r->school_id, function ($query) use ($r) {
                        return $query->where('school_id', $r->school_id);

                    });
                });
                break;
        }
        switch(!empty($r->created_at )){
            case true:
                $Addresses = $Addresses->where(function ($q) use ($r) {
                    return $q->when($r->created_at, function ($query) use ($r) {
                        return $query->whereDate('created_at', $r->created_at);

                    });
                });
                break;
        }

        switch(!empty($r->my__parent_id )){
            case true:
                $Addresses = $Addresses->where(function ($q) use ($r) {
                    return $q->when($r->my__parent_id, function ($query) use ($r) {
                        return $query->whereDate('my__parent_id', $r->my__parent_id);

                    });
                });
                break;
        }

        $parents = My_Parent::get();
        $Addresses = $Addresses->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.address.index', [
            'addresses'   => $Addresses,
            'schools'   => School::get(),
            'parents'   => $parents,

        ]);

    }

    /**
     * Show the form for creating a new address for the specified student.
     *
     * This method retrieves the student by ID and returns the view for
     * creating a new address associated with that student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The ID of the student
     * @return \Illuminate\View\View
     */

    public function create(Request $request, $id)
    {
        $student = Student::where('id', $id)->first();
        return view('dashboard.address.create', ['student' => $student]);
    }


    /**
     * Store a newly created address in storage.
     *
     * This method validates the request's input and stores a new address
     * associated with the student specified by the given ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The ID of the student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $r, $id)
    {
        $student = Student::where('id', $id)->first();

            $r->validate([
                'my__parent_id'        =>  'required',
                'New_address'            => 'required|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',

            ]);



            Address::create([
                'student_id'    =>    $student->id,
                'my__parent_id' =>    $r->my__parent_id,
                'New_address'   =>    $r->New_address,
                'school_id'     =>    $student->school_id,
                'bus_id'        =>    $student->bus_id,
                'old_address'   =>    $student->address,
                'old_latitude'      =>    $student->latitude,
                'old_longitude'     =>    $student->longitude,
                'latitude'      =>    $r->latitude,
                'longitude'     =>    $r->longitude,
                ]);


            return redirect()->route('dashboard.addresses.index')->with('success', 'تم اضافة البيانات بنجاح');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id  The ID of the address
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Address = Address::where('id', $id)->first();
        if ($Address != null) {
            if ($Address->status == 0) {
                $Address->status = 3;
                $Address->save();
            }
            return view('dashboard.address.show', ['address' => $Address]);
        }

        return redirect()->back();
    }
    /**
     * Show the form for editing the specified address.
     *
     * This method retrieves the address by ID and returns the view for
     * editing the address if it is in the correct status.
     *
     * @param  int  $id  The ID of the address
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $Address = Address::where('id', $id)->first();



      if($Address->status == 0 || $Address->status == 3) {
        if ($Address->status == 0) {
            $Address->status = 3;
            $Address->save();
        }

        return view('dashboard.address.edit', ['address' => $Address]);
        }

        return redirect()->back()->withErrors(['error' => 'لا يمكن التعديل علي الطلب بعد اتخذ اجراء']);


    }
    /**
     * Update the specified address in storage.
     *
     * This method validates the request's input and updates the address
     * information for the given ID. If the address does not exist, it
     * returns an error message. The operation is wrapped in a database
     * transaction to ensure data integrity.
     *
     * @param \Illuminate\Http\Request $r The request instance containing input data
     * @param int $id The ID of the address to update
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $r, $id)
    {
        DB::beginTransaction();

        try {

            $r->validate([
                'New_address'            => 'required|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',

            ]);
            $Address = Address::find($id);
            if($Address == null){

                return redirect()->back()->withErrors(['error' => 'لا يوجد طلب']);

            }
            $Address->update([

                'New_address'   =>    $r->New_address,
                'latitude'      =>    $r->latitude,
                'longitude'     =>    $r->longitude,
                ]);
            DB::commit(); // insert data
            return redirect()->route('dashboard.addresses.index')->with('success', 'تم تعديل البيانات بنجاح');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /**
     * Delete the specified address in storage.
     *
     * This method deletes the address information for the given ID. If the address
     * does not exist, it returns an error message. If the address status is 1,
     * it will update the student address with old address before deleting the
     * address. The operation is wrapped in a database transaction to ensure data
     * integrity.
     *
     * @param int $id The ID of the address to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $Address = Address::FindOrFail($id);
        if($Address->status == 1){
            $student = Student::where('id', $Address->student_id)->first();
            $student->update([
                'address'       =>    $Address->old_address,
                'latitude'      =>    $Address->old_latitude,
                'longitude'     =>    $Address->old_longitude,
            ]);
        }
        $Address->delete();
        return redirect()->route('dashboard.addresses.index')->with('success', 'تم حذف بيانات الطلب بنجاح');
    }
    /**
     * Update the specified address in storage.
     *
     * This method updates the status of the address for the given ID. If the address
     * status is 0 or 3, it will update the student address with new address and
     * status of the address to 1. The operation is wrapped in a database transaction
     * to ensure data integrity.
     *
     * @param int $id The ID of the address to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accepted($id)
    {
        $Address = Address::FindOrFail($id);

        if ($Address->status == 0 or $Address->status == 3) {
            $student = Student::where('id', $Address->student_id)->first();
            $student->update([
                'address'       =>    $Address->New_address,
                'latitude'      =>    $Address->latitude,
                'longitude'     =>    $Address->longitude,
            ]);
            $Address->status = 1;
            $Address->save();
            return redirect()->route('dashboard.addresses.index')->with('success', 'تم قبول الطلب');

        }else{
            return redirect()->back();

        }

    }
    /**
     * Refuse the specified address in storage.
     *
     * This method refuses the address information for the given ID. If the address
     * status is 0 or 3, it will update the address status to 2. The operation is
     * wrapped in a database transaction to ensure data integrity.
     *
     * @param int $id The ID of the address to refuse
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unaccepted($id)
    {
        $Address = Address::FindOrFail($id);

        if ($Address->status == 0 or $Address->status == 3) {
            $Address->status = 2;
            $Address->save();
            return redirect()->route('dashboard.addresses.index')->with('success', 'تم رفض الطلب');

        }else{
            return redirect()->back();

        }

    }
/**
 * Display the specified unaccepted address.
 *
 * This method retrieves an address with the given ID that has a status of 2 (unaccepted).
 * If the address is found, it fetches the associated school and buses, then renders
 * a view to display the address details along with the related buses.
 * If the address is not found, it redirects back to the previous page.
 *
 * @param int $id The ID of the address to display
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */

    public function showUnaccepted($id)
    {
        $Address = Address::where('id', $id)->where('status', 2)
        ->first();
        if ($Address != null) {
            $school = School::find($Address->school_id);
            $buses = Bus::where('school_id', $school->id)->get();
            return view('dashboard.address.show-unaccepted', ['address' => $Address, 'buses' => $buses]);
        }

        return redirect()->back();
    }
}
