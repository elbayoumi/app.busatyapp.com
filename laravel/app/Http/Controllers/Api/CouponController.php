<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        try {
            //

            return JSON($request->user()->coupons()->get());
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }
    public function show(Request $request,$model, $id)
    {
        try {
            //
            $coupon = Coupon::whereId($id);

            return sendJSON($coupon);
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

    public function check(Request $request, $model, $code)
    {
        try {
            $user = $request->user();
            $coupon = Coupon::where('code', $code)->where('model', $model)->first();
            $currentDate = Carbon::now();

            // Check if coupon exists
            if (!$coupon) {
                return JSONerror(__('Coupon code not found'), 422);
            }

            // Check coupon validity period
            if (daysDifference($coupon->allow_at, $currentDate) < 0) {
                return JSONerror(sprintf(__('The coupon code must be allowed at {date}'), $coupon->allow_at));
            }

            if ($coupon->user_limit <= 0) {
                return JSONerror(__('The coupon limit has been reached'));
            }
            if ($coupon->expires_at && $currentDate->toDateString() > Carbon::parse($coupon->expires_at)->toDateString()) {
                return JSONerror(sprintf(__('The coupon code expired at %s'), $coupon->expires_at));
            }


            // Check if user has active subscriptions
            $subscriptions = $user->subscriptions()
                ->whereDate('end_date', '>', $currentDate)
                ->get();

            if ($subscriptions->isNotEmpty()) {
                return JSONerror(__('You are already subscribed'));
            }

            // Check if user already used this coupon
            if ($user->myCoupons()->where('coupon_id', $coupon->id)->exists()) {
                return JSONerror(__('This coupon code is already used'));
            }

            // Check if coupon is restricted to a school
            if ($coupon->school_id && !$user->students()->where('school_id', $coupon->school_id)->exists()) {
                return JSONerror(__('This coupon code is not valid for your school'));
            }

            // Register coupon usage
            $user->myCoupons()->create([
                'coupon_id' => $coupon->id,
            ]);

            // Create subscription
            $futureDate = Carbon::now()->addDays($coupon->usage_limit);
            $newSubscription = $user->subscriptions()->create([
                'plan_name' => 'coupon',
                'amount' => 0,
                'start_date' => $currentDate,
                'end_date' => $futureDate,
                'payment_method' => 'coupon',
                'status' => 'coupon',
                'subscriptable_id' => $user->id,
                'subscriptable_type' => get_class($user),
            ]);

            // Decrement coupon usage limit
            $coupon->decrement('user_limit');

            return JSON($newSubscription);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function status(Request $request)
    {
        try {
            $status = $request->user()->with('subscription', function ($q) {
                return $q->where('end_date', '>=', Carbon::now());
            })->exists();
            return JSON($status);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
