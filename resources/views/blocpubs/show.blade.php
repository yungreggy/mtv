@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-dark">Détails du Bloc Publicitaire: {{ $blocPub->name }}</h1>
    
    <div class="details mb-4">
        <p><strong>Programme:</strong> {{ $blocPub->program->name }}</p>
        <p><strong>Inclure Intro MTV:</strong> {{ $blocPub->include_intro ? 'Oui' : 'Non' }}</p>
        <p><strong>Inclure Outro MTV:</strong> {{ $blocPub->include_outro ? 'Oui' : 'Non' }}</p>
        <p><strong>Durée Totale:</strong> {{ $blocPub->duration }}</p>
        <div class="actions mt-3">
            <a href="{{ route('blocPubs.edit', $blocPub->id) }}" class="btn btn-icon" title="Modifier">
                <i class="material-icons">edit</i> Modifier
            </a>
            <form action="{{ route('blocPubs.destroy', $blocPub->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-icon btn-delete" title="Supprimer">
                    <i class="material-icons">delete</i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <h3 class="mt-4 text-secondary">Publicités Associées</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Durée</th>
   
            </tr>
        </thead>
        <tbody>
            @foreach ($blocPub->pubs as $pub)
                <tr>
                    <td><a href="{{ route('pubs.show', $pub->id) }}" class="pub-link">{{ $pub->name }}</a></td>
                    <td><span class="badge">{{ $pub->duration }}</span></td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class=" mt-4">
        <a href="{{ route('blocPubs.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>
@endsection

<!-- Styles -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons');

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

    .details p {
        font-size: 1.1rem;
        color: #555;
    }

    .details p strong {
        color: #333;
    }

    .table {
        margin-top: 20px;
    }

    .table thead th {
        border-bottom: 2px solid #dee2e6;
        color: #333;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table .pub-link {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
    }

    .table .pub-link:hover {
        color: #555;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
        border-radius: 12px;
        background-color: #6c757d;
        color: #fff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 1rem;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    .text-dark {
        color: #333 !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #333;
        cursor: pointer;
        padding: 0;
        font-size: 1.5rem;
        transition: color 0.3s;
        display: flex;
        align-items: center;
    }

    .btn-icon:hover {
        color: #555;
    }

    .btn-delete {
        color: #e3342f;
    }

    .btn-delete:hover {
        color: #cc1f1a;
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
