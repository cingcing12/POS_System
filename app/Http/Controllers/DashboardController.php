<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Time-based Greeting
        $hour = date('H');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');

        // 2. Today's Stats
        $today = Carbon::today();
        $todaySales = Sale::whereDate('created_at', $today)->sum('final_total');
        $todayTransactions = Sale::whereDate('created_at', $today)->count();

        // 3. Low Stock Logic (Less than 10 items)
        $lowStock = Product::where('qty', '<=', 10)->orderBy('qty', 'asc')->take(5)->get();
        $lowStockCount = Product::where('qty', '<=', 10)->count();

        // 4. Total Products count
        $totalProducts = Product::count();

        return view('dashboard', compact(
            'greeting', 
            'todaySales', 
            'todayTransactions', 
            'lowStock', 
            'lowStockCount',
            'totalProducts'
        ));
    }
}