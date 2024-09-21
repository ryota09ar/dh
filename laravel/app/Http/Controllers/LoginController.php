<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(){
        return view('login.view');
    }

    public function login(LoginUserRequest $request){
        $credentials=$request->validated();

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route("user.home");
        }


        return back()->withErrors([
            'invalid' => 'メールアドレスかパスワードが正しくありません',
        ]);
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('login');
    }
}
