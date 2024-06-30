<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class TvPlayerController extends Controller
{
    public function show()
    {
        $currentDateTime = Carbon::now();
        $currentDate = $currentDateTime->toDateString();
        $currentTime = $currentDateTime->toTimeString();

        $programDate = ProgramDate::where('date', $currentDate)->firstOrFail();

        $programSchedules = $programDate->schedules()
            ->with([
                'films' => function($query) use ($programDate) {
                    $query->wherePivot('program_date_id', $programDate->id);
                },
                'films.genres',
                'episodes' => function($query) use ($programDate) {
                    $query->where('program_schedule_episodes.program_date_id', $programDate->id);
                },
                'program'
            ])
            ->orderBy('start_time')
            ->get();

            $currentSchedule = $programSchedules->firstWhere(function ($schedule) use ($currentTime) {
                return $schedule->start_time <= $currentTime && $schedule->end_time >= $currentTime;
            }) ?? null; // Ajouter null comme valeur par défaut si aucun horaire n'est trouvé
            

        $nextSchedule = $programSchedules->firstWhere(function ($schedule) use ($currentTime) {
            return $schedule->start_time > $currentTime;
        });

    

        $startTimeOffset = 0;
        if ($currentSchedule) {
            $scheduleStartTime = Carbon::createFromFormat('H:i:s', $currentSchedule->start_time);
            $elapsedTime = $scheduleStartTime->diffInSeconds($currentDateTime, false);
            $startTimeOffset = max(0, $elapsedTime);
        }

        $minutesUntilNextSchedule = null;
        if ($nextSchedule) {
            $nextScheduleTime = Carbon::createFromFormat('H:i:s', $nextSchedule->start_time);
            $minutesUntilNextSchedule = $currentDateTime->diffInMinutes($nextScheduleTime, false);
        }

        if ($currentSchedule && $currentSchedule->films->isNotEmpty()) {
            $currentProgramUrl = Storage::disk('films')->url($currentSchedule->films->first()->file_path);
         
        }
        
        $currentProgramUrl = null;
        if ($currentSchedule && $currentSchedule->films->isNotEmpty()) {
            $currentProgramUrl = Storage::disk('public')->url($currentSchedule->films->first()->file_path);
        } elseif ($currentSchedule && $currentSchedule->episodes->isNotEmpty()) {
            $currentProgramUrl = Storage::disk('public')->url($currentSchedule->episodes->first()->file_path);
        }
        
        return view('tvPlayer.tvPlayer', [
            'currentDateTime' => $currentDateTime,
            'currentProgram' => $currentSchedule,
            'nextProgram' => $nextSchedule,
            'programSchedules' => $programSchedules,
            'currentTime' => $currentTime,
            'currentProgramDetails' => $currentSchedule ? $currentSchedule->program : null,
            'startTimeOffset' => $startTimeOffset,
            'minutesUntilNextSchedule' => $minutesUntilNextSchedule,
            'currentProgramUrl' => $currentProgramUrl, // Passer l'URL du programme actuel
        ]);
    }
    


    // Controller method to get the next program
public function getNextProgram($currentProgramId)
{
    
    // Fetch the next program logic here
    $nextProgram = Program::where('id', '>', $currentProgramId)->first();
    return response()->json($nextProgram);
}

}
