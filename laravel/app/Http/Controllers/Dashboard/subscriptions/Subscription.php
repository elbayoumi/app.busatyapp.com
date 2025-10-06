<?php

namespace App\Http\Controllers\Dashboard\subscriptions;

use App\Http\Controllers\Controller;
use App\Models\My_Parent;
use Illuminate\Http\Request;
use App\Models\Subscription as SubscriptionModel;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Enums\SubscriptionStatus;
use Illuminate\Validation\Rules\Enum;

class Subscription extends Controller
{

    public function index(Request $r)
    {
        $subscription = My_Parent::whereHas('subscription');

        if (!empty($r->parant_id)) {
            $subscription = $subscription->where(function ($q) use ($r) {
                $q->when($r->parant_id, function ($query) use ($r) {
                    $query->where('id', $r->parant_id);
                });
            });
        }
        if (!empty($r->email)) {
            $subscription = $subscription->where(function ($q) use ($r) {
                $q->when($r->email, function ($query) use ($r) {
                    $query->where('email', 'like', "%$r->email%");
                });
            });
        }


        $headPage = 'الاشتراكات';

        // dd($subscription->with(['subscription'])->orderBy('id', 'desc')->get());
        // dd($subscription->with('subscription')->paginate(10));
        $subscription = $subscription->with('subscription')->paginate(10);

        return view('dashboard.subscription.index', [
            'subscription' => $subscription,
            'allParent' => My_Parent::whereHas('subscription')->orderBy('id', 'desc')->get(),

            'headPage' => $headPage,
            'schools'   => School::get(),
        ]);
    }



    public function create(Request $request)
    {
        try {

            $headPage = 'انشاء الاشتراك';
            return view('dashboard.subscription.create', [
                'parent' => My_Parent::get(),
                'headPage' => $headPage,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }





    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                'parent_id'   => 'required|array', // Must be an array of parent IDs
                'parent_id.*' => 'exists:my__parents,id', // Each ID must exist in the parents table
                'amount'      => 'required|numeric',
                'currency'    => 'required|string|in:USD,EUR,GBP,EGP',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date',
                'status'      => ['required', new Enum(SubscriptionStatus::class)], // Must be a valid enum value
            ]);

            // If validation fails, redirect back with errors
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Validated data
            $validated = $validator->validated();

            // Loop through each selected parent and create a subscription
            foreach ($validated['parent_id'] as $parentId) {
                SubscriptionModel::create([
                    'plan_name'          => 'Dashboard', // Static plan name (can be dynamic if needed)
                    'amount'             => $validated['amount'],
                    'start_date'         => $validated['start_date'],
                    'end_date'           => $validated['end_date'],
                    'payment_method'     => 'dashboard',
                    'status'             => SubscriptionStatus::from($validated['status'])->value, // Ensure it's a valid enum value
                    'subscriptable_id'   => $parentId,
                    'subscriptable_type' => My_Parent::class, // Set morph type to the parent model
                ]);
            }

            // Redirect to index with success message
            return redirect()->route('dashboard.subscription.index')
                             ->with('success', 'تمت إضافة الاشتراكات بنجاح لـ ' . count($validated['parent_id']) . ' مستخدم(ين)');

        } catch (\Exception $e) {
            // Catch and handle any unexpected errors
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function show($id)
    {
        try {
            $subscription = SubscriptionModel::whereId($id);

            $headPage = 'عرض الاشتراك';

            // dd($subscription->with(['subscription'])->orderBy('id', 'desc')->get());

            $subscription = $subscription->with('parents.students')->first();
            // dd($subscription);
            return view('dashboard.subscription.show', [
                'subscription' => $subscription,
                'headPage' => $headPage,
                'schools'   => School::get(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }


    public function edit($id)
    {
        try {
            $headPage = 'تعديل الاشتراك';

            // Get subscription with related parent (or any subscriptable) and their students
            $subscription = SubscriptionModel::with('subscriptable.students')->findOrFail($id);

            return view('dashboard.subscription.edit', [
                'subscription' => $subscription,
                'headPage' => $headPage,
                'schools' => School::all(),
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }



    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'plan_name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                // 'currency' => 'required|string|in:USD,EUR,GBP',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|in:cancel,pending,subscribed,not_subscribed,expired,trial,coupon',
                'payment_method' => 'required|string',
            ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $subscriptionModel = SubscriptionModel::whereId($id)->update($validator->validated());

            return redirect()->route('dashboard.subscription.index')->with('success', 'تم اضافة البيانات بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }

    public function destroy($id)
    {
        $subscriptionModel = SubscriptionModel::whereId($id);
        $subscriptionModel->delete();
        return redirect()->route('dashboard.subscription.index')->with('success', 'تم حذف بيانات الاشتراك بنجاح');
    }
}
