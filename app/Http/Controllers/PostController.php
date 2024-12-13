<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return PostResource::collection(Post::paginate(3));
    }

    /**
/**
 * Store a newly created resource in storage.
 */
public function store(StorePostRequest $request)
{
    // Validate the incoming request
    $validatedData = $request->validated();

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $imagePath = 'images/' . $imageName;
        $image->move(public_path('images'), $imageName);
        $validatedData['image_url'] = url($imagePath); // Generate the URL to be used in frontend
    }

    // Create the post
    $post = Post::create($validatedData);

    // Prepare the response
    $response = [
        'post' => new PostResource($post),
        'message' => __('Post added successfully'),
    ];

    return response()->json($response, 201); // 201 Created status code
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
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        $post->update($validatedData);
    
        // Prepare the response
        $response = [
            'post' => new PostResource($post),
            'message' => __('updated successfully'),
        ];
    
        return response()->json($response);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post )
    {
         $post->delete();
         return new PostResource($post);
    }
}
