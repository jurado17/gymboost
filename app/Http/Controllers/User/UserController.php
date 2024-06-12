<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Flavour;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockProduct;
use App\Models\UserAddress;
use App\Models\Weight;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::with('brand', 'category', 'product_images', 'promotion')->limit(4)->get();
        $weights = Weight::get();
        $flavours = Flavour::get();
        $stockProducts = StockProduct::get();

        return Inertia::render('User/Index', [
            'products' => $products,
            'weights' => $weights,
            'flavours' => $flavours,
            'stockProducts' => $stockProducts,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function profile()
    {
        return Inertia::render('User/Account/VistaGeneral');
    }

    public function profileUserData(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('User/Account/UserPersonalData', [
            'user' => $user,
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function profileAddress(Request $request)
    {
        $user = $request->user();
        $userAddress = UserAddress::where('user_id', $user->id)->get();

        return Inertia::render('User/Account/UserAddress', [
            'user' => $user,
            'userAddress' => $userAddress,
        ]);
    }

    public function store(Request $request)
    {

        $userAddress = new UserAddress();
        $userAddress->type = 'Domicilio';
        $userAddress->address1 = $request->address1;
        $userAddress->city = $request->city;
        $userAddress->province = $request->province;
        $userAddress->zip_code = $request->zip_code;
        $userAddress->country_code = $request->country_code;
        $userAddress->user_id = $request->type;
        $userAddress->user_id = $request->user_id;
        $userAddress->save();

        return redirect()->back()->with('success', 'Direccion añadida');
    }

    public function update(Request $request, $id)
    {

        $userAddress = UserAddress::findOrFail($id);

        $userAddress->type = 'Domicilio';
        $userAddress->address1 = $request->address1;
        $userAddress->city = $request->city;
        $userAddress->province = $request->province;
        $userAddress->zip_code = $request->zip_code;
        $userAddress->country_code = $request->country_code;
        $userAddress->user_id = $request->type;
        $userAddress->user_id = $request->user_id;
        $userAddress->update();

        return redirect()->back()->with('success', 'Direccion modificada');
    }

    public function delete($id)
    {

        $userAddress = UserAddress::findOrFail($id);
        $userAddress->delete();

        return redirect()->back()->with('success', 'Direccion Eliminada');
    }

    public function profileUserOrders(Request $request)
    {
        $user = $request->user();

        $userAddress = UserAddress::where('user_id', $user->id)->get();

        $orders = Order::where('created_by', $user->id)->get();

        $ordersUserIds = $orders->pluck('id');
        $orderItemUser = OrderItem::whereIn('order_id', $ordersUserIds)
            ->with('product') // Cargar la relación product
            ->get();



        return Inertia::render('User/Account/UserOrders', [
            'user' => $user,
            'userAddress' => $userAddress,
            'orders' => $orders,
            'orderItemUser' => $orderItemUser,
        ]);
    }

    public function orderDetails($id){
        $orderItem = OrderItem::where('order_id', $id)->with('product.product_images','product.brand','product.category','stockItem.weight','stockItem.flavour')->get();

        $ordersUserIds = $orderItem->pluck('order_id');
        $order = Order::whereIn('id',$ordersUserIds)->get();
        
        return Inertia::render('User/Account/OrderDetail', [
            'order' => $order,
            'orderItem' => $orderItem,
        ]);
        
    }
}
