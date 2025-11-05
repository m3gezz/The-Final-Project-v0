<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate(
            [
                'first_name' => 'required|string|min:5|max:20',
                'last_name' => 'required|string|min:5|max:20',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:5|max:50|confirmed',
                'bio' => 'sometimes|string|max:255',
                'avatar_url' => 'sometimes|string',
                'skills' => 'sometimes|array',
                'admin' => 'sometimes|boolean',//remember to hide this using unset($fields['admin']);
                'terms' => 'required|accepted',
            ]
        );

        $user = User::create($fields);
        $user->refresh();

        $data = [
            "user" => $user,
            "token" => $user->createToken($user->first_name)->plainTextToken,
        ];

        return response()->json($data, 200);
    }

    public function login(Request $request) {
        $fields = $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:5|max:50'
            ]
        );

        $user = User::where('email', $fields['email'])->first();

        if (!Hash::check($fields['password'], $user->password)) {
            $data = [
                "message" => "The password is incorrect.",
                "errors" => [
                    "password" => "The password is incorrect."
                ]
            ];
            
            return response()->json($data, 401);
        }

        $data = [
            'user' => $user,
            'token' => $user->createToken($user->first_name)->plainTextToken,
        ];

        return response()->json($data, 200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        $data = ['message' => 'Logged out successfully'];

        return response()->json($data, 200);
    }
}
