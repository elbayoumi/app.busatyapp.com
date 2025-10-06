<?php

namespace App\Http\Controllers\Api\parents\addresses;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Parents\StoreAddressRequest;
use App\Models\{
    Address,
    Student
};
use App\Services\Parent\Address\AddressRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AddressesController extends Controller
{
    private $addressRequestService;
    public function __construct()
    {
        $this->addressRequestService =new AddressRequestService();
    }
    public function index(Request $r)
    {
        try {

            $text = isset($r->text) && $r->text != '' ? $r->text : null;
            $addresses = Address::where('my__parent_id',  $r->user()->id)->with(['schools', 'students', 'parent']);
            if ($text != null) {
                $addresses = $addresses->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->whereHas('students', function ($e) use ($r) {
                            $e->where('name', 'like', "%$r->text%");
                        });
                    });
                });
            }
            $addresses = $addresses->orderBy('id', 'desc')
                ->paginateLimit();
            return response()->json([
                'data' => $addresses,
                'message' => __("success message"),
                'errors' => false,
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function show(Request $r, $id)
    {
        try {
            $addresses = Address::where('id', $id)->where('my__parent_id',  $r->user()->id)->with(['schools', 'students', 'parent'])->first();

            return response()->json([
                'data' => $addresses,
                'message' => __("success message"),
                'errors' => false,
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function create_address(Request $r)
    {
        try {

            $ids = DB::table('my__parent_student')->where('my__parent_id', $r->user()->id)->pluck('student_id');
            $students = Student::whereIn('id', $ids)->with([
                'schools',
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
                'my_Parents',

            ]);
            return response()->json([
                'data' => $students?->get(),
                'message' => __("success message"),
                'errors' => false,
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }







    public function getStore(StoreAddressRequest $r)
    {
        try {
            // Get validated data
            $data = $r->validated();

            $this->addressRequestService->createAddressRequest($data);

            // Return a success response
            return response()->json([
                'data' => [
                    'address' => __('request added'),
                ],
                'errors' => false,
                'message' =>__('Address updated successfully')
            ], 200);
        } catch (\Exception $exception) {
            // Return an error response
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function current(Request $r, $student_id)
    {
        try {
            $address = Address::where('student_id', $student_id)->where('status', 0);
            return JSONcondtion($address);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function cancel(Request $r, $student_id)
    {
        try {
            $data = $r->all();
            $data['my__parent_id'] = $r->user()->id;
            $data['student_id'] = $student_id;

            $validator = Validator::make($data, [
                'student_id' => [
                    'required',
                    'exists:students,id',
                    Rule::exists('my__parent_student', 'student_id')->where(function ($query) use ($data) {
                        return $query->where('my__parent_id', $data['my__parent_id']);
                    })
                ]
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }

            $address = Address::where('student_id', $student_id)->where('status', 0);

            if ($address->exists()) {
                $address->delete();
                return response()->json(['message' => __('Order cancellation successful')], 200);
            } else {
                return response()->json(['message' => __('No order found for cancellation')], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => __('Internal Server Error'), 'error' => $exception->getMessage()], 500);
        }
    }
}
