<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Topic;
use App\Models\Post;
use App\Http\Requests\PostRequest;

use Illuminate\Http\Request;

class PostController extends Controller {

    public function show(Post $post) {
        return view('post.show')->with(compact('post'));
    }

    public function create() {
        $topics = Topic::all();
        
        return view('post.create')->with(compact('topics'));
    }

    public function store(PostRequest $request) {
        $post = Auth::user()
            ->posts()
            ->create($request->post);

        return redirect()
            ->route('post.edit', ['post' => $post])
            ->with('success', __('Post created successfully'));
    }

    public function edit(Post $post)
    {
        if ($post->user != Auth::user()) {
            abort(403);
        }

        $topics = Topic::all();

        return view('post.edit')->with(compact('post', 'topics'));
    }

    public function update(Post $post, PostRequest $request)
    {
        if ($post->user != Auth::user()) {
            abort(403);
        }

        $post->update($request->post);

        return redirect()
            ->route('post.edit', ['post' => $post])
            ->with('success', __('Post saved successfully'));
    }

    function uploadImage(Post $post, Request $request)
    {
        if ($post->user != Auth::user()) {
            abort(403);
        }

        if ($request->ajax()) {
            $image = $post->store_image($request->file('file'));
            return response()->json(['ok' => $image->id], 200);
        }
        abort(404);
    }


    function deleteImage(Post $post)
    {
        if ($post->user != Auth::user()) {
            abort(403);
        }

        $post->delete_image();
        return redirect()
            ->route('post.edit', ['post' => $post])
            ->with('success', __('Post image deleted successfully'));
    }

    function comment(Post $post, Request $request)
    {
        $post->comments()->create([
            'user_id' => Auth::user()->id,
            'body' => $request->comment,
        ]);

        return back()->with('success', __('Comment saved successfully'));
    }
}
