<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            "email" => ["required", "max:255", "min:3", "string"],
            "password" => ["required", "string"],
        ]);

        $attempt = Auth::attempt($fields);
        $response = ["success" => $attempt ?? 0];
        return response($response);
    }
}
