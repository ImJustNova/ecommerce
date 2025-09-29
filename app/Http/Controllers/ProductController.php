<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // show catalogue
    public function index()
    {
        // get all products (change to paginate() later if needed)
        $products = Product::all();

        // pass products to the view
        return view('catalogue', compact('products'));
    }
}
