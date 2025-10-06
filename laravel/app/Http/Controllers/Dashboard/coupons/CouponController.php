<?php

namespace App\Http\Controllers\Dashboard\coupons;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index(Request $r)
    {
        $coupon = Coupon::query();

        if (!empty($r->code)) {
            $coupon = $coupon->where(function ($q) use ($r) {
                $q->when($r->code, function ($query) use ($r) {
                    $query->where('code', $r->code);
                });
            });
        }
        if (!empty($r->model)) {
            $coupon = $coupon->where(function ($q) use ($r) {
                $q->when($r->model, function ($query) use ($r) {
                    $query->where('model', $r->model);
                });
            });
        }
        if (!empty($r->school_id)) {
            $coupon = $coupon->where(function ($q) use ($r) {
                $q->when($r->school_id, function ($query) use ($r) {
                    $query->where('school_id', $r->school_id);
                });
            });
        }
        $headPage = 'الكوبونات';

        $coupon = $coupon->orderBy('id', 'desc')->paginate(10);
        // dd($coupon);
        return view('dashboard.coupons.index', [
            'coupon' => $coupon,
            'headPage' => $headPage,
        ]);
    }


    public function create(Request $request)
    {
        try {

            $headPage = 'انشاء كوبون';
            // dd($request->model);
            return view('dashboard.coupons.create', [
                // 'parent' => My_Parent::get(),
                'headPage' => $headPage,
                'model' => $request->model,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }


    public function store(Request $request)
    {
        try {
            $user = auth()->guard('web')->user();
            // Validate the input data
            $validator = Validator::make($request->all(), [
                'code' => 'nullable|string|max:255|unique:coupons,code', // Code must be unique, a string, and no more than 255 characters
                'usage_limit' => 'nullable|integer|min:1', // Usage limit must be an integer and at least 1
                'user_limit' => 'nullable|integer|min:1', // Usage limit must be an integer and at least 1
                'allow_at' => 'nullable|date|after_or_equal:today', // Allow date must be today or in the future
                'expires_at' => 'nullable|date|after:allow_at', // Expiry date must be after the allow date
                'school_id' => 'nullable|exists:schools,id', // Expiry date must be after the allow date

            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Prepare data for creating a new coupon
            $data = $validator->validated();
            $data['code'] = $data['code'] ?? radCode(); // Generate a random code if none is provided
            $data['model'] = $request->model;

            // Create the coupon with the validated data
            // $couponModel = Coupon::create(['staff_id' => auth()->guard('web')->user()->id] + $data);
            $user->coupons()->create($data);
            // Redirect back to the coupon index with a success message
            return redirect()->route('dashboard.coupon.index',['model' => request()->model,'school_id' => request()->school_id])->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions and redirect back with an error message
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
    public function users($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Load uses with usedBy relation, paginated
        $uses = $coupon->uses()
                       ->with('usedBy')
                       ->latest()
                       ->paginate(10); // Change to 15, 25, etc. if needed

        return view('dashboard.coupons.users', compact('coupon', 'uses'));
    }


    public function show(Coupon $coupon)
    {
        //
    }


    public function edit(Coupon $coupon)
    {
        try {
            $headPage = 'تعديل كوبون';

            return view('dashboard.coupons.edit', [
                'coupon' => $coupon,
                'headPage' => $headPage,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }



    public function update(Request $request, Coupon $coupon)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'nullable|string|max:255|unique:coupons,code,' . $coupon->id,
                'usage_limit' => 'nullable|integer|min:1',
                'user_limit' => 'nullable|integer|min:1',
                'allow_at' => 'nullable|date|after_or_equal:today',
                'expires_at' => 'nullable|date|after:allow_at',
                'school_id' => 'nullable|exists:schools,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();
            $data['code'] = $data['code'] ?? $coupon->code;
            $data['model'] = $request->model ?? $coupon->model;

            $coupon->update($data);

            return redirect()->route('dashboard.coupon.index', [
                'model' => $coupon->model,
                'school_id' => $coupon->school_id,
            ])->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }


    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('dashboard.coupon.index')->with('success', 'تم حذف بيانات الكوبون بنجاح');
    }
}
