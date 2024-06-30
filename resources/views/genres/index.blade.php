@extends('layouts.app')

@section('content')
<style>
    .genre-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .genre-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .genre-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .genre-table th, .genre-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .genre-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .genre-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .genre-actions {
        display: flex;
        gap: 10px;
    }
    .genre-actions form {
        display: inline-block;
    }
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination li {
        margin: 0 5px;
    }
    .pagination li a,
    .pagination li span {
        color: #555;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
    }
    .pagination li a:hover,
    .pagination li span.active {
        background-color: #555;
        color: #fff;
        border-color: #555;
    }
    .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 50px;
    }
    .btn-outline-dark {
        border: 1px solid #555;
        color: #555;
    }
    .btn-outline-dark:hover {
        background-color: #555;
        color: #fff;
    }
    .btn-link {
        color: #666;
        text-decoration: none;
    }
    .btn-link:hover {
        color: #000;
    }
</style>

<br>
<div class="genre-list-container">

<div class="genre-header">
    <h1 class="mb-4">Liste des genres</h1>
    <div class="d-flex align-items-center">
    <form action="{{ route('genres.index') }}" method="GET" class="mr-3">
            <div class="form-group mb-0 d-flex align-items-center">
                <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher un genre" value="{{ request()->get('search') }}">
                <button type="submit" class="btn btn-outline-dark">Rechercher</button>
            </div>
        </form>
        <form action="{{ route('genres.index') }}" method="GET" class="mr-3">
            <div class="form-group mb-0 d-flex align-items-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_all" value="" {{ request()->get('type') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label" for="type_all">Tous</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_music" value="music" {{ request()->get('type') == 'music' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label" for="type_music">Musique</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_film" value="film" {{ request()->get('type') == 'film' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label" for="type_film">Film/TV</label>
                </div>
            </div>
        </form>
        <a href="{{ route('genres.create') }}" class="btn btn-outline-dark">
            <i class="material-icons">add</i> Ajouter
        </a>
    </div>
</div>



    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

   <!-- Table des genres -->
<div class="table-responsive">
    @if($genres->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun genre trouvé.
        </div>
    @else
        <table class="genre-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                 
                    <th>Catégorie</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @foreach($genres as $genre)
        <tr>
            <td>{{ $genre->id }}</td>
            <td><a href="{{ route('genres.show', $genre->id) }}" class="text-dark">{{ $genre->name }}</a></td>
            <td>{{ $genre->category }}</td>
            <td>{{ $genre->type == 'music' ? 'Musique' : 'Film/TV' }}</td>
         
            <td class="genre-actions">
                <a href="{{ route('genres.show', $genre->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">visibility</i></a>
                <a href="{{ route('genres.edit', $genre->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">edit</i></a>
                <form action="{{ route('genres.destroy', $genre->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link p-0 m-0 action-icon" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce genre ?')"><i class="material-icons">delete</i></button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
    @endif
</div>


    <!-- Pagination -->
    <div class="pagination-container">
        {{ $genres->links() }}
    </div>
</div>
<br>
@endsection

<!-- Styles -->
<style>
    .material-icons {
        font-weight: 300;
        color: #666;
        vertical-align: middle;
        transition: color 0.3s;
    }
    .material-icons:hover {
        color: #000;
    }
    .btn-link {
        color: #666;
        text-decoration: none;
    }
    .btn-link:hover {
        color: #000;
    }
    .table thead th {
        text-align: left;
    }
    .table tbody td a {
        color: inherit;
        text-decoration: none;
    }
    .table tbody td a:hover {
        text-decoration: underline;
    }
    .table tbody td .action-icon {
        color: #666;
        transition: color 0.3s;
    }
    .table tbody td .action-icon:hover {
        color: #000;
    }
    .table {
        background-color: #fff;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }
    .table-borderless tbody tr td {
        border-top: none;
    }
    .table-borderless thead th {
        border-bottom: none;
    }
</style>

