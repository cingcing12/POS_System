<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        // 1. Start Query (Exclude Admins)
        $query = User::where('role', '!=', 'admin')->latest();

        // 2. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Role Filter (New)
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // 4. Paginate & Keep URL Params
        $staff = $query->paginate(12)->withQueryString();

        // 5. Dashboard Stats
        $allStaff = User::where('role', '!=', 'admin')->get();
        $totalStaff = $allStaff->count();
        $today = strtolower(date('D'));
        
        $workingToday = $allStaff->filter(function($u) use ($today) {
            $schedule = $u->week_schedule ?? [];
            return isset($schedule[$today]) && $schedule[$today] !== 'Off';
        })->count();
        
        $offToday = $totalStaff - $workingToday;

        return view('staff.index', compact('staff', 'totalStaff', 'workingToday', 'offToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'password' => 'nullable|min:8',
            'role' => 'required',
            'phone' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'schedule' => 'required|array',
            'photo' => 'required|image|max:5120',
        ]);

        $year = date('Y');
        $lastUser = User::where('national_id', 'like', "EMP-$year-%")->latest()->first();
        $sequence = $lastUser ? intval(substr($lastUser->national_id, -3)) + 1 : 1;
        $autoId = 'EMP-' . $year . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        $photoPath = $request->file('photo')->store('staff_photos', 'public');
        $password = $request->filled('password') ? Hash::make($request->password) : Hash::make('password123');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? $autoId . '@pos.system',
            'password' => $password,
            'role' => $request->role,
            'phone' => $request->phone,
            'national_id' => $autoId,
            'dob' => $request->dob,
            'address' => $request->address,
            'week_schedule' => $request->schedule,
            'photo_url' => '/storage/' . $photoPath,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Created Staff',
            'description' => "Added {$user->role}: {$user->name} ({$user->national_id})"
        ]);

        return back()->with('success', 'Staff Member Created Successfully!')
                     ->with('new_staff_data', $user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'schedule' => 'required|array',
        ]);

        // 1. Exclude 'email' from the initial extraction so we don't accidentally set it to null
        $data = $request->except(['password', 'photo', 'schedule', 'email']); 
        
        $data['week_schedule'] = $request->schedule; 

        // 2. Only update email if the user actually typed something in (and it's not hidden/empty)
        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if($user->photo_url) {
                // Fix: Check if file exists before trying to delete to avoid errors
                $oldPath = str_replace('/storage/', '', $user->photo_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $data['photo_url'] = '/storage/' . $request->file('photo')->store('staff_photos', 'public');
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Updated Staff',
            'description' => "Updated profile for: {$user->name}"
        ]);

        return back()->with('success', 'Profile updated!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;

        if($user->photo_url) Storage::disk('public')->delete(str_replace('/storage/', '', $user->photo_url));
        if(method_exists($user, 'attendances')) $user->attendances()->delete();
        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Deleted Staff',
            'description' => "Removed staff member: {$name}"
        ]);

        return back()->with('success', 'Staff removed.');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Password Reset',
            'description' => "Reset password for: {$user->name}"
        ]);

        return back()->with('success', 'Password reset.');
    }
}