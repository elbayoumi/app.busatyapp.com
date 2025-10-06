<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\My_Parent;
use Illuminate\Http\Request;

use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Dashboard\StoreParentRequest;
use App\Http\Requests\Dashboard\UpdateParentRequest;

class ParentConteoller extends Controller
{

    public function index(Request $r)
    {


        $all_Parent = My_Parent::query();

        if (!empty($r->text)) {
            $all_Parent = $all_Parent->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r,) {
                    return $query->where('name', 'like', "%$r->text%")->orWhere('email', 'like', "%{$r->text}%")
                        ->orWhere('phone', 'like', "%{$r->text}%");
                });
            });
        }

        if (!empty($r->student_id)) {
            $all_Parent = $all_Parent->where(function ($q) use ($r) {
                return $q->when($r->student_id, function ($query) use ($r) {
                    $ids = DB::table('my__parent_student')->where('student_id', $r->student_id)->pluck('my__parent_id');
                    return $query->whereIn('id', $ids);
                });
            });
        }


        $all_Parent = $all_Parent->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.parents.index', ['all_Parent' => $all_Parent]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school =  School::get();


        return view('dashboard.parents.create', compact('school'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreParentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParentRequest $r)
    {
        // Create a new parent record
        $parent = My_Parent::create($r->validated());

        // Handle the logo upload if provided
        if ($r->hasFile('logo')) {

            // Delete the existing logo if it's not the default one
            if ($parent->logo && $parent->logo != 'default.png') {
                Storage::disk('public_uploads')->delete('/parents_logo/' . $parent->logo);
            }

            // Process the new logo
            $logo = $r->file('logo');
            $logoName = $logo->hashName();

            Image::make($logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/parents_logo/' . $logoName));

            // Update the parent's logo field
            $parent->logo = $logoName;
            $parent->save();
        }

        // Redirect with success message
        return redirect()->route('dashboard.parents.index')->with('success', 'تم إضافت بيانات ولي الأمر بنجاح');
    }



    public function show($id)
    {
        $Parent = My_Parent::FindOrFail($id);
        return view('dashboard.parents.show', [
            'Parent'   => $Parent,


        ]);
    }

    public function edit($id)
    {
        $all_Parent = My_Parent::FindOrFail($id);
        $school    =  School::get();

        return view('dashboard.parents.edit', [
            'school'   => $school,
            'all_Parent' => $all_Parent,

        ]);
    }


    public function update(UpdateParentRequest $r, $id)
    {

    // Fetch the parent instance
    $parent = My_Parent::findOrFail($id);  // Retrieve the model instance

    // Update the validated data
    // $parent->update($r->validated());
            if ($r->password != null) {
            $parent->password = $r->password;
        }
        if ($r->email != null) {
            $parent->email = $r->email;
        }
        if ($r->email_verified_at != null) {
            $parent->email_verified_at = $r->email_verified_at;
        }
        if ($r->status != null) {
            $parent->status = $r->status;
        }
        if ($r->logo) {

            if ($parent->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/parents_logo/' . $parent->logo);
            } //end of if

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/parents_logo/' . $r->logo->hashName()));

            $parent->logo  = $r->logo->hashName();
        } //end of if
        $parent->save();


        return redirect()->route('dashboard.parents.index')->with('success', 'تم تعديل البيانات بنجاح');
    }


    public function destroy($id)
    {

        $Parent = My_Parent::FindOrFail($id);


        if ($Parent->logo != 'default.png') {

            Storage::disk('public_uploads')->delete('/parents_logo/' . $Parent->logo);
        } //end of if
        $Parent->delete();
        return redirect()->back()->with('success', 'تم حذف بيانات ولي الامر بنجاح');
    }
    public function addChild($id)
    {

        $Parent = My_Parent::FindOrFail($id);
        $Student = Student::where('school_id', $Parent->school_id)->get();
        return view('dashboard.parents.childs.add-child', [
            'Parent'   => $Parent,
            'Student'   => $Student,

        ]);
    }

    public function storeChild(Request $r, $id)
    {

        $r->validate([
            // 'student_id'             => 'required|max:255',
            'parent_key'            => 'required|max:255',
            'parent_secret'            => 'required|max:255',
        ]);

        $Student_ids = Student::where('parent_key', $r->parent_key)->where('parent_secret', $r->parent_secret)->first();


        if ($Student_ids != null) {

            $Parent = My_Parent::FindOrFail($id);
            $Parent->students()->syncWithoutDetaching($Student_ids->id);
            return redirect()->route('dashboard.parents.index')->with('success', 'تم   الاضافة بنجاح');
        } else {
            return redirect()->back()->with('error', 'البيانات غير صحيحة');
        }
    }


    public function child($id)
    {

        $Parent = My_Parent::FindOrFail($id);
        $childs =  $Parent->students()->get();


        return view('dashboard.parents.childs.index', [
            'Parent' => $Parent,
            'childs' => $childs,
        ]);
    }


    public function childShow($id)
    {

        $Student = Student::FindOrFail($id);
        $School = School::FindOrFail($Student->school_id);

        return view('dashboard.parents.childs.show', [
            'Student' => $Student,
            'School' => $School,


        ]);
    }
}
