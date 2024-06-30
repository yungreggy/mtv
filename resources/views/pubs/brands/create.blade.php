@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter une Nouvelle Marque/Magasin</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('brandsStores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="logo_image" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo_image" name="logo_image">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
