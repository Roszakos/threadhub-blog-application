<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'post_id' => [
                'required',
                'exists:Posts,id'
            ],
            'user_id' => [
                'nullable',
                'exists:Users,id'
            ],
            'content' => [
                'required',
                'string',
                'max:1000'
            ],
            'parent_id' => [
                'nullable',
                'exists:Comments,id'
            ]
        ]);

        $validator->sometimes('author', 'required|string|max:100', function ($request) {
            return !$request->user_id;
        });

        $data = $validator->validated();

        return (Comment::create($data));
    }

    public function update(Comment $comment, Request $request)
    {
        if ($request->user()->cannot('update', $comment)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }
        $data = $request->validate([
            'content' => [
                'required',
                'string',
                'max:1000'
            ]
        ]);
        $comment->content = $data['content'];
        $comment->save();

        return response('success', 200);
    }

    public function destroy(Comment $comment, Request $request)
    {
        if ($request->user()->cannot('delete', $comment)) {
            return redirect('dashboard')->with('error', 'Unauthorized action.');
        }

        if (str_contains($request->header('referer'), 'user')) {
            if ($comment->delete()) {
                return redirect()->route('user.show', $comment->user_id)->with(['status' => 'success']);
            } else {
                return redirect()->route('user.show', $comment->user_id)->with(['status' => 'fail']);
            }
        } else {
            if ($comment->delete()) {
                return response('success', 200);
            } else {
                return response('error', 500);
            }
        }
    }
}
