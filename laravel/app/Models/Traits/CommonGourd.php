<?php

namespace App\Models\Traits;

use App\Models\CouponUse;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Topic;
use App\Models\UserNotification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

trait CommonGourd
{
    use CommonTrait, Notify, HasApiTokens, Notifiable;
    public function setNotifications()
    {

        return $this->morphedByMany(self::class, 'userable', 'user_notifications', 'userable_id', 'notification_id');
    }
    // public function getNotifications()
    // {
    //     return $this->hasManyThrough(
    //         Notification::class,
    //         UserNotification::class,
    //         'userable_id', // Foreign key on the UserNotification table...
    //         'id',          // Foreign key on the Notification table...
    //         'id',          // Local key on the My_Parent table...
    //         'notification_id' // Local key on the UserNotification table...
    //     )->where('userable_type', self::class);
    // }
    public function userNotifications()
    {
        return $this->morphMany(UserNotification::class, 'userable');
    }
    // public function notifications()
    // {
    //     return $this->morphMany(UserNotification::class, 'userable');
    // }
    public function getNotifications()
    {
        return $this->userNotifications()
            ->with(['notification' => function ($query) {
                // ترتيب الإشعارات حسب created_at تنازلياً
                $query->orderBy('created_at', 'desc');
            }])
            ->get()
            ->map(function ($userNotification) {
                $notificationData = $userNotification->notification->data ?? [];

                $additional = $notificationData['additional'] ?? ['trip_id' => null];
                if (!is_array($additional) || count($additional) < 1) {
                    $additional = ['trip_id' => null];
                }

                return [
                    'id' => (int) ($userNotification->notification->id ?? 0),
                    'body' => (string) ($notificationData['body'] ?? __("data not found")),
                    'from' => (string) ($notificationData['from'] ?? __("data not found")),
                    'type' => (string) ($notificationData['type'] ?? __("data not found")),
                    'title' => (string) ($notificationData['title'] ?? __("data not found")),
                    'additional' => (object) $additional,
                    'read_at' => (bool) $userNotification->read_at,
                    'created_at' => Carbon::parse($userNotification->notification->created_at ?? now()),
                    'updated_at' => $userNotification->notification->updated_at
                        ? Carbon::parse($userNotification->notification->updated_at)
                        : null,
                ];
            })
            ->sortByDesc('created_at') // تأكد من الترتيب النهائي تنازلياً
            ->values(); // إعادة ترتيب الفهارس
    }

    // public function getNotifications()
    // {
    //     return $this->userNotifications()
    //         ->with(['notification' => function ($query) {
    //             $query->orderBy('created_at', 'desc');
    //         }])
    //         ->get()
    //         ->map(function ($userNotification) {
    //             $notificationData = $userNotification->notification->data ?? [];

    //             $additional = $notificationData['additional'] ?? ['trip_id' => null];
    //             if (!is_array($additional) || count($additional) < 1) {
    //                 $additional = ['trip_id' => null];
    //             }

    //             return [
    //                 'id' => (int) ($userNotification->notification->id ?? 0),
    //                 'body' => (string) ($notificationData['body'] ?? __("data not found")),
    //                 'from' => (string) ($notificationData['from'] ?? __("data not found")),
    //                 'type' => (string) ($notificationData['type'] ?? __("data not found")),
    //                 'title' => (string) ($notificationData['title'] ?? __("data not found")),
    //                 'additional' => (object) $additional,
    //                 'read_at' => (bool) $userNotification->read_at,
    //                 'created_at' => Carbon::parse($userNotification->notification->created_at ?? now()),
    //                 'updated_at' => $userNotification->notification->updated_at
    //                     ? Carbon::parse($userNotification->notification->updated_at)
    //                     : null,
    //             ];
    //         });
    // }


    // public function getNotifications()
    // {
    //     return $this->hasManyThrough(
    //         Notification::class,
    //         UserNotification::class,
    //         'userable_id', // Foreign key on the UserNotification table...
    //         'id',          // Foreign key on the Notification table...
    //         'id',          // Local key on the My_Parent table...
    //         'notification_id' // Local key on the UserNotification table...
    //     )
    //         ->where('userable_type', self::class)
    //         ->get()
    //         ->map(function ($notification) {
    //             return [
    //                 'id' => $notification->id,
    //                 'body' => $notification->data['body'],
    //                 'from' => $notification->data['from'],
    //                 'type' => $notification->data['type'],
    //                 'title' => $notification->data['title'],
    //                 // 'userNotifications' => $notification->userNotifications(),
    //                 'created_at' => $notification->created_at,
    //                 'updated_at' => $notification->updated_at,
    //                 'userable_type' => $notification->userable_type,
    //                 'userable_id' => $notification->userable_id,
    //                 'notification_id' => $notification->notification_id,
    //                 'read_at' => $notification->read_at,
    //             ];
    //         });
    // }


    // public function getPaginateNotifications()
    // {
    //     return $this->getNotifications();
    // }
    public function getPaginateNotifications()
    {
        // Retrieve notifications
        $notifications = $this->getNotifications();

        // Set up pagination
        $perPage = (int) (request()->input('limit', 10)); // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Current page number

        // Extract current items for the page
        $currentItems = array_slice($notifications->toArray(), ($currentPage - 1) * $perPage, $perPage);

        // Create a LengthAwarePaginator instance
        return new LengthAwarePaginator($currentItems, $notifications->count(), $perPage, $currentPage);
    }
    // public function markAsRead($notificationId)
    // {
    //     $this->notifications()->updateExistingPivot($notificationId, ['read_at' => now()]);
    // }
    public function topics()
    {
        // return $this->morphedByMany(Topic::class, 'userable', 'topic_users');
        return $this->morphToMany(Topic::class, 'userable', 'topic_users', 'userable_id', 'topic_id');
    }
    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscriptable');
    }
    public function subscription()
    {
        return $this->morphMany(Subscription::class, 'subscriptable');
    }
    public function myCoupons()
    {
        return $this->morphMany(CouponUse::class, 'used_by');
    }
    public function socialAccounts()
    {
        return $this->morphMany(\App\Models\SocialAccount::class, 'userable');
    }
}
