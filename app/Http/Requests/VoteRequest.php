<?php

namespace App\Http\Requests;

use App\Models\Vote;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'vote' => $this->vote ? (int)$this->vote : 1,
            'post_id' => (int)$this->postId
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vote' => [
                'required',
                Rule::in([
                    Vote::UPVOTE,
                    Vote::DOWNVOTE
                ])
            ],
            'post_id' => [
                'required',
                'exists:Posts,id'
            ],
            'user_id' => [
                'required',
                'exists:Users,id'
            ]
        ];
    }
}
