<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Album;
use App\Models\MusicVideo;
use App\Models\Genre;
use App\Models\Film;
use App\Models\TvShow;
use App\Models\TvShowsSeason;
use App\Models\TvShowsEpisode;
use App\Models\Label;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Recherche par année
        $year = intval($query);
        
        $artists = Artist::where('name', 'LIKE', "%$query%")->get();
        $albums = Album::where('title', 'LIKE', "%$query%")->orWhere('year', $year)->get();
        $musicVideos = MusicVideo::where('title', 'LIKE', "%$query%")->orWhere('year', $year)->get();
        $genres = Genre::where('name', 'LIKE', "%$query%")->get();
        $films = Film::where('title', 'LIKE', "%$query%")->orWhere('year', $year)->get();
        $tvShows = TvShow::where('title', 'LIKE', "%$query%")->orWhere('years_active', 'LIKE', "%$year%")->get();
        $tvShowSeasons = TvShowsSeason::where('year', $year)->get();
        $tvShowEpisodes = TvShowsEpisode::whereYear('air_date', $year)->with('season.tvShow')->get(); // Charger les relations nécessaires
        $labels = Label::where('name', 'LIKE', "%$query%")->get();

        return view('search.results', compact('artists', 'albums', 'musicVideos', 'genres', 'films', 'tvShows', 'tvShowSeasons', 'tvShowEpisodes', 'labels', 'query'));
    }

    public function searchByYear($year)
    {
        $artists = Artist::whereYear('created_at', $year)->get();
        $albums = Album::whereYear('created_at', $year)->get();
        $musicVideos = MusicVideo::whereYear('created_at', $year)->get();
        $genres = Genre::whereYear('created_at', $year)->get();
        $films = Film::where('year', $year)->get();
        $tvShows = TvShow::whereYear('created_at', $year)->get();
        $tvShowSeasons = TvShowsSeason::whereYear('created_at', $year)->get();
        $tvShowEpisodes = TvShowsEpisode::whereYear('created_at', $year)->get();
        $labels = Label::whereYear('created_at', $year)->get();

        return view('search.results', [
            'query' => $year,
            'artists' => $artists,
            'albums' => $albums,
            'musicVideos' => $musicVideos,
            'genres' => $genres,
            'films' => $films,
            'tvShows' => $tvShows,
            'tvShowSeasons' => $tvShowSeasons,
            'tvShowEpisodes' => $tvShowEpisodes,
            'labels' => $labels,
        ]);
    }
}


