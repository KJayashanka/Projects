<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Product;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        $products = Product::pluck('product_name');

        return view('discounts.index', compact('discounts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_label' => 'required',
            'purchase_product' => 'required',
            'discount_amount' => 'required|numeric',
        ]);

        $product = Product::where('product_name', $request->purchase_product)->first();

        if ($product) {
            $discount = new Discount([
                'discount_label' => $request->discount_label,
                'purchase_product' => $request->purchase_product,
                'discount_amount' => $request->discount_amount,
            ]);

            $product->discount = $request->discount_amount;
            $product->save();
            
            $discount->product()->associate($product);
            $discount->save();

            return redirect()->route('discounts.index')->with('success', 'Discount registered successfully.');
        } else {
            return redirect()->route('discounts.index')->with('error', 'Product not found or product name is not valid.');
        }
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        $products = Product::pluck('product_name');

        return view('discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'discount_label' => 'required',
            'purchase_product' => 'required',
            'discount_amount' => 'required|numeric',
        ]);

        $discount = Discount::findOrFail($id);
        $product = Product::where('product_name', $request->purchase_product)->first();

        if ($product) {
            $discount->update([
                'discount_label' => $request->discount_label,
                'purchase_product' => $request->purchase_product,
                'discount_amount' => $request->discount_amount,
            ]);

            $product->discount = $request->discount_amount;
            $product->save();

            return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
        } else {
            return redirect()->route('discounts.index')->with('error', 'Product not found or product name is not valid.');
        }
    }
}
