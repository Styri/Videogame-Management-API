<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\DeleteGameRequest;
use App\Http\Requests\Game\IndexGameRequest;
use App\Http\Requests\Game\ShowGameRequest;
use App\Http\Requests\Game\StoreGameRequest;
use App\Http\Requests\Game\UpdateGameRequest;
use App\Models\Game;

class GameController extends Controller
{
    public function index(IndexGameRequest $request)
    {
        $query = Game::query();
        $validated = $request->validated();

        if (isset($validated['genre'])) {
            $query->where('genre', $validated['genre']);
        }

        if (isset($validated['sort'])) {
            $query->orderBy('release_date', $validated['sort']);
        }

        return response()->json($query->paginate(10));
    }

    public function show(ShowGameRequest $request, Game $game)
    {
        return response()->json($game);
    }

    public function store(StoreGameRequest $request)
    {
        $validated = $request->validated();

        $game = $request->user()->games()->create($validated);

        return response()->json($game, 201);
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        $validated = $request->validated();

        $game->update($validated);

        return response()->json($game);
    }

    public function destroy(DeleteGameRequest $request, Game $game)
    {

        $game->delete();

        return response()->json(null, 204);
    }
}
