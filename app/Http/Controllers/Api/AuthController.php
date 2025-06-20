<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|min:8',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $user = User::where('email', $request->email)->first();

            Auth::login($user);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAuthenticated(Request $request)
    {
        try {
            if (Auth::check()) {
                return response()->json([
                    'authenticated' => true,
                    'user' => Auth::user(),
                ], 200);
            } else {
                return response()->json(['authenticated' => false], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            Auth::login($user);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Logout successful',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Logout failed',
            ], 500);
        }
    }
}
