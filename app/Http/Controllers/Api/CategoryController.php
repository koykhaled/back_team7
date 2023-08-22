<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
            $categories = CategoryResource::collection(Category::all());
            return $this->successResponse($categories, "all categories");
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
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create([
                'name' => $request->name,
                'logo' => $request->logo
            ]);
            return $this->successResponse("category created successfuly!", 201);
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
    public function update(CategoryRequest $request, $uuid)
    {
        try {
            $category = Category::where('uuid', $uuid)->get();
            $category->update([
                'name' => $request->name,
                'logo' => $request->logo
            ]);

            return $this->successResponse('the category updated successfuly');
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
            $category = Category::where('uuid', $uuid)->get();
            $category->delete($category->id);
            return $this->successResponse("the category deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
}