<?php

namespace App\Http\Requests;

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
            'user_id' => $this->user()->id
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
            'subtitle' => 'required|array',
            'content' => 'required|array',
            'subtitle.*' => 'string|required|max:200',
            'content.*' => 'string|required|max:10000',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'string' => 'This field must be a string.',
            'max:200' => 'This field cannot be longer than 200 characters.',
            'max:10000' => 'The content of 1 section cannot be longer than 10000 characters'
        ];
    }
}
