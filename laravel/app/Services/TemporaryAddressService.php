<?php

namespace App\Services;

use App\Models\TemporaryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemporaryAddressService
{
  public $modelRules = [
    "student_id" => 'required|exists:students,id',
    'address' => 'required|string|max:255',
    "latitude" => 'required|numeric|between:-90,90',
    "longitude" => 'required|numeric|between:-180,180',
    "from_date" => 'required|date:Y-m-d|after:today',
    "to_date" => 'required|date:Y-m-d|after:today',
  ];

  public $filterRules = [
    'parent_id' => 'sometimes|nullable|exists:my__parents,id',
    "student_id" => 'sometimes|nullable|exists:students,id',
    'school_id' => 'sometimes|nullable|exists:schools,id',
    "accept_status" => 'sometimes|nullable',
    "from_date" => 'sometimes|nullable|date:Y-m-d',
    "to_date" => 'sometimes|nullable|date:Y-m-d',
  ];

  public function validateRequest(Request $request)
  {
    $errors = [];

    $validator = Validator::make($request->all(), $this->modelRules);

    if ($validator->fails()) {
      $errors = $validator->errors();
    }

    $parentHasThisStudent = request()->user()->students()->where('students.id', $request->student_id)->count() > 0;

    if (!$parentHasThisStudent) {
      $errors = ["student_id" => [__("This student is not associated with this account")]];
    }

    return $errors;
  }

  public function validateFilters(Request $request)
  {
    $errors = [];

    $validator = Validator::make($request->all(), $this->filterRules);

    if ($validator->fails()) {
      $errors = $validator->errors();
    }

    return $errors;
  }

  public function index(Request $request)
  {
    $errors = $this->validateFilters($request);

    if (isset($errors) && !empty($errors)) {
      $response = [
        'body' => [
          "status" => false,
          "errors" => $errors,
          "message" => __("Validation error"),
        ],
        'code' => 422
      ];
      return $response;
    }

    $filters = $request->all();

    // paginate only in school
    if ($request->has('parent_id') && $request->parent_id != null) {
      $temporaryAddresses = TemporaryAddress::filters($filters)->with([
          'oldBus',
          'student' => function ($query) {
              $query->select('id', 'bus_id', 'name', 'address', 'latitude', 'longitude')
                      ->with(['bus:id,name']); 
          }
      ])->get();
    } else {
      $temporaryAddresses = TemporaryAddress::filters($filters)->with([
          'oldBus',
          'student' => function ($query) {
              $query->select('id', 'bus_id', 'name', 'address', 'latitude', 'longitude')
                      ->with(['bus:id,name']); 
          }
      ])->paginate();
    }

    $response = [
      'body' => [
        "status" => true,
        "data" => $temporaryAddresses,
      ],
      'code' => 200
    ];
    return $response;
  }

}
