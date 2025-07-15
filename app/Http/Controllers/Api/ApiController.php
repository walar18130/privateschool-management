<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ApiController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name"=> "required|string",
            "email"=> "required|email|unique:users,email",
            "password"=> "required|confirmed",
        ]);
        User::create($request->all());
        return response()->json([
            "status" => true,
            "message"=>"User registerd successfully",
        ]);}
        public function login(Request $request){
            $request->validate([
                "email"=> "required|email",
                "password"=> "required"
            ]);
           
            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('myToken')->plainTextToken;
        
                    return response()->json([
                        'status' => true,
                        'message' => 'Logged in successfully',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password did not match',
                    ], 401); // 401 Unauthorized
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is invalid',
                ], 401); // 401 Unauthorized
            }
        }
       
        
        public function profile(){
            $userdata= auth()->user();
            return response()->json([
                "status"=>true,
                "message"=>"profile data",
                "data" =>$userdata,
                "id"=> auth()->user()->id
            ]);
        }
        public function logout(){
            auth()->user()->tokens()->delete();
            return response()->json([
                "status"=>true,
                "message"=> "User logged out"
            ]);
        }
    }

