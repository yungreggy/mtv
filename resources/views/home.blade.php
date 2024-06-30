@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4 text-center">Bienvenue sur MTV</h1>
            <p class="lead text-center">Ceci est la page d'accueil de ton application de clips musicaux.</p>
        </div>
    </div>
    <div class="row mt-4">
        @foreach($albums as $album)
        <div class="col-md-4">
            <div class="card">
                <img src="{{ Storage::url($album->thumbnail_image) }}" class="card-img-top" alt="{{ $album->title }}" loading="lazy" title="{{ $album->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $album->title }}</h5>
                    <p class="" style="color: #333333;">
                        <a href="{{ route('artists.show', $album->artist->id) }}" style="color: #333333;">{{ $album->artist->name }} </a>
                    </p>
                    <a href="{{ route('albums.show', $album->id) }}" class="btn btn-outline-dark">Explorer</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
@endsection



