<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            "name"=>"required|string|max:200",
            "email"=>"required|email",
            "password"=>"required|string|confirmed",
        ]);
        if($validator->fails()){
            return response()->json([
                "error"=>$validator->errors()
            ],301);

        }
        $hashedpassword=bcrypt($request->password);

        $token = Str::random(50);
        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$hashedpassword,
            "remember_token"=>$token,
        ]);
        return response()->json([
            "msg"=>"user registered successfully",
            "remember_token"=>"$token"
        ],201);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>"required|email",
            "password"=>"required|string",
        ]);
        if($validator->fails()){
            return response()->json([
                "error"=>$validator->errors()
            ],301);}
        $user = User::where("email",$request->email)->first();
        if($user){
            $check = Hash::check($request->password,$user->password);
                if($check){
                    $token = Str::random(50);
                    $user->update([
                        "remember_token"=>$token
                    ]);
                    return response()->json([
                        "msg"=>"looged in successfully",
                        "your looging token is"=>$token
                    ],201);
                }else{
                    return response()->json(["msg"=>"cradintiols not correct pass "],301);
                }
            }else{
                return response()->json(["msg"=>"cradintiols not correct E"],301);
            }

    }
    public function logout(Request $request){
        $token = $request->header("remember_token");
        if($token != null){
            $user = User::where("remember_token",$token)->first();
            if($user){

                $user->update([
                    "remember_token"=>null
                ]);
                return response()->json([
                    "msg"=>"logged out"
                ],200);
            }else{ return response()->json([
                "msg"=>"token not correct"
            ],303);}
        }else{
            return response()->json([
                "msg"=>"token not found"
            ],303);
        }




    }

}
