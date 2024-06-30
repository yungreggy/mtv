@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ \Carbon\Carbon::parse($date)->translatedFormat('l j F Y') }}</h1>
    <p>Ajouter une nouvelle plage horaire </p>
    @include('partials.messages')
    <form action="{{ route('programSchedules.storeWithDate', ['program' => $program->id, 'date' => $date]) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="program_id">Programme</label>
            <select name="program_id" class="form-control" required>
                <option value="{{ $program->id }}">{{ $program->name }}</option>
            </select>
        </div>

        <div class="form-group">
    <label for="type">Type de contenu</label>
    <select name="type" id="type" class="form-control" required>
        <option value="music_video">Clip</option>
        <option value="film">Film</option>
        <option value="tv_show">TV Show</option>
        <option value="other">Other</option>
    </select>
</div>


        <div class="form-group">
            <label for="name">Nom de l'horaire</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="start_time">Heure de début</label>
            <input type="time" class="form-control" id="start_time" name="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">Heure de fin</label>
            <input type="time" class="form-control" id="end_time" name="end_time" required>
        </div>

        <!-- Option de répétition -->
        <div class="form-group">
            <label for="repeat">Répétition</label>
            <select name="repeat" class="form-control">
                <option value="none">Aucune</option>
                <option value="daily">Quotidienne</option>
                <option value="weekly">Hebdomadaire</option>
            </select>
        </div>

        <div class="form-group">
            <label for="content_type">Type de contenu</label>
            <select name="content_type" class="form-control" id="content_type" required>
                <option value="clip">Clips</option>
                <option value="film">Film</option>
                <option value="tv_show">Émission de TV</option>
            </select>
        </div>

        <div id="film_options" style="display: none;">
            <div class="form-group">
                <label for="genre_id">Genre de film</label>
                <select name="genre_id" class="form-control" id="genre_id">
                    <option value="all">Tous les genres</option>
                    @foreach (\App\Models\Genre::where('type', 'film')->get() as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="age_rating">Classement d'âge</label>
                <input type="text" class="form-control" id="age_rating" name="age_rating">
            </div>
            <div class="form-group">
                <label for="film_id">Film spécifique</label>
                <select name="film_id" class="form-control" id="film_id">
                    @foreach (\App\Models\Film::all() as $film)
                        <option value="{{ $film->id }}" data-genres="{{ $film->genres->pluck('id')->implode(',') }}">{{ $film->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="tv_show_options" style="display: none;">
            <div class="form-group">
                <label for="tv_show_id">Émission de TV spécifique</label>
                <select name="tv_show_id" class="form-control">
                    @foreach (\App\Models\TvShow::all() as $tv_show)
                        <option value="{{ $tv_show->id }}">{{ $tv_show->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contentTypeSelect = document.getElementById('content_type');
    const filmOptions = document.getElementById('film_options');
    const tvShowOptions = document.getElementById('tv_show_options');
    const genreSelect = document.getElementById('genre_id');
    const filmSelect = document.getElementById('film_id');

    function toggleContentOptions() {
        const selectedType = contentTypeSelect.value;
        console.log('Selected content type:', selectedType);
        filmOptions.style.display = selectedType === 'film' ? 'block' : 'none';
        tvShowOptions.style.display = selectedType === 'tv_show' ? 'block' : 'none';
    }

    function filterFilms() {
        const selectedGenre = genreSelect.value;
        console.log('Selected genre:', selectedGenre);
        for (let option of filmSelect.options) {
            const genres = option.getAttribute('data-genres').split(',');
            if (selectedGenre === 'all' || genres.includes(selectedGenre)) {
                option.style.display = 'block';
                console.log('Showing film:', option.text);
            } else {
                option.style.display = 'none';
                console.log('Hiding film:', option.text);
            }
        }
        filmSelect.value = ''; // Clear the film selection when changing genres
    }

    contentTypeSelect.addEventListener('change', toggleContentOptions);
    genreSelect.addEventListener('change', filterFilms);
    toggleContentOptions(); // Initial call to set the correct display
    filterFilms(); // Initial call to filter films
});
</script>

@endsection