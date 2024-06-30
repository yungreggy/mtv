@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $schedule->name }}</h1>
    <p>Programme : {{ $schedule->program->name }}</p>
    @if($schedule->scheduleDays && $schedule->scheduleDays->isNotEmpty())
        <p>Tous les @foreach($schedule->scheduleDays as $day){{ $loop->first ? '' : ', ' }}{{ $day->day_of_week }}@endforeach à {{ $schedule->start_time }}</p>
    @else
        <p>Pas de jours définis.</p>
    @endif
    
    <p>{{ $schedule->description }}</p>
    <p>Heure de début : {{ $schedule->start_time }}</p>
    <p>Heure de fin : {{ $schedule->end_time }}</p>
    <p>Statut : {{ $schedule->status }}</p>

    <h2>Films associés</h2>
    @if($schedule->films->isEmpty())
        <p>Aucun film associé à cette plage horaire.</p>
    @else
        <ul>
            @foreach($schedule->films as $film)
                <li>{{ $film->title }} ({{ $film->year }}) - {{ $film->rating }}</li>
            @endforeach
        </ul>
    @endif



    <a href="" class="btn btn-primary">Modifier</a>
    <form action="{{ route('programSchedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>
</div>
@endsection



