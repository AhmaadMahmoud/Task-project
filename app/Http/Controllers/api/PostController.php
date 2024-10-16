<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())
        ->orderBy('pinned', 'desc')
        ->get();
        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required|string',
        'cover_image' => 'required|image',
        'pinned' => 'required|boolean',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
    ]);

    {

    $post = $user->posts()->create([
        'title' => $request->title,
        'body' => $request->body,
        'pinned' => $request->pinned
    ]);
        if ($request->hasFile('cover_image')) {
        $fileName = $request->file('cover_image')->store('images', 'public');
        $post->cover_image = $fileName;
    }
    $post->save();
    if ($request->has('tags')) {
        $post->tags()->attach($request->tags);
    }
    return response()->json($post, 201);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $post = $user->posts()->find($id);
        if($post){
        return new PostResource($post);
        }else{
            return response()->json(['Message' => 'There Are No Posts']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


public function update(Request $request, $id)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $post = $user->posts()->find($id);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }
    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required|string',
        'cover_image' => 'nullable|image',
        'pinned' => 'required|boolean',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
    ]);
    $post->update($request->except('cover_image'));
        if ($request->hasFile('cover_image')) {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $fileName = $request->file('cover_image')->store('images', 'public');
        $post->cover_image = $fileName;
        $post->save();
    }
    if ($request->has('tags')) {
        $post->tags()->sync($request->tags);
    }
    return new PostResource($post);
}







    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy($id)
{
    $post = Post::where('id', $id)->where('user_id', Auth::id())->first();
    if (!$post) {
        return response()->json(['message' => 'Post not found or unauthorized'], 404);
    }
    $post->delete();
    return response()->json(['message' => 'Post deleted successfully']);
}

public function trashed(){
    $deletedPosts = Post::onlyTrashed()->get();
    if ($deletedPosts->isEmpty()) {
        return response()->json(['message' => 'No deleted posts found'], 404);
    }
    return PostResource::collection($deletedPosts);
}

    public function restore(Request $request , $id){
        $restorePosts = Post::onlyTrashed()->findOrFail($id);
        if($restorePosts){
            $restorePosts->restore();
            return response()->json(['message' => 'Restored the post']);
        }
    }
}
