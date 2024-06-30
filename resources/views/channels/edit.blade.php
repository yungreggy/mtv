@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le channel</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('channels.update', $channel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom du channel</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $channel->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $channel->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image Miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">
            @if ($channel->thumbnail_image)
                <img src="{{ Storage::url($channel->thumbnail_image) }}" class="img-fluid mt-2" style="max-height: 150px;" alt="{{ $channel->name }}">
            @endif
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
            @if ($channel->logo)
                <img src="{{ Storage::url($channel->logo) }}" class="img-fluid mt-2" style="max-height: 150px;" alt="{{ $channel->name }}">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour le channel</button>
    </form>
</div>
@endsection
