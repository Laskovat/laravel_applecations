<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm() {
        return view("auth.register");
    }
    public function register(Request $request ) {
        $data = $request->validate([
            "name"=>"required|string|max:200",
            "email"=>"required|email",
            "password"=>"required|string|min:6|confirmed"
        ]);
        $data['password']= bcrypt($request->password);
        $user = User::create($data);
        return redirect(route("logform"));

    }
    public function loginForm() {
        return view("auth.login");
    }
    public function login(Request $request ) {
        $data = $request->validate([
            "email"=>"required|email",
            "password"=>"required|string|min:6"
        ]);
        $isverify = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if($isverify){
        return redirect(route("allbook"));
        }else{
            return redirect(route("logform"))->with("error","user not found");

        }



    }
    public function logout() {
        Auth::logout();
        return redirect(route("logform"));
    }
    public function allusers() {
        return view("auth.allusers");
    }

}
