<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Resources\RestaurantResource;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $restaurants = RestaurantResource::collection(Restaurant::all());
        return $restaurants;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(Restaurant::$rules);

        $image = $request->file('image_path');
        $image_name = $image->getClientOriginalName();
        $image->move(public_path('Restaurants'), $image_name);

        $restaurant = Restaurant::create([
            'name' => $request->name,
            'image_path' => $image_name,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'category_id' => $request->category_id,
        ]);

        $data = [
            'message' => 'Restaurant created successfully',
            'restaurant' => $restaurant
        ];

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        //
        return RestaurantResource::make($restaurant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
        if($request->hasFile('image_path')) {
            if(file_exists(public_path('Restaurants/'.$restaurant->image_path))) {
                unlink(public_path('Restaurants/'.$restaurant->image_path));
            }
            $image = $request->file('image_path');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('Restaurants'), $image_name);

            $restaurant->image_path = $image_name;
        }

        // if($request->has('name')) {
        //     $restaurant->name = $request->name;
        // }

        // if($request->has('description')) {
        //     $restaurant->description = $request->description;
        // }

        // if($request->has('address')) {
        //     $restaurant->address = $request->address;
        // }

        // if($request->has('phone')) {
        //     $restaurant->phone = $request->phone;
        // }

        $restaurant->fill($request->only([
            'name',
            'description',
            'address',
            'phone',
        ]));


        $restaurant->save();
        
        $data = [
            'message' => 'Restaurant updated successfully',
            'restaurant' => $restaurant
        ];

        return $data;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        //
        $restaurant->delete();
        
        if(file_exists(public_path('Restaurants/'.$restaurant->image_path))) {
            unlink(public_path('Restaurants/'.$restaurant->image_path));
        }

        $data = [
            'message' => 'Restaurant deleted successfully',
        ];

        return $data;
    }
}