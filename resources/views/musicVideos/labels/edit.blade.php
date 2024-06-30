{{-- resources/views/musicVideos/labels/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le Label</h1>
    @include('partials.messages')
    <form action="{{ route('labels.update', $label->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nom du Label</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $label->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="logo_image" class="form-label">Image du Logo</label>
            <input type="file" class="form-control" id="logo_image" name="logo_image">
            @if($label->logo_image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$label->logo_image) }}" alt="Logo actuel" width="100">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Site Web</label>
            <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $label->website) }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $label->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foundation_year" class="form-label">Année de Fondation</label>
            <input type="number" class="form-control" id="foundation_year" name="foundation_year" value="{{ old('foundation_year', $label->foundation_year) }}">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à Jour</button>
    </form>
</div>
@endsection
