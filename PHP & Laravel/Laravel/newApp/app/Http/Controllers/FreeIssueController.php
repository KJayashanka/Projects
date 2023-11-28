<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreeIssueController extends Controller
{
    public function index()
    {
        $freeIssues = DB::table('free_issues')->get();
        $products = DB::table('products')->pluck('product_name');

        return view('free_issues.index', compact('freeIssues', 'products'));
    }

    public function create()
    {
        $products = DB::table('products')->pluck('product_name');

        return view('free_issues.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Validation logic if needed

        $free_issue_label = $request->input('free_issue_label');
        $issue_type = $request->input('issue_type');
        $purchase_product = $request->input('purchase_product');
        $free_product = $request->input('free_product');
        $purchase_quantity = $request->input('purchase_quantity');
        $free_quantity = $request->input('free_quantity');
        $lower_limit = $request->input('lower_limit');
        $upper_limit = $request->input('upper_limit');

        // Fetch product_id based on the selected purchase_product
        $product_id = DB::table('products')->where('product_name', $purchase_product)->value('id');

        
        // Insert into the database
        DB::table('free_issues')->insert([
            'free_issue_label' => $free_issue_label,
            'issue_type' => $issue_type,
            'purchase_product' => $purchase_product,
            'free_product' => $free_product,
            'purchase_quantity' => $purchase_quantity,
            'free_quantity' => $free_quantity,
            'lower_limit' => $lower_limit,
            'upper_limit' => $upper_limit,
            'purchase_product_id' => $product_id,
        ]);

        
        // Save purchase quantity and free quantity in sessions
        session(['purchase_quantity' => $purchase_quantity]);
        session(['free_quantity' => $free_quantity]);

        return redirect()->route('free_issues.index')->with('success', 'Free issue updated successfully.');
    }

    public function edit($id)
    {
        $freeIssue = DB::table('free_issues')->find($id);
        $products = DB::table('products')->pluck('product_name');

        return view('free_issues.edit', compact('freeIssue', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validation logic if needed

        $free_issue_label = $request->input('free_issue_label');
        $issue_type = $request->input('issue_type');
        $purchase_product = $request->input('purchase_product');
        $free_product = $request->input('free_product');
        $purchase_quantity = $request->input('purchase_quantity');
        $free_quantity = $request->input('free_quantity');
        $lower_limit = $request->input('lower_limit');
        $upper_limit = $request->input('upper_limit');

        // Fetch product_id based on the selected purchase_product
        $product_id = DB::table('products')->where('product_name', $purchase_product)->value('id');

        // Update the database record
        DB::table('free_issues')->where('id', $id)->update([
            'free_issue_label' => $free_issue_label,
            'issue_type' => $issue_type,
            'purchase_product' => $purchase_product,
            'free_product' => $free_product,
            'purchase_quantity' => $purchase_quantity,
            'free_quantity' => $free_quantity,
            'lower_limit' => $lower_limit,
            'upper_limit' => $upper_limit,
            'purchase_product_id' => $product_id,
        ]);

        return redirect()->route('free_issues.index')->with('success', 'Free issue updated successfully.');
    }
}
