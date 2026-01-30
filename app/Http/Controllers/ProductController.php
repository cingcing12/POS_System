<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog; // 🟢 Import This
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request) {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $path = $request->file('image')->store('products', 'public');
            $uploadedFileUrl = '/storage/' . $path;
            $barcode = $request->barcode ? $request->barcode : rand(100000000000, 999999999999);

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'barcode' => $barcode,
                'cost_price' => $request->cost_price ?? 0,
                'sale_price' => $request->sale_price,
                'qty' => $request->qty,
                'image_url' => $uploadedFileUrl,
            ]);

            // 🟢 LOG HISTORY
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'action' => 'Created Product',
                'description' => "Added Product: {$product->name} (Qty: {$product->qty})"
            ]);

            return response()->json([
        'success' => true,
        'message' => 'Product created successfully!',
        'new_barcode' => $product->barcode
    ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($product->image_url && File::exists(public_path($product->image_url))) {
                    File::delete(public_path($product->image_url));
                }
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $validated['image_url'] = 'uploads/products/' . $imageName;
            }

            $product->update($validated);

            // --- REDIRECT WITH SUCCESS MESSAGE ---
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);
            $name = $product->name; // Capture for log

            if ($product->image_url) {
                $oldPath = str_replace('/storage/', '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $product->delete();

            // 🟢 LOG HISTORY
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'action' => 'Deleted Product',
                'description' => "Deleted Product: {$name}"
            ]);

            return redirect()->route('products.index')->with('success', 'Product Deleted Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete Failed: ' . $e->getMessage());
        }
    }
}