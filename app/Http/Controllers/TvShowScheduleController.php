<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShowSchedule;
use App\Models\TvShow;
use App\Models\TvShowsSeason;
use App\Models\Genre;
use App\Models\Tag;
use App\Models\DayOfWeek;
use App\Models\Program;
use App\Models\ProgramSchedule;
use App\Models\ProgramDate;
use App\Models\TvShowsEpisode;
use Illuminate\Support\Facades\DB;

class TvShowScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = TvShowSchedule::all();
        return view('tvShowSchedules.index', compact('schedules'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::all();
        $tvShows = TvShow::orderBy('title', 'asc')->get(); // Tri par ordre alphabétique
        $seasons = TvShowsSeason::all();
        $daysOfWeek = DayOfWeek::all();
        $genres = Genre::all();
        $tags = Tag::all();
        
        return view('tvShowSchedules.create', compact('programs', 'tvShows', 'seasons', 'daysOfWeek', 'genres', 'tags'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'type' => $request->input('type', 'tv_show'),
            'continue_after_season' => $request->has('continue_after_season') ? (bool)$request->input('continue_after_season') : false,
        ]);
    
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recurrence' => 'required|string|in:none,daily,weekly',
            'genre_id' => 'nullable|exists:genres,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'age_rating' => 'nullable|array',
            'age_rating.*' => 'string|max:255',
            'tv_show_id' => 'required|exists:tv_shows,id',
            'season_id' => 'required|exists:tv_shows_seasons,id',
            'specific_date' => 'nullable|date',
            'type' => 'required|string|in:music_video,film,tv_show,other',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'exists:days_of_week,id',
            'continue_after_season' => 'nullable|boolean'
        ]);
    
        $overlappingSchedules = $this->checkScheduleConflicts($validatedData);
    
        if ($overlappingSchedules) {
            return redirect()->back()->withErrors(['duplicate' => 'Il y a un conflit d\'horaire avec une autre plage horaire.'])->withInput();
        }
    
        $schedule = $this->createSchedule($validatedData);
    
        if (isset($validatedData['days_of_week'])) {
            $schedule->daysOfWeek()->sync($validatedData['days_of_week']);
        }
    
        $this->handleTvShowScheduling($schedule, $validatedData);
    
        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Horaire de série TV créé avec succès.');
    }
    

private function createSchedule($data)
{
    return ProgramSchedule::create([
        'program_id' => $data['program_id'],
        'name' => $data['name'],
        'description' => $data['description'] ?? '',
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'status' => 'active',
        'type' => $data['type'] ?? 'tv_show',
        'continue_after_season' => $data['continue_after_season'] ?? null
    ]);
}



    private function linkProgramScheduleToDate(ProgramSchedule $schedule, ProgramDate $date, TvShowsEpisode $episode)
    {
        DB::table('program_date_program_schedule')->updateOrInsert(
            [
                'program_date_id' => $date->id,
                'program_schedule_id' => $schedule->id
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('program_schedule_episodes')->insert([
            'program_schedule_id' => $schedule->id,
            'episode_id' => $episode->id,
            'program_date_id' => $date->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    

    // Ajouter la méthode update pour inclure la vérification de chevauchement
   

private function checkScheduleConflicts($data)
{
    $query = ProgramSchedule::where('program_id', $data['program_id']);

    if (isset($data['specific_date'])) {
        // Si une date spécifique est donnée, vérifier les chevauchements sur cette date
        $query->whereHas('dates', function ($q) use ($data) {
            $q->where('date', $data['specific_date']);
        });
    } elseif (!empty($data['days_of_week'])) {
        // Si des jours de la semaine sont donnés, vérifier les chevauchements sur ces jours
        $query->whereHas('daysOfWeek', function ($q) use ($data) {
            $q->whereIn('days_of_week.id', $data['days_of_week']);
        });
    }

    // Vérifier les chevauchements de temps
    $query->where(function ($q) use ($data) {
        $q->where(function ($q) use ($data) {
            $q->where('start_time', '<', $data['end_time'])
              ->where('end_time', '>', $data['start_time']);
        });
        if (isset($data['id'])) {
            // Exclure l'horaire actuel en cas de mise à jour
            // Spécifier la table pour 'id' pour éviter l'ambiguïté
            $q->where('program_schedules.id', '!=', $data['id']);
        }
    });

    return $query->exists();
}




private function handleTvShowScheduling($schedule, $data)
{
    $seasonId = $data['season_id'];
    $tvShowId = $data['tv_show_id'];
    $episodes = TvShowsEpisode::where('season_id', $seasonId)->orderBy('episode_number')->get();
    $totalEpisodes = $episodes->count();
    $continueAfterSeason = $data['continue_after_season'] ?? false;

    if ($data['recurrence'] === 'none' && isset($data['specific_date'])) {
        $date = ProgramDate::where('date', $data['specific_date'])
                           ->where('program_id', $data['program_id'])
                           ->firstOrFail();
        $this->linkProgramScheduleToDate($schedule, $date, $episodes[0]);
    } else {
        $dates = ProgramDate::where('program_id', $data['program_id'])
                            ->whereIn(DB::raw('DAYOFWEEK(date)'), $data['days_of_week'])
                            ->orderBy('date')
                            ->get();

        $episodeIndex = 0;
        foreach ($dates as $date) {
            if ($episodeIndex >= $totalEpisodes) {
                if ($continueAfterSeason) {
                    $seasonId = $this->getNextSeasonId($tvShowId, $seasonId);
                    if (!$seasonId) break; // Si aucune autre saison, on arrête
                    $episodes = TvShowsEpisode::where('season_id', $seasonId)->orderBy('episode_number')->get();
                    $totalEpisodes = $episodes->count();
                    $episodeIndex = 0;
                } else {
                    break; // Arrêter après la saison sélectionnée
                }
            }
            $this->linkProgramScheduleToDate($schedule, $date, $episodes[$episodeIndex]);
            $episodeIndex++;
        }
    }
}

private function getNextSeasonId($tvShowId, $currentSeasonId)
{
    $currentSeason = TvShowsSeason::find($currentSeasonId);
    $nextSeason = TvShowsSeason::where('tv_show_id', $tvShowId)
                               ->where('season_number', '>', $currentSeason->season_number)
                               ->orderBy('season_number')
                               ->first();
    return $nextSeason ? $nextSeason->id : null;
}





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = TvShowSchedule::with(['program', 'tvShow', 'season', 'daysOfWeek', 'tags', 'genres'])->findOrFail($id);
        return view('tvShowSchedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = TvShowSchedule::findOrFail($id);
        $programs = Program::all();
        $tvShows = TvShow::all();
        $seasons = TvShowsSeason::all();
        $daysOfWeek = DayOfWeek::all();
        $genres = Genre::all();
        $tags = Tag::all();
        
        return view('tvShowSchedules.edit', compact('schedule', 'programs', 'tvShows', 'seasons', 'daysOfWeek', 'genres', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = TvShowSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('tvShowSchedules.index')->with('success', 'Tv Show Schedule deleted successfully.');
    }
}


