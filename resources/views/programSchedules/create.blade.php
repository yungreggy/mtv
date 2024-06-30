@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un Nouvel Horaire de Programme</h1>
    @include('partials.messages')
    <form action="{{ route('programSchedules.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="program_id">Programme</label>
            <select name="program_id" class="form-control" required>
                @foreach (\App\Models\Program::all() as $program)
                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                @endforeach
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

        <!-- Autres champs -->
        <div class="form-group">
            <label for="days_of_week">Jours de Diffusion</label>
            <div class="form-check">
                @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <input type="checkbox" class="form-check-input" id="{{ strtolower($day) }}" name="days_of_week[]" value="{{ $day }}" {{ strtolower($day) == strtolower($dayOfWeek) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ strtolower($day) }}">{{ $day }}</label><br>
                @endforeach
            </div>
        </div>
       

        <div class="form-group">
            <label for="status">Statut</label>
            <input type="text" class="form-control" id="status" name="status">
        </div>

        <div class="form-group">
            <label for="special_notes">Notes spéciales</label>
            <textarea class="form-control" id="special_notes" name="special_notes"></textarea>
        </div>

        <div class="form-group">
            <label for="priority">Priorité</label>
            <input type="number" class="form-control" id="priority" name="priority">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>


@endsection
