@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="card-title">Éditer : {{ $director->name }}</h1>
            @include('partials.messages')  <!-- Feedback and error messages -->
        </div>
        <div class="card-body">
            <form action="{{ route('directors.update', $director->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $director->name }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('directors.index') }}" class="btn btn-secondary">Annuler</a>
            </form>

            <form action="{{ route('directors.destroy', $director->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce réalisateur ?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection

