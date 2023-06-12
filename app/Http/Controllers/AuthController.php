<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

        //creación de usuarios
        public function create(Request $request){
            $validator = \Validator::make($request->input(), User::$rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }
            $user = User::create([
                'name' => $request->name, 'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User createed successfully',
                //'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
    
        }
        
    

        /**
     * Display the specified resource.
     */
    /**
     * Mostrar la información de restaurant
     * @OA\Get (
     *     path="/api/auth/login",
     *     tags={"Login"},
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
     *         )
     *     ),
     *      
     * )
     */
        public function login(Request $request){
            $validator = \Validator::make($request->input(), User::$loginRules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'status' => false,
                    'errors' => ['Unauthorized']
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User logged in succesfully',
                'data' => $user,
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
    
        }

        public function logout(){
            auth()->user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'User logged out succesfully',
            ], 200);
    
        }


}
