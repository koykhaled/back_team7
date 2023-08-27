<?php

namespace App\Http\Controllers;

use App\Http\Resources\TermResource;
use App\Models\College;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
    try {
        $college_id = College::where('uuid', $id)->first();
    $terms= TermResource::collection($college_id->terms()->get());
    return $this->successResponse($terms , 'all terms' , 200);
    } catch (\Throwable $th) {
        //throw $th;
        return $this->errorResponse("Error. " . $th->getMessage() , 500);
    }
   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        try {
            $college_id = College::where('uuid', $id)->first();
      $college=  $college_id->terms()->create([
   'name'=> $request->name
]);
return $this->successResponse($college,'terms created successfuly' , 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error. " . $th->getMessage() , 500);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
            $college_id = College::where('uuid', $id)->first();
            $college= $college_id->terms()->update([
                'name' => $request->name
            ]); 
            return $this->successResponse($college,'terms updated successfuly', 200 );
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
            //code...
            $term_id= Term::where('uuid',$id)->first();
            $term_id->delete();
            return $this->successResponse(null,'deleted successfuly', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
     
    }
}
