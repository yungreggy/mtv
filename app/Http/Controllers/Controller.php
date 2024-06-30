<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\MusicVideo;
use App\Models\Album;
use App\Models\Artist;
use App\Models\BlocPub;
use App\Models\Label;
use App\Models\Pub;
use App\Models\Director;
use App\Models\Program;
use App\Models\Channel;
use App\Models\BrandStore;
use App\Models\Replay;
use App\Models\ProgramDate;
use App\Models\TvShow;
use App\Models\TvShowsSeason;
use App\Models\TvShowsEpisode;
use App\Models\ProgramSchedule;
use App\Models\DayOfWeek;
use App\Models\Tag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

abstract class Controller
{
    public function __construct()
    {
        $this->shareCommonVariables();
        $this->shareData();
    
    }

  
    
    protected function shareData()
    {
        $currentDate = Carbon::now()->toDateString();

        // Récupérer le programme en cours
        $currentProgram = Program::where('start_date', '<=', $currentDate)
                                ->where('end_date', '>=', $currentDate)
                                ->first();

        // Vérifier si l'ID du canal est stocké en session
        $currentChannelId = Session::get('current_channel_id');
        $currentChannel = $currentChannelId ? Channel::find($currentChannelId) : ($currentProgram ? $currentProgram->channel : null);

        // Si le canal actuel n'est pas en session, stocker celui du programme en cours
        if (!$currentChannelId && $currentChannel) {
            Session::put('current_channel_id', $currentChannel->id);
        }

        // Récupérer tous les canaux
        $channels = Channel::all();

        // Partager les canaux et le programme actuel avec toutes les vues
        View::share([
            'currentChannel' => $currentChannel,
            'channels' => $channels,
            'currentProgram' => $currentProgram,
        ]);

        // Debug: Afficher les valeurs de la session pour vérifier
        Log::info('Session Data:', session()->all());
    }


    protected function shareCommonVariables()
    {
        $films = Film::all();
        $filmGenres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
        $musicVideos = MusicVideo::all();
        $albums = Album::all();
        $labels = Label::all();
        $blocPubs = BlocPub::all();
        $brandsStores = BrandStore::all();
        $channels = Channel::all();
        $directors = Director::all();
        $programs = Program::all();
        $pubs = Pub::all();
        $artists = Artist::all();
        $replays = Replay::all();
        $tvShows = TvShow::all();
        $tvSeasons = TvShowsSeason::all();
        $episodes = TvShowsEpisode::all();
        $schedules = ProgramSchedule::all();
        $programDates = ProgramDate::pluck('date');
        $daysOfWeek = DayOfWeek::all();
        $tags = Tag::all(); // Ajouté ici
    
        // Supposons que nous voulons partager le premier channel pour l'exemple
        $channel = Channel::first();
        
        $dayOfWeek = Carbon::now()->format('l');
    
        View::share(compact('films', 'filmGenres', 'musicVideos', 'albums', 'labels', 'blocPubs', 'brandsStores', 'channels', 'directors', 'programs', 'pubs', 'artists', 'replays', 'tvShows', 'episodes', 'tvSeasons', 'schedules', 'programDates', 'dayOfWeek', 'daysOfWeek', 'tags', 'channel'));
    }
    
    
}



