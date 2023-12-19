<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(User $user, Request $request)
    {
        if ($request->user() && $user->id === $request->user()->id) {
            $accountOwner = true;
        } else {
            $accountOwner = false;
        }        

        $posts = Post::where('user_id', '=', $user->id)->orderByDesc('created_at')->paginate(10, ['*'], 'posts');
        $comments = DB::table('comments')
                    ->join('posts', 'posts.id', '=', 'comments.post_id')
                    ->select('comments.id', 'comments.post_id', 'comments.user_id', 'comments.content', 'comments.created_at', 'posts.slug as postSlug')
                    ->where('comments.user_id', '=', $user->id)
                    ->orderByDesc('comments.created_at')->paginate(15, ['*'], 'comments');

        foreach ($comments as $comment) {
            $dt = Carbon::create($comment->created_at);
            $comment->{'posted'} = $dt->diffForHumans();
            $comment->author = $user->nickname;
        }
        return view('user-profile', ['user' => $user, 'posts' => $posts, 'comments' => $comments, 'accountOwner' => $accountOwner]);
    }

    public function destroy(User $user, Request $request) 
    {
        if ($request->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action');
        }
        $request->session()->flash('userId', $user->id);
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password']
        ]);

        $user->comments()->delete();
        $user->posts()->delete();
        $user->votes()->delete();
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }
}
