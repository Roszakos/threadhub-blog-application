<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'user_id' => 'exists:users,id',
            'title' => 'required|string|max:200',
            'imageAction' => 'nullable|string',
            'image' => [
                'nullable',
                File::image()
                    ->max(10000),
            ],
            'body' => 'required|min:50|max:10000',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'This field is required.',
            'string' => 'This field must be a string.',
            'title.max' => 'Title cannot be longer than 200 characters.',
            'body.min' => 'Article must be at least 50 characters long', 
            'body.max' => 'Article cannot be longer than 10000 characters',
        ];
    }
}
