<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use App\Http\Resources\DishResource;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dishes = DishResource::collection(Dish::all());

        return $dishes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(Dish::$rules);

        $image = $request->file('image_path');
        $image_name = $image->getClientOriginalName();
        $image->move(public_path('Dishes'), $image_name);

        $dish = Dish::create([
            'name' => $request->name,
            'image_path' => $image_name,
            'description' => $request->description,
            'price' => $request->price,
            'restaurant_id' => $request->restaurant_id,
        ]);

        $data = [
            'message' => 'Dish created successfully',
            'dish' => $dish
        ];

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish)
    {
        //
        return DishResource::make($dish);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dish)
    {
        //
        if($request->hasFile('image_path')) {
            if(file_exists(public_path('Dishes/'.$dish->image_path))) {
                unlink(public_path('Dishes/'.$dish->image_path));
            }
            $image = $request->file('image_path');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('Dishes'), $image_name);
            $dish->image_path = $image_name;
        }

        $dish->fill($request->only([
            'name',
            'description',
            'price',
        ]));

        $dish->save();

        $data = [
            'message' => 'Dish updated successfully',
            'dish' => $dish
        ];

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        //
        $dish->delete();

        if(file_exists(public_path('Dishes/'.$dish->image_path))) {
            unlink(public_path('Dishes/'.$dish->image_path));
        }

        $data = [
            'message' => 'Dish deleted successfully',
        ];

        return $data;
    }
}