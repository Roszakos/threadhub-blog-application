<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function index(Request $request)
    {
        $posts = Post::select()->where('user_id', $request->user()->id)->simplePaginate(6);
        return view('dashboard', ['posts' => $posts]);
    }

    public function store(StorePostRequest $request)
    {
        if ($data = $request->validated()) {
            $data['image'] = $data['image']->store('post_images', 'public');
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

        return redirect()->route('post.view', [$post]);
    }

    public function show(Post $post)
    {
        $post['author'] = DB::table('users')->select('nickname')->where('id', '=', $post->user_id)->pluck('nickname')->first();
        return view('post.show', ['post' => $post]);
    }

    public function edit(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect('dashboard')->with('error', 'You don\'t have access to that page.');
        }
        $postSectionsSubtitle = [];
        $postSectionsContent = [];

        foreach ($post->sections as $section) {
            array_push($postSectionsSubtitle, $section->title);
            array_push($postSectionsContent, $section->content);
        }

        return view('post.edit', ['post' => $post, 'postSectionsSubtitle' => $postSectionsSubtitle, 'postSectionsContent' => $postSectionsContent]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }

        if ($data = $request->validated()) {
            $post->update(['title' => $data['title']]);
            $postSections = $post->sections;

            for ($i = 0; $i < count($data['subtitle']); $i++) {
                if ($data['subtitle'][$i] && $data['content'][$i]) {
                    if (count($postSections)) {
                        $postSections[0]->update(['title' => $data['subtitle'][$i], 'content' => $data['content'][$i]]);
                    } else {
                        $this->createSection([
                            'title' => $data['subtitle'][$i],
                            'content' => $data['content'][$i],
                            'post_id' => $post->id
                        ]);
                    }
                    $postSections->shift();
                }
            }
            foreach ($postSections as $section) {
                $section->delete();
            }
        } else {
            return back();
        }
        return redirect()->route('post.view', [$post]);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->cannot('delete', $post)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }
        $post->sections()->delete();
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function getPostsForHome() {
        $posts = DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.user_id')
                    ->select('posts.title', 'posts.image', 'posts.created_at', 'users.nickname as author')
                    ->orderBy('created_at', 'desc')
                    ->limit(7)
                    ->get();
        return view('home', ['posts' => $posts]);
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
