<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            "name" => ["required", "max:255", "string"],
            "email" => ["required", "max:255", "min:3", "string", "unique:users,email"],
            "password" => ["required", "string", "confirmed"],
        ]);

        $user = User::create([
            "name" => $fields["name"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),
        ]);

        $token = $user->createToken('default-token')->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token,
        ];

        return response($response, 201);
    }
}
