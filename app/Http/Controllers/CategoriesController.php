<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->get();

        return response()->json([
            "message" => "List categories",
            "total" => $categories->count(),
            "results" => $categories
        ], 200);
    }
}
