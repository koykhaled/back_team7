<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChoiceResource;
use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        try {
            //code...
            $question_id = Question::where('uuid', $id)->first();
            $choices = ChoiceResource::collection($question_id->choices()->get());
            return $this->successResponse($choices, 'all choices', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        //
        try {
            //code...
            $question = Question::where('uuid', $id)->first();
            $choice = Choice::create([
                'content' => $request->content
            ]);
            $question->choices()->attach($choice->id);
            return $this->successResponse($choice, 'choice created successfuly', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            //code...
            $question_id = Question::where('uuid', $id)->first();
            $choice = $question_id->choices()->update([
                'content' => $request->content
            ]);
            return $this->successResponse($choice, 'choice update successfuly', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
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
            $choice_id = Choice::where('uuid', $id)->first();
            $choice_id->delete();
            return $this->successResponse(null, 'deleted successfuly', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
    }
}