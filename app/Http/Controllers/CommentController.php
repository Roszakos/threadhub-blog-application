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
                'max:500'
            ],
            'parent_id' => [
                'nullable',
                'exists:Comments,id'
            ]
        ]);

        $validator->sometimes('author', 'required|string|max:100', function($request) {
            return !$request->user_id;
        });

        $data = $validator->validated();

        return (Comment::create($data));
    }

    public function update(Comment $comment, Request $request)
    {
        $data = $request->validate([
            'content' => [
                'required',
                'string',
                'max:500'
            ]
        ]);
        $comment->content = $data['content'];
        $comment->save();

        return response('success', 200);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->delete()) {
            return response('success', 200);
        } else {
            return response('error', 500);
        }
    }
}
