<?php

namespace App\Http\Controllers;

use App\Models\CategoryGame;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryGameController extends Controller
{
    public function index()
    {
        $categories = CategoryGame::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories_game,name',
            'description' => 'nullable|string',
        ]);

        CategoryGame::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori game berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = CategoryGame::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories_game,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori game berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = CategoryGame::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori game berhasil dihapus!');
    }
}
