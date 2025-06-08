<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\User;

class CartController extends Controller
{
    public function sync(Request $request)
    {
        try{

            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            if(empty($request->input('cart'))) {
                return response()->json(['message' => 'No cart items provided'], 400);
            }

            $cartItems = $request->input('cart', []);

            foreach ($cartItems as $item) {

                $info = $item['dish']['info'] ?? $item['card']['info'] ?? null;

                CartItem::insert([
                    'user_id' => $user->id,
                    'dish_id' => $info['id'],
                    'quantity' => 1,
                    'price' => $info['price']/100,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['message' => 'Cart synced successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
