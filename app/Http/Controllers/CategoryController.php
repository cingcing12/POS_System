<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog; // 🟢 Import the Log Model
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);
        
        $category = Category::create(['name' => $request->name]);
        
        // 🟢 LOG HISTORY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Created Category',
            'description' => "Added new category: {$category->name}"
        ]);
        
        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $oldName = $category->name; // Capture old name for the log
        $category->update(['name' => $request->name]);

        // 🟢 LOG HISTORY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Updated Category',
            'description' => "Renamed category from '{$oldName}' to '{$category->name}'"
        ]);

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        
        // Prevent delete if products exist
        if($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete: This category has products linked to it.');
        }

        $name = $category->name; // Capture name before delete
        $category->delete();

        // 🟢 LOG HISTORY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'action' => 'Deleted Category',
            'description' => "Deleted category: {$name}"
        ]);

        return back()->with('success', 'Category deleted successfully!');
    }
}