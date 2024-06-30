<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\ProgramSchedule;
use App\Models\ProgramScheduleFilm;
use App\Models\ProgramDate;
use App\Models\MusicVideo;
use App\Models\Genre;
use App\Models\Tag;
use App\Models\DayOfWeek;
use App\Models\Program;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FilmScheduleController extends Controller
{
    public function create()
    {
        return view('filmSchedules.create');
    }


    public function store(Request $request)
    {
        $request->merge(['type' => $request->input('type', 'film')]);
    
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recurrence' => 'required|string|in:none,daily,weekly',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'genre_id' => 'nullable|exists:genres,id',
            'age_rating' => 'nullable|array',
            'age_rating.*' => 'string|max:255',
            'specific_date' => 'nullable|date',
            'type' => 'required|string|in:music_video,film,tv_show,other',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'exists:days_of_week,id',
            'start_year' => 'nullable|integer',
            'end_year' => 'nullable|integer',
            'exclude_tags' => 'nullable|array',
            'exclude_tags.*' => 'exists:tags,id',
        ]);
    
        // Vérification des chevauchements
        $overlappingSchedules = ProgramSchedule::where('program_id', $validatedData['program_id'])
            ->where('type', 'film')
            ->where(function ($query) use ($validatedData) {
                $query->where(function ($subQuery) use ($validatedData) {
                    foreach ($validatedData['days_of_week'] as $dayId) {
                        $subQuery->orWhereHas('daysOfWeek', function ($dayQuery) use ($dayId) {
                            $dayQuery->where('days_of_week.id', $dayId);
                        });
                    }
                })
                    ->where('start_time', '<', $validatedData['end_time'])
                    ->where('end_time', '>', $validatedData['start_time']);
            })
            ->exists();
    
        if ($overlappingSchedules) {
            return redirect()->back()->withErrors(['duplicate' => 'Il y a un conflit d\'horaire avec une autre plage horaire.'])->withInput();
        }
    
        // Création de la plage horaire
        $schedule = $this->createSchedule($validatedData);
    
        // Associer les films au même jour que le program schedule
        $this->handleFilmScheduling($schedule, $validatedData);
    
        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Plage horaire de films créée avec succès.');
    }




   public function refreshSelection($scheduleId)
{
    // Récupérer le programme horaire
    $schedule = ProgramSchedule::findOrFail($scheduleId);

    // Valider et récupérer les critères à partir du programme horaire
    $validatedData = [
        'program_id' => $schedule->program_id,
        'genre_id' => $schedule->genre_id,
        'age_rating' => $schedule->age_rating ? explode(',', $schedule->age_rating) : [],
        'days_of_week' => $schedule->daysOfWeek->pluck('id')->toArray(),
        'start_year' => $schedule->start_year,
        'end_year' => $schedule->end_year,
        'exclude_tags' => $schedule->exclude_tags ? explode(',', $schedule->exclude_tags) : [],
    ];

    // Vérifier si les critères sont correctement récupérés
    if (empty($validatedData['program_id']) || empty($validatedData['days_of_week'])) {
        return redirect()->route('programSchedules.show', $schedule->id)->withErrors(['error' => 'Les critères de sélection de films ne sont pas correctement sauvegardés.']);
    }

    // Détacher tous les films associés à ce `ProgramSchedule`
    $schedule->films()->detach();

    // Re-sélectionner les films selon les critères spécifiés
    $this->handleFilmScheduling($schedule, $validatedData);

    return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'La sélection de films a été actualisée avec succès.');
}


    
    
    public function update(Request $request, $id)
    {
        $request->merge(['type' => $request->input('type', 'film')]);
    
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recurrence' => 'nullable|string|in:none,daily,weekly',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'genre_id' => 'nullable|exists:genres,id',
            'age_rating' => 'nullable|array',
            'age_rating.*' => 'string|max:255',
            'specific_date' => 'nullable|date',
            'type' => 'required|string|in:music_video,film,tv_show,other',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'exists:days_of_week,id',
            'start_year' => 'nullable|integer',
            'end_year' => 'nullable|integer',
            'exclude_tags' => 'nullable|array',
            'exclude_tags.*' => 'exists:tags,id',
        ]);
    
        $schedule = ProgramSchedule::findOrFail($id);
    
        // Vérification des chevauchements
        $overlappingSchedules = ProgramSchedule::where('program_id', $validatedData['program_id'])
            ->where('id', '<>', $id) // Exclure l'horaire actuel des chevauchements
            ->where('type', 'film')
            ->where(function ($query) use ($validatedData) {
                $query->where(function ($subQuery) use ($validatedData) {
                    foreach ($validatedData['days_of_week'] as $dayId) {
                        $subQuery->orWhereHas('daysOfWeek', function ($dayQuery) use ($dayId) {
                            $dayQuery->where('days_of_week.id', $dayId);
                        });
                    }
                })
                    ->where('start_time', '<', $validatedData['end_time'])
                    ->where('end_time', '>', $validatedData['start_time']);
            })
            ->exists();
    
        if ($overlappingSchedules) {
            return redirect()->back()->withErrors(['duplicate' => 'Il y a un conflit d\'horaire avec une autre plage horaire.'])->withInput();
        }
    
        // Mise à jour de la plage horaire
        $schedule->update([
            'program_id' => $validatedData['program_id'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? '',
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'status' => 'active',
            'type' => $validatedData['type'] ?? 'film',
        ]);
    
        if (isset($validatedData['days_of_week'])) {
            $schedule->daysOfWeek()->sync($validatedData['days_of_week']);
        }
    
        // Associer les films au même jour que le program schedule
        $this->handleFilmScheduling($schedule, $validatedData);
    
        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Plage horaire de films mise à jour avec succès.');
    }
    




    public function edit($id)
{
    $schedule = ProgramSchedule::findOrFail($id);
    $programs = Program::all();
    $daysOfWeek = DayOfWeek::all();
    $filmGenres = Genre::all();
    $tags = Tag::all();
    
    return view('filmSchedules.edit', compact('schedule', 'programs', 'daysOfWeek', 'filmGenres', 'tags'));
}


// private function handleRecurrence(ProgramSchedule $schedule, array $data)
// {
//     // Convertir les indices des jours de la semaine en noms de jours
//     $dayNames = collect($data['days_of_week'])->map(function ($dayId) {
//         // Carbon considère que la semaine commence par dimanche (0), donc un ajustement est nécessaire
//         return Carbon::now()->startOfWeek()->addDays($dayId - 1)->format('l');
//     })->all();

//     // Récupérer toutes les dates correspondant aux jours de la semaine sélectionnés
//     $dates = ProgramDate::where('program_id', $schedule->program_id)
//                         ->whereIn(DB::raw('DAYNAME(date)'), $dayNames)
//                         ->orderBy('date')
//                         ->get();

//     if (isset($data['films'])) {
//         $films = $data['films']; // Utiliser les films filtrés passés à la méthode
//     } else {
//         // Récupérer les films disponibles, en excluant ceux déjà sélectionnés pour ce programme
//         $selectedFilms = ProgramScheduleFilm::where('program_schedule_id', $schedule->id)->pluck('film_id')->toArray();
//         $films = Film::whereNotIn('films.id', $selectedFilms)
//             ->when($data['genre_id'], function ($query) use ($data) {
//                 $query->whereHas('genres', function ($q) use ($data) {
//                     $q->where('genres.id', $data['genre_id']);
//                 });
//             })
//             ->when(isset($data['age_rating']), function ($query) use ($data) {
//                 $query->whereIn('films.rating', $data['age_rating']);
//             })
//             ->when(isset($data['exclude_tags']), function ($query) use ($data) {
//                 $query->whereDoesntHave('tags', function ($q) use ($data) {
//                     $q->whereIn('tags.id', $data['exclude_tags']);
//                 });
//             })
//             ->inRandomOrder()
//             ->get();
//     }

//     if ($films->isEmpty()) {
//         \Log::info('Aucun film disponible pour la récurrence.');
//         return;
//     }

//     $filmIndex = 0;
//     foreach ($dates as $programDate) {
//         if ($filmIndex >= $films->count()) {
//             break; // Arrêtez si nous n'avons plus de films uniques
//         }

//         // Vérifier si la date correspond au jour de la semaine spécifié
//         $dayName = Carbon::parse($programDate->date)->format('l');
//         if (!in_array($dayName, $dayNames)) {
//             continue; // Passer à la date suivante si le jour ne correspond pas
//         }

//         // Vérifier s'il y a déjà un film pour cette date dans le même filmSchedule
//         $existingFilm = ProgramScheduleFilm::where('program_schedule_id', $schedule->id)
//             ->where('program_date_id', $programDate->id)
//             ->exists();

//         if ($existingFilm) {
//             continue; // Passer à la date suivante si un film existe déjà pour cette date
//         }

//         // Associer le film au programme et à la date
//         $this->linkProgramScheduleToDate($schedule, $programDate);

//         $film = $films[$filmIndex];
//         ProgramScheduleFilm::create([
//             'program_schedule_id' => $schedule->id,
//             'film_id' => $film->id,
//             'genre_id' => $data['genre_id'],
//             'age_rating' => $film->rating,
//             'program_date_id' => $programDate->id,
//         ]);

//         $filmIndex++;
//     }
// }




private function handleFilmScheduling(ProgramSchedule $schedule, array $data)
{
    // Sélectionner les films qui correspondent aux critères, exclure les films déjà sélectionnés dans le même ProgramSchedule
    $films = Film::when($data['genre_id'], function ($query) use ($data) {
            // Si genre_id est spécifié, filtrer par genre
            if (!empty($data['genre_id'])) {
                $query->whereHas('genres', function ($q) use ($data) {
                    $q->where('genres.id', $data['genre_id']);
                });
            }
        })
        ->when($data['start_year'], function ($query) use ($data) {
            $query->where('year', '>=', $data['start_year']);
        })
        ->when($data['end_year'], function ($query) use ($data) {
            $query->where('year', '<=', $data['end_year']);
        })
        ->when(!empty($data['age_rating']), function ($query) use ($data) {
            $query->whereIn('rating', $data['age_rating']);
        })
        ->when(!empty($data['exclude_tags']), function ($query) use ($data) {
            $query->whereDoesntHave('tags', function ($q) use ($data) {
                $q->whereIn('tags.id', $data['exclude_tags']);
            });
        })
        ->inRandomOrder()
        ->get();

    if ($films->isEmpty()) {
        Log::info('Aucun film disponible pour la récurrence.');
        return;
    }

    // Récupérer toutes les dates du program schedule
    $programDates = ProgramDate::where('program_id', $data['program_id'])
                        ->whereIn(DB::raw('DAYOFWEEK(date)'), $data['days_of_week'])
                        ->orderBy('date')
                        ->get();

    Log::info('Program dates', ['dates' => $programDates->pluck('date')->toArray()]);
    Log::info('Selected films', ['films' => $films->pluck('title')->toArray()]);

    $filmIndex = 0;
    foreach ($programDates as $programDate) {
        if ($filmIndex >= $films->count()) {
            $filmIndex = 0; // Recommencer si nous avons atteint la fin de la liste des films
        }

        // Vérifier s'il y a déjà un film pour cette date dans le même filmSchedule
        $existingFilm = ProgramScheduleFilm::where('program_schedule_id', $schedule->id)
            ->where('program_date_id', $programDate->id)
            ->exists();

        if ($existingFilm) {
            continue; // Passer à la date suivante si un film existe déjà pour cette date
        }

        // Associer le film au programme et à la date
        $this->linkProgramScheduleToDate($schedule, $programDate);

        $film = $films[$filmIndex];
        ProgramScheduleFilm::create([
            'program_schedule_id' => $schedule->id,
            'film_id' => $film->id,
            'genre_id' => $data['genre_id'],
            'age_rating' => $film->rating,
            'program_date_id' => $programDate->id,
        ]);

        Log::info('Film associé', [
            'film_id' => $film->id,
            'program_date_id' => $programDate->id,
            'program_schedule_id' => $schedule->id
        ]);

        $filmIndex++;
    }
}




private function linkRandomFilmsToSchedule(ProgramSchedule $schedule, array $data, $date = null)
{
    // Sélectionne les films qui correspondent aux critères, exclut les films déjà sélectionnés dans le même ProgramSchedule
    $selectedFilms = ProgramScheduleFilm::where('program_schedule_id', $schedule->id)->pluck('film_id')->toArray();
    $films = Film::whereNotIn('id', $selectedFilms)
        ->when($data['genre_id'], function ($query) use ($data) {
            if (!empty($data['genre_id'])) {
                $query->whereHas('genres', function ($q) use ($data) {
                    $q->where('genres.id', $data['genre_id']);
                });
            }
        })
        ->inRandomOrder()
        ->get();

    if ($films->isEmpty()) {
        Log::info('Aucun film disponible pour la récurrence.');
        return; 
    }

    foreach ($films as $film) {
        ProgramScheduleFilm::create([
            'program_schedule_id' => $schedule->id,
            'film_id' => $film->id,
            'genre_id' => $data['genre_id'],
            'age_rating' => $data['age_rating'],
            'program_date_id' => ProgramDate::where('date', $date)->first()->id,
        ]);
    }
}

    

    private function createSchedule($data)
    {
        $schedule = ProgramSchedule::create([
            'program_id' => $data['program_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 'active',
            'type' => $data['type'] ?? 'film',
        ]);

        if (isset($data['days_of_week'])) {
            $schedule->daysOfWeek()->sync($data['days_of_week']);
        }

        return $schedule;
    }

   
    



    // private function checkScheduleConflicts($programId, $specificDate, $startTime, $endTime)
    // {
    //     return ProgramSchedule::where('program_id', $programId)
    //         ->whereHas('programDates', function($query) use ($specificDate) {
    //             $query->where('date', $specificDate);
    //         })
    //         ->where(function($query) use ($startTime, $endTime) {
    //             $query->whereBetween('start_time', [$startTime, $endTime])
    //                   ->orWhereBetween('end_time', [$startTime, $endTime])
    //                   ->orWhere(function($query) use ($startTime, $endTime) {
    //                       $query->where('start_time', '<=', $startTime)
    //                             ->where('end_time', '>=', $endTime);
    //                   });
    //         })
    //         ->get();
    // }
    
    // private function checkWeeklyScheduleConflicts($programId, $dayOfWeek, $startTime, $endTime)
    // {
    //     return ProgramSchedule::where('program_id', $programId)
    //         ->where('recurrence', 'weekly')
    //         ->whereJsonContains('days_of_week', $dayOfWeek)
    //         ->where(function($query) use ($startTime, $endTime) {
    //             $query->whereBetween('start_time', [$startTime, $endTime])
    //                   ->orWhereBetween('end_time', [$startTime, $endTime])
    //                   ->orWhere(function($query) use ($startTime, $endTime) {
    //                       $query->where('start_time', '<=', $startTime)
    //                             ->where('end_time', '>=', $endTime);
    //                   });
    //         })
    //         ->get();
    // }
    



    private function linkProgramScheduleToDate(ProgramSchedule $schedule, ProgramDate $programDate)
    {
        DB::table('program_date_program_schedule')->updateOrInsert(
            [
                'program_date_id' => $programDate->id,
                'program_schedule_id' => $schedule->id
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function editFilm($scheduleId, $filmId)
    {
        $schedule = ProgramSchedule::findOrFail($scheduleId);
        $allFilms = Film::all();

        $film = $filmId !== 'new' ? Film::findOrFail($filmId) : null;

        return view('filmSchedules.editFilm', compact('schedule', 'film', 'allFilms'));
    }


    public function updateFilm(Request $request, $scheduleId, $filmId)
    {
        $schedule = ProgramSchedule::findOrFail($scheduleId);

        $validatedData = $request->validate([
            'film_id' => 'required|exists:films,id',
        ]);

        // Récupérer le pivot pour l'ancien film si ce n'est pas un ajout de nouveau film
        $pivot = $filmId !== 'new' ? $schedule->films()->where('film_id', $filmId)->first()->pivot : null;

        // Détacher l'ancien film si ce n'est pas un ajout de nouveau film
        if ($pivot) {
            $schedule->films()->detach($filmId);
        }

        // Attacher le nouveau film
        $schedule->films()->attach($validatedData['film_id'], [
            'genre_id' => $pivot->genre_id ?? null,
            'age_rating' => $pivot->age_rating ?? null,
            'program_date_id' => $pivot->program_date_id ?? null,
        ]);

        return redirect()->route('programSchedules.show', $scheduleId)->with('success', 'Film mis à jour avec succès.');
    }





    private function handleSpecificDate(ProgramSchedule $schedule, $specificDate, array $data)
    {
        $programDate = ProgramDate::where('date', $specificDate)
            ->where('program_id', $data['program_id'])
            ->firstOrFail();
        $this->linkProgramScheduleToDate($schedule, $programDate);
        $this->linkRandomFilmsToSchedule($schedule, $data, $programDate->id);
    }






    






    public function show($id)
    {
        $schedule = ProgramSchedule::with(['program', 'musicVideos' => function ($query) {
            $query->orderBy('schedule_music_videos.play_time', 'asc');  
        }, 'films.genres'])->findOrFail($id); 

        $musicVideos = MusicVideo::all(); // Récupérer toutes les vidéos musicales pour les afficher dans le formulaire de sélection
        $genres = Genre::orderBy('name', 'asc')->get(); // Récupérer tous les genres et les trier par nom
        $films = Film::with('genres')->get();

        // Grouper les genres par catégorie
        $groupedGenres = $genres->groupBy('category');

        return view('programSchedules.show', compact('schedule', 'musicVideos', 'groupedGenres', 'films'));
    }


    public function addFilm(Request $request, $scheduleId)
    {
        $validatedData = $request->validate([
            'film_id' => 'required|exists:films,id',
            'program_date_id' => 'required|exists:program_dates,id',
        ]);

        $schedule = ProgramSchedule::findOrFail($scheduleId);
        $film = Film::findOrFail($validatedData['film_id']);

        Log::info('Adding film to schedule', [
            'schedule_id' => $scheduleId,
            'film_id' => $validatedData['film_id'],
            'program_date_id' => $validatedData['program_date_id']
        ]);

        ProgramScheduleFilm::create([
            'program_schedule_id' => $schedule->id,
            'film_id' => $validatedData['film_id'],
            'genre_id' => $film->genres->first()->id ?? null,
            'age_rating' => $film->age_rating,
            'program_date_id' => $validatedData['program_date_id'],
        ]);

        Log::info('Film successfully added to schedule', [
            'schedule_id' => $scheduleId,
            'film_id' => $validatedData['film_id'],
            'program_date_id' => $validatedData['program_date_id']
        ]);

        return response()->json(['message' => 'Film ajouté avec succès'], 200);
    }

    public function detachAllFilms($scheduleId)
    {
        // Récupérer le programme horaire (ProgramSchedule)
        $schedule = ProgramSchedule::findOrFail($scheduleId);

        // Détacher tous les films associés à ce programme horaire
        $schedule->films()->detach();

        // Rediriger vers la page de visualisation du programme horaire avec un message de succès
        return redirect()->route('programSchedules.show', $schedule->id)->with('success', 'Tous les films ont été détachés de ce programme horaire.');
    }

 


    
}


