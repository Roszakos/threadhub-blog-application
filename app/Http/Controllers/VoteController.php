<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\VoteRequest;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function store(VoteRequest $request)
    {
        $data = $request->validated();

        if (Vote::create($data)) {
            return response('success', 200);
        } else {
            return response('error', 500);
        }  
    }

    public function update(VoteRequest $request)
    {
        $data = $request->validated();

        $vote = Vote::select()
                    ->where('post_id', '=', $data['post_id'])
                    ->where('user_id', '=', $data['user_id'])
                    ->first();

        if ($vote->update(['vote' => $data['vote']])) {
            return response('success', 200);
        } else {
            return response('error', 500);
        }
    }

    public function destroy(VoteRequest $request)
    {
        $data = $request->validated();

        $vote = Vote::select()
            ->where('post_id', '=', $data['post_id'])
            ->where('user_id', '=', $data['user_id'])
            ->first();

        if ($vote->delete()) {
            return response('success', 200);
        } else {
            return response('error', 500);
        }
    }
}
