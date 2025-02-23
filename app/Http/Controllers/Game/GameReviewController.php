<?php
namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameReview\IndexGameReviewRequest;
use App\Http\Requests\GameReview\StoreGameReviewRequest;
use App\Models\Game;

class GameReviewController extends Controller
{
    public function index(IndexGameReviewRequest $request, Game $game)
    {
        $query = $game->reviews()->with('user');
        $validated = $request->validated();

        if (isset($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        if (isset($validated['sort_by']) && isset($validated['sort'])) {
            $query->orderBy($validated['sort_by'], $validated['sort']);
        }

        return response()->json($query->paginate(10));
    }

    public function store(StoreGameReviewRequest $request, Game $game)
    {
        $validated = $request->validated();

        $review = $game->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        return response()->json($review, 201);
    }
}

