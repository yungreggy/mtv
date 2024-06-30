@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Modifier le film diffusé pour {{ $schedule->name }}
        </div>
        @include('partials.messages')
        <div class="card-body">
            <form action="{{ route('filmSchedules.updateFilm', [$schedule->id, $film->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="film_id">Sélectionner un nouveau film</label>
                    <select class="form-control" id="film_id" name="film_id">
                        @foreach($allFilms as $newFilm)
                            <option value="{{ $newFilm->id }}" {{ $newFilm->id == $film->id ? 'selected' : '' }}>
                                {{ $newFilm->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
