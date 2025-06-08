<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function getUser()
    {
        try {
            $user = User::find(auth()->user()?->id);

            if (!$user) {
                return response()->json(['message' => null], 401);
            }

            return response()->json([
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
