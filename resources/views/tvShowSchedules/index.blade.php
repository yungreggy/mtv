@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Horaires de Séries TV</h1>
    <a href="{{ route('tvShowSchedules.create') }}" class="btn btn-primary mb-3">Ajouter un Horaire</a>
    @include('partials.messages')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Programme</th>
                <th>Série TV</th>
                <th>Saison</th>
                <th>Heure de Début</th>
                <th>Heure de Fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->id }}</td>
                    <td>{{ $schedule->name }}</td>
                    <td>{{ $schedule->program->name }}</td>
                    <td>{{ $schedule->tvShow->title }}</td>
                    <td>Saison {{ $schedule->season->season_number }}</td>
                    <td>{{ $schedule->start_time }}</td>
                    <td>{{ $schedule->end_time }}</td>
                    <td>
                        <a href="{{ route('tvShowSchedules.show', $schedule->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route('tvShowSchedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('tvShowSchedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
