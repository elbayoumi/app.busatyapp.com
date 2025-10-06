<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription as SubscriptionModel;


class SubscriptionController extends Controller
{

    public function index(Request $request)
    {
        try {
            //

            return JSON($request->user()->subscriptions()->get());
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

    public function create(Request $request,$model, $id)
    {
        try {
            // Retrieve the authenticated user
            $user = $request->user();

            // Use the relationship to access the subscription
            $subscriptionModel = $user->subscriptions()->where('id', $id)->first();

            // Check if the subscription exists
            if (!$subscriptionModel) {
                return JSONerror(__('Subscription not found'), 404);
            }

            return sendJSON($subscriptionModel);
        } catch (\Exception $e) {
            // Handle exceptions and return error message
            return JSONerror($e->getMessage(), 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'plan_name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'currency' => 'required|string|in:USD,EUR,GBP,EGP',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|in:cancel,pending,subscribed,not_subscribed,expired,trial,coupon',
                'payment_method' => 'required|string',
                'transaction_id' => 'nullable|string|unique:subscriptions,transaction_id',
            ]);


            if ($validator->fails()) {
                return JSONerror($validator->errors(), 500);
            }
            $subscriptionModel = $user->subscriptions()->create($validator->validated());

            // $subscriptionModel = SubscriptionModel::create($validator->validated() + ['parant_id'=> $request->user()->id]);

            return JSON(__('success create subscription'));
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

    public function show(Request $request,$model, $id)
    {
        try {
            // $subscriptionModel=SubscriptionModel::whereId($id)->where('parant_id',$request->user()->id);
            $subscriptionModel = $request->user()->with('subscription', function ($q) use ($id) {
                $q->where('id', $id);
            });
            return JSON($subscriptionModel);
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }


    public function update(Request $request,$model, $id)
    {
        try {
            try {
                $user = $request->user();

                $validator = Validator::make($request->all(), [
                    'plan_name' => 'required|string|max:255',
                    'amount' => 'required|numeric',
                    'currency' => 'required|string|in:USD,EUR,GBP,EGP',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'status' => 'required|in:cancel,pending,subscribed,not_subscribed,expired,trial,coupon',
                    'payment_method' => 'required|string',
                    'transaction_id' => 'nullable|string|unique:subscriptions,transaction_id',
                ]);


                if ($validator->fails()) {
                    return JSONerror($validator->errors(), 500);
                }
                // Find the subscription using the relationship
                $subscriptionModel = $user->subscriptions()->where('id', $id)->first();

                // Check if the subscription exists
                if (!$subscriptionModel) {
                    return JSONerror(__('Subscription not found'), 404);
                }

                // $subscriptionModel = SubscriptionModel::whereId($id)->where('parant_id', $request->user()->id)->update($validator->validated());
                $subscriptionModel = $subscriptionModel->update($validator->validated());

                return JSON($subscriptionModel);
            } catch (\Exception $e) {
                return JSONerror($e->getMessage(), 500);
            };
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }


    public function destroy(Request $request,$model, $id)
    {
        try {
            // Retrieve the authenticated user
            $user = $request->user();

            // Find the subscription using the relationship
            $subscriptionModel = $user->subscriptions()->where('id', $id)->first();

            // Check if the subscription exists
            if (!$subscriptionModel) {
                return JSONerror(__('Subscription not found'), 404);
            }

            // Delete the subscription
            $subscriptionModel->delete();

            return JSON(__('Subscription deleted successfully'));
        } catch (\Exception $e) {
            // Handle exceptions and return error message
            return JSONerror($e->getMessage(), 500);
        }
    }

    public function trashed(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = $request->user();

            // Retrieve the trashed subscriptions using the relationship
            $subscriptionModel = $user->subscriptions()
                ->onlyTrashed() // Include only trashed subscriptions
                ->paginate(10); // Adjust the pagination limit as needed

            return JSON($subscriptionModel);
        } catch (\Exception $e) {
            // Handle exceptions and return error message
            return JSONerror($e->getMessage(), 500);
        }
    }

}
