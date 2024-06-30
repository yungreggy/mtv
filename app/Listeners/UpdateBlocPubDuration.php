<?php

namespace App\Listeners;

use App\Events\PubUpdated;
use App\Models\BlocPub;

class UpdateBlocPubDuration
{
    public function handle(PubUpdated $event)
    {
        $pub = $event->pub;

        // Récupérer tous les blocs publicitaires contenant cette pub
        $blocPubs = BlocPub::whereHas('pubs', function($query) use ($pub) {
            $query->where('pub_id', $pub->id);
        })->get();

        // Recalculer la durée de chaque bloc publicitaire
        foreach ($blocPubs as $blocPub) {
            $totalDuration = 0;

            foreach ($blocPub->pubs as $pub) {
                $totalDuration += $this->convertDurationToSeconds($pub->duration);
            }

            $minutes = floor($totalDuration / 60);
            $seconds = $totalDuration % 60;

            $formattedDuration = sprintf('%d minutes %02d secondes', $minutes, $seconds);

            $blocPub->update([
                'duration' => $formattedDuration
            ]);
        }
    }

    private function convertDurationToSeconds($duration)
    {
        list( $minutes, $seconds) = explode(':', $duration);
        return  $minutes * 60 + $seconds;
    }
}

