<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\PostSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
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
        $posts = Post::select()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->simplePaginate(6);
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

    public function show(Post $post, Request $request)
    {
        if (!Cookie::get('post-' . $post->id)) {
            Cookie::queue(Cookie::make('post-' . $post->id, true, 60));
            $post->incrementViewCount();
        }

        if ($request->user()) {
            $vote = DB::table('votes')
                ->select('vote')
                ->where('post_id', '=', $post->id)
                ->where('user_id', '=', $request->user()->id)
                ->pluck('vote')
                ->first();
        } else {
            $vote = null;
        }
        $post['author'] = DB::table('users')
            ->select('nickname')
            ->where('id', '=', $post->user_id)
            ->pluck('nickname')
            ->first();
        $upvotes = DB::table('votes')
            ->selectRaw('count(id) as votes')
            ->where('post_id', '=', $post->id)
            ->where('vote', '=', 1)
            ->pluck('votes')
            ->all();
        $downvotes = DB::table('votes')
            ->selectRaw('count(id) as votes')
            ->where('post_id', '=', $post->id)
            ->where('vote', '=', 2)
            ->pluck('votes')
            ->all();

        $comments = DB::table('comments')
            ->select('id', 'user_id', 'author', 'content', 'created_at')
            ->where('post_id', '=', $post->id)
            ->where('parent_id', '=', null)
            ->orderByDesc('created_at')
            ->get();

        $comments = $comments->map(function ($comment) {
            $comment->{'replies'} = $this->commentReplies($comment);
            $dt = Carbon::create($comment->created_at);
            $comment->{'posted'} = $dt->diffForHumans();
            if ($comment->user_id) {
                $comment->author = User::find($comment->user_id)->nickname;
                if (Auth::user() && $comment->user_id === Auth::user()->id) {
                    $comment->{'owner'} = true;
                } else {
                    $comment->{'owner'} = false;
                }
            } else {
                $comment->{'owner'} = false;
            }
            return $comment;
        });

        return view('post.show', [
            'post' => $post,
            'vote' => $vote,
            'upvotes' => $upvotes[0],
            'downvotes' => $downvotes[0],
            'comments' => $comments
        ]);
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

    public function getPostsForHome()
    {
        $posts = DB::table('posts')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.image',
                'posts.created_at',
                'users.nickname as author'
            )
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        $trendingPost = $posts->shift();
        $trendingPost->{'snippet'} = DB::table('post_sections')
            ->select('content')
            ->where('post_id', '=', $trendingPost->id)
            ->orderBy('id')
            ->pluck('content')
            ->first();
        $trendingPost->snippet = trim(substr($trendingPost->snippet, 0, 200)) . '...';

        foreach ($posts as $post) {
            $post->{'snippet'} = DB::table('post_sections')
                ->select('content')
                ->where('post_id', '=', $post->id)
                ->orderBy('id')
                ->pluck('content')
                ->first();
            $post->snippet = trim(substr($post->snippet, 0, 100)) . '...';
        }

        return view('home', ['posts' => $posts, 'trendingPost' => $trendingPost]);
    }

    private function commentReplies($comment)
    {
        $replies = DB::table('comments')
            ->select('id', 'user_id', 'author', 'content', 'created_at')
            ->where('parent_id', '=', $comment->id)
            ->orderByDesc('created_at')
            ->get();
        if (count($replies)) {
            $replies->map(function ($comment) {
                $comment->{'replies'} = $this->commentReplies($comment);
                $dt = Carbon::create($comment->created_at);
                $comment->{'posted'} = $dt->diffForHumans();
                if ($comment->user_id) {
                    $comment->author = User::find($comment->user_id)->nickname;
                    if (Auth::user() && $comment->user_id === Auth::user()->id) {
                        $comment->{'owner'} = true;
                    } else {
                        $comment->{'owner'} = false;
                    }
                } else {
                    $comment->{'owner'} = false;
                }
                return $comment;
            });
        }
        return $replies;
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
