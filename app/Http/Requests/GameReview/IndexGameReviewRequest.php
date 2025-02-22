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
        return [];
    }
}
