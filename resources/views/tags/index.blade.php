@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Tags</h1>
    @include('partials.messages') <!-- Inclusion des messages -->

    <!-- Barre de recherche et filtres -->
    <form method="GET" action="{{ route('tags.index') }}" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
        </div>
        <div class="form-group mr-2">
            <select name="sort_by" class="form-control">
                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Date d'ajout (croissant)</option>
                <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Date d'ajout (décroissant)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    <a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">Ajouter un Tag</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Date d'ajout</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    <td><a href="{{ route('tags.show', $tag->id) }}">{{ $tag->name }}</a></td>
                    <td>{{ $tag->created_at }}</td>
                    <td>
                        <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $tags->appends(request()->query())->links() }}
    </div>
</div>
@endsection

