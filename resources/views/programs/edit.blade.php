@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center font-weight-bold">Modifier le Programme</h1>
    @include('partials.messages')
    <form action="{{ route('programs.update', $program->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" class="form-control" value="{{ $program->name }}"  >
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"  >{{ $program->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" name="start_date" class="form-control" value="{{ $program->start_date }}"  >
        </div>

        <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" name="end_date" class="form-control" value="{{ $program->end_date }}"  >
        </div>

        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" name="genre" class="form-control" value="{{ $program->genre }}"  >
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <input type="text" name="status" class="form-control" value="{{ $program->status }}"  >
        </div>

        <div class="form-group">
            <label for="target_audience">Audience cible</label>
            <input type="text" name="target_audience" class="form-control" value="{{ $program->target_audience }}"  >
        </div>

        <div class="form-group">
            <label for="channels">Canaux</label>
            <select name="channels[]" class="form-control" multiple  >
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}" {{ in_array($channel->id, $program->channels->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour le programme</button>
    </form>

    <div class="mt-5">
        <h2 class="text-center font-weight-bold">Canaux associés</h2>
        <ul class="list-group">
            @foreach($program->channels as $channel)
                <li class="list-group-item">{{ $channel->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
