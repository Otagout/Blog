<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return CommentResource::collection(Comment::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 public function deleteComment($id){
     $comment = Comment::findOrFail($id);
     $comment->delete();
     return response()->json([
        
        'message' => __('Comment deleted successfully'),
    ]);
 }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $formFields = $request->validated();
        $comment = Comment::create([
            'post_id' => $formFields['post_id'],
            'author_name' => $formFields['author_name'],
            'author_email' => $formFields['author_email'],
            'content' => $formFields['content'],
            'created_at' => now(),  // Optionally add the timestamp
        ]);
    
        return response()->json([
            'comment' => new CommentResource($comment),
            'message' => __('Comment added successfully'),
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Comment $comment)
    {
       $comment->delete();
       return new CommentResource($comment);
    }
}
