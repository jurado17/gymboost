<?php
namespace App\Helper;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount(): int
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->sum('quantity');
        } else {
            return array_reduce(self::getCookieCartItems(), fn ($carry, $item) => $carry + $item['quantity'], 0);
        }
    }

    public static function getCartItems()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(function (CartItem $item) {
                return [
                    'product_id' => $item->product_id,
                    'weight_id' => $item->weight_id,
                    'flavour_id' => $item->flavour_id,
                    'quantity' => $item->quantity,
                    'final_price' => $item->final_price,
                ];
            })->toArray();
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function setCookieCartItems(array $cartItems)
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);
    }

    public static function saveCookieCartItems()
    {
        $user = auth()->user();
        $userCartItems = CartItem::where('user_id', $user->id)
            ->get()
            ->keyBy(function ($item) {
                return "{$item->product_id}_{$item->weight_id}_{$item->flavour_id}";
            });

        $savedCartItems = [];
        foreach (self::getCookieCartItems() as $cartItem) {
            $key = "{$cartItem['product_id']}_{$cartItem['weight_id']}_{$cartItem['flavour_id']}";

            if (isset($userCartItems[$key])) {
                $userCartItems[$key]->update([
                    'quantity' => $cartItem['quantity'],
                    'final_price' => $cartItem['final_price']
                ]);
            } else {
                $savedCartItems[] = [
                    'user_id' => $user->id,
                    'product_id' => $cartItem['product_id'],
                    'weight_id' => $cartItem['weight_id'],
                    'flavour_id' => $cartItem['flavour_id'],
                    'quantity' => $cartItem['quantity'],
                    'final_price' => $cartItem['final_price'],
                ];
            }
        }

        if (!empty($savedCartItems)) {
            CartItem::insert($savedCartItems);
        }

        // Limpia el carrito de la cookie
        self::setCookieCartItems([]);
    }

    public static function moveCartItemsIntoDb()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            $existingCartItem = CartItem::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'weight_id' => $cartItem['weight_id'],
                'flavour_id' => $cartItem['flavour_id'],
            ])->first();

            if (!$existingCartItem) {
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'weight_id' => $cartItem['weight_id'],
                    'flavour_id' => $cartItem['flavour_id'],
                    'quantity' => $cartItem['quantity'],
                    'final_price' => $cartItem['final_price'],
                ];
            }
        }

        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

    public static function getProductsAndCartItems()
    {
        $cartItems = self::getCartItems();

        $productIds = Arr::pluck($cartItems, 'product_id');
        $products = Product::whereIn('id', $productIds)->with('product_images')->get();

        $cartItems = collect($cartItems)->mapWithKeys(function ($item) {
            return [$item['product_id'] => $item];
        });

        return [$products, $cartItems];
    }
}
