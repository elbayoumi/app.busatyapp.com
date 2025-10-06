<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;
use Illuminate\Http\Request;
use App\Models\App;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apps = App::all();
        // $appsName = getAppsName();
        // dd($appsName);
        return view('dashboard.apps.index',compact('apps'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve the names of existing apps from the database
        $existingApps = App::pluck('name')->toArray();

        // Get all application names from the routes/api folder
        $allApps = getAppsName();

        // Filter out apps that are already stored in the database
        $newApps = array_diff($allApps, $existingApps);

        // Return the view with only new apps that are not stored in the database
        return view('dashboard.apps.create', compact('newApps'));
    }
    public function edit(App $app)
    {

        return view('dashboard.apps.edit',compact('app'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppRequest $request)
    {
        // Create and save the new app
        $app = App::create($request->validated());

        return redirect()->route('dashboard.app.index')->with('success', 'تم حفظ بيانات التطبيق بنجاح');
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $app = App::findOrFail($id);
        return view('dashboard.apps.index',compact('app'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateAppRequest $request, App $app)
    {
        $validated = $request->validated();

        // If the app is under updating, force the status to active
        if (isset($validated['is_updating']) && $validated['is_updating']) {
            $validated['status'] = 1;
        }

        $app->update($validated);

        return redirect()->route('dashboard.app.index')
            ->with('success', 'The app details have been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(App $app)
    {
        $app->delete();
        return redirect()->route('dashboard.app.index')->with('success', 'تم حذف بيانات التطبيق بنجاح');
    }
}
