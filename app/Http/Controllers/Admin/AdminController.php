<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {

        $users = User::get();
        $countUsers = User::get()->count();
        $userAddress = UserAddress::get();
        $brands = Brand::get();
        $categories = Category::get();  
        $products = Product::with('category', 'brand', 'product_images')->get();
        $orders = Order::get();
        $countOrders = Order::get()->count();
        $countSellProducts  = OrderItem::get()->sum('quantity');
        $orderItems = OrderItem::get();
        $payment = Payment::get();
        $paymentIncome = Payment::sum('amount');

        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
            'userAddress' => $userAddress,
            'brands' => $brands,
            'categories' => $categories,
            'products' => $products,
            'orders' => $orders,
            'orderItems' => $orderItems,
            'payment' => $payment,
            'countUsers' => $countUsers,
            'countOrders' => $countOrders,
            'paymentIncome' => $paymentIncome,  
            'countSellProducts' => $countSellProducts
        ]);
        
    }
}
