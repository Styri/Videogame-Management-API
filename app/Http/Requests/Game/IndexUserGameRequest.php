<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserGameRequest extends FormRequest
{
    public function authorize(): bool
    {
       return auth()->check();
    }

    public function rules(): array
    {
        return [
            'genre' => 'nullable|string|max:50',
            'sort' => 'nullable|string|in:asc,desc'
        ];
    }
}
