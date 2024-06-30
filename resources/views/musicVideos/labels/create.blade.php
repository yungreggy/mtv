@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter un label</h1>
    @include('partials.messages')
    <form action="{{ route('labels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom du label</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="logo_image" class="form-label">Image du logo</label>
            <input type="file" class="form-control" id="logo_image" name="logo_image" accept="image/jpeg, image/png, image/gif">
        </div>
        <div class="mb-3">
            <label for="website" class="form-label">Site web</label>
            <input type="url" class="form-control" id="website" name="website">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="foundation_year" class="form-label">Année de fondation</label>
            <input type="number" class="form-control" id="foundation_year" name="foundation_year">
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
@endsection
