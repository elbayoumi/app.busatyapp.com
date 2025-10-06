<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\{
    Attendant,
    Bus,
    Gender,
    Religion,
    School,
    Type_Blood,
};

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class AttendantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:super|attendants-list'])->only(['index']);
        $this->middleware(['permission:super|attendants-show'])->only(['show']);
        $this->middleware(['permission:super|attendants-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|attendants-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|attendants-destroy'])->only(['destroy']);
    }


    /**
     * Display a listing of attendants with optional filtering.
     *
     * This method retrieves a paginated list of attendants, optionally
     * filtering by text, school_id, and type based on the request parameters.
     * The results are ordered by the attendant ID in descending order and
     * include associated school data.
     *
     * @param Request $r The incoming HTTP request containing potential filters.
     * @return \Illuminate\View\View The view displaying the attendant list.
     */

    public function index(Request $r)
    {
        $all_Attendant = Attendant::query();
        if (!empty($r->text)) {
            $all_Attendant = $all_Attendant->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r) {
                    return $query->where('name', 'like', "%$r->text%");
                });
            });
        }

        if (!empty($r->school_id)) {
            $all_Attendant = $all_Attendant->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id,);
                });
            });
        }
        if (!empty($r->type)) {
            $all_Attendant = $all_Attendant->where(function ($q) use ($r) {
                return $q->when($r->type, function ($query) use ($r) {
                    return $query->where('type', $r->type,);
                });
            });
        }


        $headPage = 'المرفقين';
        $all_Attendant = $all_Attendant->with('schools', function ($q) {
            return $q->select('id', 'name');
        })->orderBy('id', 'desc')->paginate(10);
        // dd($all_Attendant);
        return view('dashboard.attendants.index', [
            'all_Attendant' => $all_Attendant,
            'headPage' => $headPage,
            'schools'   => School::get(),

        ]);
    }
    /**
     * Display a form for creating a new attendant.
     *
     * This method retrieves data for the form, including all schools,
     * genders, type bloods, and religions. It renders the view for
     * the create form.
     *
     * @return \Illuminate\View\View The view displaying the create form.
     */
    public function create()
    {
        $school =  School::get();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();


        return view('dashboard.attendants.create', [
            'school'   => $school,
            'genders'   => $genders,
            'typeBlood' => $typeBlood,
            'religion' => $religion,

        ]);
    }
    /**
     * Store a newly created attendant in storage.
     *
     * This method validates the request data according to the rules
     * defined in the $rules array. It then creates a new Attendant
     * instance with the validated data and saves it. If a logo is
     * provided, it is resized and saved to the public_uploads
     * directory. Finally, the method redirects to the index route
     * with a success message.
     *
     * @param Request $r The incoming request containing the data.
     * @return \Illuminate\Http\RedirectResponse The redirect response.
     */
    public function store(Request $r)
    {

        $rules = [
            'name'             => 'required|max:255',
            'username'            => 'required|string|unique:attendants,username',
            'password'         => 'required|string|min:6',
            'created_at'     => 'nullable|date|date_format:Y-m-d',
            'birth_date'       => 'nullable|date|date_format:Y-m-d',
            'gender_id'        =>  'nullable',
            'school_id'        =>  'required',
            'religion_id'      =>  'nullable',
            'type__blood_id'   =>  'nullable',
            'phone'            => 'required|max:255',
            'address'          => 'required|max:255',
            'city_name'        => 'nullable|max:255',
            'type'             =>  'required|in:admins,drivers',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'bus_id'          => ['nullable', 'exists:buses,id', Rule::unique('attendants')->where(function ($query) use ($r) {
                return $query->where('type', $r->type);
            })],
        ];
        $r->validate($rules);

        $Attendant                   = new Attendant();
        $Attendant->name             = $r->name;
        $Attendant->username         = $r->username;
        $Attendant->password         = Hash::make($r->password);
        $Attendant->created_at       = $r->created_at;
        $Attendant->birth_date       = $r->birth_date;
        $Attendant->gender_id        = $r->gender_id;
        $Attendant->school_id        = $r->school_id;
        $Attendant->religion_id      = $r->religion_id;
        $Attendant->type__blood_id   = $r->type__blood_id;
        $Attendant->phone            = $r->phone;
        $Attendant->address          = $r->address;
        $Attendant->city_name        = $r->city_name;
        $Attendant->type             = $r->type;
        if ($r->bus_id != null) {
            $Attendant->bus_id           = $r->bus_id;
        }


        if ($r->logo) {

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/attendants_logo/' . $r->logo->hashName()));

            $Attendant->logo = $r->logo->hashName();
        } //end of if
        $Attendant->save();
        return redirect()->route('dashboard.attendants.index')->with('success', 'تم اضافة بيانات المرافق بنجاح');
    }



    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendant = Attendant::FindOrFail($id);
        return view('dashboard.attendants.show', [
            'attendant'   => $attendant,
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
        $attendant = Attendant::FindOrFail($id);
        $school =  School::get();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();

        $buses = Bus::where('school_id', $attendant->school_id)->get();
        return view('dashboard.attendants.edit', [
            'school'   => $school,
            'genders'   => $genders,
            'typeBlood' => $typeBlood,
            'religion' => $religion,
            'attendant' => $attendant,
            'buses' => $buses,
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
        $Attendant = Attendant::FindOrFail($id);

        $rules = [
            'name'             => 'nullable|max:255',
            'username' => ['required', Rule::unique('attendants')->ignore($Attendant->id),],
            'password'         => 'nullable|string|min:6',
            'created_at'     => 'nullable|date|date_format:Y-m-d',
            'birth_date'       => 'nullable|date|date_format:Y-m-d',
            'gender_id'        =>  'required',
            'religion_id'      =>  'nullable',
            'type__blood_id'   =>  'nullable',
            'phone'            => 'required|max:255',
            'address'          => 'required|max:255',
            'city_name'        => 'nullable|max:255',
            'type'             =>  'required|in:admins,drivers',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'bus_id'          => ['nullable', 'exists:buses,id', Rule::unique('attendants')->where(function ($query) use ($r) {
                return $query->where('type', $r->type);
            })->ignore($Attendant->id)],
        ];

        $r->validate($rules);



        $Attendant->name             = $r->name;
        $Attendant->username            = $r->username;
        $Attendant->created_at     = $r->created_at;
        $Attendant->birth_date       = $r->birth_date;
        $Attendant->gender_id        = $r->gender_id;
        $Attendant->religion_id      = $r->religion_id;
        $Attendant->type__blood_id   = $r->type__blood_id;
        $Attendant->phone            = $r->phone;
        $Attendant->address          = $r->address;
        $Attendant->city_name        = $r->city_name;
        $Attendant->type             = $r->type;
        if ($r->bus_id != null) {
            $Attendant->bus_id             = $r->bus_id;
        }
        if ($r->password != null) {
            $Attendant->password = Hash::make($r->password);
        }

        if ($r->logo) {

            if ($Attendant->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/attendants_logo/' . $Attendant->logo);
            } //end of if

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/attendants_logo/' . $r->logo->hashName()));

            $Attendant->logo  = $r->logo->hashName();
        } //end of if
        $Attendant->save();



        return redirect()->route('dashboard.attendants.index')->with('success', 'تم تحديث بيانات المدرسة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Attendant::FindOrFail($id);

        if ($user->logo != 'default.png') {

            Storage::disk('public_uploads')->delete('/attendants_logo/' . $user->logo);
        } //end of if

        $user->delete();
        return redirect()->back()->with('success', 'تم حذف بيانات المرافق بنجاح');
    }
    /// driver
    public function getDriver($id)
    {


        $Attendant_drivers = Attendant::where('type', 'drivers')
            ->where("school_id", $id)
            ->pluck("name", "id");


        return $Attendant_drivers;
    }

    /**
     * Get all admins for a given school
     *
     * @param int $id the school id
     * @return \Illuminate\Support\Collection
     */
    public function getAdmins($id)
    {

        $Attendant_admins = Attendant::where('type', 'admins')
            ->where("school_id", $id)
            ->pluck("name", "id");

        return $Attendant_admins;
    }
}
