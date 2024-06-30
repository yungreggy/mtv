@extends('layouts.app')

@section('content')
<style>

.artist-table img {
        border-radius: 50%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .artist-table img:hover {
        transform: scale(1.1);
    }



    .artist-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .artist-header {
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
    .artist-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .artist-table th, .artist-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .artist-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .artist-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .artist-table img {
        border-radius: 50%;
        object-fit: cover;
    }
    .artist-actions {
        display: flex;
        gap: 10px;
    }
    .artist-actions form {
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
<div class="artist-list-container">
    <h1 class="artist-header">Liste des artistes</h1>

    <p>Total d'artistes : {{ $artistCount }}</p>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <div class="filters-container">
        <form action="{{ route('artists.index') }}" method="GET" class="form-inline mb-3">
            <label for="sort" class="mr-2">Trier par:</label>
            <select name="sort" id="sort" class="form-control mr-2">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                <option value="genre" {{ request('sort') == 'genre' ? 'selected' : '' }}>Genre</option>
                <option value="date_added" {{ request('sort') == 'date_added' ? 'selected' : '' }}>Date d'ajout</option>
            </select>
            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Appliquer</button>
        </form>

        <form action="{{ route('artists.index') }}" method="GET" class="form-inline mb-3 ml-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        
        <div class="alphabet-filter mb-3 ml-3">
            @foreach(range('A', 'Z') as $letter)
                <a href="{{ route('artists.index', array_merge(request()->query(), ['letter' => $letter])) }}" class="btn btn-sm {{ request('letter') == $letter ? 'btn-dark' : 'btn-outline-dark' }}">{{ $letter }}</a>
            @endforeach
        </div>
    </div>

    <!-- Table des artistes -->
    <div class="table-responsive">
        <table class="artist-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Genre principal</th>
                    <th>Année de début</th>
               
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artists as $artist)
                <tr>
                <td>
    <a href="{{ route('artists.show', $artist->id) }}">
        @if ($artist->thumbnail_image)
            <img src="{{ Storage::url($artist->thumbnail_image) }}" alt="{{ $artist->name }}" title=
            "{{ $artist->name }}" style="width: 100px; height: 100px;" loading="lazy">
        @else
            <img src="https://via.placeholder.com/100" alt="Image par défaut" style="width: 100px; height: 100px;">
        @endif
    </a>
</td>

                    <td>
                        <a href="{{ route('artists.show', $artist->id) }}" class="font-weight-bold text-dark">{{ $artist->name }}</a>
                    </td>
                    <td>{{ $artist->main_genre }}</td>
                    <td>{{ $artist->career_start_year }}</td>
                  
                    <td class="artist-actions">
                        <a href="{{ route('artists.show', $artist->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('artists.edit', $artist->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('artists.destroy', $artist->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-dark btn-sm" onclick="return confirm('Es-tu sûr de vouloir supprimer cet artiste ?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Lien pour ajouter un nouvel artiste -->
    <div class="text-center">
        <a href="{{ route('artists.create') }}" class="btn btn-dark mt-3"><i class="fas fa-plus"></i> Ajouter un nouvel artiste</a>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $artists->links() }}
    </div>
</div>
<br>
@endsection


