<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class IndexGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'genre' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:150',
            'developer' => 'nullable|string|max:75',
            'publisher' => 'nullable|string|max:75',
            'is_multi_player' => 'nullable|boolean',
            'is_single_player' => 'nullable|boolean',
            'sort' => 'nullable|string|in:asc,desc',
            'sort_by' => 'nullable|string|in:title,release_date,created_at'
        ];
    }
}
