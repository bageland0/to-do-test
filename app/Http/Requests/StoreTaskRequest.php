<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'todo_id' => ['required', 'exists:todos,id'],
            'user_id' => ['exists:users,id'],
            'text' => ['required', 'string', 'min:1', 'max:526'],
            'image' => ['image'],
            'thumbnail' => ['image'],
        ];
    }
}