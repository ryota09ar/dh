<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(){
        return view('user.create');
    }

    public function store(CreateUserRequest $request){
        $validated=$request->validated();

        User::create([
            "family_name"=>$validated["family_name"],
            "first_name"=>$validated['first_name'],
            "email"=>$validated['email'],
            "password"=>Hash::make($validated['password'])
        ]);

        return redirect()->route('user.createComplete');
    }

    public function edit()
    {
        return view("user.edit");
    }

    public function update(EditUserRequest $request){
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            $user->password = Hash::make($request['password']);
            $user->save();
            return redirect()->route('user.updateComplete');
        } else {
            // ユーザーが見つからない場合の処理
            return redirect()->back();
        }
    }

    public function createComplete(){
        return view('user.createComplete');
    }

    public function updateComplete()
    {
        return view('user.updateComplete');
    }

    public function home(){
        return view('user.home');
    }
}
