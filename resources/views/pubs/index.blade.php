@extends('layouts.app')

@section('content')
<style>
    .music-video-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .music-video-header {
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
    .music-video-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .music-video-table th, .music-video-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .music-video-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .music-video-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .music-video-table img {
        border-radius: 50%;
        object-fit: cover;
    }
    .music-video-actions {
        display: flex;
        gap: 10px;
    }
    .music-video-actions form {
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
<div class="music-video-list-container">
    <h1 class="music-video-header">Liste des Publicités</h1>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <!-- Filtres de tri et de recherche -->
    <div class="filters-container">
        <form action="{{ route('pubs.index') }}" method="GET" class="form-inline mb-3">
            <label for="sort" class="mr-2">Trier par:</label>
            <select name="sort" id="sort" class="form-control mr-2">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Année</option>
                <option value="date_added" {{ request('sort') == 'date_added' ? 'selected' : '' }}>Date d'ajout</option>
            </select>
            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Appliquer</button>
        </form>

        <form action="{{ route('pubs.index') }}" method="GET" class="form-inline mb-3 ml-3">
            <label for="type" class="mr-2">Type de Publicité:</label>
            <select name="type" id="type" class="form-control mr-2">
                <option value="">Tous les types</option>
                @foreach($adTypes as $adType)
                    <option value="{{ $adType->ad_type }}" {{ request('type') == $adType->ad_type ? 'selected' : '' }}>{{ $adType->ad_type }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Appliquer</button>
        </form>
        
        <form action="{{ route('pubs.index') }}" method="GET" class="form-inline mb-3 ml-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        
        <div class="alphabet-filter mb-3 ml-3">
            @foreach(range('A', 'Z') as $letter)
                <a href="{{ route('pubs.index', array_merge(request()->query(), ['letter' => $letter])) }}" class="btn btn-sm {{ request('letter') == $letter ? 'btn-dark' : 'btn-outline-dark' }}">{{ $letter }}</a>
            @endforeach
        </div>
    </div>

    <!-- Table des publicités -->
    <div class="table-responsive">
        <table class="music-video-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Marque/Magasin</th>
                    <th>Année</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pubs as $pub)
                <tr>
                    <td>
                        <a href="{{ route('pubs.show', $pub->id) }}" class="font-weight-bold text-dark">{{ $pub->name }}</a>
                    </td>
                    <td>
                        @if ($pub->brandStore)
                            <a href="{{ route('brandsStores.show', $pub->brandStore->id) }}" class="text-dark">{{ $pub->brandStore->name }}</a>
                        @endif
                    </td>
                    <td>{{ $pub->year }}</td>
                    <td>{{ $pub->ad_type }}</td>
                    <td class="music-video-actions">
                        <a href="{{ route('pubs.show', $pub->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('pubs.edit', $pub->id) }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('pubs.destroy', $pub->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-dark btn-sm" onclick="return confirm('Es-tu sûr de vouloir supprimer cette publicité ?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Lien pour ajouter une nouvelle publicité -->
    <div class="text-center">
        <a href="{{ route('pubs.create') }}" class="btn btn-dark mt-3"><i class="fas fa-plus"></i> Ajouter une nouvelle publicité</a>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $pubs->links() }}
    </div>
</div>
<br>
@endsection
