<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShow;
use App\Models\TvShowsSeason;

class TvShowSeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TvShow $tvShow, TvShowsSeason $season)
    {
        return view('tvShows.seasons.edit', compact('tvShow', 'season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TvShow $tvShow, TvShowsSeason $season)
    {
        $request->validate([
            'year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'episode_count' => 'required|integer',
        ]);

        $season->update($request->all());

        return redirect()->route('tvShows.show', $tvShow->id)->with('success', 'Saison mise à jour avec succès.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
