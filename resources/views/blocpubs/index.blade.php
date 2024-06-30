@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-dark">Bloc Publicitaire</h1>
    <a href="{{ route('blocPubs.create') }}" class="btn btn-add mb-3">Ajouter un Bloc Publicitaire</a>
    <ul class="list-group">
        @foreach ($blocPubs as $blocPub)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('blocPubs.show', $blocPub->id) }}">
                        <strong>{{ $blocPub->id }} - {{ $blocPub->name }}</strong>
                    </a>
                </div>
                <div class="actions">
                    <a href="{{ route('blocPubs.show', $blocPub->id) }}" class="btn-icon" title="Voir">
                        <i class="material-icons">visibility</i>
                    </a>
                    <a href="{{ route('blocPubs.edit', $blocPub->id) }}" class="btn-icon" title="Modifier">
                        <i class="material-icons">edit</i>
                    </a>
                    <form action="{{ route('blocPubs.destroy', $blocPub->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Supprimer">
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
        background-color: #f4f4f4;
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

    .text-dark {
        color: #333 !important;
    }

    .btn-add {
        background-color: #6c757d;
        border: none;
        color: #fff;
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
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .list-group-item:hover {
        background-color: #f1f1f1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .btn-icon {
        color: #6c757d;
        text-decoration: none;
        font-size: 1.5rem;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .btn-icon:hover {
        color: #5a6268;
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
