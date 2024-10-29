<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    function categoryview()
    {
        $categories = Category::with('subcategories')->get();
        return view('vendor/category/category', compact('categories'));
    }
    // Store a new category
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);
        // Check for existing category name
    if (Category::where('name', $request->input('category_name'))->exists()) {
        return redirect('/category')->with('error', 'Category name already exists.');
    }
        Category::create([
            'name' => $request->input('category_name'),
            'vendor_id' => Auth::id(),
        ]);

        return redirect('/category')->with('message', 'Category update successfully');
    }

    // Store a new subcategory
    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'subcategory_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        // Check for existing subcategory name
    if (Subcategory::where('name', $request->input('subcategory_name'))->exists()) {
        return redirect('/category')->with('error', 'Subcategory name already exists.');
    }
        $subcategory = new Subcategory([
            'name' => $request->input('subcategory_name'),
            'category_id' => $request->input('category_id'),
            'vendor_id' => Auth::id(),
        ]);

        // Associate the subcategory with the selected category
        $category = Category::find($request->input('category_id'));
        $category->subcategories()->save($subcategory);

        return redirect('/category')->with('message', 'SubCategory update successfully');
    }
    // Delete a category and its subcategories
    public function destroycategory(Category $category)
    {
        if ($category->vendor_id !== Auth::id()) {
            return redirect('/category')->with('error', 'You are not authorized to delete this category');
        }
        $category->subcategories()->delete(); // Delete associated subcategories
        $category->delete(); // Delete the category
        return redirect('/category')->with('message', 'Category and subcategories deleted successfully');
    }

    // Delete a subcategory individually
    public function destroySubcategory(Subcategory $subcategory)
    {
        if ($subcategory->vendor_id !== Auth::id()) {
            return redirect('/category')->with('error', 'You are not authorized to delete this subcategory');
        }
        $subcategory->delete();
        return redirect('/category')->with('message', 'Subcategory deleted successfully');
    }
    // Update category name
    public function updateCategory(Request $request, Category $category)
    {
        if ($category->vendor_id !== Auth::id()) {
            return redirect('/category')->with('error', 'You are not authorized to edit this category');
        }
        $request->validate([
            'edit_category_name' => 'required|string|max:255',
        ]);
        // Check for existing category name, excluding the current category
    if (Category::where('name', $request->input('edit_category_name'))
    ->where('id', '!=', $category->id)
    ->exists()) {
return redirect('/category')->with('error', 'Category name already exists.');
}
        $category->update([
            'name' => $request->input('edit_category_name'),
        ]);

        return redirect('/category')->with('message', 'Category updated successfully');
    }

    // Update subcategory name
    public function updateSubcategory(Request $request, SubCategory $subcategory)
    {
        if ($subcategory->vendor_id !== Auth::id()) {
            return redirect('/category')->with('error', 'You are not authorized to edit this subcategory');
        }
        $request->validate([
            'edit_subcategory_name' => 'required|string|max:255',
        ]);
            // Check for existing subcategory name, excluding the current subcategory
    if (Subcategory::where('name', $request->input('edit_subcategory_name'))
    ->where('id', '!=', $subcategory->id)
    ->exists()) {
return redirect('/category')->with('error', 'Subcategory name already exists.');
}

        $subcategory = Subcategory::findOrFail($request->input('subcategory_id'));
        $subcategory->update([
            'name' => $request->input('edit_subcategory_name'),
        ]);

        return redirect('/category')->with('message', 'Subcategory updated successfully');
    }
}
