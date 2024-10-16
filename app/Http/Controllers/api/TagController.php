<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        if($tags)
        {
            return response()->json($tags);
        }else{
            return response()->json([
                'Message' => 'There Are No Tags'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tags = $request->validate([
            'name' => 'required|string|unique:tags|max:255'
        ]);
        Tag::create($tags);
        return response()->json([
            'Message' => 'Tag Inserted!',
            'Tag' => $tags
        ] , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        if($tag){
            return response()->json($tag);
        }else{
            return response()->json(['Message' => 'This Tag Is Not Available']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:tags|max:255',
        ]);

        $tag = Tag::find($id);
        $tag->update([
            'name' => $request->name,
        ]);
        if($tag){
            return response()->json(['Message' => 'Updated Successfully!',
            'Tag' => $tag]);
        }else{
            return response()->json(['Message' => 'The Tag is Not Exist']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if($tag){
            $tag->delete();
            return response()->json(['Message' => 'Tag Deleted!']);
        }else{
            return response()->json(['Message' => 'The Tag is Not Available now!']);
        }
    }
}
