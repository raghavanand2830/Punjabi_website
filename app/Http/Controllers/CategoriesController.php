<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class  CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
       
        Category::create([
            'name' => $request->name,
        
        ]);

        return redirect()->route('categories.index')
            ->with('alert', 'Category created successfully.');
    }

    public function show($id)
    {
        $Category = Category::find($id);
        return view('categories.show', compact('Category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {

        $category->update([
            'name' => $request->name ? $request->name: $category->name ,
      
        ]);
       
        return redirect()->route('categories.index')
            ->with('alert', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}