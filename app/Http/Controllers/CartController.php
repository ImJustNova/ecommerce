<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return view('cart', compact('cart', 'total'));
    }

    //add product
    public function add(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'qty' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', $product->name.' added to cart.');
    }

    //update quantity
    public function update(Request $request, $id)
    {
        $qty = (int) $request->input('qty', 1);
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['qty'] = $qty;
            }
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    //remove item
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    //clear cart
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
    
    //place order
    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        DB::beginTransaction();
        
        try {
            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => Auth::user()->name,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                $product = Product::find($item['id']);
                if ($product && $product->stock >= $item['qty']) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            session()->forget('cart');

            return redirect()->route('orders.index')->with('success', 'Order placed successfully! Order #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('cart.index')->with('error', 'Failed to place order. Please try again.');
        }
    }
}