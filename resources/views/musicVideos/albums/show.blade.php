@extends('layouts.app')

@section('content')
<div class="container album-container">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="album-title">{{ $album->title }}</h1>
        @if($nextAlbum)
    <a href="{{ route('albums.show', $nextAlbum->id) }}" class="next-album-link" style="font-size: 1rem; display: flex; align-items: center;">
        <span class="fst-italic">{{ $nextAlbum->title }}</span>
        <span class="material-icons" style="font-size: 1em; margin-left: 4px;">
            arrow_forward
        </span>
    </a>
@endif

    </div>
    <div class="row">

        <div class="col-md-4">
            @if ($album->thumbnail_image)
                <img src="{{ Storage::url($album->thumbnail_image) }}" class="img-fluid album-image" title="{{ $album->title }}" alt="{{ $album->title }}">
            @else
                <img src="https://via.placeholder.com/250" class="img-fluid album-image" alt="{{ $album->title }}">
                <!-- Formulaire de téléversement d'image -->
                <form id="uploadThumbnailForm" action="{{ route('albums.uploadThumbnail', $album->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="thumbnail_image" class="form-label">Ajouter une image</label>
                        <input type="file" class="form-control-file form-control-sm" id="thumbnail_image" name="thumbnail_image">
                    </div>
                    <button type="submit" class="btn btn-outline-secondary btn-sm" style="padding: 4px 8px; font-size: 0.6rem;">Téléverser</button>
                </form>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Année de sortie</h3>
                    <p class="card-text">{{ $album->year }}</p>
                    <h3 class="card-title">Label</h3>
                    <p class="card-text">
                        @if ($album->labels->isEmpty())
                            N/A
                        @elseif ($album->labels->count() == 1)
                            <a href="{{ route('labels.show', $album->labels->first()->id) }}">{{ $album->labels->first()->name }}</a>
                        @else
                            @foreach($album->labels as $label)
                                <a href="{{ route('labels.show', $label->id) }}">{{ $label->name }}</a>@if (!$loop->last) / @endif
                            @endforeach
                        @endif
                    </p>
                    <h3 class="card-title">Artiste</h3>
                    <p class="card-text"><a href="{{ route('artists.show', $album->artist->id) }}">{{ $album->artist ? $album->artist->name : 'N/A' }}</a></p>
                    <h3 class="card-title">Description</h3>
                    <p class="card-text">{{ $album->description }}</p>
                    <h3 class="card-title">Date de sortie</h3>
                    <p class="card-text">{{ $album->release_date }}</p>

                    <h3 class="card-title">Genres</h3>
                    <p class="card-text">
                        @foreach($album->genres as $genre)
                            <a href="{{ route('genres.show', $genre->id) }}" class="badge badge-secondary">
                                {{ $genre->name }}
                            </a>
                        @endforeach
                        <button type="button" class="btn btn-sm btn-outline-dark" id="addGenreButton">
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
    </div>

    <div class="mt-4">
        <h2 class="music-videos-title">Music Videos</h2>
        @if ($album->musicVideos->isEmpty())
            <p>No music videos found.</p>
        @else
            <ul class="list-group">
                @foreach ($album->musicVideos as $musicVideo)
                    <li class="list-group-item">
                        <a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a> ({{ $musicVideo->year }})
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('musicVideos.createWithAlbum', ['album' => $album->id]) }}" class="btn btn-teal mt-3">Add a Music Video</a>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('albums.edit', $album->id) }}" class="btn btn-outline-teal">Modifier l'album</a>
        <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Es-tu sûr de vouloir supprimer cet album ?')">Supprimer l'album</button>
        </form>
    </div>
</div>

<!-- Script pour gérer le téléversement et rafraîchir la page -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('uploadThumbnailForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                console.log('Response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.success) {
                    location.reload();
                } else {
                    alert('Échec du téléversement de l\'image: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    });
</script>
@endsection






<script>


document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM complètement chargé');

            const addGenreButton = document.getElementById('addGenreButton');
            if (addGenreButton) {
                console.log('Bouton + trouvé');
                addGenreButton.addEventListener('click', function() {
                    console.log('Bouton + cliqué');
                    const addGenreDiv = document.getElementById('addGenreDiv');
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

            const linkGenreForm = document.getElementById('linkGenreForm');
            if (linkGenreForm) {
                console.log('Formulaire de lien de genre trouvé');
                linkGenreForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Formulaire soumis');

                    const form = e.target;
                    const formData = new FormData(form);
                    console.log('Données du formulaire:', formData);

                    fetch('{{ route("albums.linkGenre", $album->id) }}', {
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
                            const genreBadge = document.createElement('a');
                            genreBadge.href = '/genres/' + data.genre.id;
                            genreBadge.className = 'badge badge-secondary';
                            genreBadge.textContent = data.genre.name;

                            const genresContainer = document.querySelector('p strong');
                            if (genresContainer) {
                                genresContainer.insertAdjacentElement('afterend', genreBadge);
                            } else {
                                console.error('Conteneur des genres non trouvé');
                            }

                            // Cacher la div et réinitialiser le formulaire
                            const addGenreDiv = document.getElementById('addGenreDiv');
                            addGenreDiv.style.display = 'none';
                            form.reset();
                            console.log('Formulaire réinitialisé et div cachée');

                            // Rafraîchir la page
                            console.log('Rafraîchissement de la page');
                            location.reload();
                        } else {
                            // Gérer les erreurs
                            console.log('Erreur lors de la liaison du genre:', data.message);
                            alert('Erreur lors de la liaison du genre: ' + data.message);
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
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #f8f9fa;
    }
    .album-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .album-title {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 20px;
    }
    .album-image {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .card-text {
        color: darkslategrey;
        line-height: 1.6;
    }
    .btn {
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .btn-teal {
        background-color: #20c997;
        color: #fff;
        border: none;
    }
    .btn-teal:hover {
        background-color: #17a589;
    }
    .btn-outline-teal {
        background-color: transparent;
        color: #20c997;
        border: 2px solid #20c997;
    }
    .btn-outline-teal:hover {
        background-color: #20c997;
        color: #fff;
    }
    .btn-outline-danger {
        background-color: transparent;
        color: #dc3545;
        border: 2px solid #dc3545;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
    .music-videos-title {
        font-size: 1.5rem;
        margin-top: 30px;
        margin-bottom: 20px;
    }
    .list-group-item {
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        color:#17a589;
        background-color: #fff;
        transition: background-color 0.3s ease;
    }
    .list-group-item:hover {
        background-color: #f1f1f1;
    }
    .list-group-item a {
        color: darkslategray;
        text-decoration: none;
    }
    .list-group-item a:hover {
        text-decoration: underline;
    }
</style>

