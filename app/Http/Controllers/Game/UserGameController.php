<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\IndexGameRequest;
use App\Http\Requests\Game\IndexUserGameRequest;

class UserGameController extends Controller
{
    public function index(IndexUserGameRequest $request)
    {
        $query = $request->user()->games();
        $validated = $request->validated();

        if (isset($validated['genre'])) {
            $query->where('genre', $validated['genre']);
        }

        if (isset($validated['title'])) {
            $query->where('title', 'LIKE', '%' . $validated['title'] . '%');
        }

        if (isset($validated['developer'])) {
            $query->where('developer', 'LIKE', '%' . $validated['developer'] . '%');
        }

        if (isset($validated['publisher'])) {
            $query->where('publisher', 'LIKE', '%' . $validated['publisher'] . '%');
        }

        if (isset($validated['is_multi_player'])) {
            $query->where('is_multi_player', $validated['is_multi_player']);
        }

        if (isset($validated['is_single_player'])) {
            $query->where('is_single_player', $validated['is_single_player']);
        }

        if (isset($validated['sort_by']) && isset($validated['sort'])) {
            $query->orderBy($validated['sort_by'], $validated['sort']);
        }

        return response()->json($query->paginate(10));
    }
}
