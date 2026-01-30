<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'phone' => 'nullable']);
        Customer::create($request->all());
        return back()->with('success', 'Customer Added!');
    }
}