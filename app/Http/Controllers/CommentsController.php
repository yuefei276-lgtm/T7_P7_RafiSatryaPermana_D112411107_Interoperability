<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::with([
            'user:id,name,email',
            'post:id,title'
        ])->paginate(10);

        return response()->json($comments, 200);
    }
}
