<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        // $posts = DB::table('posts')
        //             ->join('votes', 'votes.post_id', '=', 'posts.id')
        //             ->select('title', 'image', 'slug', 'created_at');
        $posts = $user->posts;
        $comments = $user->comments;
        return view('user-profile', ['user' => $user, 'posts' => $posts, 'comments' => $comments]);
    }
}
