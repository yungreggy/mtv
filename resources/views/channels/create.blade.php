@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter un nouveau channel</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('channels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom du channel</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image Miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter le channel</button>
    </form>
</div>
@endsection
