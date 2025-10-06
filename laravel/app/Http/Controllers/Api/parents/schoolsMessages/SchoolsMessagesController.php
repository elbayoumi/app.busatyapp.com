<?php

namespace App\Http\Controllers\Api\parents\schoolsMessages;

use App\Http\Controllers\Controller;
use App\Http\Resources\Parents\SchoolsMessages\SchoolsMessagesResource;
use Illuminate\Http\Request;
use App\Models\ParentSchoolMessage;


class SchoolsMessagesController extends Controller
{

    public function getAll(Request $request)
    {
        try {
            $user = $request->user();
            $userMessages = ParentSchoolMessage::where('my__parent_id', $user->id)
            ->with([
            'school_messages',
            ])
            ->orderBy('id', 'desc')
            ->paginateLimit();

                return response()->json([
                    'data' => [
                        'userMessages' => SchoolsMessagesResource::collection($userMessages),
                        'userMessages_count' => $userMessages->count(),
                        'userMessages_new_count' => $userMessages->where('status', 0)->count(),

                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);

        }
    }


    public function getNew(Request $request)
    {
        try {
            $user = $request->user();
            $userMessagesNew = ParentSchoolMessage::where('my__parent_id', $user->id)->with(['parents.'])->where('status', 0)
            ->with([
            'school_messages',
            ])
            ->orderBy('id', 'desc')
            ->paginateLimit();

                return response()->json([
                    'data' => [
                        'userMessagesNew' => SchoolsMessagesResource::collection($userMessagesNew),
                        'userMessages_count' => $userMessagesNew->count(),

                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);
        }
    }


    public function getShow(Request $request, $id)
    {
        try {
            $user = $request->user();
            $userMessages = ParentSchoolMessage::where('my__parent_id', $user->id)->where('id', $id)
            ->with([
            'school_messages',
            ])

            ->first();
            if ($userMessages != null) {
                if($userMessages->status == 0) {
                    $userMessages->status = 1;
                    $userMessages->save();
                }
                return response()->json([
                    'data' =>  SchoolsMessagesResource::make($userMessages),
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Message not found'),
                'status' => false,
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);

        }
    }


    public function getAllRed(Request $request)
    {
        try {
            $user = $request->user();
            $userMessages = ParentSchoolMessage::where('my__parent_id', $user->id)->where('status', 0)
            ->first();
            if ($userMessages != null) {
                if($userMessages->status == 0) {
                    $userMessages->status = 1;
                    $userMessages->save();
                }
                return response()->json([
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Messages All read'),
                'status' => false,
            ], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);
        }
    }

}
