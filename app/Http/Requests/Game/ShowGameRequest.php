<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class ShowGameRequest extends FormRequest
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
