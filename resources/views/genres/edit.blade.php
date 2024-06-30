@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le Genre</h1>
    
    @include('partials.messages')

    <form action="{{ route('genres.update', $genres->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom du Genre</label>
            <input type="text" name="name" class="form-control" value="{{ $genres->name }}" required>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" class="form-control" required>
                <option value="music" {{ $genres->type == 'music' ? 'selected' : '' }}>Musique</option>
                <option value="film" {{ $genres->type == 'film' ? 'selected' : '' }}>Film</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
