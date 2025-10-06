<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function all(Request $request, $type)
    {
        try {
            // Validate and sanitize input values
            $text = !empty($request->input('text')) ? $request->input('text') : false;
            $status = !empty($request->input('status')) ? ($request->input('status') == 0 ? null : $request->input('status')) : "all";

            // Initialize query
            $query = Question::query()->where('type', $type)->lang();

            // Apply status filter if not "all"
            if ( "all" !== $status) {
                $query->where('status', $status);
            }

            // Apply text filter if provided
            if ($text) {
                $query->where(function ($q) use ($text) {
                    $q->where('question', 'like', "%$text%")
                      ->orWhere('answer', 'like', "%$text%")
                      ->orWhere('id', 'like', "%$text%");
                });
            }

            // Apply sorting and pagination
            $questions = $query->orderBy('id', 'desc')->paginateLimit();
            // Return JSON response
            return JSON($questions);
        } catch (\Exception $exception) {
            // Return error response
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function show(Request $request,$id)
    {
        try {
            // Pass the question model to the view
            $question = Question::where('id', $id)->lang()->first();

            return JSON($question);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
