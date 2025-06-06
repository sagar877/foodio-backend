<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CartController extends Controller
{
    public function sync(Request $request)
    {
        try{
            $user = auth()->user();

            info($request);





        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
