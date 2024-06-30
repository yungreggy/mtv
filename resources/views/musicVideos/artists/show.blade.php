@extends('layouts.app')

@section('content')


<div class="container artist-container">



    <h1 class="mb-4">{{ $artist->name }}</h1>
    <div class="row">
    <div class="col-md-4">
    @if ($artist->thumbnail_image)
        <img src="{{ Storage::url($artist->thumbnail_image) }}" loading="lazy" class="img-fluid artist-image" alt="{{ $artist->name }}">
    @else
        <img src="https://via.placeholder.com/150" loading="lazy" class="img-fluid artist-image" alt="{{ $artist->name }}">
        <!-- Formulaire de téléversement d'image -->
        <form id="uploadThumbnailForm" action="{{ route('artists.uploadThumbnail', $artist->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
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
            <h3 class="card-title">Biography</h3>
            <p class="card-text">{{ $artist->biography }}</p>
            <h3 class="card-title">Main Genre 
                        @if (!$artist->main_genre)
                            <span class="add-main-genre">
                                <i class="fas fa-plus"></i>
                            </span>
                        @endif
                    </h3>
                    <p class="card-text">
                        @if ($artist->main_genre)
                            @php
                                $mainGenre = $artist->genres->where('name', $artist->main_genre)->first();
                            @endphp
                            @if ($mainGenre)
                                <a href="{{ route('genres.show', $mainGenre->id) }}">
                                    {{ $artist->main_genre }}
                                </a>
                            @else
                                {{ $artist->main_genre }}
                            @endif
                        @else
                            N/A
                        @endif
                        @if (!$artist->main_genre)
                            <form id="main-genre-form" action="{{ route('artists.updateMainGenre', $artist->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PATCH')
                                <select name="main_genre" class="form-control mt-2">
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->name }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Save</button>
                            </form>
                        @endif
                    </p>
            <h3 class="card-title">Career Start Year</h3>
            <p class="card-text">{{ $artist->career_start_year }}</p>
            <h3 class="card-title">Country of Origin</h3>
            <p class="card-text">{{ $artist->country_of_origin }}</p>
        </div>
    </div>
</div>




    </div>

    <div class="mt-4">
    <h2>Albums</h2>
    @if ($artist->albums->isEmpty())
        <p>No albums found.</p>
    @else
        <ul class="list-group">
            @foreach ($artist->albums as $album)
                <li class="list-group-item d-flex align-items-center">
                    @if ($album->thumbnail_image)
                        <img src="{{ Storage::url($album->thumbnail_image) }}" alt="{{ $album->title }}" class="img-thumbnail mr-3" style="width: 100px; height: 100px;">
                    @else
                        <img src="https://via.placeholder.com/100" alt="No Image" class="img-thumbnail mr-3" style="width: 100px; height: 100px;">
                    @endif
                    <a href="{{ route('albums.show', $album->id) }}">{{ $album->title }}   ({{ $album->year }})  </a>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('albums.createWithArtist', ['artist_id' => $artist->id]) }}" class="btn btn-outline-dark mt-3">Add an Album</a>
</div>


    <div class="mt-4">
        <h2>Music Videos</h2>
        @if ($artist->musicVideos->isEmpty())
            <p>No music videos found.</p>
        @else
            <ul class="list-group">
                @foreach ($artist->musicVideos as $musicVideo)
                    <li class="list-group-item">
                        <a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a> ({{ $musicVideo->year }})
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('musicVideos.createWithArtist', ['artist_id' => $artist->id]) }}" class="btn btn-outline-dark mt-3">Add a Music Video</a>
    </div>

    <div class="mt-4">
        <a href="{{ route('artists.edit', $artist->id) }}" class="btn btn-outline-dark">Modifier l'artiste</a>
    </div>
</div>

<div class="mt-4">
<a href="{{ url()->previous() }}" class="btn btn-outline-dark" id="backToList">Retour</a>

</div>

<div class="mt-4">
<a href="{{ route('artists.index') }}" class="btn btn-outline-dark" id="backToList">Retour à la liste</a>
</div>


@endsection

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
            .then(response => response.json())
            .then(data => {
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


<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #f8f9fa;
    }
    .artist-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .artist-image {
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
        color: #555;
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
    .btn-outline-dark {
        background-color: transparent;
        color: #343a40;
        border: 2px solid #343a40;
    }
    .btn-outline-dark:hover {
        background-color: #343a40;
        color: #fff;
    }
    .music-videos-title,
    .albums-title {
        font-size: 1.5rem;
        margin-top: 30px;
        margin-bottom: 20px;
    }
    .list-group-item {
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        color: #343a40;
        background-color: #fff;
        transition: background-color 0.3s ease;
    }
    .list-group-item:hover {
        background-color: #f1f1f1;
    }
    .list-group-item a {
        color: #343a40;
        text-decoration: none;
    }
    .list-group-item a:hover {
        text-decoration: underline;
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document is ready');

    const addMainGenreIcon = document.querySelector('.add-main-genre');
    const mainGenreForm = document.getElementById('main-genre-form');

    if (addMainGenreIcon) {
        console.log('Add Main Genre icon found');
        addMainGenreIcon.addEventListener('click', function() {
            console.log('Add Main Genre icon clicked');
            mainGenreForm.style.display = 'block';
            addMainGenreIcon.style.display = 'none';
        });
    }

    // Montrer l'icône + uniquement au survol de "Main Genre" si aucun genre principal n'est lié
    const cardTitles = document.querySelectorAll('.card-title');
    console.log('Card titles found:', cardTitles.length);

    cardTitles.forEach(title => {
        console.log('Processing title:', title.textContent);
        if (title.textContent.includes('Main Genre')) {
            console.log('Main Genre title found');
            title.addEventListener('mouseenter', function() {
                console.log('Mouse entered Main Genre title');
                if (!mainGenreForm || !mainGenreForm.style.display === 'block') {
                    addMainGenreIcon.style.display = 'inline';
                }
            });

            title.addEventListener('mouseleave', function() {
                console.log('Mouse left Main Genre title');
                if (!mainGenreForm || !mainGenreForm.style.display === 'block') {
                    addMainGenreIcon.style.display = 'none';
                }
            });
        }
    });
});
</script>

<style>
.add-main-genre {
    cursor: pointer;
    color: #007bff;
    margin-left: 10px;
}

.add-main-genre:hover {
    color: #0056b3;
}
</style>

