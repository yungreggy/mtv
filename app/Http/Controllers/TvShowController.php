<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShow;
use App\Models\TvShowsSeason;
use App\Models\Genre;


class TvShowController extends Controller
{
    public function index()
{
    $tvShows = TvShow::with('genres')->get();
    return view('tvShows.index', compact('tvShows'));
}


public function getSeasons(TvShow $tvShow)
{
    // Assurez-vous que la relation 'seasons' est définie dans le modèle TvShow
    $seasons = $tvShow->seasons()->get();

    return response()->json(['seasons' => $seasons]);
}

    public function create()
    {
        $genres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
        return view('tvShows.create', compact('genres'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'years_active' => 'nullable|string',
        'genre_ids' => 'nullable|array',
        'genre_ids.*' => 'integer|exists:genres,id',
        'description' => 'nullable|string',
        'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'creator' => 'nullable|string',
        'season_count' => 'required|integer|min:1',
        'target_audience' => 'nullable|string',
        'official_website' => 'nullable|url',
        'status' => 'nullable|string',
        'country_of_origin' => 'nullable|string',
    ]);

    $posterPath = null;
    if ($request->hasFile('poster')) {
        $posterPath = $request->file('poster')->store('posters', 'public');
    }

    $tvShow = TvShow::create([
        'title' => $request->title,
        'years_active' => $request->years_active,
        'description' => $request->description,
        'poster' => $posterPath,
        'creator' => $request->creator,
        'season_count' => $request->season_count,
        'target_audience' => $request->target_audience,
        'official_website' => $request->official_website,
        'status' => $request->status,
        'country_of_origin' => $request->country_of_origin,
    ]);

    // Attacher les genres
    if ($request->genre_ids) {
        $tvShow->genres()->attach($request->genre_ids);
    }

    // Créer automatiquement les saisons
    for ($i = 1; $i <= $request->season_count; $i++) {
        TvShowsSeason::create([
            'tv_show_id' => $tvShow->id,
            'season_number' => $i,
            'year' => null,
            'start_date' => null,
            'end_date' => null,
            'episode_count' => 0,
            'description' => 'Saison ' . $i,
            'thumbnail_image' => '',
            'streaming_url' => '',
        ]);
    }

    return redirect()->route('tvShows.index')->with('success', 'Série TV et ses saisons ajoutées avec succès.');
}

    
    
    
public function show(TvShow $tvShow)
{
    $genres = Genre::where('type', 'film')->get();
    $tvShow->load('seasons.episodes');
    return view('tvShows.show', compact('tvShow', 'genres'));
}

public function edit(TvShow $tvShow)
{
    $genres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
    return view('tvShows.edit', compact('tvShow', 'genres'));
}


    public function update(Request $request, TvShow $tvShow)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'years_active' => 'required|string|max:255',
            'description' => 'required|string',
            'creator' => 'required|string|max:255',
            'season_count' => 'required|integer|min:1',
            'target_audience' => 'required|string|max:255',
            'status' => 'required|string|max:100',
            'country_of_origin' => 'required|string|max:100',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:genres,id',
        ]);
    
        $data = $request->except('poster');
    
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }
    
        $tvShow->update($data);
    
        // Mettre à jour les genres
        if ($request->genre_ids) {
            $tvShow->genres()->sync($request->genre_ids);
        }
    
        // Mise à jour des saisons
        $currentSeasonCount = $tvShow->seasons()->count();
        $newSeasonCount = $request->season_count;
    
        if ($newSeasonCount > $currentSeasonCount) {
            for ($i = $currentSeasonCount + 1; $i <= $newSeasonCount; $i++) {
                TvShowsSeason::create([
                    'tv_show_id' => $tvShow->id,
                    'season_number' => $i,
                    'year' => null,
                    'start_date' => null,
                    'end_date' => null,
                    'episode_count' => 0,
                    'description' => 'Saison ' . $i,
                    'thumbnail_image' => '',
                    'streaming_url' => '',
                ]);
            }
        } elseif ($newSeasonCount < $currentSeasonCount) {
            $tvShow->seasons()->where('season_number', '>', $newSeasonCount)->delete();
        }
    
        return redirect()->route('tvShows.show', $tvShow->id)->with('success', 'Série TV mise à jour avec succès.');
    }

    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();

        return redirect()->route('tvShows.index')->with('success', 'Série TV supprimée avec succès.');
    }
}
