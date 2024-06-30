@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="schedule-title">Program Schedules</h1>
    <a href="{{ route('programSchedules.create') }}" class="btn btn-primary btn-add">Add New Schedule</a>
    <ul class="schedule-list">
        @foreach ($schedules as $schedule)
            <li class="schedule-item">
                <div class="schedule-details">
                <span class="schedule-name"><a href="{{ route('programSchedules.show', $schedule->id) }}">{{ $schedule->name }}</a></span>

                    <span class="schedule-days">
                        @foreach ($schedule->daysOfWeek as $day)
                            <strong>{{ $day->name }}</strong>@if (!$loop->last), @endif
                        @endforeach
                    </span>
                    <span class="schedule-time">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                    </span>
                </div>
                <div class="schedule-actions">
                    <a href="{{ route('programSchedules.show', $schedule->id) }}" class="btn-icon" title="View">
                        <i class="material-icons">visibility</i>
                    </a>
                    @if ($schedule->type === 'film')
                        <a href="{{ route('filmSchedules.edit', $schedule->id) }}" class="btn-icon" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                    @else
                        <a href="{{ route('programSchedules.edit', $schedule->id) }}" class="btn-icon" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                    @endif
                    <form action="{{ route('programSchedules.destroy', $schedule->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                            <i class="material-icons">delete</i>
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection

<!-- Styles -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Material+Icons&display=swap');

    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
        color: #4a4a4a;
    }

    .container {
        max-width: 900px;
        margin: 30px auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .schedule-title {
        font-size: 2.5rem;
        color: #34495e;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 700;
    }

    .btn-add {
        background-color: #3498db;
        border: none;
        color: #ffffff;
        padding: 12px 25px;
        font-size: 1.2rem;
        text-transform: uppercase;
        border-radius: 30px;
        display: block;
        margin: 0 auto 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-add:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    .schedule-list {
        list-style-type: none;
        padding: 0;
    }

    .schedule-item {
        background-color: #e3f2fd;
        border: 1px solid #90caf9;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .schedule-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .schedule-details {
        display: flex;
        flex-direction: column;
        max-width: 70%;
    }

    .schedule-name {
        font-weight: 600;
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .schedule-time, .schedule-days {
        font-size: 1rem;
        color: #607d8b;
    }

    .schedule-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-icon {
        color: #16a085;
        text-decoration: none;
        font-size: 1.5rem;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .btn-icon:hover {
        color: #1abc9c;
    }

    .btn-delete {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #e74c3c;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .btn-delete:hover {
        color: #c0392b;
        transform: translateY(-2px);
    }

    .material-icons {
        font-family: 'Material Icons';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }
</style>

