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
    /**
     * Listado de las catogorias
     * @OA\Get (
     *     path="/api/categories",
     *     tags={"Category"},
     *    security={{"bearer":{}}},
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
     *                     @OA\Property(
     *                         property="imagen_path",
     *                         type="string",
     *                         example="1.png"
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
        $categories=CategoryResource::collection(Category::all());
        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Registrar la información de la categoria
     * @OA\Post (
     *     path="/api/categories",
     *     tags={"Category"},
     *     
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
     *                      @OA\Property(
     *                          property="imagen_path",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Aderson Felix",
     *                     "imagen_path":"1.png"
     *                }
     *             )
     *         )
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The name field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
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
    /**
     * Mostrar la información de una categoria 
     * @OA\Get (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * 
     *      security={{"bearer":{}}},
     * 
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      
     * )
     */
    public function show(Category $category)
    {
        //
        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Actualizar la información de la categoria
     * @OA\Put (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
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
     *                      @OA\Property(
     *                          property="imagen_path",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name": "Aderson Felix Editado",
     *                     "imagen_path": "1.png"
     *                }
     *             )
     *         )
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix Editado"),
     *              @OA\Property(property="imagen_path", type="string", example="1.png"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The name field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
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
    /**
     * Eliminar la información de una categoria
     * @OA\Delete (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      security={{"bearer":{}}},
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