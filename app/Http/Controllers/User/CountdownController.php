<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CountdownController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $cart = CartItem::where('user_id', $user->id)->get();
        dd($cart);   
    }
}
