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

        if (isset($validated['sort'])) {
            $query->orderBy('release_date', $validated['sort']);
        }

        return response()->json($query->paginate(10));
    }
}
