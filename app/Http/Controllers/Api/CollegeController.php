<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollegeRequest;
use App\Http\Resources\CollegeResource;
use App\Models\Category;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $colleges = CollegeResource::collection(College::all());
            return $this->successResponse($colleges, "all colleges");
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
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
    public function store(CollegeRequest $request)
    {
        try {
            $category = Category::where('uuid', $request->category_id)->first();
            $category->colleges()->create([
                'name' => $request->name,
                'logo' => $request->logo,
            ]);
            return $this->successResponse('collage created successfuly', 201);
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
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
    public function update(Request $request, $uuid)
    {
        try {
            $college = College::where('uuid', $uuid)->get();
            $college->update([
                'name' => $request->name,
                'logo' => $request->logo
            ]);

            return $this->successResponse('the college updated successfuly');
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        try {
            $college = College::where('uuid', $uuid)->get();
            $college->delete($college->id);
            return $this->successResponse("the category deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
}