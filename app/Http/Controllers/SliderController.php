<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Api\UploadImage;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use ApiResponse , UploadImage;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
     
      try {
        $slider= Slider::where('uuid', $id)->first();
        $sliders= SliderResource::collection($slider->all());
        return $this->successResponse($sliders , 'all sliders' , 200);
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
    public function store(Request $request , $id)
    {
        //
       
        try {
            //code...
            $slider= Slider::where('uuid', $id)->first();
            $sliders= Slider::create([
           'img_url'=> $request->img_url,
           'link' => $request->link
            ]); 
            return $this->successResponse($sliders , 'createed', 201);
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
            $slider= Slider::where('uuid' , $id)->first();
            $slider->delete();
            return $this->successResponse(null , 'deleted successfuly' , 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Error." . $th->getMessage());
        }
    }
}

