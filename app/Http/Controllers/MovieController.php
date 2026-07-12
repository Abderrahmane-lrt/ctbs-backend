<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $movies = Movie::paginate(5);

        return response()->json([
            'movies' => $movies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'genre' => 'required|string',
            'poster_url' => 'nullable|url',
        ]);
        $movie = Movie::create($fields);

        return response()->json([
            'message' => 'Movie added Successfully!',
            'movie' => $movie
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return response()->json([
            'movie' => $movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        // validate movie 
        $fields = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration_minutes' => 'sometimes|integer',
            'genre' => 'sometimes|string',
            'poster_url' => 'nullable|url',
        ]);

        // update movie
        $movie->update($fields);

        return response()->json([
            'message' => 'Movie Updated Successfuly',
            'movie' => $movie
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
        $movie->delete();
        return response()->json([
            'message' => 'Movie Deleted Successfully'
        ], 200);
    }
}
