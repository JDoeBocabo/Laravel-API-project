<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponses;
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
        try {
            $formData = $request->validate([
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|confirmed|min:10|max:255'
            ]);

            $user = User::create($formData);

            $token = $user->createToken($request->name);

            return ApiResponses::sendSuccessResponse('User created', null, true, [
                'user' => $user,
                'token' => $token->plainTextToken
            ]);
        } catch (\Exception $e) {
            return ApiResponses::sendErrorResponse('Something went wrong', $e);
        }

    }
    public function login(Request $request){
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:10|max:255'
            ]);
            $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                throw new \Exception('Invalid credentials', 401);
            }
            $token = $user->createToken($user);

            return ApiResponses::sendSuccessResponse('User logged in', true, [
                'user' => $user,
                'token' => $token->plainTextToken
            ]);

        } catch (\Exception $e) {
            return ApiResponses::sendErrorResponse('Something went wrong', $e);
        }

    }
    public function logout(Request $request){

        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
