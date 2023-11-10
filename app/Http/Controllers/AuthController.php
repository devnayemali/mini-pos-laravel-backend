<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    function login(UserRequest $request)
    {
        $user = (new User())->getUserByEmailOrPhone($request->all());
        if ($user && Hash::check($request->input('password'), $user->password)){
           $userData['token'] = $user->createToken($user->email)->plainTextToken;
           $userData['email'] = $user->email;
           $userData['name'] = $user->name;
           $userData['phone'] = $user->phone;
           $userData['photo'] = $user->photo;
           return response()->json($userData);
        }
        throw ValidationException::withMessages([
            'error' => ['The Provide credentials are incorrect']
        ]);
    }

    function logout() 
    {
        auth()->user()->tokens()->delete();
        return response()->json(['msg' => 'You have successfully logout']);
    }
}
