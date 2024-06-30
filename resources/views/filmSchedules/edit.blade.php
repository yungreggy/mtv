@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la plage horaire {{$schedule->name}}</h1>
    @include('partials.messages')
    <form action="{{ route('filmSchedules.update', $schedule->id) }}" method="POST">
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
            <label for="name">Nom de la Plage Horaire</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $schedule->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $schedule->description }}</textarea>
        </div>

        <div class="form-group">
    <label for="recurrence">Récurrence</label>
    <select class="form-control" id="recurrence" name="recurrence" required>
        <option value="none" {{ $schedule->recurrence == 'none' ? 'selected' : '' }}>Aucune</option>
        <option value="daily" {{ $schedule->recurrence == 'daily' ? 'selected' : '' }}>Quotidienne</option>
        <option value="weekly" {{ $schedule->recurrence == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
    </select>
</div>


        <div class="form-group" id="days-of-week" style="display: {{ $schedule->recurrence == 'weekly' ? 'block' : 'none' }};">
            <label for="days_of_week">Jours de la semaine</label>
            <div class="form-check">
                @foreach($daysOfWeek as $day)
                    <input type="checkbox" class="form-check-input" id="day_{{ $day->id }}" name="days_of_week[]" value="{{ $day->id }}" {{ in_array($day->id, $schedule->daysOfWeek->pluck('id')->toArray()) ? 'checked' : '' }}>
                    <label class="form-check-label" for="day_{{ $day->id }}">{{ $day->name }}</label><br>
                @endforeach
            </div>
        </div>

        <div class="form-group" id="specific-date-field" style="display: {{ $schedule->recurrence == 'none' ? 'block' : 'none' }};">
            <label for="specific_date">Date spécifique</label>
            <input type="date" class="form-control" id="specific_date" name="specific_date" value="{{ $schedule->specific_date }}" min="{{ $programDates->first() }}" max="{{ $programDates->last() }}">
        </div>

        <div class="form-group">
    <label for="start_time">Heure de début</label>
    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ date('H:i', strtotime($schedule->start_time)) }}" required>
</div>

<div class="form-group">
    <label for="end_time">Heure de fin</label>
    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ date('H:i', strtotime($schedule->end_time)) }}" required>
</div>


        <div class="form-group">
            <label for="genre_id">Genre</label>
            <select class="form-control" id="genre_id" name="genre_id">
                <option value="">Tous les genres</option>
                @foreach($filmGenres as $genre)
                    <option value="{{ $genre->id }}" {{ $schedule->genre_id == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="age_rating">Classification par Âge</label>
            <div id="age_rating">
            @foreach(['G', 'PG', 'PG-13', 'R', 'NC-17', 'unrated'] as $rating)
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="age_rating[]" value="{{ $rating }}" id="age_rating_{{ $rating }}" {{ in_array($rating, $schedule->age_rating ?? []) ? 'checked' : '' }}>
        <label class="form-check-label" for="age_rating_{{ $rating }}">{{ $rating }}</label>
    </div>
@endforeach

            </div>
        </div>

        <div class="form-group">
            <label for="exclude_tags">Exclure par Tags</label>
            <div id="exclude_tags">
            @foreach($tags as $tag)
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="exclude_tags[]" value="{{ $tag->id }}" id="exclude_tag_{{ $tag->id }}" {{ in_array($tag->id, $schedule->exclude_tags ?? []) ? 'checked' : '' }}>
        <label class="form-check-label" for="exclude_tag_{{ $tag->id }}">{{ $tag->name }}</label>
    </div>
@endforeach

              
            </div>
        </div>

        <div class="form-group">
            <label for="start_year">Année de début</label>
            <input type="number" class="form-control" id="start_year" name="start_year" value="{{ $schedule->start_year }}" placeholder="Année de début">
        </div>

        <div class="form-group">
            <label for="end_year">Année de fin</label>
            <input type="number" class="form-control" id="end_year" name="end_year" value="{{ $schedule->end_year }}" placeholder="Année de fin">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recurrenceField = document.getElementById('recurrence');
    const daysOfWeekField = document.getElementById('days-of-week');
    const specificDateField = document.getElementById('specific-date-field');

    recurrenceField.addEventListener('change', function () {
        if (this.value === 'none') {
            daysOfWeekField.style.display = 'none';
            specificDateField.style.display = 'block';
        } else if (this.value === 'weekly') {
            daysOfWeekField.style.display = 'block';
            specificDateField.style.display = 'none';
        } else {
            daysOfWeekField.style.display = 'none';
            specificDateField.style.display = 'none';
        }
    });
});
</script>
@endsection
