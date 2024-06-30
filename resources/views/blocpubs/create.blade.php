@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Créer un Bloc Publicitaire</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('blocPubs.store') }}" method="POST">
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
            <label for="include_intro">Inclure Intro MTV</label>
            <input type="checkbox" id="include_intro" name="include_intro" checked>
        </div>
        <div class="form-group">
            <label for="include_outro">Inclure Outro MTV</label>
            <input type="checkbox" id="include_outro" name="include_outro" checked>
        </div>
        <div class="form-group">
            <label for="number_of_pubs">Nombre de Pubs</label>
            <input type="number" class="form-control" id="number_of_pubs" name="number_of_pubs" value="6" required>
        </div>
        <div class="form-group">
            <label for="ad_types">Types de Publicités</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="All" id="ad_type_all" name="ad_types[]" onclick="toggleAll(this)" checked>
                <label class="form-check-label" for="ad_type_all">All</label>
            </div>
            @foreach(['Commercial', 'Music Video Ad', 'Event Sponsorship', 'MTV Bumper', 'MTV Promo', 'Show Trailer', 'Celebrity Endorsement', 'Public Service Announcement', 'Festival', 'Album Release', 'Movie TV Spot', 'Concert Promo', 'Merchandise Ad'] as $adType)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $adType }}" id="ad_type_{{ strtolower(str_replace(' ', '_', $adType)) }}" name="ad_types[]" checked>
                    <label class="form-check-label" for="ad_type_{{ strtolower(str_replace(' ', '_', $adType)) }}">{{ $adType }}</label>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <label for="start_year">Année de début</label>
            <input type="number" class="form-control" id="start_year" name="start_year" required>
        </div>
        <div class="form-group">
            <label for="end_year">Année de fin</label>
            <input type="number" class="form-control" id="end_year" name="end_year" required>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function toggleAll(source) {
            const checkboxes = document.getElementsByName('ad_types[]');
            for (let i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        // Pré-cocher toutes les cases à cocher si "All" est coché au chargement
        const allCheckbox = document.getElementById('ad_type_all');
        if (allCheckbox && allCheckbox.checked) {
            toggleAll(allCheckbox);
        }
    });
</script>
@endsection
