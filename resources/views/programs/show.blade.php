@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ $program->name }}
        </div>
        <div class="card-body">
            <p class="card-text">Description: {{ $program->description }}</p>
            
            @if ($program->thumbnail_image)
                <div class="text-center">
                    <img src="{{ asset('storage/' . $program->thumbnail_image) }}" class="img-fluid rounded" alt="Image de {{ $program->name }}">
                </div>
            @endif
            <h5 class="mt-4">Canaux Diffusant Ce Programme :</h5>
            <ul class="list-group">
                @foreach ($program->channels as $channel)
                    <li class="list-group-item">
                        <a href="{{ route('channels.show', $channel->id) }}">{{ $channel->name }}</a>
                    </li>
                @endforeach
            </ul>

            <h5 class="mt-4">Calendrier de Programmation :</h5>
            @foreach ($calendar as $month => $dates)
                <h6 class="mt-4">{{ $month }}</h6>
                <div class="row mb-2"></div>
                <div class="row">
                    @php
                        $firstDayOfMonth = $dates[0]->format('N');
                    @endphp
                    @for ($i = 1; $i < $firstDayOfMonth; $i++)
                        <div class="col border text-center calendar-day empty-day"></div>
                    @endfor

                    @foreach ($dates as $date)
                        <div class="col border text-center calendar-day">
                            <a href="{{ route('programDates.show', ['program' => $program->id, 'date' => $date->format('Y-m-d')]) }}">
                                <div class="day-number"><strong>{{ $date->format('j') }}</strong></div>
                                {{ $date->format('D') }}
                                @php
                                    $programDate = $program->dates->firstWhere('date', $date->format('Y-m-d'));
                                @endphp
                                @if ($programDate)
                                    @foreach ($programDate->schedules as $schedule)
                                        <div class="schedule" style="display: flex; gap:0.5rem;">
                                            <p>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}</p>
                                            <p><strong>{{ $schedule->name }}</strong></p>
                                            
                                           
                                        </div>
                                    @endforeach
                                @endif
                            </a>
                        </div>
                        @if ($date->format('N') == 7)
                            </div><div class="row">
                        @endif
                    @endforeach

                    @php
                        $remainingDays = 7 - end($dates)->format('N');
                    @endphp
                    @for ($i = 0; $i < $remainingDays; $i++)
                        <div class="col border text-center calendar-day empty-day"></div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<style>
    .schedule {
        margin-top: 0.5rem;
    }
    .calendar {
        margin-top: 20px;
    }
    .calendar .row {
        margin-left: 0;
        margin-right: 0;
    }
    .calendar-day {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        position: relative;
        min-height: 250px;
        padding-top: 5px;
        align-items: flex-start;
        justify-content: flex-start;
        margin: 5px;
        transition: background-color 0.3s;
        cursor: pointer;
    }
    .calendar-day:hover {
        background-color: #e9ecef;
    }
    .calendar-day a {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        padding: 5px;
        font-weight: bold;
        font-size: 1rem;
        margin-bottom: 5px;
    }
    .empty-day {
        opacity: 0;
        cursor: default;
    }
    .day-number {
        position: absolute;
        top: 5px;
        left: 5px;
    }
    .calendar-day div {
        font-size: 0.75rem;
    }
</style>

