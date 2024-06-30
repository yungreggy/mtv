@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Genre</h1>
        @include('partials.messages')
        <form action="{{ route('genres.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom du Genre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label>Type</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_music" value="music" checked>
                    <label class="form-check-label" for="type_music">Musique</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_film" value="film">
                    <label class="form-check-label" for="type_film">Film/TV</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
@endsection

