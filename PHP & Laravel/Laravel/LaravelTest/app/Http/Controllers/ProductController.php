<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sku_code' => 'required',
            'sku_name' => 'required',
            'distributor_price' => 'required',
            'mrp' => 'required',
            'units' => 'required|numeric',
            'measure' => 'required',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'sku_code' => 'required',
            'sku_name' => 'required',
            'distributor_price' => 'required',
            'mrp' => 'required',
            'units' => 'required|numeric',
            'measure' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index');
    }
};
