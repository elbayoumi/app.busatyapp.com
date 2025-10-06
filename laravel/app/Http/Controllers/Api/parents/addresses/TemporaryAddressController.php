<?php

namespace App\Http\Controllers\Api\parents\addresses;

use Illuminate\Http\Request;
use App\Models\TemporaryAddress;
use App\Http\Controllers\Controller;
use App\Enum\TemporaryAddressStatusEnum;
use App\Services\TemporaryAddressService;
use Illuminate\Support\Facades\Validator;

class TemporaryAddressController extends Controller
{
  public $temporaryAddressService;

  public function __construct()
  {
    $this->temporaryAddressService = new TemporaryAddressService;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $response = $this->temporaryAddressService->index($request);
    return response()->json($response['body'], $response['code']);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $errors = $this->temporaryAddressService->validateRequest($request);

      $studentHasProcessingAddress = TemporaryAddress::where('student_id', $request->student_id)
        ->where('accept_status', 0)->exists();

      if ($studentHasProcessingAddress) {
        $errors = ["student_id" => [__("This student already has an in processing temporary address request")]];
      }

      if (isset($errors) && !empty($errors)) {
        return response()->json(
          [
            "status" => false,
            "errors" => $errors,
            "message" => __("Validation error"),
          ],
          422
        );
      }

      $temporaryAddress = TemporaryAddress::create($request->all());

      return response()->json([
        "status" => true,
        "data" => $temporaryAddress,
        "message" => __("Updated successfully"),
      ], 200);

    } catch (\Throwable $th) {

      return response()->json([
        "status" => false,
        "errors" => ['error' => $th->getMessage()],
        "message" => __("Internal Server Error"),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return \Illuminate\Http\Response
   */
  public function show(TemporaryAddress $temporaryAddress)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return \Illuminate\Http\Response
   */
  public function edit(TemporaryAddress $temporaryAddress)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, TemporaryAddress $temporaryAddress)
  {
    try {
      if (!$temporaryAddress->accept_status == TemporaryAddressStatusEnum::getArray()[0]) {
        $errors = ["student_id" => [__("This address has been processed & can't be updated")]];
      }

      $errors = $this->temporaryAddressService->validateRequest($request);

      if (isset($errors) && !empty($errors)) {
        return response()->json(
          [
            "status" => false,
            "errors" => $errors,
            "message" => __("Validation error"),
          ],
          422
        );
      }

      $temporaryAddress->update($request->all());

      return response()->json([
        "status" => true,
        "data" => $temporaryAddress,
        "message" => __("Updated successfully"),
      ], 200);

    } catch (\Throwable $th) {

      return response()->json([
        "status" => false,
        "errors" => ['error' => $th->getMessage()],
        "message" => __("Internal Server Error"),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return \Illuminate\Http\Response
   */
  public function destroy(TemporaryAddress $temporaryAddress)
  {
    // 
  }

  public function cancel(TemporaryAddress $temporaryAddress)
  {
    if ($temporaryAddress->accept_status == TemporaryAddressStatusEnum::getArray()[0]) {
      $temporaryAddress->accept_status = 3;
      $temporaryAddress->save();

      return response()->json([
        "status" => true,
        "message" => __("Cancelled successfully"),
      ]);
    } else {
      return response()->json([
        "status" => false,
        "message" => __("This address has been processed & can't be cancelled"),
      ] , 
      422);
    }
  }

  public function status () 
  {
    return TemporaryAddressStatusEnum::getAssociativeArray();
  }
}
