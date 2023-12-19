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
use Illuminate\Support\Facades\Storage;
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
            if (isset($data['image'])) {
                $data['image'] = $data['image']->store('post_images', 'public');
            }
            $post = Post::create($data);
        } else {
            return back();
        }

        return redirect()->route('post.view', [$post])->with('success', 'Article created successfully');
    }

    public function show(Post $post, Request $request)
    {
        $isOwner = false;

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
            if ($request->user()->id === $post->user_id || $request->user()->role === 'admin') {
                $isOwner = true;
            } else {
                $isOwner = false;
            }
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

        $comments = $comments->map(function ($comment) use ($request) {
            $comment->{'replies'} = $this->commentReplies($comment);
            $dt = Carbon::create($comment->created_at);
            $comment->{'posted'} = $dt->diffForHumans();

            if ($comment->user_id) {
                $comment->author = User::find($comment->user_id)->nickname;
                if ($request->user() && $comment->user_id === $request->user()->id) {
                    $comment->{'owner'} = true;
                } else {
                    $comment->{'owner'} = false;
                }
            } else {
                $comment->{'owner'} = false;
            }
            if ($request->user() && $request->user()->role === 'admin') {
                $comment->{'owner'} = true;
            }
            return $comment;
        });

        $commentsAmount = $this->countComments($comments);

        return view('post.show', [
            'post' => $post,
            'vote' => $vote,
            'upvotes' => $upvotes[0],
            'downvotes' => $downvotes[0],
            'comments' => $comments,
            'commentsAmount' => $commentsAmount,
            'isOwner' => $isOwner
        ]);
    }

    public function edit(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect('dashboard')->with('error', 'You don\'t have access to that page.');
        }

        return view('post.edit', ['post' => $post]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }

        if ($data = $request->validated()) {
            if ($data['imageAction'] == 'delete') {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $post->update(['title' => $data['title'], 'body' => $data['body'], 'image' => null]);
            } else if ($data['imageAction'] == 'change') {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                if (!empty($data['image'])) {
                    $data['image'] = $data['image']->store('post_images', 'public');
                    $post->update(['title' => $data['title'], 'body' => $data['body'], 'image' => $data['image']]);
                } else {
                    $post->update(['title' => $data['title'], 'body' => $data['body']]);
                }
            } else {
                $post->update(['title' => $data['title'], 'body' => $data['body']]);
            }
        } else {
            return back();
        }
        return redirect()->route('post.view', [$post])->with('success', 'Article updated successfully');
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->cannot('delete', $post)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }
        $post->comments()->delete();
        $post->votes()->delete();
        $post->delete();

        if (str_contains($request->header('referer'), 'admin')) {
            return redirect()->route('admin.dashboard')->with('success', 'Article deleted successfully');
        }

        return redirect()->route('dashboard')->with('success', 'Article deleted successfully');
    }

    public function getPostsForHome()
    {
        $trendingPost = null;
        for ($i = 1; $i < 5; $i++) {
            $date = date('Y-m-d H:i:s', strtotime('-' . $i . 'week'));
            $latestPosts = Post::where('created_at', '>', $date)
                ->where('views', '>', '5')
                ->orderByDesc('created_at')
                ->get();
            if (count($latestPosts) > 3) {
                break;
            }
        }

        if (count($latestPosts)) {
            foreach ($latestPosts as $post) {
                $post->{'activity'} = $post->votes()->count() / $post->views;
            }
            $latestPosts = $latestPosts->sortByDesc('activity');
            $trendingPost = $latestPosts->first();
        }

        if ($trendingPost) {
            $excludeFromQuery = $trendingPost->id;
        } else {
            $excludeFromQuery = null;
        }

        $posts = DB::table('posts')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.body as snippet',
                'posts.image',
                'posts.views',
                'posts.slug',
                'posts.created_at',
                'users.nickname as author'
            )
            ->whereNot('posts.id', '=', $excludeFromQuery)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        if (count($posts)) {
            if (!$trendingPost) {
                $trendingPost = $posts->shift();
            } else {
                $posts->pop();
            }


            $trendingPost->{'upvotes'} = DB::table('votes')
                ->selectRaw('count(id) as votes')
                ->where('post_id', '=', $trendingPost->id)
                ->where('vote', '=', 1)
                ->pluck('votes')
                ->first();
            $trendingPost->{'downvotes'} = DB::table('votes')
                ->selectRaw('count(id) as votes')
                ->where('post_id', '=', $trendingPost->id)
                ->where('vote', '=', 2)
                ->pluck('votes')
                ->first();
            $trendingPost->{'snippet'} = $trendingPost->body;

            foreach ($posts as $post) {
                $post->{'upvotes'} = DB::table('votes')
                    ->selectRaw('count(id) as votes')
                    ->where('post_id', '=', $post->id)
                    ->where('vote', '=', 1)
                    ->pluck('votes')
                    ->first();
                $post->{'downvotes'} = DB::table('votes')
                    ->selectRaw('count(id) as votes')
                    ->where('post_id', '=', $post->id)
                    ->where('vote', '=', 2)
                    ->pluck('votes')
                    ->first();
            }
        }

        return view('home', ['posts' => $posts, 'trendingPost' => $trendingPost]);
    }

    public function articlesPage(Request $request)
    {
        $search = '';
        if (!$request->query('search')) {
            $posts = Post::select()->orderByDesc('created_at')->paginate(10);
        } else {
            $validator = Validator::make($request->all(), [
                'search' => 'string|nullable|max:255'
            ]);

            if ($validator->fails()) {
                return redirect()->route('post.articles');
            }

            $search = $validator->safe()->only('search')['search'];

            $posts = Post::select()
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
                ->orderByDesc('created_at')
                ->paginate(10);
        }

        return view('articles', ['posts' => $posts, 'search' => $search]);
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
                if (Auth::user() && Auth::user()->role === 'admin') {
                    $comment->{'owner'} = true;
                }
                return $comment;
            });
        }
        return $replies;
    }

    public function countComments($comments)
    {
        $amount = count($comments);

        foreach ($comments as $comment) {
            if (count($comment->replies)) {
                $amount += $this->countComments($comment->replies);
            }
        }

        return $amount;
    }

}
