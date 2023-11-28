<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('product.edit', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'price' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $product = new Product([
            'product_code' => $this->generateProductCode(),
            'product_name' => $request->input('product_name'),
            'price' => $request->input('price'),
            'expiry_date' => $request->input('expiry_date'),
        ]);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product registered successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edited_product_name' => 'required',
            'edited_price' => 'required|numeric',
            'edited_expiry_date' => 'required|date',
        ]);

        $product = Product::find($id);
        $product->product_name = $request->input('edited_product_name');
        $product->price = $request->input('edited_price');
        $product->expiry_date = $request->input('edited_expiry_date');
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    private function generateProductCode($length = 6)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $productCode = '';
        for ($i = 0; $i < $length; $i++) {
            $productCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $productCode;
    }
}
