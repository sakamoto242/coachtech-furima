<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest; 
use Illuminate\Http\Request;

class CommentController extends Controller
{
   
    public function store(CommentRequest $request, $productId)
    {
        

        Comment::create([
            'user_id'    => Auth::id(),
            'product_id' => $productId,
            'comment'    => $request->comment, 
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }
}