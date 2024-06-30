@extends('layouts.app')

@section('content')
<style>
    .channel-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .channel-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    .channel-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .channel-table th, .channel-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .channel-table th {
        background-color: #f1f1f1;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .channel-table td {
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }
    .channel-table .thumbnail-image {
        height: 40px;
        width: 40px;
        object-fit: cover;
    }
    .channel-table .logo-image {
        height: 40px;
        width: 40px;
        object-fit: contain;
    }
    .channel-actions {
        display: flex;
        gap: 10px;
    }
    .channel-actions form {
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
</style>

<br>
<div class="channel-list-container">
    <div class="channel-header">
        <h1 class="mb-4">Liste des Channels</h1>
        <a href="{{ route('channels.create') }}" class="btn btn-outline-dark">
            <i class="material-icons">add</i> Ajouter un Channel
        </a>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    @include('partials.messages')

    <!-- Filtres de tri et de recherche -->
    <div class="filters-container">
        <form action="{{ route('channels.index') }}" method="GET" class="form-inline mb-3">
            <label for="sort" class="mr-2">Trier par:</label>
            <select name="sort" id="sort" class="form-control mr-2">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                <option value="date_added" {{ request('sort') == 'date_added' ? 'selected' : '' }}>Date d'ajout</option>
            </select>
            <button type="submit" class="btn btn-outline-dark"><i class="material-icons">filter_list</i> Appliquer</button>
        </form>

        <form action="{{ route('channels.index') }}" method="GET" class="form-inline mb-3 ml-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-dark"><i class="material-icons">search</i> Rechercher</button>
        </form>
        
        <div class="alphabet-filter mb-3 ml-3">
            @foreach(range('A', 'Z') as $letter)
                <a href="{{ route('channels.index', array_merge(request()->query(), ['letter' => $letter])) }}" class="btn btn-sm {{ request('letter') == $letter ? 'btn-outline-dark' : 'btn-outline-dark' }}">{{ $letter }}</a>
            @endforeach
        </div>
    </div>

    <!-- Table des channels -->
    <div class="table-responsive">
        <table class="channel-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image Miniature</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($channels as $channel)
                <tr>
                    <td>
                        <a href="{{ route('channels.show', $channel->id) }}" class="font-weight-bold text-dark">{{ $channel->name }}</a>
                    </td>
                    <td>{{ $channel->description }}</td>
                    <td>
                        @if ($channel->thumbnail_image)
                            <img src="{{ Storage::url($channel->thumbnail_image) }}" alt="{{ $channel->name }}" class="thumbnail-image">
                        @else
                            <img src="https://via.placeholder.com/40" alt="Placeholder" class="thumbnail-image">
                        @endif
                    </td>
                    <td>
                        @if ($channel->logo)
                            <img src="{{ Storage::url($channel->logo) }}" alt="{{ $channel->name }}" class="logo-image">
                        @else
                            <img src="https://via.placeholder.com/40" alt="Placeholder" class="logo-image">
                        @endif
                    </td>
                    <td class="channel-actions">
                        <a href="{{ route('channels.show', $channel->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">visibility</i></a>
                        <a href="{{ route('channels.edit', $channel->id) }}" class="btn btn-link p-0 m-0 action-icon"><i class="material-icons">edit</i></a>
                        <form action="{{ route('channels.destroy', $channel->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 m-0 action-icon" onclick="return confirm('Es-tu sûr de vouloir supprimer ce channel ?')"><i class="material-icons">delete</i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $channels->links() }}
    </div>
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
    .table tbody td a.channel-link {
        color: inherit;
        text-decoration: none;
    }
    .table tbody td a.channel-link:hover {
        text-decoration: underline;
    }
    .table tbody td .thumbnail-image {
        height: 40px;
        width: 40px;
        object-fit: cover;
    }
    .table tbody td .logo-image {
        height: 40px;
        width: 40px;
        object-fit: contain;
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

