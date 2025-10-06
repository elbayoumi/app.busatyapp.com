<?php

namespace App\Http\Controllers\Api\schools\buses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Schools\StoreBusesRequest;
use App\Models\{
    School,
    Bus,
    Student,
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Validation\Rule,
};

use PDF;
use App\Http\Requests\StoreStudentsToBusRequest;
use App\Http\Resources\Schools\BusResource;
use App\Repositories\Schools\Buses\BusesInterface;

class BusesController extends Controller
{
    private $busesRepository;
    /**
     * BusesController constructor.
     *
     * @param BusesInterface $busesRepository
     */
    function __construct(BusesInterface $busesRepository)
    {
        $this->busesRepository=$busesRepository;
    }
    /**
     * Get all buses that belong to the school of the authenticated user.
     *
     * @param Request $r
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $r, $param = 'paginate')
    {
        try {

            $text = isset($r->text) && $r->text != '' ? $r->text : null;

            $buses = Bus::where('school_id', $r->user()->id);
            if ($text != null) {
                $buses = $buses->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', '%' . $r->text . '%');
                    });
                });
            }
            if ($param == 'get') {
                $buses = $buses->orderBy('id', 'desc')->get();
            } elseif ($param == 'paginate') {
                $buses = $buses->orderBy('id', 'desc')->paginateLimit();
            }
            return response()->json([
                'data' => $buses,
                'message' => __("success message"),
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request, $id)
    {
        try {
            $bus = Bus::where('school_id', $request->user()->id)->where('id', $id)
                ->with(['students.grade']);
            return sendJSON($bus);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Get all buses that don't have drivers yet
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function availableAddDriver(Request $request)
    {
        try {
            $buses = Bus::select('id', 'name')->where('school_id', $request->user()->id)
                ->whereDoesntHave('attendants', function ($query) {
                    $query->where('type', 'drivers');
                })
                ->get();
            $buses = BusResource::collection($buses);

            return JSON($buses);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Get all buses that don't have admins yet
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function availableAddAdmins(Request $request)
    {
        try {
            $buses = Bus::select('id', 'name')->where('school_id', $request->user()->id)
                ->whereDoesntHave('attendants', function ($query) {
                    $query->where('type', 'admins');
                })
                ->get();
            $buses = BusResource::collection($buses);
            return JSON($buses);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Get all students that don't exist in specific bus
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowStudentsDosNotExistInBus(Request $request, $id)
    {
        try {
            $students = Student::where('school_id', $request->user()->id)
                ->where('bus_id', '<>', $id)
                ->with(['grade', 'bus:id,name'])
                ->paginateLimit();

            return JSON($students);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_bus(Request $r)
    {
        try {
            $school =  School::where('id', $r->user()->id);
            return sendJSON($school);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Store a newly created bus in storage.
     *
     * @param  \App\Http\Requests\StoreBusesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function getStore(StoreBusesRequest $r)
    {
        try{
        $user = $r->user();
        $bus = $user->buses()->create($r->validated());
            return response()->json([
                'data' => $bus,
                'errors' => false,
                'message' => __('Bus added successfully')
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r, $id)
    {
        try {
            $bus = Bus::where('school_id', $r->user()->id)->where('id', $id)->first();
            return sendJSON($bus);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function update_bus(Request $r, $id)
    {

        $bus = Bus::where('school_id', $r->user()->id)->where('id', $id);

        if ($bus->exists()) {


            $validator = Validator::make($r->all(), [
                'name' => ['required', "min:1",'max:30', Rule::unique('buses')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id);
                })->ignore($id)],
                'car_number' => ['required', "min:1" ,'max:15', Rule::unique('buses')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id);
                })->ignore($id)],
                'notes' =>  ['nullable', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 403);
            }
            $bus = $bus->first();
            $bus->name                      = $r->name;
            $bus->notes                     = $r->notes;
            $bus->car_number                = $r->car_number;
            $bus->school_id                 = $r->user()->id;
            $bus->save();


            return response()->json([
                'data' => ['bus' => $bus],
                'message' => __("success message"),
                'status' => true
            ], 200);
        }

        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }

    public function delete_bus(Request $r, $id)
    {

        $bus = Bus::where('school_id', $r->user()->id)->where('id', $id);

        if ($bus->exists()) {
            $bus->delete();

            return response()->json([
                'message' => __("success message"),
                'status' => true
            ], 200);
        }
        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 403);
    }


    public function addStudentsToBus(Request $r)
    {

        try {
            $buses = Bus::where('school_id', $r->user()->id)->with(['students'])->get();
            $students = Student::where('school_id', $r->user()->id)->orderBy('id', 'desc')->get();
            return response()->json([
                'data' => ['buses' => $buses, 'students' => $students],
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function storeStudentsToBus(StoreStudentsToBusRequest $r)
    {
        try {

            // Fetch updated students to return in response
            $updatedStudents = $this->busesRepository->storeStudentsToBus($r);

            return response()->json(['data' =>$updatedStudents, 'errors' => false, 'message' => __("Data has been added successfully")], 200);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function removeStudentsToBus(Request $r)
    {
        try {
            $date = $r->all();
            $validator = Validator::make($date, [
                'bus_id' =>  ['required'],
                'student_id'    =>   ['required', 'array', 'min:1', Rule::exists('students', 'id')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id);
                })]

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }



            $students = Student::where('school_id', $r->user()->id)->where('bus_id', $r->bus_id)->whereIn('id', $r->student_id)->get();
            if (count($students) != 0) {
                foreach ($students as $student) {

                    $student->bus_id     = null;

                    $student->trip_type  = null;

                    $student->save();
                }

                return response()->json(['data' => $students, 'errors' => false, 'message' => __("Data has been added successfully")], 200);
            }
            return response()->json(['errors' => true, 'message' => __('At least one student is required')], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function addStudentToBus(Request $r)
    {
        try {
            // Bus::where('school_id', $r->user()->id)
            $buses = $r->user()->buses()->with(['students'])->get();
            $students = $r->user()->students()->orderBy('id', 'desc')->get();
            return response()->json([
                'data' => ['buses' => $buses, 'students' => $students],
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function filterStudentsByBusAndClassroom(Request $r)
    {
        try {
            // Validate the request inputs
            $validated = $r->validate([
                'with_bus_id' => 'nullable|integer|min:1',
                'without_bus_id' => 'required|integer|min:1',
                'classroom_id' => 'nullable|integer|min:1',
            ]);

            $with_bus_id = $validated['with_bus_id'] ?? null;
            $without_bus_id = $validated['without_bus_id'];
            $classroomId = $validated['classroom_id'] ?? null;

            // Start the query for the authenticated user's students
            $query = $r->user()->students();

            // Grouped logic:
            $query->where(function ($q) use ($without_bus_id, $with_bus_id) {
                // Students without this bus
                $q->where('bus_id', '<>', $without_bus_id);

                // Or students with this bus if provided
                if (!is_null($with_bus_id)) {
                    $q->orWhere('bus_id', $with_bus_id);
                }
            });

            // Optional classroom filter
            if (!is_null($classroomId)) {
                $query->where('classroom_id', $classroomId);
            }

            $students = $query->paginate(10);

            return response()->json($students);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json(['errors' => $validationException->errors()], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    public function storeStudentToBus(Request $r)
    {
        try {


            $date = $r->all();
            $validator = Validator::make($date, [
                'bus_id' =>  ['nullable'],

                'student_id'    =>   ['required', Rule::exists('students', 'id')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id);
                })],
                'trip_type'        =>  ['nullable', 'in:full_day,end_day,start_day'],

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }
            if ($r->bus_id != null) {
                $validator = Validator::make($date, [
                    'bus_id' =>  [Rule::exists('buses', 'id')->where(function ($query) use ($r) {
                        return $query->where('school_id', $r->user()->id);
                    })],
                    'trip_type'        =>  ['required'],

                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
                }
            }

            $student = Student::where('school_id', $r->user()->id)->where('id', $r->student_id)->first();
            if ($student != null) {
                $student->bus_id    = $r->bus_id;

                if ($r->bus_id != null) {
                    $student->trip_type  = $r->trip_type;
                } else {
                    $student->trip_type  = null;
                }
                $student->save();

                return response()->json(['data' => $student, 'errors' => false, 'message' => __("Data has been added successfully")], 200);
            }
            return response()->json(['errors' => true, 'message' => __('Student must be added')], 403);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function bus_export_pdf(Request $r, $id = null, $ln = 'ar')
    {

        $bus = Bus::where("school_id", $r->user()->id);
        if ($id == null) {
            $bus = $bus->first();
        } else {
            $bus = $bus->where("id", $id)->first();
        }

        $pdf = PDF::loadView("dashboard.buses.pdf_$ln", ['bus' => $bus]);

        return $pdf->download($bus['name'] . '.pdf');
    }
}
