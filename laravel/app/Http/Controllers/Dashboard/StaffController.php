<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Governorate;
use App\Models\City;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Intervention\Image\Facades\Image;

class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:super|staff-list'])->only(['index']);
        $this->middleware(['permission:super|staff-show'])->only(['show']);
        $this->middleware(['permission:super|staff-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|staff-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|staff-destroy'])->only(['destroy']);
    }


    public function index()
    {

        $all_staff = Staff::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.staff.index', ['all_staff' => $all_staff]);
    }

    public function find(Request $r)
    {

        $staff = Staff::where('phone', 'like', "%$r->text%")
        ->orWhere('name', 'like', "%$r->text%")
        ->paginate(10);
        return view('dashboard.staff.index', ['staff' => $staff, 'text' => $r->text]);
    }


    public function create()
    {
        $roles = Role::all();
        $permissions_groups = Permission::get()->groupBy('group');
        return view('dashboard.staff.create', compact(['roles', 'permissions_groups']));
    }


    public function store(Request $r)
    {
        $r->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'string', 'email', 'max:90','unique:staff'],
            'phone' => ['required','unique:staff'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required'],
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $Staff = new Staff;
        $Staff->name = $r->name;
        $Staff->email = $r->email;
        $Staff->phone = $r->phone;
        $Staff->password = Hash::make($r->password);
        if (!empty($r->logo) ) {

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/staffs_logo/' . $r->logo->hashName()));

                $Staff->logo = $r->logo->hashName();

        }//end of if
        $Staff->save();
        $Staff->assignRole($r->role);


        return redirect()->route('dashboard.staff.index')->with('success', 'Staff added successfully');

    }


    public function show($id)
    {
        $Staff = Staff::findOrfail($id);
        $roles = Role::all();
        $permissions_groups = Permission::get()->groupBy('group');
        return view('dashboard.staff.show', compact([
            'Staff',
            'roles',
            'permissions_groups',

        ]));
    }


    public function edit($id)
    {
        $Staff = Staff::findOrfail($id);

        $roles = Role::all();
        $permissions_groups = Permission::get()->groupBy('group');
        return view('dashboard.staff.edit', compact([
            'Staff',
            'roles',
            'permissions_groups',

        ]));
    }


    public function update(Request $r, $id)
    {

        $Staff = Staff::findOrfail($id);
        $r->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'string', 'email', 'max:90', 'unique:staff,email,' .$id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'phone' => ['required', 'unique:staff,phone,' .$id],
            'role' => ['required'],
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',


        ]);

        $Staff = Staff::findOrfail($id);
        $Staff->syncRoles([$r->role]);

        if (!empty($r->name)) {
            $Staff->name = $r->name;
        }

        if (!empty($r->email)) {
            $Staff->email = $r->email;
        }

        if (!empty($r->phone)) {
            $Staff->phone = $r->phone;
        }

        if (!empty($r->status)) {
            $Staff->status = $r->status;
        }

        if (!empty($r->password)) {
            $Staff->password = Hash::make($r->password);
        }


        if ($r->logo) {

            if ($Staff->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/staffs_logo/' . $Staff->logo);

            }//end of if

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/staffs_logo/' . $r->logo->hashName()));

                $Staff->logo  = $r->logo->hashName();

        }//end of if

        $Staff->save();



        return redirect()->route('dashboard.staff.index')->with('success', 'Staff data has been modified successfully');
    }

    public function update_info(Request $r, $id)
    {

        $Staff = Staff::findOrfail($id);
        $r->validate([

        ]);

        $Staff = Staff::findOrfail($id);
        $Staff->gender = $r->gender;
        $Staff->birth_date = $r->birth_date;
        $Staff->address_line_1 = $r->address_line_1;
        $Staff->address_line_1 = $r->address_line_1;
        $Staff->address_line_2 = $r->address_line_2;
        $Staff->governorate_id = $r->governorate_id;
        $Staff->city_id = $r->city_id;
        $Staff->save();


        return redirect()->route('dashboard.staff.index')->with('success', 'Staff data has been modified successfully');
    }


    public function destroy(Staff $Staff)
    {
        // $Staff = Staff::findOrfail($id);
        if ($Staff->logo != 'default.png') {

            Storage::disk('public_uploads')->delete('/staffs_logo/' . $Staff->logo);

        }//end of if


        $Staff->delete();
        return redirect()->back()->with('success', 'Staff deleted successfully');
    }
}
