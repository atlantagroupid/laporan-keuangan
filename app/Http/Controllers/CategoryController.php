<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Store a new category for the authenticated user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:pemasukan,pengeluaran',
        ]);

        Auth::user()->categories()->create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:pemasukan,pengeluaran',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki transaksi.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
