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

                $info = $item['dish']['info'] || $item['card']['info'];

                CartItem::insert([
                    'user_id' => $user->id,
                    'dish_id' => $info['id'],
                    'quantity' => 1,
                    'price' => $info['price']/100,
                    'image_id' => $info['imageId'] // Assuming image_id is part of the info
                ]);
            }

            return response()->json(['message' => 'Cart synced successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $cartItem = $request->input('cart');

            $details = $cartItem['dish']['info'] ?? $cartItem['card']['info'] ?? null;
            
            CartItem::create([
                'user_id' => $user->id,
                'dish_id' => $details['id'],
                'quantity' => 1,
                'price' => $details['price']/100,
                'image_id' => $details['imageId'] ?? null, // Assuming image_id is part of the details
            ]);

            return response()->json(['message' => 'Item added to cart successfully'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
