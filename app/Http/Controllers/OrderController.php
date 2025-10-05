<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with('items.product')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
    return view('index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access.');
        }
        
        $order->status = $request->input('status', 'pending');
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order status updated.');
    }
}