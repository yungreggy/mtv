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
    gap: 10px;
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

</style>

<br>
<div class="tv-show-list-container">
    <div class="tv-show-header">
        <h1 class="mb-4">Liste des séries TV</h1>
        <a href="{{ route('tvShows.create') }}" class="btn btn-outline-dark">
            <i class="material-icons">add</i> Ajouter
        </a>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <!-- Filtres de tri et de recherche -->
<div class="filters-container">
    <form action="{{ route('tvShows.index') }}" method="GET" class="form-inline mb-3">
        <label for="sort" class="mr-2">Trier par:</label>
        <select name="sort" id="sort" class="form-control mr-2">
            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
            <option value="years_active" {{ request('sort') == 'years_active' ? 'selected' : '' }}>Années d'activité</option>
            <option value="season_count" {{ request('sort') == 'season_count' ? 'selected' : '' }}>Nombre de Saisons</option>
        </select>
        <button type="submit" class="btn btn-outline-dark"><i class="material-icons">filter_list</i> Appliquer</button>
    </form>

    <form action="{{ route('tvShows.index') }}" method="GET" class="form-inline mb-3 ml-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-dark"><i class="material-icons">search</i> Rechercher</button>
    </form>
    
    <div class="alphabet-filter mb-3 ml-3">
        @foreach(range('A', 'Z') as $letter)
            <a href="{{ route('tvShows.index', array_merge(request()->query(), ['letter' => $letter])) }}" class="btn btn-sm {{ request('letter') == $letter ? 'btn-outline-dark' : 'btn-outline-dark' }}">{{ $letter }}</a>
        @endforeach
    </div>
</div>


    <!-- Table des séries TV -->
    <div class="table-responsive">
        <table class="tv-show-table">

        <thead>
    <tr>
        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'id_asc' ? 'id_desc' : 'id_asc'])) }}" class="text-dark" style="font-size: 12px;">ID<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'id_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>
        <th style="font-size: 12px;">Poster</th>

        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'title_asc' ? 'title_desc' : 'title_asc'])) }}" class="text-dark" style="font-size: 12px;">Titre<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'title_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>

        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'years_active_asc' ? 'years_active_desc' : 'years_active_asc'])) }}" class="text-dark" style="font-size: 12px;">Années d'activité<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'years_active_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>

        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'season_count_asc' ? 'season_count_desc' : 'season_count_asc'])) }}" class="text-dark" style="font-size: 12px;">Nombre de Saisons<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'season_count_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>

        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'genre_asc' ? 'genre_desc' : 'genre_asc'])) }}" class="text-dark" style="font-size: 12px;">Genres<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'genre_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>

        <th><a href="{{ route('tvShows.index', array_merge(request()->query(), ['sort' => request('sort') === 'country_of_origin_asc' ? 'country_of_origin_desc' : 'country_of_origin_asc'])) }}" class="text-dark" style="font-size: 12px;">Pays d'origine<i class="material-icons" style="font-size: 12px;">{{ request('sort') === 'country_of_origin_asc' ? 'expand_less' : 'expand_more' }}</i></a></th>

        <th style="font-size: 12px;">Actions</th>
    </tr>
</thead>





            <tbody>
                @foreach($tvShows as $tvShow)
                    <tr>
                        <td>{{ $tvShow->id }}</td>
                        <td>
                            @if($tvShow->poster)
                                <img src="{{ asset('storage/' . $tvShow->poster) }}" alt="Poster" class="poster-image" >
                            @else
                                <span>Pas de poster</span>
                            @endif
                        </td>
                        <td><a href="{{ route('tvShows.show', $tvShow->id) }}" class="text-dark">{{ $tvShow->title }}</a></td>
                        <td>{{ $tvShow->years_active }}</td>
                        <td>{{ $tvShow->season_count }}</td>
                      <td>
    @if($tvShow->genres->isEmpty())
        N/A
    @else
        @foreach($tvShow->genres as $genre)
            <a href="{{ route('genres.show', $genre->id) }}">{{ $genre->name }}</a>{{ !$loop->last ? ', ' : '' }}
        @endforeach
    @endif
</td>

                        <td>{{ $tvShow->country_of_origin }}</td>
                        <td class="tv-show-actions">
                            <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">visibility</i></a>
                            <a href="{{ route('tvShows.edit', $tvShow->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">edit</i></a>
                            <form action="{{ route('tvShows.destroy', $tvShow->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 m-0 action-icon" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette série TV ?')"><i class="material-icons">delete</i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
   
</div>
<br>
@endsection

<!-- Styles -->
<style>
  .material-icons {
    font-weight: 300;
    color: #666; /* Gris foncé */
    vertical-align: middle;
    transition: color 0.3s;
}
.material-icons:hover {
    color: #000; /* Noircir au survol */
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
    height: 150px;
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
