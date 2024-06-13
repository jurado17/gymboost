<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\OrderInvoice;
use App\Models\CartBackup;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\StockProduct;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;


class CheckoutController extends Controller
{
    public function index(Request $request, $total)
    {
        $user = $request->user();
        if ($user) {
            $userAddress = UserAddress::whereUserId($user->id)->with('user')->get();
            $cartItems = CartItem::whereUserId($user->id)->with('product')->get();

            return Inertia::render('User/Checkout', [
                'userAddress' => $userAddress,
                'cartItems' => $cartItems,
                'total' => $total
            ]);
        } else {
            return redirect()->route('register');
        }
    }


    private function createOrderItems($user, $order)
    {
        $cartItems = CartItem::whereUserId($user->id)->get();
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'type' => 'Domicilio',
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'weight_id' => $cartItem->weight_id,
                'flavour_id' => $cartItem->flavour_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->final_price /$cartItem->quantity,
                'created_by' => $user->id,
            ]);
            $cartItem->delete();
        }
    }

    private function saveNewAddress($user, $newAddress)
    {
        $addressCount = UserAddress::where('isMain', 1)->count();
        if ($addressCount > 0) {
            UserAddress::where('isMain', 1)->update(['isMain' => 0]);
        }

        $address = new UserAddress();
        $address->type = 'Domicilio';
        $address->address1 = $newAddress['address1'];
        $address->city = $newAddress['city'];
        $address->zip_code = $newAddress['zip_code'];
        $address->country_code = $newAddress['country_code'];
        $address->province = $newAddress['province'];
        $address->user_id = $user->id;
        $address->isMain = 1;
        $address->save();
    }

    private function createOrder($request, $user, $address, $sessionId)
    {
        $order = new Order();
        $order->status = 'paid';
        $order->total_price = $request->total;
        $order->session_id = $sessionId;
        $order->created_by = $user->id;
        $order->user_address_id = $address->user_id;
        $order->save();

        return $order;
    }

    private function createPayment($user, $order)
    {
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'status' => 'paid',
            'type' => 'paypal',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        Payment::create($paymentData);
    }

    private function createCartBackup($user, $products, $carts)
{
    $mergedData = [];

    foreach ($carts as $cartItem) {
        foreach ($products as $product) {
            if ($cartItem['product_id'] == $product['id']) {
                $mergedData[] = array_merge(
                    $product,
                    ["name" => $product["name"], 'price' => $product['price']],
                    $cartItem
                );
            }
        }
    }

    $cartBackup = new CartBackup(
        [
            'user_id' => $user->id,
            'cart_data' => $mergedData
        ]
    );

    // Asegúrate de que `cart_data` es un array antes de devolverlo a la vista
    return $cartBackup->cart_data;
}

    public function decreaseStock($cartItems)
    {

        foreach ($cartItems as $cartItem) {
            $stockProduct = StockProduct::where('product_id', $cartItem['product_id'])
                ->where('weight_id', $cartItem['weight_id'])
                ->where('flavour_id', $cartItem['flavour_id'])
                ->get();

                // dd($cartItem, $stockProduct);
            foreach ($stockProduct as $stockProduct) {
                $stockProduct['quantity'] -= $cartItem['quantity'];
                $stockProduct->save();
            }

        }
    }


    public function success(Request $request)
    {
        // Obtener detalles del usuario
        $user = $request->user();
        $products = $request->products;
        $cartItems = $request->cartItems;
        $address = $request->address;
        $sessionId = session()->getId();

        //resta el stock de los productos
        $this->decreaseStock($cartItems);

        // // Guardar la nueva dirección del usuario
        // $this->saveNewAddress($user, $address);

        //crea un backup de la orden para mostrarla en la vista orderSummary
        $cartBackup = $this->createCartBackup($user, $products, $cartItems);

        // Crear la orden en la base de datos
        $order = $this->createOrder($request, $user, $address, $sessionId);

        // Crear los elementos de la orden
        $this->createOrderItems($user, $order);

        // Crear el pago en la base de datos
        $this->createPayment($user, $order);

        // Enviar correo electrónico de confirmación (opcional)
        Mail::to($user->email)->send(new OrderInvoice($order));


        return Inertia::render('User/OrderSummary', ['order' => $order, 'cartData' => $cartBackup, 'userAddress' => $address]);
    }


    public function cancel(Request $request)
    {
        dd('SE HA CANCELADO EL PAGO');
        return Inertia::render('User/CheckoutCancel');
    }
}
