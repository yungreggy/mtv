@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $schedule->name }}</h1>
    <div>
        <p><strong>Programme:</strong> {{ $schedule->program->name }}</p>
        <p><strong>Série TV:</strong> {{ $schedule->tvShow->title }}</p>
        <p><strong>Saison:</strong> Saison {{ $schedule->season->season_number }}</p>
        <p><strong>Heure de début:</strong> {{ $schedule->start_time }}</p>
        <p><strong>Heure de fin:</strong> {{ $schedule->end_time }}</p>
        <p><strong>Description:</strong> {{ $schedule->description }}</p>
    </div>
    <a href="{{ route('tvShowSchedules.edit', $schedule->id) }}" class="btn btn-warning">Modifier</a>
    <form action="{{ route('tvShowSchedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">Supprimer</button>
    </form>
    <a href="{{ route('tvShowSchedules.index') }}" class="btn btn-secondary">Retour à la liste des horaires</a>
</div>
@endsection
