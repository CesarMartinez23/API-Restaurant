<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Resources\RestaurantResource;
/**
* @OA\Info(
*             title="API AppRestaurant", 
*             version="1.0",
*             description="Listado de URI´S de la API restaurante"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
* 
* 
*/
class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Listado de restaurants
     * @OA\Get (
     *     path="/api/restaurants",
     *     tags={"Restaurant"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Aderson"
     *                     ), 
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string",
     *                         example="1.png"
     *                     ), 
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="Datos"
     *                     ),
     *                     @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="etc"
     *                     ),
     * 
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         example="60020601"
     *                     ),
     *                     @OA\Property(
     *                         property="categori_id",
     *                         type="sinteger",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
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

    /**
     * Registrar la información del restautant
     * @OA\Post (
     *     path="/api/restaurants",
     *     tags={"Restaurant"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string"
     *                     ), 
     *                     @OA\Property(
     *                         property="description",
     *                         type="string"
     *                     ), 
     * 
     *                     @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="etc"
     *                     ),
     * 
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         example="60020601"
     *                     ),
     *                     @OA\Property(
     *                         property="categori_id",
     *                         type="integer",
     *                         example="1"
     *                     ),
     *                 ),
     *                 example={
     *                     "name":"Aderson Felix",
     *                     "imagen_path":"1.png",
     *                     "description":"datos",
     *                     "address":"datos",
     *                     "phone":"60020601",
     *                     "category_id":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="address", type="string", example="datos"),
     *              @OA\Property(property="phone", type="string", example="60020601"),
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The description field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
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
    /**
     * Mostrar la información de restaurant
     * @OA\Get (
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="address", type="string", example="datos"),
     *              @OA\Property(property="phone", type="string", example="60020601"),
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      
     * )
     */
    public function show(Restaurant $restaurant)
    {
        //
        return RestaurantResource::make($restaurant);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Actualizar la información de restaurant
     * @OA\Put (
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string"
     *                     ), 
     *                     @OA\Property(
     *                         property="description",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="address",
     *                         type="string",
     *                     ),
     * 
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="categori_id",
     *                         type="integer"
     *                     ),
     *                      
     *                 ),
     *                 example={
     *                     "name":"Aderson Felix",
     *                     "imagen_path":"1.png",
     *                     "description":"datos",
     *                     "address":"datos",
     *                     "phone":"60020601",
     *                     "category_id":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *               @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="address", type="string", example="datos"),
     *              @OA\Property(property="phone", type="string", example="60020601"),
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The description field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
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
    /**
     * Eliminar la información de restaurant
     * @OA\Delete (
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="NO CONTENT"
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo realizar correctamente la operación"),
     *          )
     *      )
     * )
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