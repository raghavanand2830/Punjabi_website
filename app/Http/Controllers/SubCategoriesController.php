<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Hash;

class  SubCategoriesController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            
        ]);
       
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
           
        ]);
       
        return redirect()->route('subcategories.index')
            ->with('alert', 'subcategories created successfully.');
    }

   

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subcategory','categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {

        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
         
        ]);
       
        $subcategory->update([
            'name' => $request->name ? $request->name: $subcategory->name ,
            'category_id' => $request->category_id ? $request->category_id: $subcategory->category_id ,
        ]);

        return redirect()->route('subcategories.index')
            ->with('success', 'subcategories updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
       
        $subcategory->delete();
        return redirect()->route('subcategories.index')
            ->with('success', 'subCategory deleted successfully.');
    }
}