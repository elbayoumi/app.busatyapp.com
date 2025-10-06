<?php

namespace App\Services\Parent\Address;

use App\Enum\AddressStatusEnum;
use App\Models\Address; // Assuming you have an Address model
use App\Models\Student;

class AddressRequestService
{
    /**
     * Handle the request to create a new address for a parent.
     *
     * @param array $data
     * @param Student|null $student
     * @return Address|null
     */
    public function createAddressRequest(array $data): void
    {
        $student = Student::where('id', $data['student_id'])->first();
        $this->addresesRequestsProcessing($data, $student ,true);

    }
    public function storeChildren (array $data, $student): void
    {
        $this->addresesRequestsProcessing($data, $student);
    }
     private function addressProcess($data, $student, $status): void
    {
        $dataLatitude=isset($data['latitude']);
        $dataLongitude=isset($data['longitude']);
        $dataAddress=isset($data['address']);

        $latitude = $dataLatitude ? $data['latitude'] :$student->latitude;
        $longitude = $dataLongitude ? $data['longitude'] :$student->longitude;
        $address = $dataAddress ? $data['address'] :$student->address;

        $parent = request()->user();
        if (!$status instanceof AddressStatusEnum) {
            $status = AddressStatusEnum::from($status);
        }
         $parent->address()->create([
            'student_id'    => $student->id,
            'address'   => $address,
            'school_id'     => $student->school_id,
            'bus_id'        => $student->bus_id,
            'old_address'   => $student->address,
            'old_latitude'  => $student->latitude,
            'old_longitude' => $student->longitude,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'status'        => $status, // Assuming STATUS_PROCESSING is defined as 3 in your Address model
        ]);
    }


    public function addresesRequestsProcessing (array $data, $student ,$reqestCon=false): void
    {



        $dataLatitude=isset($data['latitude']);
        $dataLongitude=isset($data['longitude']);
        $dataAddress=isset($data['address']);
        $dataLocation=$dataLatitude && $dataLongitude;

        $studentLatitude= empty($student->latitude);
        $studentLongitude= empty($student->longitude);
        $studentAddress= empty($student->address);
        $studentLocation=$studentLatitude && $studentLongitude;


        $latitude = $dataLatitude ? $data['latitude'] :null;
        $longitude = $dataLongitude ? $data['longitude'] :null;
        $address = $dataAddress ? $data['address'] :null;



        if( (!$studentLocation) && $dataLocation){
            $this->addressProcess($data,$student,AddressStatusEnum::PROCESSING);

        }elseif($dataAddress && (!$studentAddress)){
            $this->addressProcess($data,$student,AddressStatusEnum::PROCESSING);
        }

        if( $studentLocation && $dataLocation){
            if($reqestCon){
                $reqestCon=false;
               $this->addressProcess($data,$student,AddressStatusEnum::ACCEPT);
            }


            $student->latitude = $latitude;
            $student->longitude = $longitude;

        }
        if($dataAddress && $studentAddress){
            if($reqestCon){
               $this->addressProcess($data,$student,AddressStatusEnum::ACCEPT);
            }
            $student->address = $address;

        }




        $student->save();



    }
    // public function addresesRequestsProcessing(array $data, $student, callable $anonymous = null): void
    // {



    //     $dataLatitude=isset($data['latitude']);
    //     $dataLongitude=isset($data['longitude']);
    //     $dataAddress=isset($data['address']);
    //     $dataLocation=$dataLatitude && $dataLongitude;

    //     $studentLatitude= empty($student->latitude);
    //     $studentLongitude= empty($student->longitude);
    //     $studentAddress= empty($student->address);
    //     $studentLocation=$studentLatitude && $studentLongitude;


    //     $latitude = $dataLatitude ? $data['latitude'] :null;
    //     $longitude = $dataLongitude ? $data['longitude'] :null;
    //     $address = $dataAddress ? $data['address'] :null;



    //     if( (!$studentLocation) && $dataLocation){
    //         $this->addressProcess($data,$student,AddressStatusEnum::PROCESSING);

    //     }elseif($dataAddress && (!$studentAddress)){
    //         $this->addressProcess($data,$student,AddressStatusEnum::PROCESSING);
    //     }

    //     if( $studentLocation && $dataLocation){
    //         $student->latitude = $latitude;
    //         $student->longitude = $longitude;
    //         $anonymous();
    //     }
    //     if($dataAddress && $studentAddress){
    //         $student->address = $address;
    //         $anonymous();

    //     }

    //     // if( $studentLocation && $studentAddress){
    //     //     $student->latitude = $latitude;
    //     //     $student->longitude = $longitude;
    //     //     $student->address = $address;
    //     // }else{

    //     // }




    //     $student->save();


    //     // $latitude = isset($data['latitude']) && empty($student->latitude);
    //     // $longitude = isset($data['longitude']) && empty($student->longitude);
    //     // $address = isset($data['address']) && empty($student->address);
    //     //     $student->latitude = $data['latitude'];
    //     //     $student->longitude = $data['longitude'];
    //     //     $student->address = $data['address'];

    // }
}
