<?php

namespace App\Http\Requests\GameReview;

use Illuminate\Foundation\Http\FormRequest;

class IndexGameReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'sort' => 'nullable|string|in:asc,desc',
            'sort_by' => 'nullable|string|in:rating,created_at'
        ];
    }
}
