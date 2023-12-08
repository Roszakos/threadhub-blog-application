<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        $posts = $user->posts->sortByDesc('created_at');
        $comments = $user->comments->sortByDesc('created_at')->map(function ($comment) use ($user) {
            $dt = Carbon::create($comment->created_at);
            $comment->{'posted'} = $dt->diffForHumans();
            $comment->{'postSlug'} = DB::table('posts')
                                        ->select('slug')
                                        ->where('id', '=', $comment->post_id)
                                        ->value('slug');
            $comment->author = $user->nickname;
            return $comment;
        })->filter(function ($comment) {
            return $comment->postSlug;
        });

        return view('user-profile', ['user' => $user, 'posts' => $posts, 'comments' => $comments, 'accountOwner' => $accountOwner]);
    }
}
