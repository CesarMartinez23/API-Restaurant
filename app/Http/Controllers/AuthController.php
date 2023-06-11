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




}
