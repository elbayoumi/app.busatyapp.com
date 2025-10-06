<?php

namespace App\Http\Controllers\Api\schools\students;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\schools\students\StudentRequest;
use App\Http\Requests\Schools\students\StudentUpdateRequest;
use App\Imports\StudentImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{
    Bus,
    Gender,
    My_Parent,
    Religion,
    Student,
    Type_Blood,
    School,
    Trip
};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/schools/students/{bus_id?}",
     *     tags={"Schools"},
     *     summary="Get students",
     *     description="Get students with filter by bus_id, grade_id, parent_key, text",
     *     @OA\Parameter(
     *         description="lang",
     *         in="path",
     *         name="ln",
     *         required=false,
     *         example="ar",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="bus_id",
     *         in="path",
     *         name="bus_id",
     *         required=false,
     *         example=1,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="The input data needed to get students",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text","parent_key","bus_id","grade_id"},
     *             @OA\Property(property="text", type="string", example="text", description="text"),
     *             @OA\Property(property="parent_key", type="string", example="parent_key", description="parent key"),
     *             @OA\Property(property="bus_id", type="integer", example=1, description="bus id"),
     *             @OA\Property(property="grade_id", type="integer", example=1, description="grade id"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="students"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(Request $r, $bus_id = null)
    {
        try {

            $students = Student::query()->where('school_id', $r->user()->id)
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',
                    'my_Parents',

                ]);

            if (!empty($bus_id)) {
                $students = $students->where('bus_id', '!=', $bus_id);
            }

            if (!empty($r->text)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', "%$r->text%");
                    });
                });
            }
            if (!empty($r->parent_key)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->parent_key, function ($query) use ($r) {
                        return $query->where('parent_key', $r->parent_key);
                    });
                });
            }




            if (!empty($r->bus_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->bus_id, function ($query) use ($r) {
                        return $query->where('bus_id', $r->bus_id);
                    });
                });
            }

            if (!empty($r->grade_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->grade_id, function ($query) use ($r) {

                        return $query->where('grade_id', $r->grade_id);
                    });
                });
            }

            $students = $students->orderBy('id', 'desc')->paginateLimit();

            $data = [
                'students' => $students,
                'buses' => $r->user()->buses()->get(),
                'grades' => $r->user()->grades()->get(),

            ];
            return JSON($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/schools/students/with_parent",
     *     tags={"Schools"},
     *     summary="Get students with parent",
     *     description="Get students with parent",
     *     @OA\Parameter(
     *         description="lang",
     *         in="path",
     *         name="ln",
     *         required=false,
     *         example="ar",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="The input data needed to get students",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text","parent_key","bus_id","grade_id"},
     *             @OA\Property(property="text", type="string", example="text", description="text"),
     *             @OA\Property(property="parent_key", type="string", example="parent_key", description="parent key"),
     *             @OA\Property(property="bus_id", type="integer", example=1, description="bus id"),
     *             @OA\Property(property="grade_id", type="integer", example=1, description="grade id"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="students with parent"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function indexStudentParent(Request $r)
    {
        try {

            $students = Student::query()->where('school_id', $r->user()->id)->whereHas('my_Parents')->with([
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
                'my_Parents',
                'address',

            ]);
            if (!empty($r->text)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', "%$r->text%");
                    });
                });
            }
            if (!empty($r->parent_key)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->parent_key, function ($query) use ($r) {
                        return $query->where('parent_key', $r->parent_key);
                    });
                });
            }




            if (!empty($r->bus_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->bus_id, function ($query) use ($r) {
                        return $query->where('bus_id', $r->bus_id);
                    });
                });
            }

            if (!empty($r->grade_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->grade_id, function ($query) use ($r) {

                        return $query->where('grade_id', $r->grade_id);
                    });
                });
            }

            $students = $students->orderBy('id', 'desc')->paginateLimit();

            $data = [
                'students' => $students,
                'buses' => $r->user()->buses()->get(),
                'grades' => $r->user()->grades()->get(),

            ];
            return JSON($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * Get students without parents
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexStudentDosntHaveParent(Request $r)
    {
        try {
            $students = Student::query()
                ->where('school_id', $r->user()->id)
                ->whereDoesntHave('my_Parents') // Excludes students who have parents
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',

                ]);

            if (!empty($r->text)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', "%$r->text%");
                    });
                });
            }
            if (!empty($r->parent_key)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->parent_key, function ($query) use ($r) {
                        return $query->where('parent_key', $r->parent_key);
                    });
                });
            }




            if (!empty($r->bus_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->bus_id, function ($query) use ($r) {
                        return $query->where('bus_id', $r->bus_id);
                    });
                });
            }

            if (!empty($r->grade_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->grade_id, function ($query) use ($r) {

                        return $query->where('grade_id', $r->grade_id);
                    });
                });
            }

            $students = $students->orderBy('id', 'desc')->paginateLimit();

            $data = [
                'students' => $students,
                'buses' => $r->user()->buses()->get(),
                'grades' => $r->user()->grades()->get(),

            ];
            return JSON($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/schools/students/no_address",
     *     tags={"Schools"},
     *     summary="Get students without address",
     *     description="Get students without address",
     *     @OA\Parameter(
     *         description="lang",
     *         in="path",
     *         name="ln",
     *         required=false,
     *         example="ar",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="The input data needed to get students",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text","parent_key","bus_id","grade_id"},
     *             @OA\Property(property="text", type="string", example="text", description="text"),
     *             @OA\Property(property="parent_key", type="string", example="parent_key", description="parent key"),
     *             @OA\Property(property="bus_id", type="integer", example=1, description="bus id"),
     *             @OA\Property(property="grade_id", type="integer", example=1, description="grade id"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="students without address"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function indexStudentDosntHaveAddress(Request $r)
    {
        try {

            $students = Student::query()
                ->where('school_id', $r->user()->id)
                ->whereDoesntHave('address') // Excludes students who have parents
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',
                    'address'
                ]);

            if (!empty($r->text)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', "%$r->text%");
                    });
                });
            }
            if (!empty($r->parent_key)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->parent_key, function ($query) use ($r) {
                        return $query->where('parent_key', $r->parent_key);
                    });
                });
            }




            if (!empty($r->bus_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->bus_id, function ($query) use ($r) {
                        return $query->where('bus_id', $r->bus_id);
                    });
                });
            }

            if (!empty($r->grade_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->grade_id, function ($query) use ($r) {

                        return $query->where('grade_id', $r->grade_id);
                    });
                });
            }

            $students = $students->orderBy('id', 'desc')->paginateLimit();

            $data = [
                'students' => $students,
                'buses' => $r->user()->buses()->get(),
                'grades' => $r->user()->grades()->get(),

            ];
            return JSON($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * Retrieve a list of students who have an address associated with them.
     *
     * This function filters students based on the school ID of the user making the request
     * and only includes students who have an address. It supports additional filtering by
     * student name, parent key, bus ID, and grade ID. The results are ordered by student
     * ID in descending order and paginated.
     *
     * @param Request $r The request object containing filters such as text, parent_key, bus_id, and grade_id.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the filtered list of students,
     *         buses, and grades or an error message if the operation fails.
     */

    public function indexStudentHasAddress(Request $r)
    {
        try {

            $students = Student::query()
                ->where('school_id', $r->user()->id)
                ->whereHas('address') // Excludes students who have parents
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',
                    'address'
                ]);

            if (!empty($r->text)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->where('name', 'like', "%$r->text%");
                    });
                });
            }
            if (!empty($r->parent_key)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->parent_key, function ($query) use ($r) {
                        return $query->where('parent_key', $r->parent_key);
                    });
                });
            }




            if (!empty($r->bus_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->bus_id, function ($query) use ($r) {
                        return $query->where('bus_id', $r->bus_id);
                    });
                });
            }

            if (!empty($r->grade_id)) {
                $students = $students->where(function ($q) use ($r) {
                    return $q->when($r->grade_id, function ($query) use ($r) {

                        return $query->where('grade_id', $r->grade_id);
                    });
                });
            }

            $students = $students->orderBy('id', 'desc')->paginateLimit();

            $data = [
                'students' => $students,
                'buses' => $r->user()->buses()->get(),
                'grades' => $r->user()->grades()->get(),

            ];
            return JSON($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/schools/students/{id}",
     *     tags={"Schools"},
     *     summary="Get student by id",
     *     description="Get student by id",
     *     @OA\Parameter(
     *         description="id of student",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        try {
            $student = Student::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendance',
                    'absences',
                ]);
            return sendJSON($student);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/schools/students/create",
     *     tags={"Schools"},
     *     summary="Get data to create students",
     *     description="Get data to create students",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function create(Request $r)
    {
        try {
            $school =  School::where('id', $r->user()->id)->first();
            $genders = Gender::get();
            $typeBlood = Type_Blood::get();
            $religion = Religion::get();
            $grades =  $school->grades()->get();
            $classrooms =  $school->classrooms()->get();

            $buses = Bus::where('school_id', $r->user()->id)
                ->with([
                    'schools',
                    'students',
                ])
                ->orderBy('id', 'desc')
                ->get();


            $school =  School::where('id', $r->user()->id)->first();
            $grades =  $school->grades()->get();
            $data = [
                'school'   => $school,
                'genders'   => $genders,
                'typeBlood' => $typeBlood,
                'religion' => $religion,
                'grades'   => $grades,
                'buses' => $buses,
                'classrooms' => $classrooms,

            ];

            if ($school != null) {
                return JSON($data);
            }

            return response()->json([
                'message' => __("data not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function getStore(StudentRequest $r)
    {
        try {
            $user = $r->user();

            // Use a transaction to keep things consistent
            return DB::transaction(function () use ($r, $user) {
                // Create the student under this school
                $data    = $r->validated();
                $student = $user->students()->create($data);

                // If a trip_id was provided, attach the student to that trip
                if ($r->filled('trip_id')) {
                    $tripId = (int) $r->input('trip_id');

                    // Ensure the trip belongs to the same school
                    $trip = Trip::query()
                        ->where('school_id', $user->id)
                        ->findOrFail($tripId);

                    // Attach without removing anything else (many-to-many)
                    // trip_student pivot will be created if not exists
                    $trip->students()->syncWithoutDetaching([$student->id]);
                }

                // Optionally load relations back (only if you want them in the response)
                // e.g., ['grade','classroom','trips'] if Student has those relations
                // $student->load(['trips']);

                return response()->json([
                    'data'    => $student,
                    'errors'  => false,
                    'message' => __('Student added successfully'),
                ], 201);
            });
        } catch (\Throwable $exception) {
            return response()->json([
                'errors'  => true,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }



    /**
     * Edit student details.
     *
     * Retrieves student information and related data for editing purposes based on the provided student ID
     * and the authenticated user's school ID. Includes associated data such as schools, gender, religion,
     * blood type, grades, classrooms, parents, attendance, absences, and buses.
     *
     * @param Request $r The incoming request instance containing user and request data.
     * @param int $id The ID of the student to be edited.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the student data and related information
     * or an error message if the data is not found.
     */

    public function edit(Request $r, $id)
    {
        try {
            $student = Student::where('school_id', $r->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendance',
                    'absences',
                    'bus'
                ])
                ->first();
            $school =  School::where('id', $r->user()->id)->first();
            $genders = Gender::get();
            $typeBlood = Type_Blood::get();
            $religion = Religion::get();
            $grades =  $school->grades()->get();
            $buses = Bus::where('school_id', $r->user()->id)
                ->with([
                    'schools',
                    'students',
                ])
                ->orderBy('id', 'desc')
                ->get();

            $classrooms =  $school->classrooms()->get();

            $data = [
                'school'   => $school,
                'genders'   => $genders,
                'typeBlood' => $typeBlood,
                'religion' => $religion,
                'grades'   => $grades,
                'buses'   =>  $buses,
                'student' => $student,
                'classrooms' => $classrooms

            ];

            if ($school != null && $student != null) {
                return JSON($data);
            }

            return response()->json([
                'message' => __("data not found"),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Update a student's information.
     *
     * @param Request $r The HTTP request containing the student's data.
     * @param int $id The ID of the student to update.
     *
     * @return \Illuminate\Http\JsonResponse The response containing the updated student data
     * or an error message.
     *
     * This method validates the input data, checks if the student exists,
     * and updates the student record with the provided information.
     * It handles validation errors and exceptions, returning appropriate JSON responses.
     */



    public function update_student(StudentUpdateRequest $request, $id)
    {
        try {
            $schoolId = $request->user()->id;

            // 1) Fetch student & ensure ownership
            $student = Student::where('id', $id)
                ->where('school_id', $schoolId)
                ->firstOrFail();

            // 2) Start DB transaction for atomic update
            DB::transaction(function () use ($request, $student, $schoolId) {

                // Handle logo file upload (if sent as file)
                if ($request->hasFile('logo')) {
                    $path = $request->file('logo')->store('students', 'public');
                    $student->logo = $path;
                } elseif ($request->filled('logo') && is_string($request->input('logo'))) {
                    // Handle base64 or existing path
                    $student->logo = $request->input('logo');
                }

                // Update scalar fields
                $student->update([
                    'name'         => $request->name,
                    'phone'        => $request->phone,
                    'gender_id'    => $request->gender_id,
                    'grade_id'     => $request->grade_id,
                    'classroom_id' => $request->classroom_id,
                    'address'      => $request->address,
                    'latitude'     => $request->latitude,
                    'longitude'    => $request->longitude,
                ]);

                // 3) Attach to trip if provided
                if ($request->filled('trip_id')) {
                    $trip = Trip::where('school_id', $schoolId)->findOrFail($request->trip_id);
                    $trip->students()->syncWithoutDetaching([$student->id]);
                }
            });

            // Optional: eager-load relations
            $student->load(['grade', 'classroom']);

            return response()->json([
                'status'  => true,
                'message' => __('student updated successfully'),
                'data'    => $student,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => __('Student or related data not found'),
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Delete a student record from the database.
     *
     * This function attempts to find a student by the given ID associated with the logged-in user's school.
     * If the student is found and has a custom logo, the logo file is deleted from storage.
     * The student record is then deleted from the database.
     *
     * @param Request $r The HTTP request object containing user and other request information.
     * @param int $id The ID of the student to be deleted.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure of the deletion operation.
     */

    public function delete_student(Request $r, $id)
    {

        try {
            $student = Student::where('id', $id)->where('school_id', $r->user()->id)->first();


            if ($student != null) {
                if ($student->logo != 'default.png') {

                    Storage::disk('public_uploads')->delete('/students_logo/' . $student->logo);
                } //end of if

                $student->delete();

                return response()->json(['errors' => false, 'message' => __("student deleted successfully")], 200);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }



    /**
     * Get school data to export students
     *
     * @queryParam int school_id required School id
     *
     * @response {
     *  "data": {
     *      "school": {
     *          "id": 1,
     *          "name": "School Name",
     *          "logo": "logo.png",
     *          "grades": [
     *              {
     *                  "id": 1,
     *                  "name": "Grade 1",
     *                  "classrooms": [
     *                      {
     *                          "id": 1,
     *                          "name": "Classroom 1",
     *                          "students": [
     *                              {
     *                                  "id": 1,
     *                                  "name": "Student Name",
     *                                  "phone": "0123456789",
     *                                  "Date_Birth": "2000-01-01",
     *                                  "gender_id": 1,
     *                                  "type__blood_id": 1,
     *                                  "religion_id": 1,
     *                                  "grade_id": 1,
     *                                  "classroom_id": 1,
     *                                  "bus_id": 1,
     *                                  "address": "Address",
     *                                  "city_name": "City",
     *                                  "latitude": 0,
     *                                  "longitude": 0,
     *                                  "logo": "logo.png"
     *                              }
     *                          ]
     *                      }
     *                  ]
     *              }
     *          ]
     *      }
     *  },
     *  "errors": false,
     *  "message": "success message"
     * }
     */
    public function students_data_export(Request $request)
    {
        try {
            $school = School::where('id', $request->user()->id)
                ->with([
                    'grades',
                    'classrooms',
                    'students',
                ])
                ->first();
            if ($school != null) {
                return JSON($school);
            }
            return response()->json([
                'message' => __("School not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/schools/students/export",
     *     tags={"Schools"},
     *     summary="Export students",
     *     description="Export students",
     *     @OA\Parameter(
     *         description="lang",
     *         in="path",
     *         name="ln",
     *         required=false,
     *         example="ar",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="The input data needed to export students",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from","to","grade_id","classroom_id"},
     *             @OA\Property(property="from", type="string", example="2022-01-01", description="from date"),
     *             @OA\Property(property="to", type="string", example="2022-01-20", description="to date"),
     *             @OA\Property(property="grade_id", type="integer", example=1, description="grade id"),
     *             @OA\Property(property="classroom_id", type="integer", example=1, description="classroom id"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="students exported successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error in exporting students"
     *     )
     * )
     */
    public function students_export(Request $r, $ln = 'ar')
    {
        try {
            $validator = Validator::make($r->all(), [
                'from' => 'required|date|date_format:Y-m-d',
                'to' => 'required|date|date_format:Y-m-d|after_or_equal:from',
                'grade_id'         =>  ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id)->where('grade_id', $r['grade_id']);
                })],
            ], [
                'to.after_or_equal' => __("End date must be greater than or equal to the start date"),
                'from.date_format' => __("Date format must be yyyy-mm-dd"),
                'to.date_format' => __("Date format must be yyyy-mm-dd"),
                'classroom_id'     =>  ['required', 'exists:classrooms,id', Rule::exists('classrooms', 'id')->where(function ($query) use ($r) {
                    return $query->where('school_id', $r->user()->id)->where('grade_id', $r['grade_id']);
                })],
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }


            $students = Student::whereBetween('created_at', [$r->from, $r->to])
                ->where('school_id', $r->user()->id)
                ->where('grade_id', $r->grade_id)
                ->where('classroom_id', $r->classroom_id)
                ->get();

            if ($students->count() > 0) {
                return Excel::download(new StudentsExport($students, $ln), 'طلاب مدرسة ' . $r->user()->name . ' من ' . $r->from . ' الي ' . $r->from . '.xlsx');
            }
            return response()->json([
                'message' => 'Students not found',
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function upload(Request $request)
    {


        try {
            $School = School::where('id', $request->user()->id)
                ->with([
                    'grades',
                    'classrooms',
                    'students',
                ]);

            return response()->json([
                'data' => $School->first(),
                'message' => 'success message',
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function students_import(Request $r)
    {
        try {
            DB::beginTransaction();

            // ✅ Validate the uploaded file and related data
            $validator = Validator::make($r->all(), [
                'attachment' => 'required|mimes:xlsx,xls',
                'grade_id'   => [
                    'required',
                    'exists:grades,id',
                    Rule::exists('school_grade')->where(function ($query) use ($r) {
                        $query->where('school_id', $r->user()->id)
                            ->where('grade_id', $r['grade_id']);
                    }),
                ],
                'classroom_id' => [
                    'required',
                    'exists:classrooms,id',
                    Rule::exists('classrooms', 'id')->where(function ($query) use ($r) {
                        $query->where('school_id', $r->user()->id)
                            ->where('grade_id', $r['grade_id']);
                    }),
                ],
            ]);

            if ($validator->fails()) {
                $allErrors = Arr::flatten($validator->errors()->toArray());
                $errorCounts = array_count_values($allErrors);

                $finalErrors = [];
                foreach ($errorCounts as $message => $count) {
                    $finalErrors[] = ($count > 1) ? "{$message} (repeated {$count} times)" : $message;
                }

                return response()->json([
                    'errors' => true,
                    'messages' => implode("; ", $finalErrors)
                ], 403);
            }

            // ✅ Store the uploaded file temporarily
            $file = $r->file('attachment');
            $filePath = $file->store('temp_uploads');

            // ✅ Import and process the data
            Excel::import(
                new \App\Imports\StudentImport(
                    $r->user()->id,
                    $r->grade_id,
                    $r->classroom_id,
                    $filePath // Pass file path for auto-deletion after processing
                ),
                $filePath
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'messages' => 'Students data imported successfully.'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollback();

            $allErrors = [];
            foreach ($e->failures() as $failure) {
                foreach ($failure->errors() as $error) {
                    $allErrors[] = "Row {$failure->row()}: {$error}";
                }
            }

            $errorCounts = array_count_values($allErrors);
            $finalErrors = [];
            foreach ($errorCounts as $message => $count) {
                $finalErrors[] = ($count > 1) ? "{$message} (repeated {$count} times)" : $message;
            }

            return response()->json([
                'errors' => true,
                'messages' => implode("; ", $finalErrors)
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'errors' => true,
                'messages' => "An unexpected error occurred: " . $e->getMessage()
            ], 500);
        }
    }


    // public function students_import(Request $r)
    // {
    //     try {
    //         DB::beginTransaction();


    //         $validator = Validator::make($r->all(), [
    //             'attachment'       => 'required|mimes:xlsx,xls',
    //             'grade_id'         =>  ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($r) {
    //                 return $query->where('school_id', $r->user()->id)->where('grade_id', $r['grade_id']);
    //             })],
    //             'classroom_id'     =>  ['required', 'exists:classrooms,id', Rule::exists('classrooms', 'id')->where(function ($query) use ($r) {
    //                 return $query->where('school_id', $r->user()->id)->where('grade_id', $r['grade_id']);
    //             })],
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
    //         }

    //         $import =  Excel::import(new StudentImport($r->user()->id, $r->grade_id, $r->classroom_id), $r->file('attachment'));
    //         DB::commit(); // insert data

    //         return JSON($import);
    //     } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
    //         DB::rollback();

    //         $failures = $e->failures();
    //         $failer_array = [];
    //         foreach ($failures as $failure) {
    //             $failer_array[] = "يرجى تصحيح البيانات في السطر". $failure->row().':' . $failure->errors()[0] ;
    //         }
    //         return response()->json(['errors' => true, 'message' => $failer_array], 500);

    //     }

    //     // if (session()->has('errors')) {
    //     //     $errors = session('errors');

    //     //     $error_array = [];
    //     //     foreach ($errors->all() as $error) {

    //     //         $error_array += [$error];
    //     //     }
    //     // } else {
    //     //     return response()->json([
    //     //         'message' => 'success message',
    //     //         'status' => true
    //     //     ]);
    //     // }
    // }
    public function parentAll(Request $request, $id)
    {
        try {

            $student = Student::where('school_id', $request->user()->id)->where('id', $id)->first();

            if ($student != null) {
                $ids = DB::table('my__parent_student')->where('student_id', $student->id)->pluck('my__parent_id');
                $parents = My_Parent::whereIn('id', $ids)
                    ->orderBy('id', 'desc')
                    ->paginateLimit();

                if (count($parents) != 0) {
                    return response()->json([
                        'data' => $parents,
                        'message' => 'success message',
                        'status' => true
                    ]);
                }
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function parentShow(Request $request, $id)
    {
        try {
            $students_id = Student::where('school_id', $request->user()->id)->pluck('id');
            $ids = DB::table('my__parent_student')->whereIn('student_id', $students_id)->pluck('my__parent_id');
            $parent = My_Parent::whereIn('id', $ids)->where('id', $id)->first();
            if ($parent != null) {
                return response()->json([
                    'data' => $parent,
                    'message' => 'success message',
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => 'parent not found',
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function download(Request $r)
    {

        try {
            return response()->download(public_path('uploads/import_students_file/import_students_file.xlsx'));
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }

        return false;
    }
    public function downloadShow(Request $r)
    {

        try {
            return JSON('import_students_file.xlsx');
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }

        return false;
    }
}
