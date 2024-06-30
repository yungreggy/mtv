@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <!-- Boutons Next et Previous -->
            <div class="d-flex justify-content-between">
                @if($previousVideo)
                <a href="{{ route('musicVideos.show', $previousVideo->id) }}" class="btn btn-custom">
                    &larr; Previous
                </a>
                @else
                <span class="btn btn-custom disabled">&larr; Previous</span>
                @endif

                @if($nextVideo)
                <a href="{{ route('musicVideos.show', $nextVideo->id) }}" class="btn btn-custom">
                    Next &rarr;
                </a>
                @else
                <span class="btn btn-custom disabled">Next &rarr;</span>
                @endif
            </div>
            <h1 class="card-title">{{ $musicVideo->title }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    @if($musicVideo->file_path)
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                        <video class="embed-responsive-item" controls>
                            <source src="{{ asset('storage/' . $musicVideo->file_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <h3>Détails</h3>
                    @if($musicVideo->album && $musicVideo->album->artist)
                    <p><strong>Artiste :</strong>
                        <a href="{{ route('artists.show', $musicVideo->album->artist->id) }}">
                            {{ $musicVideo->album->artist->name }}
                        </a>
                    </p>
                    @endif
                    @if($musicVideo->album)
                    <p><strong>Album :</strong>
                        <a href="{{ route('albums.show', $musicVideo->album->id) }}">
                            {{ $musicVideo->album->title }}
                        </a>
                    </p>
                    @if($musicVideo->release_date)
                    <p><strong>Date de sortie :</strong> {{ \Carbon\Carbon::parse($musicVideo->release_date)->format('F d, Y') }}</p>
                    @endif
                    @endif
                    @if($musicVideo->director)
                    <p><strong>Réalisateur :</strong>
                        <a href="{{ route('directors.show', $musicVideo->director->id) }}">{{ $musicVideo->director->name }}</a>
                    </p>
                    @endif

                    <h3 class="card-title">Labels</h3>
                    <p class="card-text">
                        @if ($musicVideo->labels && $musicVideo->labels->isNotEmpty())
                        @if ($musicVideo->labels->count() == 1)
                        <a href="{{ route('labels.show', $musicVideo->labels->first()->id) }}">{{ $musicVideo->labels->first()->name }}</a>
                        @else
                        @foreach($musicVideo->labels as $label)
                        <a href="{{ route('labels.show', $label->id) }}">{{ $label->name }}</a>@if (!$loop->last) / @endif
                        @endforeach
                        @endif
                        @else
                        N/A
                        @endif
                    </p>
                    <p><strong>Année :</strong> {{ $musicVideo->year }}</p>
                    <p><strong>Durée :</strong> {{ $musicVideo->duration }}</p>
                    <p><strong>Qualité vidéo :</strong> {{ $musicVideo->video_quality }}</p>
                    <p><strong>Tags :</strong> {{ $musicVideo->tags }}</p>
                    <p><strong>Date de mise en ligne :</strong> {{ $musicVideo->timestamp }}</p>
                    <p><strong>Genres :</strong>
                        @foreach($musicVideo->genres as $genre)
                        <a href="{{ route('genres.show', $genre->id) }}" class="badge badge-secondary">
                            {{ $genre->name }}
                        </a>
                        @endforeach
                        <button type="button" class="btn btn-sm " id="addGenreButton">
                            +
                        </button>
                    </p>
                    <!-- Div pour ajouter un genre -->
                    <div id="addGenreDiv" style="display: none;">
                        <form id="linkGenreForm">
                            @csrf
                            <div class="form-group">
                                <label for="genre_select">Ajouter un genre</label>
                                <select class="form-control" id="genre_select" name="genre_id">
                                    @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-secondary outline">Lier le genre</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('musicVideos.edit', $musicVideo->id) }}" class="btn btn-primary">Éditer</a>
            <a href="{{ route('musicVideos.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM complètement chargé');

        var addGenreButton = document.getElementById('addGenreButton');
        if (addGenreButton) {
            console.log('Bouton + trouvé');
            addGenreButton.addEventListener('click', function() {
                console.log('Bouton + cliqué');
                var addGenreDiv = document.getElementById('addGenreDiv');
                console.log('État actuel de addGenreDiv:', addGenreDiv.style.display);
                if (addGenreDiv.style.display === 'none') {
                    addGenreDiv.style.display = 'block';
                    console.log('addGenreDiv affiché');
                } else {
                    addGenreDiv.style.display = 'none';
                    console.log('addGenreDiv caché');
                }
            });
        } else {
            console.error('Bouton + non trouvé');
        }

        var linkGenreForm = document.getElementById('linkGenreForm');
        if (linkGenreForm) {
            console.log('Formulaire de lien de genre trouvé');
            linkGenreForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Formulaire soumis');

                let form = e.target;
                let formData = new FormData(form);
                console.log('Données du formulaire:', formData);

                fetch('{{ route("musicVideos.linkGenre", $musicVideo->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => {
                        console.log('Réponse reçue:', response);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Données reçues:', data);
                        if (data.success) {
                            // Ajouter le nouveau genre à la liste des genres
                            console.log('Genre ajouté avec succès:', data.genre);
                            let genreBadge = document.createElement('a');
                            genreBadge.href = '/genres/' + data.genre.id;
                            genreBadge.className = 'badge badge-secondary';
                            genreBadge.textContent = data.genre.name;

                            document.querySelector('p strong').insertAdjacentElement('afterend', genreBadge);

                            // Cacher la div et réinitialiser le formulaire
                            var addGenreDiv = document.getElementById('addGenreDiv');
                            addGenreDiv.style.display = 'none';
                            form.reset();
                            console.log('Formulaire réinitialisé et div cachée');

                            // Rafraîchir la page
                            console.log('Rafraîchissement de la page');
                            location.reload();
                        } else {
                            // Gérer les erreurs
                            console.log('Erreur lors de la liaison du genre');
                            alert('Erreur lors de la liaison du genre');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
            });
        } else {
            console.error('Formulaire de lien de genre non trouvé');
        }
    });
</script>


<style>
    .btn-custom {
        background-color: transparent;
        border: 1px solid #ccc;
        color: #333;
        padding: 10px 20px;
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-custom:hover {
        background-color: #f1f1f1;
        color: #000;
    }

    .btn-custom.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>