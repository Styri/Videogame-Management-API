<?php

namespace App\Http\Requests\GameReview;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:10',
            'review' => 'required|string|max:1500'
        ];
    }
}
