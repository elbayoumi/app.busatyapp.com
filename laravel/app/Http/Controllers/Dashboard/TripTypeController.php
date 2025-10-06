<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TripTypeRequest;
use App\Models\TripType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        // dd($r->text);
        $text = isset($r->text) && $r->text != '' ? $r->text : null;
        //to check if email valid and search

        $all_tripType = TripType::query();
        if ($text != null) {
            $all_tripType = $all_tripType->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r,) {
                    return $query->where('name', 'like', "%$r->text%")->orWhere('description', 'like', "%{$r->text}%")
                        ->orWhere('id', 'like', "%{$r->text}%");
                });
            });
        }



        $all_tripType = $all_tripType->where('tripable_type','App\Models\Staff')->orderBy('id', 'desc')->paginate(10);

        return view('dashboard.tripType.index', compact('all_tripType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {

            $headPage = 'انشاء نوع رحلة افتراضي';
            return view('dashboard.tripType.create', compact('headPage'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TripTypeRequest $tripTypeRequest)
    {
        $user = auth()->user();
        $user->createdTripTypes()->create($tripTypeRequest->validated());

        return redirect()->route('dashboard.trip-type.index')->with('success', 'تم اضافة البيانات بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TripType  $tripType
     * @return \Illuminate\Http\Response
     */
    public function show(TripType $tripType)
    {
        // Pass the TripType model to the view
        return view('dashboard.tripType.show', compact('tripType'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TripType  $tripType
     * @return \Illuminate\Http\Response
     */
    public function edit(TripType $tripType)
    {
        // Prepare the page title or any additional data
        $headPage = 'تعديل السؤال';

        // Pass the TripType model and title to the view
        return view('dashboard.tripType.edit', compact('tripType', 'headPage'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TripType  $tripType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TripType $tripType)
    {
        // Validate the request data

        // Update the TripType with the validated data
        $tripType->update($request->validated());

        // Redirect back with a success message
        return redirect()->route('dashboard.trip-type.index')->with('success', 'تم تحديث السؤال بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TripType  $tripType
     * @return \Illuminate\Http\Response
     */
    public function destroy(TripType $tripType)
    {
        // Delete the TripType
        $tripType->delete();

        // Redirect back with a success message
        return redirect()->route('dashboard.trip-type.index')->with('success', 'تم حذف السؤال بنجاح');
    }
}
