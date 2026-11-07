<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id', 'ASC')->get();

        return response()->json([
            "message" => "List tags",
            "total" => $tags->count(),
            "results" => $tags
        ], 200);
    }
}
