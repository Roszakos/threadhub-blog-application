<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'You don\'t have access to that page');
        }

        $users = User::where('role', '=', 'user')
                    ->orderByDesc('created_at')
                    ->paginate(10, ['*'], 'users');
        $posts = Post::whereNot('id', '=', 'null')
                    ->orderByDesc('created_at')
                    ->paginate(10, ['*'], 'articles');

        return view('admin.dashboard', ['users' => $users, 'posts' => $posts]);
    }
}
