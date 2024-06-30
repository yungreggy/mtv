<?php

namespace App\Http\Controllers;

use App\Models\MusicVideo;
use App\Models\ProgramSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MusicVideoScheduleController extends Controller
{
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

    public function removeMusicVideo($scheduleId, $musicVideoId)
    {
        $schedule = ProgramSchedule::findOrFail($scheduleId);
        $schedule->musicVideos()->detach($musicVideoId);

        // Recalculer les heures de diffusion
        $this->recalculatePlayTimes($schedule);

        return redirect()->route('programSchedules.show', $scheduleId)->with('success', 'Vidéo musicale supprimée avec succès.');
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
}














