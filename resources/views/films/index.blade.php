@extends('layouts.app')

@section('content')
<style>
    .tv-show-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .tv-show-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tv-show-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .tv-show-table th, .tv-show-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .tv-show-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .tv-show-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .tv-show-table .poster-image {
        height: 150px;
        width: auto;
        object-fit: cover;
    }
    .tv-show-actions {
        display: flex;
        justify-content: center;
        align-items: stretch;
    }
    .tv-show-actions form {
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

    .poster-image {
        height: 150px;
        width: auto;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animation pour le hover */
    }
    .poster-image:hover {
        transform: scale(1.05); /* Agrandir l'image légèrement */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ajouter une ombre */
    }
</style>

<br>
<div class="tv-show-list-container">
    <div class="tv-show-header">
        <h1 class="mb-4">Liste des films</h1>
        <a href="{{ route('films.create') }}" class="btn btn-outline-dark">
            <i class="material-icons">add</i> Ajouter
        </a>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')
<!-- Filtres -->
<!-- Formulaire de recherche et de tri -->
<div class="tv-show-filters mb-4">
        <form method="GET" action="{{ route('films.index') }}" class="form-inline">
            <div class="form-group mr-2">
                <label for="search" class="mr-2">Rechercher:</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Rechercher...">
            </div>
            <div class="form-group mr-2">
                <label for="sort_by" class="mr-2">Trier par:</label>
                <select name="sort_by" id="sort_by" class="form-control">
                    <option value="title_asc" {{ request('sort_by') == 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                    <option value="title_desc" {{ request('sort_by') == 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
                    <option value="director" {{ request('sort_by') == 'director' ? 'selected' : '' }}>Réalisateur</option>
                    <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Année</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline-dark">Appliquer</button>
        </form>
    </div>


    <!-- Table des films -->
    <div class="table-responsive">
        <table class="tv-show-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>Réalisateur</th>
                    <th>Durée</th>
                    <th>Genres</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($films as $film)
                    <tr>
                        <td>{{ $film->id }}</td>
                        <td>
                            @if($film->local_image_path)
                                <a href="{{ route('films.show', $film->id) }}" title="{{ $film->title }}">
                                    <img src="{{ asset('storage/' . $film->local_image_path) }}" alt="Poster" class="poster-image" loading="lazy">
                                </a>
                            @else
                                <span>Pas de poster</span>
                            @endif
                        </td>
                        <td><a href="{{ route('films.show', $film->id) }}" class="text-dark">{{ $film->title }}</a></td>
                        <td><a href="{{ route('search.year', ['year' => $film->year]) }}">{{ $film->year }}</a></td>

                        <td>
                            @if($film->director)
                                <a href="{{ route('directors.show', $film->director->id) }}" class="text-dark">{{ $film->director->name }}</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $film->duration }}</td>
                        <td>
                            @if($film->genres->isEmpty())
                                N/A
                            @else
                                @foreach($film->genres as $genre)
                                    <a href="{{ route('genres.show', $genre->id) }}">{{ $genre->name }}</a>{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            @endif
                        </td>
                        <td class="rating">
    @if(in_array($film->rating, ['G', 'PG']))
        <span class="badge badge-success" title="{{ $film->rating == 'G' ? 'General Audience' : 'Parental Guidance Suggested' }}" style="cursor: default;">{{ $film->rating }}</span>
    @elseif($film->rating == 'PG-13')
        <span class="badge badge-warning" title="Parents Strongly Cautioned" style="cursor: default;">{{ $film->rating }}</span>
    @elseif(in_array($film->rating, ['R', 'NC-17']))
        <span class="badge badge-danger" title="{{ $film->rating == 'R' ? 'Restricted' : 'Adults Only' }}" style="cursor: default;">{{ $film->rating }}</span>
    @else
        <span style="cursor: default;">{{ $film->rating }}</span>
    @endif
</td>


<style>
    .badge-success {
        background-color: green;
        color: white;
    }
    .badge-warning {
        background-color: orange;
        color: white;
    }
    .badge-danger {
        background-color: red;
        color: white;
    }
</style>

                        <td class="tv-show-actions text-center">
                       
                            <a href="{{ route('films.edit', $film->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">edit</i></a>
                            <form action="{{ route('films.destroy', $film->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 m-0 action-icon" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce film ?')"><i class="material-icons">delete</i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $films->appends(['sort_by' => request('sort_by'), 'search' => request('search')])->links() }}
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
    .table tbody td .poster-image {
        height: 50px;
        width: auto;
        object-fit: cover;
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
