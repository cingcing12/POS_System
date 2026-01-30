<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF; // Ensure you have 'barryvdh/laravel-dompdf' installed and alias set

class AttendanceController extends Controller
{
    /**
     * Display the attendance dashboard.
     */
    public function index(Request $request) {
        $user = auth()->user();

        // Initialize collections to avoid undefined variable errors
        $workingNow = collect();
        $allHistory = collect();
        $totalPresent = 0;
        $totalLate = 0;
        $today = null;
        $history = collect();

        if ($user->role === 'admin') {
            // --- ADMIN VIEW LOGIC ---

            // 1. Stats: "Working Now" (Always shows who is currently clocked in today)
            $workingNow = Attendance::whereDate('date', today())
                                    ->whereNull('check_out')
                                    ->with('user')
                                    ->get();

            // 2. Main Table Data (Filtered by Search bar)
            $historyQuery = Attendance::with('user');

            // Filter by Staff Name or ID
            if ($request->filled('search')) {
                $historyQuery->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('national_id', 'like', '%' . $request->search . '%');
                });
            }

            // Filter by Specific Date (from the Filter Bar)
            if ($request->filled('date')) {
                $historyQuery->whereDate('date', $request->date);
            } else {
                // Default: Sort by latest date
                $historyQuery->orderBy('date', 'desc');
            }

            // Paginate results
            $allHistory = $historyQuery->orderBy('created_at', 'desc')
                                       ->paginate(20)
                                       ->withQueryString();

            // 3. Daily Stats counts
            $totalPresent = Attendance::whereDate('date', today())->count();
            $totalLate = Attendance::whereDate('date', today())->where('status', 'Late')->count();

        } else {
            // --- STAFF VIEW LOGIC ---
            
            // Get today's record to show Clock In/Out status
            $today = Attendance::where('user_id', $user->id)
                               ->where('date', date('Y-m-d'))
                               ->first();
            
            // Get recent personal history
            $history = Attendance::where('user_id', $user->id)
                                 ->latest()
                                 ->limit(10)
                                 ->get();
        }

        return view('attendance.index', compact(
            'workingNow', 'allHistory', 'totalPresent', 'totalLate', 
            'today', 'history'
        ));
    }

    /**
     * Handle Staff Check-In (Button Click).
     */
    public function checkIn() {
        $user = auth()->user();

        if($user->role === 'admin') return back()->with('error', 'Admins cannot check in.');

        // 1. Get Schedule
        $todayDay = strtolower(date('D')); // e.g., 'mon', 'tue'
        $schedule = $user->week_schedule; 
        
        // 2. Validate Schedule Existence
        if (!isset($schedule[$todayDay])) {
            return back()->with('error', 'No schedule found for today.');
        }

        $shiftType = $schedule[$todayDay];

        // 3. Validate Day Off
        if ($shiftType === 'Off') {
            return back()->with('error', 'You cannot check in today. It is your day off.');
        }

        // 4. Validate Shift Times
        $now = now();
        $currentTime = $now->format('H:i');

        if ($shiftType === 'Morning') {
            // Morning: Strict check to prevent late evening check-ins
            if ($currentTime > '12:00') return back()->with('error', 'Too late for Morning shift.');
        } elseif ($shiftType === 'Evening') {
            // Evening: Strict check to prevent early morning check-ins
            if ($currentTime < '12:00') return back()->with('error', 'Too early! Evening shift starts at 1:00 PM.');
        }

        // 5. Calculate Status (Late or Present)
        $isLate = false;
        
        // Morning starts 8:00 AM (Late after 8:15)
        if ($shiftType === 'Morning' && $currentTime > '08:15') $isLate = true;
        
        // Evening starts 1:00 PM (Late after 1:15 PM / 13:15)
        if ($shiftType === 'Evening' && $currentTime > '13:15') $isLate = true;
        
        // Full Time starts 8:00 AM (Late after 8:15)
        if ($shiftType === 'Full Time' && $currentTime > '08:15') $isLate = true;

        // 6. Create Record
        Attendance::create([
            'user_id' => $user->id,
            'date' => date('Y-m-d'),
            'check_in' => $now->format('H:i:s'),
            'status' => $isLate ? 'Late' : 'Present'
        ]);

        return back()->with('success', "Checked In Successfully! Shift: {$shiftType}");
    }

    /**
     * Handle Staff Check-Out (Button Click).
     */
    public function checkOut() {
        if(auth()->user()->role === 'admin') return back()->with('error', 'Admins cannot check out.');

        $attendance = Attendance::where('user_id', auth()->id())
                                ->where('date', date('Y-m-d'))
                                ->first();
        
        if($attendance) {
            $attendance->update(['check_out' => now()->format('H:i:s')]);
            return back()->with('success', 'Checked Out Successfully!');
        }

        return back()->with('error', 'You have not checked in yet.');
    }

    /**
     * Generate PDF Report based on Modal Selection.
     */
    public function exportPdf(Request $request)
    {
        $query = Attendance::with('user');
        $title = "Attendance Report";
        $dateInfo = "";

        // 1. Export by Specific Date (Daily)
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
            $dateInfo = "Date: " . Carbon::parse($request->date)->format('F d, Y');
        } 
        // 2. Export by Month & Year (Monthly)
        elseif ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)->whereYear('date', $request->year);
            $dateInfo = "Month: " . Carbon::createFromDate(null, $request->month)->format('F') . " " . $request->year;
        } 
        // 3. Export by Year (Yearly)
        elseif ($request->filled('year')) {
            $query->whereYear('date', $request->year);
            $dateInfo = "Annual Report: " . $request->year;
        } 
        // 4. Default Fallback (Today)
        else {
            $query->whereDate('date', today());
            $dateInfo = "Date: " . today()->format('F d, Y');
        }

        // Get records sorted by date and time
        $records = $query->orderBy('date', 'asc')->orderBy('check_in', 'asc')->get();

        // Load the PDF View
        $pdf = PDF::loadView('attendance.pdf', compact('records', 'title', 'dateInfo'));
        
        return $pdf->download('attendance_report.pdf');
    }
}