<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use App\Models\TvShowsEpisode;
use App\Models\TvShowsSeason;
use App\Models\Director;
use Illuminate\Http\Request;

class TvShowEpisodeController extends Controller
{
    public function index(TvShow $tvShow)
    {
        $episodes = $tvShow->episodes;
        return view('tvShows.episodes.index', compact('tvShow', 'episodes'));
    }

    public function create(TvShow $tvShow)
    {
        return view('tvShows.episodes.create', compact('tvShow'));
    }
  
    public function createMultiple(TvShow $tvShow, TvShowsSeason $season)
    {
        return view('tvShows.episodes.createMultiple', compact('tvShow', 'season'));
    }

    public function storeMultiple(Request $request, TvShow $tvShow, TvShowsSeason $season)
    {
        $request->validate([
            'episode_count' => 'required|integer|min:1',
        ]);

        for ($i = 1; $i <= $request->episode_count; $i++) {
            TvShowsEpisode::create([
                'season_id' => $season->id,
                'episode_number' => $i,
                'title' => 'Épisode ' . $i,
                'air_date' => null,
                'description' => '',
                'duration' => null,
            ]);
        }

        // Mettre à jour le nombre d'épisodes dans la saison
        $season->episode_count = $season->episodes()->count();
        $season->save();

        return redirect()->route('tvShows.show', $tvShow->id)->with('success', 'Épisodes ajoutés avec succès.');
    }


    public function store(Request $request, TvShow $tvShow)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'file_path' => 'nullable|string',
            'episode_number' => 'required|integer',
            'overall_episode_number' => 'required|integer',
            'air_date' => 'required|date',
            'guest_stars' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'director_id' => 'nullable|exists:directors,id',
            'writer' => 'nullable|string',
            'streaming_url' => 'nullable|string',
        ]);

        $tvShow->episodes()->create($validatedData);
        return redirect()->route('tvShows.episodes.index', $tvShow->id)->with('success', 'Épisode ajouté avec succès!');
    }
    
    
    public function show(TvShow $tvShow, TvShowsSeason $season, TvShowsEpisode $episode)
{
    // Charger explicitement la saison de l'épisode et la série TV associée
    $season = $episode->season;
    $tvShow = $season->tvShow;

    // Récupérer l'épisode suivant
    $nextEpisode = TvShowsEpisode::where('season_id', $season->id)
                    ->where('episode_number', '>', $episode->episode_number)
                    ->orderBy('episode_number')
                    ->first();

    // Vérifier si la durée n'est pas nulle avant de convertir en minutes
    $durationInMinutes = null;
    if ($episode->duration) {
        $duration = \Carbon\Carbon::createFromFormat('H:i:s', $episode->duration);
        $durationInMinutes = $duration->hour * 60 + $duration->minute;
    }

    return view('tvShows.episodes.show', compact('tvShow', 'season', 'episode', 'nextEpisode', 'durationInMinutes'));
}

    
    
    
    
    
public function edit(TvShow $tvShow, TvShowsSeason $season, TvShowsEpisode $episode)
{
    $directors = Director::orderBy('name', 'asc')->get();

    // Charger explicitement la saison de l'épisode et la série TV associée
    $season = $episode->season;
    $tvShow = $season->tvShow;

    // Récupérer l'épisode suivant
    $nextEpisode = TvShowsEpisode::where('season_id', $season->id)
                    ->where('episode_number', '>', $episode->episode_number)
                    ->orderBy('episode_number')
                    ->first();

    // Récupérer l'épisode précédent
    $previousEpisode = TvShowsEpisode::where('season_id', $season->id)
                        ->where('episode_number', '<', $episode->episode_number)
                        ->orderBy('episode_number', 'desc')
                        ->first();

    return view('tvShows.episodes.edit', compact('tvShow', 'season', 'episode', 'directors', 'previousEpisode', 'nextEpisode'));
}







    

    
public function update(Request $request, TvShow $tvShow, TvShowsSeason $season, TvShowsEpisode $episode)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'episode_number' => 'required|integer|min:1',
        'air_date' => 'nullable|date',
        'description' => 'nullable|string',
        'duration' => 'nullable|date_format:H:i',
        'file_path' => 'nullable|string|max:255',
    ]);

    $episode->title = $request->input('title');
    $episode->episode_number = $request->input('episode_number');
    $episode->air_date = $request->input('air_date');
    $episode->description = $request->input('description');

    // Mettez à jour le chemin du fichier si nécessaire
    if ($request->has('file_path')) {
        $episode->file_path = $request->input('file_path');
    }

    // Assurez-vous que la durée est bien formatée
    if ($request->has('duration')) {
        $episode->duration = $request->input('duration');
    }

    $episode->save();

    return redirect()->route('tvShows.episodes.edit', ['tvShow' => $tvShow->id, 'season' => $season->id, 'episode' => $episode->id])
                     ->with('success', 'Épisode mis à jour avec succès.');
}






    public function destroy(TvShow $tvShow, TvShowsEpisode $episode)
    {
        $episode->delete();
        return redirect()->route('tvShows.episodes.index', $tvShow->id)->with('success', 'Épisode supprimé avec succès!');
    }
}
