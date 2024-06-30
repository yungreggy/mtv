@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un Horaire de Série TV</h1>
    @include('partials.messages')
    <form action="{{ route('tvShowSchedules.store') }}" method="POST">
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
    <label for="tv_show_id">Série TV</label>
    <select class="form-control" id="tv_show_id" name="tv_show_id" required>
        <option value="" disabled selected>Sélectionnez une série</option>
        @foreach($tvShows as $tvShow)
            <option value="{{ $tvShow->id }}">{{ $tvShow->title }}</option>
        @endforeach
    </select>
</div>


        <div class="form-group">
            <label for="season_id">Saison</label>
            <select class="form-control" id="season_id" name="season_id" required>
                <option value="">Sélectionnez une série TV d'abord</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nom de l'Horaire</label>
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

        <div class="form-group d-none" id="days-of-week">
            <label for="days_of_week">Jours de la semaine</label>
            <div class="form-check">
                @foreach($daysOfWeek as $day)
                    <input type="checkbox" class="form-check-input" id="day_{{ $day->id }}" name="days_of_week[]" value="{{ $day->id }}">
                    <label class="form-check-label" for="day_{{ $day->id }}">{{ $day->name }}</label><br>
                @endforeach
            </div>
        </div>

   <div class="form-group">
            <label for="start_time">Heure de début</label>
            <input type="time" class="form-control" id="start_time" name="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">Heure de fin</label>
            <input type="time" class="form-control" id="end_time" name="end_time" required>
        </div>

        <div class="form-group d-none" id="specific-date-field">
            <label for="specific_date">Date spécifique</label>
            <input type="date" class="form-control" id="specific_date" name="specific_date" min="{{ $programDates->first() }}" max="{{ $programDates->last() }}">
        </div>

        <div class="form-group">
            <label for="continue_after_season">Action après la saison sélectionnée</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="continue_after_season" id="continue_after_season_yes" value="1" checked>
                <label class="form-check-label" for="continue_after_season_yes">
                    Continuer avec la saison suivante
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="continue_after_season" id="continue_after_season_no" value="0">
                <label class="form-check-label" for="continue_after_season_no">
                    Arrêter après la saison sélectionnée
                </label>
            </div>
        </div>


        

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const recurrenceField = document.getElementById('recurrence');
  const daysOfWeekField = document.getElementById('days-of-week');
  const specificDateField = document.getElementById('specific-date-field');
  const tvShowField = document.getElementById('tv_show_id');
  const seasonField = document.getElementById('season_id');
  const nameField = document.getElementById('name');

  recurrenceField.addEventListener('change', () => {
    console.log('Recurrence field changed');
    if (recurrenceField.value === 'none') {
      console.log('Showing specific date field');
      daysOfWeekField.classList.add('d-none');
      specificDateField.classList.remove('d-none');
    } else if (recurrenceField.value === 'weekly') {
      console.log('Showing days of week field');
      daysOfWeekField.classList.remove('d-none');
      specificDateField.classList.add('d-none');
    } else {
      console.log('Hiding specific date and days of week fields');
      daysOfWeekField.classList.add('d-none');
      specificDateField.classList.add('d-none');
    }
  });

  tvShowField.addEventListener('change', () => {
    console.log('TV show field changed');
    const tvShowId = tvShowField.value;
    if (tvShowId) {
      console.log('Fetching seasons for TV show');
      fetch(`/tv-shows/${tvShowId}/seasons`)
        .then(response => {
          console.log('Network response received');
          return response.ok ? response.json() : Promise.reject(new Error('Network response was not ok'));
        })
        .then(data => {
          console.log('Seasons data received');
          seasonField.innerHTML = '';
          data.seasons.forEach(season => {
            const option = document.createElement('option');
            option.value = season.id;
            option.textContent = `Saison ${season.season_number}`;
            seasonField.appendChild(option);
          });

          // Mettre à jour le nom de l'horaire
          if (data.seasons.length > 0) {
            nameField.value = `${tvShowField.options[tvShowField.selectedIndex].text}`;
          }
        })
        .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
        });
    } else {
      console.log('Clearing seasons field');
      seasonField.innerHTML = '<option value="">Sélectionnez une série TV d\'abord</option>';
      nameField.value = '';
    }
  });

  seasonField.addEventListener('change', () => {
    const tvShowName = tvShowField.options[tvShowField.selectedIndex].text;
    const seasonNumber = seasonField.options[seasonField.selectedIndex].text.split(' ')[1];
    nameField.value = `${tvShowName}`;
  });
});
</script>



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
