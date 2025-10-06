<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowNotificationResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use App\Models\Notification; // Import the Notification model
use App\Models\UserNotification; // Import the UserNotification model
use Carbon\Carbon;

class NotificationController extends Controller
{
    // Retrieve all notifications with pagination
    public function index(Request $request)
    {
        $user = $request->user();
        // Retrieve paginated notifications
        $paginatedNotifications = $user->getPaginateNotifications();
        // return response()->json($paginatedNotifications);
        // Return the response as JSON
        return new NotificationResource($paginatedNotifications);
    }
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        // Update all unread notifications
        $user->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return JSON('All notifications marked as read successfully.');
    }

    // Display a specific notification
    public function show(Request $request, $id)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // Retrieve the specific notification for the user
        $userNotification = $user->userNotifications()
            ->with('notification') // Load the notification relation
            ->where('notification_id', $id) // Find the notification by its ID
            ->first();

        // Check if the notification exists
        if (!$userNotification) {
            return response()->json(['message' => __('Notification not found.')], 404);
        }

        // Mark the notification as read
        $userNotification->read_at = true;
        $userNotification->save();

        // Return the notification data with the associated notification details
        return new ShowNotificationResource($userNotification);
    }



    // Update the notification status to "read"
    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Check if the notification exists
        $notification = UserNotification::where('notification_id', $id)
            ->where('userable_id', $user->id)
            ->where('userable_type', get_class($user))
            ->first();

        if (!$notification) {
            return JSON(['message' => 'Notification not found.'], 404);
        }

        // Update the notification status
        $notification->read_at = now(); // Mark the notification as read
        $notification->save();

        return JSON(['message' => 'Notification marked as read.']);
    }

    // Delete a specific notification
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        // Check if the notification exists
        $notification = UserNotification::where('notification_id', $id)
            ->where('userable_id', $user->id)
            ->where('userable_type', get_class($user))
            ->first();

        if (!$notification) {
            return JSON(['message' => 'Notification not found.'], 404);
        }

        // Delete the notification
        $notification->delete();

        return JSON(['message' => 'Notification deleted successfully.']);
    }

    // You can add more methods as needed
}
