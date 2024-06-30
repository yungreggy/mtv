<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramSchedule;
use App\Models\MusicVideo;
use App\Models\Film;
use App\Models\BlocPub;
use App\Models\TvShow;
use App\Models\Genre;

class ProgramScheduleController extends Controller
{
    // Méthode pour afficher le formulaire d'ajout de média
    public function addMedia(Request $request)
    {
        $schedule_id = $request->get('schedule_id');
        $mediaTypes = ['music_video', 'film', 'bloc_pub', 'tv_show'];
        $genres = Genre::orderBy('name')->get();
        $frequencies = ['daily', 'weekly', 'monthly', 'yearly'];

        $musicVideos = MusicVideo::all();
        $films = Film::all();
        $blocPubs = BlocPub::all();
        $tvShows = TvShow::all();

        return view('programSchedules.addMedia', compact('schedule_id', 'mediaTypes', 'genres', 'frequencies', 'musicVideos', 'films', 'blocPubs', 'tvShows'));
    }

    // Méthode pour stocker le média ajouté
    public function storeMedia(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:program_schedules,id',
            'media_type' => 'required|string',
            'genre_id' => 'required|exists:genres,id',
            'frequency' => 'required|string',
            'media_id' => 'required|integer',
        ]);

        $programSchedule = ProgramSchedule::find($request->schedule_id);

        switch ($request->media_type) {
            case 'music_video':
                $programSchedule->musicVideos()->attach($request->media_id);
                break;
            case 'film':
                $programSchedule->films()->attach($request->media_id);
                break;
            case 'bloc_pub':
                $programSchedule->blocPubs()->attach($request->media_id);
                break;
            case 'tv_show':
                $programSchedule->tvShows()->attach($request->media_id);
                break;
        }

        return redirect()->route('programSchedules.show', $request->schedule_id)
                         ->with('success', 'Média ajouté avec succès.');
    }
}
