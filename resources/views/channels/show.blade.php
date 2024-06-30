@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails du Canal</h1>
    <div class="card">
        <div class="card-header">
            {{ $channel->name }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Description:</h5>
            @if ($channel->description)
                <p>{{ $channel->description }}</p>
            @else
                <p class="text-muted">Aucune description disponible pour ce canal.</p>
            @endif
            
            <p>Statut: {{ $channel->status }}</p>
            <p>Type de Canal: {{ $channel->type }}</p> <!-- Assurez-vous que ces attributs existent -->

            @if ($channel->logo)
                <div class="text-center">
                    <img src="{{ Storage::url($channel->logo) }}" class="img-fluid rounded shadow-sm" alt="Logo de {{ $channel->name }}">
                </div>
            @else
                <div class="text-center">
                    <img src="https://via.placeholder.com/150" class="img-fluid rounded shadow-sm" alt="Logo de {{ $channel->name }}">
                </div>
            @endif

            <h5 class="mt-4">Programmes Diffusés sur ce Canal:</h5>
            @if ($channel->programs->isEmpty())
                <p class="text-muted">Aucun programme n'est actuellement diffusé sur ce canal.</p>
            @else
                <ul class="list-group">
                    @foreach ($channel->programs as $program)
                        <li class="list-group-item">
                            <a href="{{ route('programs.show', $program->id) }}">{{ $program->name }}</a>
                            @if ($program->description)
                                - {{ $program->description }}
                            @else
                                - <span class="text-muted">Aucune description disponible.</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-4">
                <a href="{{ route('channels.edit', $channel->id) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('channels.destroy', $channel->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


