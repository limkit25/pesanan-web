<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $categoryId = $request->query('category');
        
        $products = Product::with('category')
            ->where('is_active', true)
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->get();
            
        return view('welcome', compact('categories', 'products', 'categoryId'));
    }
}
