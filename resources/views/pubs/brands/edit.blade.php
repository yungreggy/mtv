@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la Marque/Magasin</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('brandsStores.update', $brandStore->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $brandStore->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $brandStore->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="logo_image" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo_image" name="logo_image">
            <img src="{{ $brandStore->logo_image ? asset('storage/' . $brandStore->logo_image) : 'https://via.placeholder.com/150' }}" class="img-thumbnail mt-2" alt="Current Logo">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
