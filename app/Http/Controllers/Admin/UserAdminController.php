<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserAdminController extends Controller
{
    public function index()
    {
        $user = User::get();

        return Inertia::render('Admin/Users/Index', ['users' => $user]);
    }

    public function show($userId)
    {
        $user = User::find($userId);
        $userAddress = UserAddress::where('user_id', $userId)->get();
        $ordersUser = Order::where('created_by', $userId)
        ->with(['order_items.product.product_images']) // Cargar las relaciones necesarias
        ->get();

        $ordersUserIds = $ordersUser->pluck('id');
        $orderItemUser = OrderItem::whereIn('order_id', $ordersUserIds)
    ->with('product') // Cargar la relación product
    ->get();
        
        
        return Inertia::render('Admin/Users/UserDetail', ['user' => $user, 'userAddress' => $userAddress, 'ordersUser' => $ordersUser, 'orderItemUser' => $orderItemUser]);
    }
}
