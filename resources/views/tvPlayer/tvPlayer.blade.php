@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <h1 class="mb-4">TV Player</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="current-date mb-3">
                <a href="{{ url('programs/1/dates/' . $currentDateTime->toDateString()) }}" class="text-decoration-none">
                    {{ $currentDateTime->isoFormat('dddd, MMMM Do YYYY, h:mm:ss A') }}
                </a>
            </div>
            @if ($currentProgram && $currentProgram->type === 'film' && $currentProgram->films->isNotEmpty())
                @foreach ($currentProgram->films as $film)
                    <div class="film-info">
                        <h3>{{ $film->title }} <small><i>({{ $film->year }})</i></small></h3>
                    </div>
                @endforeach
            @elseif ($currentProgram && $currentProgram->type === 'tv_show' && $currentProgram->episodes->isNotEmpty())
                @foreach ($currentProgram->episodes as $episode)
                    <div class="episode-info">
                        <h3>{{ $episode->title }} <small><i>(S{{ $episode->season->season_number }}E{{ $episode->episode_number }})</i></small></h3>
                    </div>
                @endforeach
            @endif

            <br>

            <div class="player-container mb-4" style="position: relative;">
    <video id="tvPlayer" class="w-100 rounded" controls autoplay>
        @php
            $type = '';
            $filePath = '';

            if ($currentProgram && $currentProgram->type === 'film' && $currentProgram->films->isNotEmpty()) {
                $type = 'films';
                $relativePath = str_replace(env('APP_URL').'/storage/', '', $currentProgramUrl);
                $pathParts = explode('/', $relativePath);
                $filePath = implode('/', $pathParts);
            } elseif ($currentProgram && $currentProgram->type === 'tv_show' && $currentProgram->episodes->isNotEmpty()) {
                $type = 'tv_shows';
                $relativePath = str_replace(env('APP_URL').'/storage/', '', $currentProgramUrl);
                $pathParts = explode('/', $relativePath);
                $filePath = implode('/', $pathParts);
            }

            $url = route('videos.stream', ['type' => $type, 'filePath' => $filePath]);
        @endphp
        <source src="{{ $url }}" type="video/mp4">
        Votre navigateur ne supporte pas la balise vidéo.
    </video>
    <img src="{{ asset(str_replace('public/', 'storage/', $channel->logo)) }}" alt="Channel Logo" class="channel-logo">
</div>


                <style>
                    /* Masquer la barre de contrôle de la timeline */
                    #tvPlayer::-webkit-media-controls-timeline,
                    #tvPlayer::-webkit-media-controls-seek-back-button,
                    #tvPlayer::-webkit-media-controls-seek-forward-button,
                    #tvPlayer::-webkit-media-controls-current-time-display,
                    #tvPlayer::-webkit-media-controls-time-remaining-display,
                    #tvPlayer::-webkit-media-controls-play-button,
                    #tvPlayer::-webkit-media-controls-pause-button {
                        display: none;
                    }

                    .channel-logo {
                        position: absolute;
                        bottom: 10px;
                        right: 10px;
                        opacity: 0.4;
                        width: 40px; /* Ajuste la taille selon tes besoins */
                        height: auto; /* Maintient le ratio d'aspect de l'image */
                        pointer-events: none; /* Assure que le logo ne bloque pas les interactions avec la vidéo */
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var player = document.getElementById('tvPlayer');
                        var sourceElement = player.querySelector('source');

                        if (sourceElement && sourceElement.src) {
                            var programStartTime = new Date('{{ $currentDateTime->format('Y-m-d') }}T{{ $currentProgram->start_time }}');
                            var programEndTime = new Date('{{ $currentDateTime->format('Y-m-d') }}T{{ $currentProgram->end_time }}');
                            var currentTime = new Date();
                            var timeOffset = (currentTime - programStartTime) / 1000;

                            console.log('URL de la vidéo:', sourceElement.src);

                            player.addEventListener('loadedmetadata', function() {
                                console.log('Durée de la vidéo disponible:', player.duration);
                                console.log('Décalage calculé:', timeOffset);

                                if (timeOffset > 0 && timeOffset < player.duration) {
                                    player.currentTime = timeOffset;
                                }
                            });

                            player.addEventListener('timeupdate', function() {
                                var currentVideoTime = player.currentTime;
                                var elapsedProgramTime = (new Date() - programStartTime) / 1000;

                                // Empêcher l'utilisateur d'avancer au-delà du temps réel
                                if (currentVideoTime > elapsedProgramTime) {
                                    player.currentTime = elapsedProgramTime;
                                }

                                // Arrêter la vidéo si elle dépasse l'heure de fin du programme
                                if (new Date() >= programEndTime || currentVideoTime >= player.duration) {
                                    player.pause();
                                    player.removeAttribute('src'); // Supprimer la source pour éviter le redémarrage
                                    player.load();
                                    document.getElementById('playButton').style.display = 'none';
                                }
                            });

                            // Empêcher les actions de recherche
                            player.addEventListener('seeking', function() {
                                var currentVideoTime = player.currentTime;
                                var elapsedProgramTime = (new Date() - programStartTime) / 1000;

                                if (currentVideoTime > elapsedProgramTime) {
                                    player.currentTime = elapsedProgramTime;
                                }
                            });

                            player.addEventListener('canplay', function() {
                                player.play().catch((error) => {
                                    console.error("Erreur lors de la tentative de lecture automatique:", error);
                                    document.getElementById('playButton').style.display = 'block';
                                });
                            });

                            document.getElementById('playButton').addEventListener('click', function() {
                                player.play();
                            });

                            document.getElementById('stopButton').addEventListener('click', function() {
                                player.pause();
                                player.currentTime = 0; // Réinitialiser le temps de lecture à 0
                            });
                        } else {
                            console.error("Source de la vidéo non trouvée ou URL de la vidéo vide.");
                        }
                    });
                </script>
           
         
        </div>

        <div class="col-md-4">
            <div class="program-schedule p-3 bg-light rounded shadow-sm">
                @if($currentProgramDetails)
                    <div class="current-program-details mb-4">
                        <a href="{{ route('programs.show', $currentProgramDetails->id) }}" class="text-decoration-none">
                            <h4>{{ $currentProgramDetails->name }}</h4>
                        </a>
                    </div>
                @endif

                <ul class="list-group">
                    @foreach($programSchedules as $schedule)
                        @php
                            $isPast = $schedule->end_time < $currentTime;
                            $isCurrent = $schedule->start_time <= $currentTime && $schedule->end_time >= $currentTime;
                            $isNext = $nextProgram && $nextProgram->id == $schedule->id;
                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('g:i A');
                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('g:i A');
                        @endphp
                        <li class="list-group-item {{ $isPast ? 'past-program' : ($isCurrent ? 'current-program' : ($isNext ? 'next-program' : '')) }}">
                            <a href="{{ route('programSchedules.show', $schedule->id) }}" class="text-decoration-none">
                                <h5 class="mb-1">{{ $schedule->name }}</h5>
                                <small>{{ $startTime }} - {{ $endTime }}</small>
                            </a>
                            @if ($schedule->type === 'tv_show' && $schedule->episodes->isNotEmpty())
                                @foreach ($schedule->episodes as $episode)
                                    <div class="episode-info">
                                        <small>{{ $episode->title }} (S{{ $episode->season->season_number }}E{{ $episode->episode_number }})</small>
                                    </div>
                                @endforeach
                            @elseif ($schedule->type === 'film' && $schedule->films->isNotEmpty())
                                @foreach ($schedule->films as $film)
                                    <div class="film-info">
                                        <small>{{ $film->title }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<br><br><br><br><br>
@endsection



<style>
    a, a:link, a:visited, a:hover, a:active {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #333;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #0056b3;
    }

    .list-group-item {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: scale(1.02);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .player-container {
        animation: fadeIn 1s ease-in-out;
    }

    .episode-info, .film-info {
        margin-top: 5px;
    }

    .current-date a, .current-program-details a {
        color: #333;
        font-weight: bold;
    }

    .current-program-details h4 {
        margin: 0;
    }

    .program-schedule {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .program-schedule h3 {
        margin-bottom: 15px;
    }

    .list-group-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .list-group-item h5 {
        margin: 0;
        font-size: 1rem;
    }

    .list-group-item small {
        display: block;
        margin-top: 5px;
    }

    .past-program {
        color: #6c757d;
    }

    .current-program {
        background-color: #28a745;
        color:orangered ;
        font-weight: bold;
    }

    .next-program {
        background-color: #ffc107;
        font-weight: bold;
    }
</style>
