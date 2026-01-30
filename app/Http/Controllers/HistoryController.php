<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 🟢 FIXED SEARCH LOGIC
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // 1. Search columns in activity_logs table
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('user_role', 'like', "%{$search}%")
                  
                  // 2. Search related 'users' table (Fixes searching by Name)
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Role (Admin, Stock, Sale)
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        $logs = $query->paginate(20)->withQueryString(); // Keep search filters in pagination links

        return view('admin.history', compact('logs'));
    }
}