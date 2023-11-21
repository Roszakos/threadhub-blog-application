<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostSection;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(StorePostRequest $request)
    {
        if ($data = $request->validated()) {
            $post = Post::create($data);
            for ($i = 0; $i < count($data['subtitle']); $i++) {
                if ($data['subtitle'][$i] && $data['content'][$i]) {
                    $this->createSection([
                        'title' => $data['subtitle'][$i],
                        'content' => $data['content'][$i],
                        'post_id' => $post->id
                    ]);
                }
            }
        } else {
            return back();
        }

        return $this->show($post);
    }

    public function show(Post $post)
    {

        $postSections = $post->sections->map(function ($section) {
            return collect($section->toArray())
                ->only(['title', 'content'])
                ->all();
        });
        return view('post.show', ['post' => $post, 'postSections' => $postSections]);
    }

    private function createSection($data)
    {
        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string', 'max:10000'],
            'post_id' => ['required', 'numeric', 'exists:Posts,id']
        ]);

        return PostSection::create($validator->validated());
    }
}
