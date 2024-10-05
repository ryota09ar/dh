<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
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

        if(Auth::guard('web')->attempt($credentials)){
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

    public function adminShow(){
        return view('login.admin');
    }
    public function adminLogin(LoginAdminRequest $request){
        $credentials=$request->validated();
        if(Auth::guard('admin')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route("admin.menu");
        }
        return back()->withErrors([
            'invalid' => 'IDかパスワードが正しくありません',
        ]);
    }

    public function adminLogout(){
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
