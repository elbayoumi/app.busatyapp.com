<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{
    School,
    AdesSchool,
    Ades
};
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class AdesController extends Controller
{
    /**
     * Display a listing of the Ades resource.
     *
     * Filters the Ades based on the provided request parameters such as text, id, date, and email.
     * Supports searching by title, body, and alt fields with text, filtering by id, creation date, and email.
     * Paginates the results and includes related AdesSchool and School data.
     *
     * @param Request $r The request object containing potential filter parameters.
     * @return \Illuminate\View\View The view displaying the filtered list of Ades.
     */

    public function index(Request $r)
    {
        $ades = Ades::query();
        if (!empty($r->text)) {
            $ades = $ades->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r,) {
                    return $query->where('title', 'like', "%$r->text%")->orWhere('body', 'like', "%{$r->text}%")
                        ->orWhere('alt', 'like', "%{$r->text}%");
                });
            });
        }
        if (!empty($r->id)) {
            $ades = $ades->where(function ($q) use ($r) {
                $q->when($r->id, function ($query) use ($r) {
                    $query->where('id', $r->id);
                });
            });
        }
        if (!empty($r->date)) {
            $ades = $ades->where(function ($q) use ($r) {
                $q->when($r->date, function ($query) use ($r) {
                    $query->where('created_at', $r->date);
                });
            });
        }
        if (!empty($r->email)) {
            $ades = $ades->where(function ($q) use ($r) {
                $q->when($r->email, function ($query) use ($r) {
                    $query->where('email', 'like', "%$r->email%");
                });
            });
        }


        $headPage = 'الاعلانات';

        $ades = $ades->with('adesSchool.schools')->orderBy('id', 'desc')->paginate(10);
        // dd($ades );

        return view('dashboard.adesSchool.index', [
            'ades' => $ades,
            'allSchool' => School::orderBy('id', 'desc')->get(),

            'headPage' => $headPage,
        ]);
    }
    /**
     * Display a form to create a new Ades resource.
     *
     * Creates a page displaying a form to create a new Ades resource.
     * The form includes fields for the title, body, and image.
     * The page also includes a link to return to the Ades index page.
     *
     * @return \Illuminate\View\View The view displaying the form to create a new Ades.
     */
    public function create()
    {
        try {

            $headPage = 'انشاء اعلان';
            return view('dashboard.adesSchool.create', [
                'headPage' => $headPage,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
        /**
         * Store a newly created Ades resource in storage.
         *
         * Validates a form with fields for the title, body, and image.
         * If the form is invalid, redirects the user back with errors.
         * If the form is valid, creates a new Ades resource and redirects the user to the Ades index page.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|max:1000|min:1',
                'body' => 'nullable|max:1000|min:1',
                'link' => 'nullable|url|max:1000|min:1',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'alt' => 'nullable|max:1000|min:1',
            ], [
                'title.max' => 'The title may not be greater than 1000 characters.',
                'title.min' => 'The title must be at least 1 character.',
                'body.max' => 'The body may not be greater than 1000 characters.',
                'body.min' => 'The body must be at least 1 character.',
                'link.url' => 'The link must be a valid URL.',
                'link.max' => 'The link may not be greater than 1000 characters.',
                'link.min' => 'The link must be at least 1 character.',
                'image.image' => 'The uploaded file must be an image.',
                'image.mimes' => 'The image must be of type jpeg, png, or jpg.',
                'image.max' => 'The image may not be greater than 2048 kilobytes.',
                'alt.max' => 'The alt may not be greater than 1000 characters.',
                'alt.min' => 'The alt must be at least 1 character.',
            ]);

            $data = $validator->validated();
            if (!empty($request->image)) {


                Image::make($request->image)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/ades_image/' . $request->image->hashName()));

                $data['image']  = $request->image->hashName();
            } //end of if
            else {
                $data['image']  = null;
            }
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $ades = Ades::create($data);

            return redirect()->route('dashboard.adesSchool.index')->with('success', 'تم اضافة الاعلان بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
    /**
     * Store a newly created adesSchool in storage.
     *
     * @param int $schoolId
     * @param int $adesId
     * @param string $ades_to
     * @return \Illuminate\Http\Response
     */
    public function storeSchooleToAdes($schoolId, $adesId, $ades_to)
    {
        try {

            $adesSchoolCheck = AdesSchool::where('ades_id', $adesId)->where('to', $ades_to)->where('school_id', $schoolId);

            if ($adesSchoolCheck->exists()) {

                return redirect()->back()->withErrors('exists add ades')->withInput();
            }

            $ades = AdesSchool::create(['to' => $ades_to, 'ades_id' => $adesId, 'school_id' => $schoolId]);

            return redirect()->back()->with('success', 'تم اضافة الاعلان بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
    /**
     * Remove the specified adesSchool from storage.
     *
     * Checks if the adesSchool exists for the given ID.
     * If it does not exist, redirects back with an error message.
     * If it exists, deletes the adesSchool and redirects back with a success message.
     *
     * @param int $adesSchoolId The ID of the adesSchool to be removed.
     * @return \Illuminate\Http\Response Redirects back with success or error message.
     */

    public function removeSchooleToAdes($adesSchoolId)
    {
        try {

            $adesSchoolCheck = AdesSchool::whereId($adesSchoolId);
            // dd($adesSchoolCheck->get());
            if (!$adesSchoolCheck->exists()) {
                return redirect()->back()->withErrors('not exists ades to school')->withInput();
            }
            $adesSchoolCheck->delete();

            return redirect()->back()->with('success', 'تم حذف الاعلان بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
    /**
     * Show the specified adesSchool with its schools for the given ID.
     *
     * Checks if the adesSchool exists for the given ID.
     * If it does not exist, redirects back with an error message.
     * If it exists, queries the adesSchool with its schools and redirects to the view
     * with a success message.
     *
     * @param int $id The ID of the adesSchool to be shown.
     * @return \Illuminate\Http\Response Redirects back with success or error message.
     */
    public function showSchools($id)
    {
        try {

            $ades = Ades::with('adesSchool.schools')->find($id);
            $headPage = 'عرض الاعلان';
            $allSchool = School::orderBy('id', 'desc')->get();
            return view('dashboard.adesSchool.showSchools', [
                'ades' => $ades,
                'allSchool' => $allSchool,

                'headPage' => $headPage,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
    /**
     * Display the specified resource.
     *
     * @param int $id The ID of the adesSchool to be shown.
     * @return \Illuminate\Http\Response Show the adesSchool with pagination.
     */
    public function show($id)
    {
        try {
            $adesSchool = AdesSchool::where('ades_id', $id)->with('schools')->orderBy('id', 'desc')->paginate(10);
            $headPage = 'عرض الاعلان';
            $allSchool = School::orderBy('id', 'desc')->get();
            $mainAdes = Ades::find($id);

            return view('dashboard.adesSchool.show', compact('adesSchool', 'allSchool', 'headPage', 'mainAdes'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
/**
 * Show the form for editing the specified Ades resource.
 *
 * @param int $id The ID of the Ades to be edited.
 * @return \Illuminate\View\View The view displaying the form to edit the Ades.
 */

    public function edit($id)
    {
        try {
            $headPage = 'تعديل الاعلان';
            return view('dashboard.adesSchool.edit', [
                "ades" => Ades::find($id),
                'headPage' => $headPage,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }
    public function update(Request $request, $id)
    {
        try {
            $ades = Ades::find($id);
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:1000|min:1',
                'body' => 'nullable|max:1000|min:1',
                'link' => 'nullable|url|max:1000|min:1',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'alt' => 'nullable|max:1000|min:1',
            ], [
                'title.required' => 'The title field is required.',
                'title.max' => 'The title may not be greater than 1000 characters.',
                'title.min' => 'The title must be at least 1 character.',
                'body.max' => 'The body may not be greater than 1000 characters.',
                'body.min' => 'The body must be at least 1 character.',
                'link.url' => 'The link must be a valid URL.',
                'link.max' => 'The link may not be greater than 1000 characters.',
                'link.min' => 'The link must be at least 1 character.',
                'image.image' => 'The uploaded file must be an image.',
                'image.mimes' => 'The image must be of type jpeg, png, or jpg.',
                'image.max' => 'The image may not be greater than 2048 kilobytes.',
                'alt.max' => 'The alt may not be greater than 1000 characters.',
                'alt.min' => 'The alt must be at least 1 character.',
            ]);

            $data = $validator->validated();
            if (!empty($request->image)) {


                Image::make($request->image)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/ades_image/' . $request->image->hashName()));

                $data['image']  = $request->image->hashName();
            } //end of if
            else {
                $data['image']  = $ades->image;
            }
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $ades = $ades->update($data);

            return redirect()->route('dashboard.adesSchool.index')->with('success', 'تم تعديل الاعلان بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }

    public function destroy($id)
    {
        Ades::whereId($id)->delete();
        return redirect()->route('dashboard.adesSchool.index')->with('success', 'تم حذف بيانات الاعلان بنجاح');
    }
}
