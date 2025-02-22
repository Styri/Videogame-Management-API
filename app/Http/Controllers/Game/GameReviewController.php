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
        $reviews = $game->reviews()->with('user')->paginate(10);

        return response()->json($reviews);
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

