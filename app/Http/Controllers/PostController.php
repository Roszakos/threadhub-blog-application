<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['title'];

        $post = Post::create($data);

        // for ($i = 0; $i < count($data['subtitle']); $i++) {
        //     if ($data['subtitle'][$i] && $data['content'][$i]) {
        //         $this->createSection($data['subtitle'][$i], $data['content'][$i], $post->id);
        //     }
        // }

        return view('post.create');
    }
}
