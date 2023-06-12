<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

/**
     * @OA\POST(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Register",
     *     description="Register to system.",
     *     operationId="register",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User Name",
     *                     type="string",
     *                     example="Jhon Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string",
     *                     example="jhon@email.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string",
     *                     example="12345678"
     *                
     *                 ),
     *                 required={"name", "email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
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
     * @OA\POST(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login to system.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
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
