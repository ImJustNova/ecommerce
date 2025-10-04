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
        // Get only the logged-in user's orders with related items and products
        $orders = Order::where('user_id', Auth::id())
                      ->with('items.product')
                      ->orderBy('created_at', 'desc') // Show newest orders first
                      ->get();
        
    return view('index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Optional: Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access.');
        }
        
        $order->status = $request->input('status', 'pending');
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order status updated.');
    }
}