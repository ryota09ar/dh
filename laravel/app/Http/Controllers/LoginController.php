<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(){
        return view('login.view');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            "email"=>["required"],
            "password"=>["required"]
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route("user.home");
        }

        return back()->withErrors([
            'password' => 'メールアドレスかパスワードが正しくありません',
        ]);
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('login');
    }
}
