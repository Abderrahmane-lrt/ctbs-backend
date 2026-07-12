<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Showtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'cinema']);

        if ($request->filled('city')) {
            $query->whereHas('cinema', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        if ($request->filled('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }

        $showtimes = $query->orderBy('start_time', 'asc')->get();

        return response()->json(['showtimes' => $showtimes]);
    }

    public function show(Showtime $showtime)
    {
        $showtime->load(['movie', 'cinema']);

        return response()->json(['showtime' => $showtime]);
    }

    public function store(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'cinema_id' => 'required|exists:cinemas,id',
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_time' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
        ]);

        if (! $this->userOwnsCinema($request->user(), $fields['cinema_id'])) {
            return response()->json([
                'message' => 'You do not own this cinema.',
            ], 403);
        }

        $showtime = Showtime::create($fields);

        return response()->json([
            'message' => 'Showtime scheduled successfully.',
            'showtime' => $showtime->load(['movie', 'cinema']),
        ], 201);
    }

    public function update(Request $request, Showtime $showtime): JsonResponse
    {
        if (! $this->userOwnsCinema($request->user(), $showtime->cinema_id)) {
            return response()->json([
                'message' => 'You do not own this cinema.',
            ], 403);
        }

        $fields = $request->validate([
            'movie_id' => 'sometimes|exists:movies,id',
            'cinema_id' => 'sometimes|exists:cinemas,id',
            'room_name' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'start_time' => 'sometimes|date|after:now',
            'price' => 'sometimes|numeric|min:0',
        ]);

        if (isset($fields['cinema_id']) && ! $this->userOwnsCinema($request->user(), $fields['cinema_id'])) {
            return response()->json([
                'message' => 'You do not own the target cinema.',
            ], 403);
        }

        $showtime->update($fields);

        return response()->json([
            'message' => 'Showtime updated successfully.',
            'showtime' => $showtime->load(['movie', 'cinema']),
        ]);
    }

    public function destroy(Request $request, Showtime $showtime): JsonResponse
    {
        if (! $this->userOwnsCinema($request->user(), $showtime->cinema_id)) {
            return response()->json([
                'message' => 'You do not own this cinema.',
            ], 403);
        }

        $showtime->delete();

        return response()->json([
            'message' => 'Showtime deleted successfully.',
        ]);
    }

    private function userOwnsCinema($user, int $cinemaId): bool
    {
        return Cinema::where('id', $cinemaId)->where('owner_id', $user->id)->exists();
    }
}
