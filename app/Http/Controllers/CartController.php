<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    // show cart page
    public function index()
    {
        $cart = session()->get('cart', []);
        // cart is array: productId => ['id'=>..., 'name'=>..., 'price'=>..., 'qty'=>...]
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return view('cart', compact('cart', 'total'));
    }

    // add product to cart
    public function add(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cart = session()->get('cart', []);

        // if product exists in cart, increase qty
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

        // optional flash message
        return redirect()->back()->with('success', $product->name.' added to cart.');
    }

    // update quantity
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

    // remove item
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    // clear cart (helper)
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
    
    // place order
    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        // Create order
        $order = Order::create([
            'customer_name' => 'Guest User', // later: use auth user
            'status' => 'pending',
        ]);

        // Save items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
