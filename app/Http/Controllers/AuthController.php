<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Handle admin login and generate a token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find admin by email manually from the XML file
        $admins = Admin::all();
        $admin = null;
        foreach ($admins as $a) {
            if ($a['email'] === $request->email) {
                $admin = Admin::find($a['id']); // Return as an instance of Admin class
                break;
            }
        }

        // Check if the admin exists and the password is correct
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate a custom token for the admin and save it in tokens.xml
        $token = Str::random(60);
        Token::create([
            'admin_id' => $admin->id,
            'token' => $token,
            'created_at' => now()->toDateTimeString(),
        ]);

        // Return token and admin data (without sensitive info like password)
        return response()->json([
            'admin' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'email' => $admin->email,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Handle admin logout by revoking the current access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Invalidate the current token (if provided)
        if ($request->bearerToken()) {
            Token::deleteByToken($request->bearerToken());
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}