@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="card-title">Ajouter un nouveau Réalisateur</h1>
            @include('partials.messages')  <!-- Feedback and error messages -->
        </div>
        <div class="card-body">
            <form action="{{ route('directors.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
                <a href="{{ route('directors.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
