@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une Plage Horaire de Films</h1>
    @include('partials.messages')
    <form action="{{ route('filmSchedules.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="program_id">Programme</label>
            <select class="form-control" id="program_id" name="program_id" required>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nom de la Plage Horaire</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="recurrence">Récurrence</label>
            <select class="form-control" id="recurrence" name="recurrence" required>
                <option value="none">Aucune</option>
                <option value="daily">Quotidienne</option>
                <option value="weekly">Hebdomadaire</option>
            </select>
        </div>

        <div class="form-group" id="days-of-week" style="display: none;">
            <label for="days_of_week">Jours de la semaine</label>
            <div class="form-check">
                @foreach($daysOfWeek as $day)
                    <input type="checkbox" class="form-check-input" id="day_{{ $day->id }}" name="days_of_week[]" value="{{ $day->id }}">
                    <label class="form-check-label" for="day_{{ $day->id }}">{{ $day->name }}</label><br>
                @endforeach
            </div>
        </div>

        <div class="form-group" id="specific-date-field" style="display: none;">
            <label for="specific_date">Date spécifique</label>
            <input type="date" class="form-control" id="specific_date" name="specific_date" min="{{ $programDates->first() }}" max="{{ $programDates->last() }}">
        </div>

        <div class="form-group">
            <label for="start_time">Heure de début</label>
            <input type="time" class="form-control" id="start_time" name="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">Heure de fin</label>
            <input type="time" class="form-control" id="end_time" name="end_time" required>
        </div>

        <div class="form-group">
            <label for="genre_id">Genre</label>
            <select class="form-control" id="genre_id" name="genre_id">
                <option value="">Tous les genres</option>
                @foreach($filmGenres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="age_rating">Classification par Âge</label>
            <div id="age_rating">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="G" id="age_rating_g">
                    <label class="form-check-label" for="age_rating_g">G</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="PG" id="age_rating_pg">
                    <label class="form-check-label" for="age_rating_pg">PG</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="PG-13" id="age_rating_pg13">
                    <label class="form-check-label" for="age_rating_pg13">PG-13</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="R" id="age_rating_r">
                    <label class="form-check-label" for="age_rating_r">R</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="NC-17" id="age_rating_nc17">
                    <label class="form-check-label" for="age_rating_nc17">NC-17</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="age_rating[]" value="unrated" id="age_rating_unrated">
                    <label class="form-check-label" for="age_rating_unrated">Unrated</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="exclude_tags">Exclure par Tags</label>
            <input type="text" class="form-control" id="exclude_tags" name="exclude_tags" placeholder="Entrez les tags à exclure, séparés par des virgules">
        </div>

        <div class="form-group">
            <label for="start_year">Année de début</label>
            <input type="number" class="form-control" id="start_year" name="start_year" placeholder="Année de début">
        </div>

        <div class="form-group">
            <label for="end_year">Année de fin</label>
            <input type="number" class="form-control" id="end_year" name="end_year" placeholder="Année de fin">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
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
