@extends('layouts.app')

@section('content')
<style>
    .album-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .album-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
    }
    .filters-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .filters-container form,
    .filters-container .alphabet-filter {
        flex: 1;
    }
    .filters-container .alphabet-filter {
        text-align: right;
    }
    .alphabet-filter a {
        margin: 2px;
        color: #555;
    }
    .album-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .album-table th, .album-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .album-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .album-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .album-actions {
        display: flex;
        gap: 10px;
    }
    .album-actions form {
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
        border-radius: 4px;
    }
    .btn-dark {
        background-color: #555;
        color: #fff;
        border: none;
    }
    .btn-dark:hover {
        background-color: #444;
    }
    .btn-outline-dark {
        border: 1px solid #555;
        color: #555;
    }
    .btn-outline-dark:hover {
        background-color: #555;
        color: #fff;
    }
</style>

<br>
<div class="album-list-container">
    <h1 class="album-header">Liste des albums</h1>
    <p>Total d'albums : {{ $albumCount }}</p>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <!-- Filtres de tri et de recherche -->
    <div class="filters-container">
        <form action="{{ route('albums.index') }}" method="GET" class="form-inline mb-3">
            <label for="sort" class="mr-2">Trier par:</label>
            <select name="sort" id="sort" class="form-control mr-2">
                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
                <option value="artist_asc" {{ request('sort') == 'artist_asc' ? 'selected' : '' }}>Artiste (A-Z)</option>
                <option value="artist_desc" {{ request('sort') == 'artist_desc' ? 'selected' : '' }}>Artiste (Z-A)</option>
                <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Année (croissant)</option>
                <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Année (décroissant)</option>
            </select>
            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Appliquer</button>
        </form>
        
        <form action="{{ route('albums.index') }}" method="GET" class="form-inline mb-3 ml-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Rechercher</button>
        </form>
    </div>

    <!-- Table des albums -->
    <div class="table-responsive">
        <table class="album-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Année de sortie</th>
                    <th>Artiste</th>
                    <th>Nombre de pistes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($albums as $album)
                <tr>
                    <td>
                        <a href="{{ route('albums.show', $album->id) }}" class="font-weight-bold text-dark">{{ $album->title }}</a>
                    </td>
                    <td>{{ $album->year }}</td>
                    <td>
                        <a href="{{ route('artists.show', $album->artist->id) }}" class="text-dark">{{ $album->artist ? $album->artist->name : 'N/A' }}</a>
                    </td>
                    <td>{{ $album->track_count }}</td>
                    <td class="album-actions">
                        <a href="{{ route('albums.show', $album->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('albums.edit', $album->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('albums.destroy', $album->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-dark btn-sm" onclick="return confirm('Es-tu sûr de vouloir supprimer cet album ?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $albums->links() }}
    </div>
</div>
<br>
@endsection
