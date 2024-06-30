<?php


namespace App\Http\Controllers;

use App\Models\ProgramSchedule;
use App\Models\Replay;
use App\Models\ScheduleDay;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReplayScheduleController extends Controller
{
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
            'start_time' => $request->input('replay_start_time') . ':00', // Ajout des secondes pour le format H:i:s
            'end_time' => $request->input('replay_end_time') . ':00',
            'description' => $request->input('description', ''), // Optionnel, selon votre modèle
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
}
