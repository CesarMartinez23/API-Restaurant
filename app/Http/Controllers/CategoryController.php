<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories=CategoryResource::collection(Category::all());
        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(Category::$rules);

        $image = $request->file('image_path');
        $image_name = $image->getClientOriginalName();
        $image->move(public_path('Categories'), $image_name);

        $category = Category::create([
            'name'=>$request->name,
            'image_path'=>$image_name,
        ]);

        $data = [
            'message' => 'Category created successfully',
            'category' => $category
        ];

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        // $request->validate(Category::$rules);

        // if($request->has('name')){
        //     $category->name = $request->name;
        // }

        $category->fill($request->only(['name']));

        if($request->has('image_path')){
            if(file_exists(public_path('Categories/'.$category->image_path))) {
                unlink(public_path('Categories/'.$category->image_path));
            }
            $image = $request->file('image_path');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('Categories'), $image_name);
            $category->image_path = $image_name;
        }

        $category->save();

        $data = [
            'message' => 'Category updated successfully',
            'category' => $category
        ];

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        
        if(file_exists(public_path('Categories/'.$category->image_path))){
            unlink(public_path('Categories/'.$category->image_path));
        }

        $data = [
            'message' => 'Category deleted successfully',
        ];

        return $data;
    }
}