<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\ActivityLog; // 🟢 Import This
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $transactions = StockTransaction::with(['product', 'user'])
                        ->where('type', 'in')
                        ->latest()
                        ->take(10)
                        ->get();
                        
        return view('stock.index', compact('products', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        DB::transaction(function () use ($request) {
            StockTransaction::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'qty' => $request->qty,
                'created_at' => now(),
            ]);

            $product = Product::find($request->product_id);
            $product->increment('qty', $request->qty);

            // 🟢 LOG HISTORY (Works for Admin AND Stock users)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'action' => 'Stock In',
                'description' => "Added {$request->qty} units to: {$product->name}"
            ]);
        });

        return back()->with('success', 'Stock Added Successfully!');
    }
}