<?php

namespace App\Http\Controllers;

use App\Models\ProgramSchedule;
use App\Models\MusicVideo;
use App\Models\Program;
use App\Models\Film;
use App\Models\ProgramDate;
use App\Models\BlocPub;
use App\Models\TvShow;
use App\Models\TvShowsEpisode;
use App\Models\TvShowsSeason;
use App\Models\Genre;
use App\Models\ScheduleDay;
use App\Models\Replay;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProgramScheduleController extends Controller
{
    public function index()
    {
        $schedules = ProgramSchedule::all();
        return view('programSchedules.index', compact('schedules'));
    }

    // public function create(Request $request)
    // {
    //     $type = $request->query('type', 'default');

    //     return view('programSchedules.create', compact('type'));
    // }


    public function createWithDate($programId, $date)
    {
        $program = Program::findOrFail($programId);
        $dayOfWeek = Carbon::parse($date)->format('l'); // Obtenir le jour de la semaine en format long (e.g., "Monday")
        return view('programSchedules.createWithDate', compact('program', 'date', 'dayOfWeek'));
    }


    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'nullable|string|max:100',
            'priority' => 'nullable|integer',
            'days_of_week' => 'required|array',
            'days_of_week.*' => 'string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'replay_days_of_week.*' => 'array',
            'replay_start_time.*' => 'required_with:replay_days_of_week.*|date_format:H:i',
            'replay_end_time.*' => 'required_with:replay_days_of_week.*|date_format:H:i',
        ]);

        // Création du programme
        $programSchedule = ProgramSchedule::create([
            'program_id' => $request->program_id,
            'name' => $request->name,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'special_notes' => $request->special_notes,
            'priority' => $request->priority,
        ]);

        // Liaison des jours de diffusion
        if ($request->has('days_of_week')) {
            foreach ($request->days_of_week as $day) {
                ScheduleDay::create([
                    'program_schedule_id' => $programSchedule->id,
                    'day_of_week' => $day,
                ]);
            }
        }

        // Création des rediffusions
        if ($request->has('replay_days_of_week')) {
            foreach ($request->replay_days_of_week as $index => $days) {
                foreach ($days as $day) {
                    $scheduleDay = ScheduleDay::where('day_of_week', $day)->first();
                    if ($scheduleDay) {
                        Replay::create([
                            'program_schedule_id' => $programSchedule->id,
                            'schedule_day_id' => $scheduleDay->id,
                            'start_time' => $request->replay_start_time[$index],
                            'end_time' => $request->replay_end_time[$index],
                            'description' => $request->description, // Ou une autre description spécifique à la rediffusion
                        ]);
                    }
                }
            }
        }

        return redirect()->route('programSchedules.index')->with('success', 'Programme ajouté avec succès.');
    }

    public function storeWithDate(Request $request, $programId, $date)
    {
        $validatedData = $this->validateRequest($request);

        $programSchedule = $this->createProgramSchedule($validatedData, $programId, $date);

        $programDate = $this->attachScheduleToDate($programSchedule, $programId, $date);

        $this->handleRepetitions($request, $programSchedule, $programDate, $date, $programId);

        $this->attachSpecificContent($request, $programSchedule);

        return redirect()->route('programDates.show', ['program' => $programId, 'date' => $date])
            ->with('success', 'Programme ajouté avec succès.');
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'repeat' => 'nullable|string|in:none,daily,weekly',
            'content_type' => 'required|string|in:clip,film,tv_show',
            'genre_id' => 'nullable|exists:genres,id',
            'age_rating' => 'nullable|string',
            'film_id' => 'nullable|exists:films,id',
            'tv_show_id' => 'nullable|exists:tv_shows,id',
            'type' => 'nullable|string'
        ]);
    }

    protected function createProgramSchedule($data, $programId, $date)
    {
        return ProgramSchedule::create([
            'program_id' => $programId,
            'name' => $data['name'],
            'description' => $data['description'],
            'start_time' => $date . ' ' . $data['start_time'],
            'end_time' => $date . ' ' . $data['end_time'],
            'type' => $data['type']
        ]);
    }

    protected function handleRepetitions(Request $request, $programSchedule, $programDate, $date, $programId)
    {
        if ($request->repeat && $request->repeat !== 'none') {
            $startDate = Carbon::parse($date);
            $endDate = Carbon::parse($programDate->program->end_date);
            $interval = $request->repeat === 'daily' ? 'P1D' : 'P1W';

            $dates = new \DatePeriod($startDate, new \DateInterval($interval), $endDate);

            foreach ($dates as $repetitionDate) {
                if ($repetitionDate->format('Y-m-d') !== $date) { // Ne pas répéter pour la date de départ
                    $this->createRepetition($programSchedule, $request, $repetitionDate, $programId);
                }
            }
        }
    }

    protected function attachScheduleToDate($programSchedule, $programId, $date)
    {
        $programDate = ProgramDate::where('program_id', $programId)->where('date', $date)->firstOrFail();
        $programDate->schedules()->attach($programSchedule->id);

        return $programDate;
    }

    protected function createRepetition($programSchedule, $request, $repetitionDate, $programId)
    {
        $newSchedule = $programSchedule->replicate();
        $newSchedule->start_time = $repetitionDate->format('Y-m-d') . ' ' . $request->start_time;
        $newSchedule->end_time = $repetitionDate->format('Y-m-d') . ' ' . $request->end_time;

        // Vérifier les doublons avant de sauvegarder
        $existingSchedule = ProgramSchedule::where('program_id', $programId)
            ->where('start_time', $newSchedule->start_time)
            ->where('end_time', $newSchedule->end_time)
            ->first();

        if (!$existingSchedule) {
            $newSchedule->save();

            $repetitionProgramDate = ProgramDate::firstOrCreate([
                'program_id' => $programId,
                'date' => $repetitionDate->format('Y-m-d'),
            ]);

            $repetitionProgramDate->schedules()->attach($newSchedule->id);
        }
    }

    protected function attachSpecificContent(Request $request, $programSchedule)
    {
        if ($request->content_type === 'film' && $request->film_id) {
            DB::table('program_schedule_films')->insert([
                'program_schedule_id' => $programSchedule->id,
                'film_id' => $request->film_id,
                'genre_id' => $request->genre_id,
                'age_rating' => $request->age_rating,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($request->content_type === 'tv_show' && $request->tv_show_id) {
            $programSchedule->tvShows()->attach($request->tv_show_id);
        }
    }






    // ProgramScheduleController.php
    public function show($id)
{
    $schedule = ProgramSchedule::with(['program', 'musicVideos' => function ($query) {
        $query->orderBy('schedule_music_videos.play_time', 'asc');  // Assure le tri par heure de diffusion
    }, 'films.genres', 'episodes.season.tvShow'])->findOrFail($id); // Inclure la relation films, genres, et épisodes avec saison et série

    $musicVideos = MusicVideo::all();
    $genres = Genre::orderBy('name', 'asc')->get();
    $films = Film::with('genres')->get();

    // Récupérer les films associés triés par date de diffusion
    $filmsWithDates = DB::table('program_schedule_films')
        ->join('films', 'program_schedule_films.film_id', '=', 'films.id')
        ->join('program_dates', 'program_schedule_films.program_date_id', '=', 'program_dates.id')
        ->where('program_schedule_films.program_schedule_id', $id)
        ->orderBy('program_dates.date', 'asc')
        ->select('films.*', 'program_dates.date as diffusion_date', 'program_schedule_films.age_rating', 'program_schedule_films.genre_id', 'program_schedule_films.program_date_id')
        ->get()
        ->map(function ($film) {
            $filmGenres = Film::find($film->id)->genres->pluck('name')->toArray();
            $film->genres = implode(', ', $filmGenres);
            return $film;
        });

    // Grouper les genres par catégorie
    $groupedGenres = $genres->groupBy('category');

    // Récupérer les épisodes associés triés par date de diffusion
    $episodesWithDates = $schedule->episodes()->with('season.tvShow')->get()->map(function ($episode) use ($id) {
        $episode->diffusion_date = DB::table('program_schedule_episodes')
            ->join('program_dates', 'program_schedule_episodes.program_date_id', '=', 'program_dates.id')
            ->where('program_schedule_episodes.program_schedule_id', $id)
            ->where('program_schedule_episodes.episode_id', $episode->id)
            ->orderBy('program_dates.date', 'asc')
            ->value('program_dates.date');
        return $episode;
    });

    return view('programSchedules.show', compact('schedule', 'musicVideos', 'groupedGenres', 'films', 'filmsWithDates', 'episodesWithDates'));
}

    
    



    public function edit($id)
    {
        $schedule = ProgramSchedule::findOrFail($id);
        return view('programSchedules.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $schedule = ProgramSchedule::findOrFail($id);

        $validatedData = $request->validate([
            'program_id' => 'required|integer|exists:programs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'repeat_schedule' => 'nullable|string',
            'status' => 'required|string',
            'special_notes' => 'nullable|string',
            'priority' => 'required|integer'
        ]);

        $schedule->update($validatedData);
        return redirect()->route('programSchedules.index')->with('success', 'Schedule updated successfully!');
    }

    public function destroy($id)
    {
        $schedule = ProgramSchedule::findOrFail($id);

        // Détacher les relations
        $schedule->replays()->delete();
        $schedule->days()->delete();
        $schedule->musicVideos()->detach();
        $schedule->films()->detach();
        $schedule->blocPubs()->detach();
       

        // Supprimer le ProgramSchedule
        $schedule->delete();

        // Rediriger avec un message de succès
        return redirect()->route('programSchedules.index')->with('success', 'Schedule deleted successfully!');
    }




    // ProgramScheduleController.php

    public function addMusicVideo(Request $request, $id)
    {
        $request->validate([
            'music_video_id' => 'required|exists:music_videos,id',
            'day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);

        $schedule = ProgramSchedule::findOrFail($id);
        $musicVideo = MusicVideo::findOrFail($request->input('music_video_id'));

        // Vérifier que le jour de la semaine est associé au programme
        $associatedDays = $schedule->days->pluck('day_of_week')->toArray();
        if (!in_array($request->input('day_of_week'), $associatedDays)) {
            return redirect()->route('programSchedules.show', $schedule->id)->withErrors(['day_of_week' => 'Le jour sélectionné n\'est pas associé à cet horaire.']);
        }

        // Calculer l'heure de diffusion pour la nouvelle vidéo musicale
        $playTime = $this->calculatePlayTime($schedule, $request->input('day_of_week'));

        // Ajouter la vidéo musicale à l'horaire avec l'heure de diffusion calculée et le jour de la semaine
        $schedule->musicVideos()->attach($musicVideo->id, [
            'play_time' => $playTime,
            'day_of_week' => $request->input('day_of_week')
        ]);

        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Vidéo musicale ajoutée avec succès.');
    }




    protected function calculatePlayTime($schedule)
    {
        $startTime = Carbon::parse($schedule->start_time);
        $totalDuration = 0;

        foreach ($schedule->musicVideos as $video) {
            $totalDuration += $video->getDurationInSecondsAttribute();
        }

        return $startTime->addSeconds($totalDuration);
    }

    protected function recalculatePlayTimes($schedule)
    {
        $startTime = Carbon::parse($schedule->start_time);
        $totalDuration = 0;

        foreach ($schedule->musicVideos()->orderBy('pivot_play_time')->get() as $video) {
            $playTime = $startTime->copy()->addSeconds($totalDuration);
            $schedule->musicVideos()->updateExistingPivot($video->id, ['play_time' => $playTime]);
            $totalDuration += $video->getDurationInSecondsAttribute();
        }
    }

    public function removeMusicVideo($scheduleId, $musicVideoId)
    {
        $schedule = ProgramSchedule::findOrFail($scheduleId);
        $schedule->musicVideos()->detach($musicVideoId);

        // Recalculer les heures de diffusion
        $this->recalculatePlayTimes($schedule);

        return redirect()->route('programSchedules.show', $scheduleId)->with('success', 'Vidéo musicale supprimée avec succès.');
    }

    public function moveMusicVideo($scheduleId, $musicVideoId, $direction)
    {
        $schedule = ProgramSchedule::findOrFail($scheduleId);
        $musicVideos = $schedule->musicVideos()->orderBy('pivot_play_time', 'asc')->get();
        $musicVideoIndex = $musicVideos->search(function ($item) use ($musicVideoId) {
            return $item->id == $musicVideoId;
        });

        if ($direction === 'up' && $musicVideoIndex > 0) {
            $musicVideos->splice($musicVideoIndex - 1, 0, [$musicVideos->splice($musicVideoIndex, 1)[0]]);
        } elseif ($direction === 'down' && $musicVideoIndex < count($musicVideos) - 1) {
            $musicVideos->splice($musicVideoIndex + 1, 0, [$musicVideos->splice($musicVideoIndex, 1)[0]]);
        }

        // Détacher toutes les vidéos musicales
        $schedule->musicVideos()->detach();

        // Réattacher les vidéos musicales avec les heures de diffusion recalculées
        $totalDuration = 0;
        foreach ($musicVideos as $video) {
            $playTime = Carbon::parse($schedule->start_time)->addSeconds($totalDuration);
            $schedule->musicVideos()->attach($video->id, ['play_time' => $playTime]);
            $totalDuration += $video->getDurationInSecondsAttribute();
        }

        return redirect()->route('programSchedules.show', $scheduleId)->with('success', 'Vidéo musicale déplacée avec succès.');
    }


    public function calculateBroadcastTimes($schedule)
    {
        $daysOfWeek = $schedule->days->pluck('day_of_week')->toArray();
        $startDate = Carbon::parse($schedule->frequency->start_date);
        $endDate = $schedule->frequency->end_date ? Carbon::parse($schedule->frequency->end_date) : Carbon::now()->addYear();

        $broadcastTimes = [];

        while ($startDate->lessThanOrEqualTo($endDate)) {
            if (in_array($startDate->format('l'), $daysOfWeek)) {
                $broadcastTimes[] = $startDate->copy()->format('Y-m-d H:i:s');
            }
            $startDate->addDay();
        }

        return $broadcastTimes;
    }


    public function addReplay(Request $request, $id)
    {
        $request->validate([
            'replay_day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'replay_start_time' => 'required|date_format:H:i',
            'replay_end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $originalSchedule = ProgramSchedule::findOrFail($id);

        // Créer une nouvelle entrée dans program_schedules pour la rediffusion
        $newSchedule = ProgramSchedule::create([
            'program_id' => $originalSchedule->program_id,
            'name' => $originalSchedule->name . ' (Rediffusion)',
            'description' => $request->input('description', $originalSchedule->description),
            'start_time' => $request->input('replay_start_time'), // Garder en format H:i
            'end_time' => $request->input('replay_end_time'),
            'repeat_schedule' => $originalSchedule->repeat_schedule,
            'status' => $originalSchedule->status,
            'special_notes' => $originalSchedule->special_notes,
            'priority' => $originalSchedule->priority,
            'frequency_id' => $originalSchedule->frequency_id,
        ]);

        // Créer une nouvelle entrée dans schedule_days pour la rediffusion
        $scheduleDay = ScheduleDay::create([
            'program_schedule_id' => $newSchedule->id,
            'day_of_week' => $request->input('replay_day_of_week'),
        ]);

        // Créer une entrée dans la table replays
        $replay = Replay::create([
            'program_schedule_id' => $originalSchedule->id,
            'schedule_day_id' => $scheduleDay->id,
            'start_time' => $request->input('replay_start_time') . ':00',
            'end_time' => $request->input('replay_end_time') . ':00',
            'description' => $request->input('description', ''),
        ]);

        // Calculer la différence entre les heures de début de l'original et de la rediffusion
        $originalStartTime = Carbon::parse($originalSchedule->start_time);
        $replayStartTime = Carbon::parse($request->input('replay_start_time'));
        $timeDifference = $originalStartTime->diffInSeconds($replayStartTime);

        // Copier toutes les vidéos du program_schedule original vers la rediffusion
        foreach ($originalSchedule->musicVideos as $musicVideo) {
            $originalPlayTime = Carbon::parse($musicVideo->pivot->play_time);
            $newPlayTime = $originalPlayTime->copy()->addSeconds($timeDifference);

            $newSchedule->musicVideos()->attach($musicVideo->id, [
                'play_time' => $newPlayTime->format('Y-m-d H:i:s'),
                'day_of_week' => $request->input('replay_day_of_week'),
            ]);
        }

        return redirect()->route('programSchedules.show', $originalSchedule->id)->with('success', 'Rediffusion ajoutée avec succès.');
    }


    public function deleteReplay($id)
    {
        // Trouver la rediffusion par ID
        $replay = Replay::findOrFail($id);

        // Trouver le programme associé à la rediffusion
        $programSchedule = ProgramSchedule::findOrFail($replay->program_schedule_id);

        // Supprimer les associations avec les vidéos musicales
        $programSchedule->musicVideos()->detach();

        // Supprimer la rediffusion
        $replay->delete();

        return redirect()->back()->with('success', 'Rediffusion supprimée avec succès.');
    }
    private function filterMusicVideosByYear($query, $year, $yearRangeStart, $yearRangeEnd)
    {
        if ($year) {
            $query->where('year', $year);
        } elseif ($yearRangeStart && $yearRangeEnd) {
            $query->whereBetween('year', [$yearRangeStart, $yearRangeEnd]);
        }
        return $query;
    }

    public function addRandomClips(Request $request, $id)
    {
        $schedule = ProgramSchedule::findOrFail($id);

        // Récupérer les jours associés au programme
        $days = $schedule->days->pluck('day_of_week')->toArray();

        // Limiter le nombre de vidéos musicales chargées en mémoire
        $maxVideos = 100;
        $batchSize = 60; // Réduire la taille du lot à 60

        $debugInfo = [];

        // Récupérer les genres sélectionnés
        $selectedGenres = $request->input('genres', []);

        DB::beginTransaction();

        try {
            // Initialiser une collection pour suivre les vidéos ajoutées pour chaque jour
            $addedVideos = [];

            foreach ($days as $day) {
                $debugInfo[] = 'Processing day: ' . $day;

                // Vérifier les valeurs de start_time et end_time
                $startTime = Carbon::parse($schedule->start_time);
                $endTime = Carbon::parse($schedule->end_time);
                $debugInfo[] = 'Start time: ' . $startTime->format('H:i:s');
                $debugInfo[] = 'End time: ' . $endTime->format('H:i:s');

                // Ajuster le calcul de availableSeconds
                if ($endTime->lessThan($startTime)) {
                    // Si end_time est avant start_time, cela signifie que end_time est le lendemain
                    $endTime->addDay();
                }
                $availableSeconds = $startTime->diffInSeconds($endTime);
                $debugInfo[] = 'Available seconds: ' . $availableSeconds;

                // Vérifier si availableSeconds est négatif
                if ($availableSeconds <= 0) {
                    $debugInfo[] = 'Skipping day due to negative available seconds';
                    continue;
                }

                // Initialiser la durée utilisée
                $usedSeconds = 0;

                // Ajouter des vidéos musicales aléatoires jusqu'à ce que la durée totale soit atteinte ou que la limite de vidéos soit atteinte
                $videoCount = 0;

                // Charger un petit lot de vidéos musicales en fonction des genres sélectionnés
                $query = MusicVideo::query();

                // Charger un petit lot de vidéos musicales avec les genres et l'année spécifiés
                $musicVideosQuery = MusicVideo::whereHas('genres', function ($query) use ($selectedGenres) {
                    $query->whereIn('genres.id', $selectedGenres);
                });

                $musicVideosQuery = $this->filterMusicVideosByYear(
                    $musicVideosQuery,
                    $request->input('year'),
                    $request->input('year_range_start'),
                    $request->input('year_range_end')
                );

                $musicVideos = $musicVideosQuery->inRandomOrder()->limit($batchSize)->get();

                foreach ($musicVideos as $musicVideo) {
                    // Vérifier si la vidéo a déjà été ajoutée pour ce jour
                    if (isset($addedVideos[$day]) && in_array($musicVideo->id, $addedVideos[$day])) {
                        $debugInfo[] = 'Skipping video due to duplicate in the same day';
                        continue;
                    }

                    // Vérifier si la vidéo a déjà été ajoutée au programme pour un autre jour
                    $exists = $schedule->musicVideos()->wherePivot('music_video_id', $musicVideo->id)->exists();
                    if ($exists) {
                        $debugInfo[] = 'Skipping video due to duplicate in the program schedule';
                        continue;
                    }

                    // Convertir la durée de `time` en secondes
                    $videoDuration = Carbon::parse($musicVideo->duration)->secondsSinceMidnight();

                    // Vérifier si la durée est bien numérique
                    if (!is_numeric($videoDuration)) {
                        $debugInfo[] = 'Skipping video due to non-numeric duration';
                        Log::info("Video ID {$musicVideo->id} has a non-numeric duration: " . $musicVideo->duration);
                        continue;
                    }

                    $debugInfo[] = 'Video duration: ' . $videoDuration;

                    // Vérifier si ajouter cette vidéo dépasse la durée totale disponible
                    if ($usedSeconds + $videoDuration > $availableSeconds) {
                        $debugInfo[] = 'Skipping video, duration exceeds available time';
                        break;
                    }

                    // Calculer l'heure de diffusion de cette vidéo
                    $playTime = $startTime->copy()->addSeconds($usedSeconds);
                    $debugInfo[] = 'Play time: ' . $playTime->format('Y-m-d H:i:s');

                    // Ajouter la vidéo musicale à l'horaire pour ce jour
                    $schedule->musicVideos()->attach($musicVideo->id, [
                        'play_time' => $playTime->format('Y-m-d H:i:s'),
                        'day_of_week' => $day,
                    ]);
                    $debugInfo[] = 'Added video: ' . $musicVideo->id . ' on ' . $day;

                    // Ajouter la vidéo à la collection des vidéos ajoutées pour ce jour
                    $addedVideos[$day][] = $musicVideo->id;

                    // Mettre à jour la durée utilisée
                    $usedSeconds += $videoDuration;

                    // Incrémenter le compteur de vidéos
                    $videoCount++;

                    if ($videoCount >= $maxVideos) {
                        break;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $debugInfo[] = 'Finished adding random clips';
        return response()->json($debugInfo);
    }






    public function deleteAllClips($id)
    {
        // Trouver le programme d'horaire par ID
        $schedule = ProgramSchedule::findOrFail($id);

        // Détacher toutes les vidéos musicales associées
        $schedule->musicVideos()->detach();

        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Tous les clips ont été supprimés avec succès.');
    }





























   
   
    

    
}
