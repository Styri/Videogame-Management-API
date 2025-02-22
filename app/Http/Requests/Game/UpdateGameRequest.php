<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
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
        return [
                'title' => 'required|string|max:150|unique:games,title,'.$this->game->id,
                'description' => 'required|string|max:255',
                'release_date' => 'required|date',
                'genre' => 'required|string|max:50',
                'publisher' => 'required|string|max:75',
                'developer' => 'required|string|max:75',
                'is_multi_player' => 'boolean',
                'is_single_player' => 'boolean'
        ];
    }
}
