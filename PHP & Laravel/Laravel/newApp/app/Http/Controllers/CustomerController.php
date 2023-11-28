<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_contact' => 'required|numeric|digits:10',
        ]);

        $customer = new Customer([
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact' => $request->input('customer_contact'),
            'customer_code' => $this->generateCustomerCode(),
        ]);

        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer registered successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edited_customer_name' => 'required',
            'edited_customer_address' => 'required',
            'edited_customer_contact' => 'required|numeric|digits:10',
        ]);

        $customer = Customer::find($id);
        $customer->customer_name = $request->input('edited_customer_name');
        $customer->customer_address = $request->input('edited_customer_address');
        $customer->customer_contact = $request->input('edited_customer_contact');
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }
    
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customer.edit', compact('customer'));
    }

    private function generateCustomerCode($length = 6)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $customerCode = '';
        for ($i = 0; $i < $length; $i++) {
            $customerCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $customerCode;
    }
}

