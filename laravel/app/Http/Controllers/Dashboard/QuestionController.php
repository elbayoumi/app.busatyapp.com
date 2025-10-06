<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Grade;
use App\Models\Question;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        // dd($r->text);
        $text = isset($r->text) && $r->text != '' ? $r->text : null;
        //to check if email valid and search
        $status = !(isset($r->status) && $r->status != '') ? "all" : ($r->status == 0 ? null : $r->status);
        $type = !(isset($r->type) && $r->type != '') ? "all" : ($r->type == 0 ? null : $r->type);

        $all_question = $status == "all" ? Question::query() : Question::query()->where('status', $status);
        $all_question = $type == "all" ? $all_question  : $all_question->where('type', $type);
        if ($text != null) {
            $all_question = $all_question->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r,) {
                    return $query->where('question', 'like', "%$r->text%")->orWhere('answer', 'like', "%{$r->text}%")
                        ->orWhere('id', 'like', "%{$r->text}%");
                });
            });
        }



        $all_question = $all_question->orderBy('id', 'desc')->paginate(10);

            //  => $all_question,




        return view('dashboard.question.index',compact('all_question') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {

            $headPage = 'انشاء سؤال شائع';
            return view('dashboard.question.create', compact('headPage'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        };
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $questionRequest)
    {
        Question::create($questionRequest->validated());
        return redirect()->route('dashboard.question.index')->with('success', 'تم اضافة البيانات بنجاح');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // Pass the question model to the view
        return view('dashboard.question.show', compact('question'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // Prepare the page title or any additional data
        $headPage = 'تعديل السؤال';

        // Pass the question model and title to the view
        return view('dashboard.question.edit', compact('question', 'headPage'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        // Validate the request data

        // Update the question with the validated data
        $question->update($request->validated());

        // Redirect back with a success message
        return redirect()->route('dashboard.question.index')->with('success', 'تم تحديث السؤال بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        // Delete the question
        $question->delete();

        // Redirect back with a success message
        return redirect()->route('dashboard.question.index')->with('success', 'تم حذف السؤال بنجاح');
    }

}
