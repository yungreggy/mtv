@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold">Détails de la Marque/Magasin</h1>
        <p class="lead text-muted">Découvrez toutes les informations sur nos marques et magasins partenaires.</p>
    </div>
    <div class="d-flex flex-column align-items-center mb-5">
        <div class="card border-0 shadow-sm" style="width: 300px;">
            <img src="{{ $brandStore->logo_image ? asset('storage/' . $brandStore->logo_image) : 'https://via.placeholder.com/300' }}" class="card-img-top" alt="Logo de {{ $brandStore->name }}">
            <div class="card-body text-center">
                <h5 class="card-title font-weight-bold">{{ $brandStore->name }}</h5>
                <p class="card-text text-muted">{{ $brandStore->description }}</p>
            </div>
        </div>
    </div>

    <h2 class="mb-4 text-center text-uppercase font-weight-bold">Publicités Associées</h2>
    <div class="list-group">
        @foreach ($brandStore->pubs as $pub)
            <a href="{{ route('pubs.show', $pub->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $pub->name }}</h5>
                </div>
                <p class="mb-1 text-muted">{{ $pub->description }}</p>
                <small class="text-muted">Blocs de pubs associés :</small>
                <ul class="list-unstyled text-muted">
                    @foreach ($pub->blocPubs as $blocPub)
                        <li>{{ $blocPub->name }} - {{ $blocPub->description }}</li>
                    @endforeach
                </ul>
            </a>
        @endforeach
    </div>
</div>
@endsection

