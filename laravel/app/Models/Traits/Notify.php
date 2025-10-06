<?php

namespace App\Models\Traits;

use App\Models\Notification;
use App\Models\UserNotification;

trait Notify
{
    // Retrieve all notifications with pagination
    public function getPaginateNotifications($limit = 10)
    {
        return $this->morphedByMany(Notification::class, 'userable', 'user_notifications', 'userable_id', 'notification_id')
                    ->paginate($limit);
    }

    // Mark a notification as read
    public function markAsRead($notificationId)
    {
        $notification = UserNotification::where('notification_id', $notificationId)
            ->where('userable_id', $this->id)
            ->where('userable_type', get_class($this))
            ->first();

        if ($notification) {
            $notification->read_at = true;
            $notification->save();
            return $notification;
        }

        return false;
    }

    // Delete a notification
    public function deleteNotification($notificationId)
    {
        return UserNotification::where('notification_id', $notificationId)
            ->where('userable_id', $this->id)
            ->where('userable_type', get_class($this))
            ->delete();
    }
}
