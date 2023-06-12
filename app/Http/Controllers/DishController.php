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
    /**
     * Listado de los dishes
     * @OA\Get (
     *     path="/api/dishes",
     *     tags={"Dish"},
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
     *                         property="name",
     *                         type="string",
     *                         example="Aderson"
     *                     ), 
     * 
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string",
     *                         example="1.png"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="Pasta"
     *                     ), 
     *                     @OA\Property(
     *                         property="price",
     *                         type="numeric",
     *                         example="0"
     *                     ),
     * 
     *                     
     *                     @OA\Property(
     *                         property="restaurant_id",
     *                         type="number",
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
        $dishes = DishResource::collection(Dish::all());

        return $dishes;
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Registrar la información del dish
     * @OA\Post (
     *     path="/api/dishes",
     *     tags={"Dish"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ), 
     * 
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string"
     *                     ), 
     *                     @OA\Property(
     *                         property="price",
     *                         type="numeric"
     *                     ),
     * 
     *                     
     *                     @OA\Property(
     *                         property="restaurant_id",
     *                         type="number"
     *                     ),
     *                 ),
     *                 example={
     *                     "name":"Aderson Felix",
     *                     "imagen_path":"P1.png",
     *                     "description":"datos",
     *                     "price":"10",
     *                     "restaurant_id": "1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="price", type="number", example="0"),
     *              @OA\Property(property="restaurant_id", type="integer", example="1"),
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
    /**
     * Mostrar la información de un dish
     * @OA\Get (
     *     path="/api/dishes/{id}",
     *     tags={"Dish"},
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
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="price", type="number", example="0"),
     *              @OA\Property(property="restaurant_id", type="integer", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      
     * )
     */
    public function show(Dish $dish)
    {
        //
        return DishResource::make($dish);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Actualizar la información del dish
     * @OA\Put (
     *     path="/api/v1/dishes/{id}",
     *     tags={"Dish"},
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
     *                         property="name",
     *                         type="string"
     *                     ), 
     * 
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string"
     *                     ), 
     *                     @OA\Property(
     *                         property="price",
     *                         type="numeric"
     *                     ),
     * 
     *                     
     *                     @OA\Property(
     *                         property="restaurant_id",
     *                         type="number"
     *                     ),
     *                 ),
     *                 example={
     *                     "name":"Aderson Felix",
     *                     "imagen_path":"P1.png",
     *                     "description":"datos",
     *                     "price":"10",
     *                     "restaurant_id": "1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="description", type="string", example="pasta"),
     *              @OA\Property(property="price", type="number", example="0"),
     *              @OA\Property(property="restaurant_id", type="integer", example="1"),
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
    /**
     * Eliminar la información de un dish
     * @OA\Delete (
     *     path="/api/dishes/{id}",
     *     tags={"Dish"},
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