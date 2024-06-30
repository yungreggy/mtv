@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier un Horaire de Série TV</h1>
    @include('partials.messages')
    <form action="{{ route('tvShowSchedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="program_id">Programme</label>
            <select class="form-control" id="program_id" name="program_id" required>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ $schedule->program_id == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nom de l'Horaire</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $schedule->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $schedule->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="recurrence">Récurrence</label>
            <select class="form-control" id="recurrence" name="recurrence" required>
                <option value="none" {{ $schedule->recurrence == 'none' ? 'selected' : '' }}>Aucune</option>
                <option value="daily" {{ $schedule->recurrence == 'daily' ? 'selected' : '' }}>Quotidienne</option>
                <option value="weekly" {{ $schedule->recurrence == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
            </select>
        </div>

        <div class="form-group" id="days-of-week" style="{{ $schedule->recurrence == 'weekly' ? 'display: block;' : 'display: none;' }}">
            <label for="days_of_week">Jours de la semaine</label>
            <div class="form-check">
                @foreach($daysOfWeek as $day)
                    <input type="checkbox" class="form-check-input" id="day_{{ $day->id }}" name="days_of_week[]" value="{{ $day->id }}" {{ in_array($day->id, $schedule->daysOfWeek->pluck('id')->toArray()) ? 'checked' : '' }}>
                    <label class="form-check-label" for="day_{{ $day->id }}">{{ $day->name }}</label><br>
                @endforeach
            </div>
        </div>

        <div class="form-group" id="specific-date-field" style="{{ $schedule->recurrence == 'none' ? 'display: block;' : 'display: none;' }}">
            <label for="specific_date">Date spécifique</label>
            <input type="date" class="form-control" id="specific_date" name="specific_date" value="{{ $schedule->specific_date }}" min="{{ $programDates->first() }}" max="{{ $programDates->last() }}">
        </div>

        <div class="form-group">
            <label for="start_time">Heure de début</label>
            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $schedule->start_time }}" required>
        </div>

        <div class="form-group">
            <label for="end_time">Heure de fin</label>
            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $schedule->end_time }}" required>
        </div>

        <div class="form-group">
            <label for="tv_show_id">Série TV</label>
            <select class="form-control" id="tv_show_id" name="tv_show_id" required>
                @foreach($tvShows as $tvShow)
                    <option value="{{ $tvShow->id }}" {{ $schedule->tv_show_id == $tvShow->id ? 'selected' : '' }}>{{ $tvShow->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="season_id">Saison</label>
            <select class="form-control" id="season_id" name="season_id" required>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" {{ $schedule->season_id == $season->id ? 'selected' : '' }}>Saison {{ $season->season_number }} ({{ $season->tvShow->title }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Modifier

