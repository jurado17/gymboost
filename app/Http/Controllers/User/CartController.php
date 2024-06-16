<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Flavour;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\StockProduct;
use App\Models\UserAddress;
use App\Models\Weight;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::whereUserId($user->id)
                ->with(['product.product_images', 'weight', 'flavour'])
                ->get();
            $userAddress = UserAddress::whereUserId($user->id)->where('IsMain', 1)->first();

            $stockProducts = StockProduct::whereIn('product_id', $cartItems->pluck('product_id'))
                ->whereIn('weight_id', $cartItems->pluck('weight_id'))
                ->whereIn('flavour_id', $cartItems->pluck('flavour_id'))
                ->get();


            return Inertia::render('User/CartList', [
                'cartItems' => $cartItems,
                'userAddress' => $userAddress,
                'stockProducts' => $stockProducts
            ]);
        } else {
            list($products, $cartItems) = Cart::getProductsAndCartItems();
    
            $cartItems = $products->map(function ($product) use ($cartItems) {
                $stockProduct = StockProduct::where('product_id', $product->id)
                    ->where('weight_id', $cartItems[$product->id]['weight_id'])
                    ->where('flavour_id', $cartItems[$product->id]['flavour_id'])
                    ->with(['weight', 'flavour'])
                    ->first();
    
                return [
                    'product' => $product,
                    'quantity' => $cartItems[$product->id]['quantity'],
                    'final_price' => $product->price,
                    'weight' => $stockProduct->weight,
                    'flavour' => $stockProduct->flavour
                ];
            });
    
            return Inertia::render('User/CartList', ['cartItems' => $cartItems]);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string'
        ]);

        $coupon = Promotion::where('discount_code', $request->discount_code)
                            ->where('isActive', true)
                            ->where(function ($query) {
                                $query->where('active_until', '>=', now())
                                      ->orWhereNull('active_until');
                            })
                            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Invalid or expired coupon.'], 400);
        }

        $discount = $coupon->porcentual_discount;
        return response()->json(['discount' => $discount, 'message' => 'Coupon applied successfully!']);
    }

    
public function store(Request $request, Product $product, $weight, $flavour, $quantity, $price)
{
    $user = $request->user();
    $reservedUntil = now()->addMinutes(20);

    DB::beginTransaction();

    try {
        $stockProduct = StockProduct::where([
            'product_id' => $product->id,
            'weight_id' => $weight,
            'flavour_id' => $flavour,
        ])->lockForUpdate()->first();

        if (!$stockProduct || $stockProduct->quantity < $quantity) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'No hay suficiente stock disponible.']);
        }

        $stockProduct->decrement('quantity', $quantity);
        if ($stockProduct->quantity < 20) {
            $stockProduct->isStocked = false;
        }
        $stockProduct->save();

        if ($user) {
            $cartItem = CartItem::where([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'weight_id' => $weight,
                'flavour_id' => $flavour,
            ])->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
                $cartItem->update([
                    'reserved_until' => $reservedUntil, 
                    'final_price' => $price * $cartItem->quantity
                ]);
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'weight_id' => $weight,
                    'flavour_id' => $flavour,
                    'quantity' => $quantity,
                    'final_price' => $price,
                    'reserved_until' => $reservedUntil,
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id && $item['weight_id'] == $weight && $item['flavour_id'] == $flavour) {
                    $item['quantity'] += $quantity;
                    $item['reserved_until'] = $reservedUntil->timestamp;
                    $item['final_price'] = $price * $item['quantity'];
                    $isProductExists = true;
                    break;
                }
            }

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'weight_id' => $weight,
                    'flavour_id' => $flavour,
                    'quantity' => $quantity,
                    'final_price' => $price,
                    'reserved_until' => $reservedUntil->timestamp,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Producto añadido al carrito correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Ocurrió un error al añadir el producto al carrito.']);
    }
}

public function update(Request $request, Product $product, Weight $weight, Flavour $flavour)
{
    $quantity = $request->integer('quantity');
    $user = $request->user();
    $stockProduct = StockProduct::where([
        'product_id' => $product->id,
        'weight_id' => $weight->id,
        'flavour_id' => $flavour->id
    ])->first();

    if (!$stockProduct) {
        return redirect()->back()->withErrors(['error' => 'Stock product not found']);
    }

    // Verificar si hay suficiente stock
    if ($stockProduct->quantity < 20) {
        $stockProduct->isStocked = false;
        $stockProduct->save();
        return redirect()->back()->withErrors(['error' => 'Insufficient stock to increase the quantity']);
    }

    if ($user) {
        $cartItem = CartItem::where([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'weight_id' => $weight->id,
            'flavour_id' => $flavour->id
        ])->first();

        if ($cartItem) {
            $oldQuantity = $cartItem->quantity;
            $quantityDifference = $quantity - $oldQuantity;

            // Verificar si hay suficiente stock para la diferencia de cantidad
            if ($stockProduct->quantity - $quantityDifference < 20) {
                return redirect()->back()->withErrors(['error' => 'Insufficient stock to increase the quantity']);
            }

            $cartItem->update([
                'quantity' => $quantity,
                'final_price' => ($cartItem->final_price / $oldQuantity) * $quantity
            ]);

            $stockProduct->decrement('quantity', $quantityDifference);

            // Actualizar isStocked basado en la nueva cantidad
            $stockProduct->isStocked = $stockProduct->quantity >= 20;
            $stockProduct->save();
        } else {
            return redirect()->back()->withErrors(['error' => 'Cart item not found']);
        }
    } else {
        $cartItems = Cart::getCookieCartItems();
        $found = false;

        foreach ($cartItems as &$item) {
            if ($item['product_id'] === $product->id && $item['weight_id'] == $weight->id && $item['flavour_id'] == $flavour->id) {
                $oldQuantity = $item['quantity'];
                $quantityDifference = $quantity - $oldQuantity;

                // Verificar si hay suficiente stock para la diferencia de cantidad
                if ($stockProduct->quantity - $quantityDifference < 20) {
                    return redirect()->back()->withErrors(['error' => 'Insufficient stock to increase the quantity']);
                }

                $item['quantity'] = $quantity;
                $item['final_price'] = ($item['final_price'] / $oldQuantity) * $quantity;
                $found = true;

                $stockProduct->decrement('quantity', $quantityDifference);

                // Actualizar isStocked basado en la nueva cantidad
                $stockProduct->isStocked = $stockProduct->quantity >= 20;
                $stockProduct->save();

                break;
            }
        }

        if ($found) {
            Cart::setCookieCartItems($cartItems);
        } else {
            return redirect()->back()->withErrors(['error' => 'Cart item not found in cookies']);
        }
    }

    return redirect()->back();
}


    public function delete(Request $request, Product $product, Weight $weight, Flavour $flavour)
    {
        $user = $request->user();

        $stockProduct = StockProduct::where([
            'product_id' => $product->id,
            'weight_id' => $weight->id,
            'flavour_id' => $flavour->id
        ])->first();

        if (!$stockProduct) {
            return redirect()->back()->withErrors(['error' => 'Stock product not found']);
        }

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id, 'weight_id' => $weight->id, 'flavour_id' => $flavour->id])->first();
            if ($cartItem) {
                $quantity = $cartItem->quantity;
                $cartItem->delete();
                $stockProduct->increment('quantity', $quantity);
            } else {
                return redirect()->back()->withErrors(['error' => 'Cart item not found']);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $found = false;
            foreach ($cartItems as $i => $item) {
                if ($item['product_id'] === $product->id && $item['weight_id'] == $weight->id && $item['flavour_id'] == $flavour->id) {
                    $quantity = $item['quantity'];
                    array_splice($cartItems, $i, 1);
                    $found = true;
                    $stockProduct->increment('quantity', $quantity);
                    break;
                }
            }
            if ($found) {
                Cart::setCookieCartItems($cartItems);
            } else {
                return redirect()->back()->withErrors(['error' => 'Cart item not found in cookies']);
            }
        }
        return redirect()->back();
    }
}
