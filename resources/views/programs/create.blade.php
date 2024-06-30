@extends('layouts.app')

@section('content')
<div class="container">
    @include('partials.messages')
    <h1>Ajouter un nouveau programme</h1>
    <form action="{{ route('programs.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"  ></textarea>
        </div>
        <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" name="start_date" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" name="end_date" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" name="genre" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" name="status" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="target_audience">Audience cible</label>
            <input type="text" name="target_audience" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="channels">Canaux</label>
            <select name="channels[]" class="form-control" multiple  >
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
