@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Liste des labels</h1>
    
    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <!-- Lien pour ajouter un nouveau label -->
    <a href="{{ route('labels.create') }}" class="btn btn-outline-dark mb-4"><i class="fas fa-plus"></i> Ajouter un nouveau label</a>

    <!-- Table des labels -->
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Nom</th>
                    <th>Logo</th>
                    <th>Site Web</th>
                    <th>Description</th>
                    <th>Année de fondation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $label)
                <tr>
                    <td>{{ $label->name }}</td>
                    <td>
                        @if ($label->logo_image)
                            <img src="{{ Storage::url($label->logo_image) }}" alt="{{ $label->name }}" class="img-thumbnail" style="height: 40px;">
                        @else
                            <img src="https://via.placeholder.com/40" alt="Placeholder" class="img-thumbnail" style="height: 40px;">
                        @endif
                    </td>
                    <td><a href="{{ $label->website }}" target="_blank">{{ $label->website }}</a></td>
                    <td>{{ $label->description }}</td>
                    <td>{{ $label->foundation_year }}</td>
                    <td>
                        <a href="{{ route('labels.show', $label->id) }}" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i> Voir</a>
                        <a href="{{ route('labels.edit', $label->id) }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                        <form action="{{ route('labels.destroy', $label->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Es-tu sûr de vouloir supprimer ce label ?')"><i class="fas fa-trash-alt"></i> Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $labels->links() }}
    </div>
</div>
@endsection

<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .container {
        margin-top: 20px;
    }
    h1 {
        font-size: 2rem;
        color: #343a40;
        margin-bottom: 20px;
    }
    .btn-outline-dark {
        background-color: transparent;
        color: #343a40;
        border: 1px solid #343a40;
        padding: 5px 10px;
        text-transform: uppercase;
    }
    .btn-outline-dark:hover {
        background-color: #343a40;
        color: #fff;
    }
    .btn-outline-info {
        background-color: transparent;
        color: #17a2b8;
        border: 1px solid #17a2b8;
        padding: 5px 10px;
        text-transform: uppercase;
    }
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #fff;
    }
    .btn-outline-warning {
        background-color: transparent;
        color: #ffc107;
        border: 1px solid #ffc107;
        padding: 5px 10px;
        text-transform: uppercase;
    }
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #fff;
    }
    .btn-outline-danger {
        background-color: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 5px 10px;
        text-transform: uppercase;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
    .table {
        border: 1px solid #dee2e6;
        background-color: #ffffff;
    }
    .thead-dark th {
        background-color: #343a40;
        color: #fff;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
    .img-thumbnail {
        border: 1px solid #dee2e6;
        border-radius: 0;
    }
    a {
        color: #343a40;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    a:hover {
        color: #000;
        text-decoration: underline;
    }
</style>
