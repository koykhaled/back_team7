<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Term;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {

        try {

            if ($subject = Subject::where('uuid', $request->subject_id)->first()) {
                $questions = ($subject->questions()
                    ->inRandomOrder()->limit(50)->get());
            } elseif ($term = Term::where('uuid', $request->term_id)->first()) {
                $questions = ($term->questions()->inRandomOrder()->limit(50)->get());
            } else {
                if (!Auth::user()) {
                    throw new Exception("Please Sign In to get Questions");
                } else {
                    $questions = (Question::where('college_id', Auth::user()->college_id)
                        ->inRandomOrder()
                        ->limit(50)->get());
                }
            }
            foreach ($questions as $question) {
                $subject = Subject::find($question->subject_id);
                $choices = $question->choices()->inRandomOrder()->get();
                $question['subject_name'] = $subject->name;
                $question['choices'] = $choices;
            }
            return $this->successResponse(QuestionResource::collection($questions), 'all questions', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage(), 500);
        }
    }


    public function store(Request $request, $id)
    {
        //

        try {
            $subject = Subject::where('uuid', $id)->first();
            $question = new QuestionResource($subject->questions()->create(
                [
                    'content' => $request->content,
                    'reference' => $request->reference,
                    'college_id' => $subject->college_id
                ]
            ));
            $question['subject_name'] = $subject->name;
            if ($request->has('term')) {
                $term = Term::where('uuid', $request->term)->first();
                $term->questions()->attach($question->id);
                $question['term_name'] = $term->name;
            }
            return $this->successResponse($question, 'question created successfuly', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error. " . $th->getMessage(), 500);
        }
    }



    public function update(Request $request, $id)
    {
        //

        try {
            //code...
            $subject_id = Subject::where('uuid', $id)->first();
            $question = $subject_id->questions()->update(
                [
                    'content' => $request->content,
                    'reference' => $request->reference
                ]
            );
            return $this->successResponse($question, 'question updated successfuly', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error. " . $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $question_id = Question::where('uuid', $id)->first();
            $question_id->delete();
            return $this->successResponse(null, 'deleted successfuly', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
    }
}