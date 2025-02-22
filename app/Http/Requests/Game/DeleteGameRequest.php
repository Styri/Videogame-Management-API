<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class DeleteGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (
                $this->game->user_id === auth()->id() ||
                auth()->user()->role->name === 'Admin'
            );
    }

    public function rules(): array
    {
        return [];
    }
}
