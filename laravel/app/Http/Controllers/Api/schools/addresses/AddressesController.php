<?php

namespace App\Http\Controllers\Api\schools\addresses;

use App\Enum\AddressStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\schools\addresses\UnacceptedAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AddressesController extends Controller
{

    public function index(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'status' => ['nullable', 'in:new,accept,unaccept,processing'],
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 403);
            }
            $text = isset($r->text) && $r->text != '' ? $r->text : null;
            $created_at = isset($r->created_at) && $r->created_at != '' ? $r->created_at : null;
            $status = isset($r->status) && $r->status != '' ? $r->status : null;

            $address = Address::query()->where('school_id', $r->user()->id)->with([
                'schools',
                'students.grade',
                'parent',
                'bus',
            ]);
            if (!empty($r->text)) {
                $address = $address->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->whereHas('students', function ($e) use ($r) {
                            $e->where('name', 'like', "%$r->text%");
                        });
                    });
                });
            }


            if (!empty($r->created_at)) {
                $address = $address->where(function ($q) use ($r) {
                    return $q->when($r->created_at, function ($query) use ($r) {
                        return $query->whereDate('created_at', $r->created_at);
                    });
                });
            }


            if (!empty($r->status)) {

                switch ($status) {
                    case 'new':
                        $status = 3;
                        break;
                    case 'accept':
                        $status = 1;
                        break;
                    case 'unaccept':
                        $status = 2;
                        break;
                    case 'processing':
                        $status = 3;
                        break;
                    default:
                        $status = 3;
                        break;
                }

                $address = $address->where(function ($q) use ($r, $status) {
                    return $q->when($status, function ($query) use ($r, $status) {
                        if ($status == 'new') {
                            return $query->where('status', '<', 1);
                        } else {
                            return $query->where('status', $status);
                        }
                    });
                });
            }


            $address = $address->orderBy('id', 'desc')->paginateLimit();
            return response()->json([
                'data' => [
                    'request_addresses' =>  $address,
                    'request_addresses_count' =>  $address->count(),
                ],
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }



    public function show(Request $request, $id)
    {
        try {
            $address = Address::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'students.grade',
                    'bus',
                    'parent',
                ])
                ->first();

            if ($address != null) {


                if ($address->status == 0) {
                    $address->status = AddressStatusEnum::PROCESSING;
                    $address->save();
                }
                return response()->json([
                    'data' => $address,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function accepted(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Retrieve the address by ID
            $address = Address::whereId($id)->watting()->first();

            if (!$address) {
                // Address not found
                return response()->json(['error' => __("Address not found")], 404);
            }

            // Retrieve the related student
            $student = $address->students()->first();

            if (!$student) {
                // Student not found
                return response()->json(['error' => __("Student not found")], 404);
            }

            // Update the student information
            $student->update([
                'address' => $address->address ,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
            ]);

            // Update the address status
            $address->update(['status' => AddressStatusEnum::ACCEPT]);

            // Commit the transaction
            DB::commit();

            return JSON($address);

        } catch (\Exception $exception) {
            // Rollback the transaction in case of error
            DB::rollback();

            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
    public function unaccepted(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $address = Address::where('school_id', $request->user()->id)->watting()->where('id', $id)
                ->with([
                    'schools',
                    'students.grade',
                    'bus',
                    'parent',
                ])
                ->first();

                if (!$address) {
                    // Address not found
                    return response()->json(['error' => __("Address not found")], 404);
                }

                $address->update(['status' => AddressStatusEnum::UNACCEPT]);


                DB::commit();

                return JSON($address);

        } catch (\Exception $exception) {
            DB::rollback();

            return JSONerror($exception->getMessage(), 500);
        }
    }
}
