<?php

namespace App\Http\Controllers\Api\schools\addresses;


use App\Models\Attendant;
use Illuminate\Http\Request;
use App\Models\TemporaryAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TemporaryAddressOldBus;
use App\Enum\TemporaryAddressStatusEnum;
use App\Services\TemporaryAddressService;
use Illuminate\Support\Facades\Validator;
use App\Services\AttendantNotificationService;

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
    // 
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
    //
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

  public function schoolResponse(Request $request, TemporaryAddress $temporaryAddress)
  {
    $validator = Validator::make($request->all(), [
      'accept_status' => 'required|in:1,2',
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => false,
        "errors" => $validator->errors(),
        "message" => __("Validation error"),
      ], 422);
    }

    if ($temporaryAddress->accept_status == TemporaryAddressStatusEnum::getArray()[0]) {
      $temporaryAddress->accept_status = $request->accept_status;
      $temporaryAddress->save();

      if ($request->accept_status == 1) {
        $message = __("Accepted successfully & previous approved temporary address has been replaced");
        // change previous approved addresses to replaced 
        TemporaryAddress::where('student_id', $temporaryAddress->student_id)
          ->where('accept_status', 1)
          ->where('id', '!=', $temporaryAddress->id)
          ?->update(['accept_status' => 4]);
      } else {
        $message = __("Rejected successfully");
      }
      return response()->json([
        "status" => true,
        "message" => $message,
      ]);
    } else {
      return response()->json([
        "status" => false,
        "message" => __("This address has been processed & can't be updated"),
      ]);
    }
  }

  public function status () 
  {
    return TemporaryAddressStatusEnum::getAssociativeArray();
  }

  public function changeBus(Request $request, TemporaryAddress $temporaryAddress)
  {
    $errors = [];
    
    $validator = Validator::make($request->all(), [
      'new_bus_id' => 'required',
      'timezone' => 'required|timezone',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
    }

    $schoolHasTheBus = $request->user()->buses()->where('id', $request->new_bus_id)->count() > 0;

    if (!$schoolHasTheBus) {
      $errors = ["bus_id" => [__("This bus is not associated with this school")]];
    }

    if ( $temporaryAddress->accept_status != TemporaryAddressStatusEnum::getArray()[1]) 
    {
      $errors = ["bus_id" => [__("This temporary address is not approved yet")]];
    }

    if (isset($errors) && count($errors) > 0) {
      return response()->json([
        "status" => false,
        "errors" => $errors,
        "message" => __("Validation error"),
      ], 422);
    }

    // update the student bus & store the old bus 

    $oldBusId = $temporaryAddress->student->bus_id;

    DB::transaction(function ()  use ($temporaryAddress, $request) {
      if ( !$temporaryAddress->has('oldBus')) {
        $temporaryAddress->oldBus()->attach($temporaryAddress->student->bus_id , [
          'timezone' => $request->timezone
        ]);
      }

      $temporaryAddress->student->update([
        'bus_id' => $request->new_bus_id
      ]);
    });

    // sending a notification to attendants of both buses
    # get attendants of both buses
    $attendants = Attendant::where('bus_id', $request->new_bus_id)
      ->orWhere('bus_id', $oldBusId)
      ->get();

      $title = __("A new temporary bus for a student");
      $body = __("The student ") 
        . " ({$temporaryAddress->student->name}) " 
        . __("has been moved temporarily to the bus ") 
        . " ({$temporaryAddress->student->bus->name}) ";

      $attendantNotificationService = new AttendantNotificationService(
        $attendants
      );
    
      $attendantNotificationService->sendMulticastNotification(
        $title,
        $body
      );

      return response()->json([
        "status" => true,
        "message" => __("The temporary bus has been added successfully"),
      ]);
  }
}
