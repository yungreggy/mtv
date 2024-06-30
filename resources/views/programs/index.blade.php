@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Programmes</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Durée</th>
                <th>Genre</th>
                <th>Statut</th>
                <th>Audience Cible</th>
                <th>Canaux</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $program)
                <tr>
                    <td>{{ $program->name }}</td>
                    <td>{{ $program->description }}</td>
                    <td>{{ $program->duration }}</td>
                    <td>{{ $program->genre }}</td>
                    <td>{{ $program->status }}</td>
                    <td>{{ $program->target_audience }}</td>
                    <td>
                        @foreach($program->channels as $channel)
                            <span class="badge badge-secondary">{{ $channel->name }}</span>
                        @endforeach
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('programs.show', $program->id) }}" class="btn btn-outline-info btn-sm mr-2">Voir</a>
                        <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-outline-warning btn-sm mr-2">Modifier</a>
                        <form action="{{ route('programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ce programme ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
