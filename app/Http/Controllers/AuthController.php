<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function user($id){
        return User::find($id);
    }
    public function users(Request $request){
        return User::all();
    }
    public function register(Request $request){
        $formData = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:10|max:255'
        ]);

        $user = User::create($formData);

        $token = $user->createToken($request->name);

        return [
            'user' => $user->name,
            'token' => $token->plainTextToken,
            'message' => 'User created successfully'
        ];
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:10|max:255'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return [
                'message' => 'Invalid credentials'
            ];
        }
        $token = $user->createToken($user);
        return [
            'user' => $user,
            'token' => $token->plainTextToken,
            'message' => 'User logged in successfully'
        ];
    }
    public function logout(Request $request){

        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
