@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <h1>Liste des Réalisateurs</h1>
    @include('partials.messages')

    <!-- Bouton + Ajouter -->
    <a href="{{ route('directors.create') }}" class="btn btn-primary mb-3">+ Ajouter</a>

    <form action="{{ route('directors.index') }}" method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher par nom" value="{{ request('search') }}">
        <select name="sort_by" class="form-control mr-2">
            <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
            <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
            <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Date d'ajout (Asc)</option>
            <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Date d'ajout (Desc)</option>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    @if($directors->isEmpty())
        <p>Aucun réalisateur trouvé.</p>
    @else
        <ul class="list-group">
            @foreach($directors as $director)
                <li class="list-group-item">
                    <a href="{{ route('directors.show', $director->id) }}">{{ $director->name }}</a>
                    <span class="float-right">{{ $director->created_at->format('d M Y') }}</span>
                </li>
            @endforeach
        </ul>

        <br>
        {{ $directors->appends(request()->query())->links() }}
    @endif
</div>
@endsection


<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     const searchNameInput = document.getElementById('searchName');
    //     const filterGenreSelect = document.getElementById('filterGenre');
    //     const directorList = document.getElementById('directorList');
    //     const directors = @json($directors);

    //     function filterDirectors() {
    //         const searchName = searchNameInput.value.toLowerCase();
    //         const filterGenre = filterGenreSelect.value;

    //         const filteredDirectors = directors.filter(director => {
    //             return (
    //                 director.name.toLowerCase().includes(searchName) &&
    //                 (!filterGenre || director.genre.toLowerCase() === filterGenre)
    //             );
    //         });

    //         directorList.innerHTML = '';

    //         filteredDirectors.forEach(director => {
    //             const listItem = document.createElement('a');
    //             listItem.href = `/directors/${director.id}`;
    //             listItem.classList.add('list-group-item', 'list-group-item-action');
    //             listItem.textContent = director.name;
    //             directorList.appendChild(listItem);
    //         });
    //     }

    //     searchNameInput.addEventListener('input', filterDirectors);
    //     filterGenreSelect.addEventListener('change', filterDirectors);
    // });
</script> 
