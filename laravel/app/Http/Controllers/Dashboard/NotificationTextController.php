<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\NotificationText;
use App\Models\Topic;
use App\Services\Firebase\FcmService;
use Illuminate\Http\Request;

class NotificationTextController extends Controller
{
    // Display a listing of the notifications
    public function index()
    {

        $notifications = NotificationText::paginateLimit();
        // dd($notifications[0]->title);
        return view('dashboard.notifications.index', compact('notifications'));
    }
    public function send()
    {

        $fcmService=new FcmService();
        $topics=Topic::pluck('name')->toArray();
        foreach ($topics as $topic){
            $fcmService->sendNotificationToTopic($topic);
        }

        return redirect()->route('dashboard.notifications.index')->with('success', 'Notification sended successfully.');
    }
    // Show the form for creating a new notification
    public function create()
    {
        $models = NotificationText::getAllModels();
        // dd($models);
        return view('dashboard.notifications.create', compact('models'));
    }

    // Store a newly created notification in storage
    public function store(Request $request)
    {
        // dd($request->for_model_additional);
        $request->validate([
            'title' => 'required|array',
            'title.*' => 'required|string',
            // 'title.en' => 'required|string',
            'body' => 'required|array',
            'body.ar' => 'required|string',
            'body.en' => 'required|string',
            'default_body' => 'nullable|array',
            'default_body.ar' => 'required|string',
            'default_body.en' => 'required|string',
            'for_model_type' => 'required|string',
            'to_model_type' => 'required|string',
            'group' => 'boolean',
            'for_model_additional' => 'nullable|array',
            'for_model_additional.*' => 'nullable|string',
            'to_model_additional' => 'nullable|array',
            'to_model_additional.*' => 'nullable|string',
        ]);

        NotificationText::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'default_body' => $request->input('default_body')??$request->input('body'),
            'for_model_type' => $request->input('for_model_type'),
            'to_model_type' => $request->input('to_model_type'),
            'group' => $request->input('group', false),
            'model_additional' => [ // Ensure this is a JSON string
                'for_model_additional' => $request->input('for_model_additional', []),
                'to_model_additional' => $request->input('to_model_additional', []),
            ],
        ]);

        return redirect()->route('dashboard.notifications.index')->with('success', 'Notification created successfully.');
    }

    // Show the form for editing the specified notification
    public function edit(NotificationText $notification)
    {
        $models = NotificationText::getAllModels();
        $model_additional = $notification->model_additional;
        // dd($model_additional['to_model_additional']);
        return view('dashboard.notifications.edit', compact('notification','models','model_additional'));
    }

    // Update the specified notification in storage
    public function update(Request $request, NotificationText $notification)
    {
        $request->validate([
            'title' => 'required|array',
            'title.ar' => 'required|string',
            'title.en' => 'required|string',
            'body' => 'required|array',
            'body.ar' => 'required|string',
            'body.en' => 'required|string',
            'default_body' => 'nullable|array',
            'default_body.ar' => 'required|string',
            'default_body.en' => 'required|string',
            'for_model_type' => 'required|string',
            'to_model_type' => 'required|string',
            'group' => 'boolean',
        ]);
        $modelAdditional = $notification->model_additional;

        // تحقق مما إذا كانت البيانات غير فارغة
        if ($request->filled('for_model_additional') || $request->filled('to_model_additional')) {
            $modelAdditional = [
                'for_model_additional' => $request->input('for_model_additional', []),
                'to_model_additional' => $request->input('to_model_additional', []),
            ];
        }
        $notification->update([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'default_body' => $request->input('default_body')??$request->input('body'),
            'for_model_type' => $request->input('for_model_type'),
            'to_model_type' => $request->input('to_model_type'),
            'group' => $request->input('group', false),
            'model_additional' => $modelAdditional, // إدخال البيانات فقط إذا كانت غير فارغة
        ]);

        return redirect()->route('dashboard.notifications.index')->with('success', 'Notification updated successfully.');
    }

    // Remove the specified notification from storage
    public function destroy(NotificationText $notification)
    {
        $notification->delete();
        return redirect()->route('dashboard.notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
