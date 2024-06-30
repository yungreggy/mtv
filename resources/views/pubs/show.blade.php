@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('pubs.index') }}" class="btn btn-outline-secondary mb-4">Retour à la liste</a>
    
    <h1 class="mb-5 text-center font-weight-bold">{{ $pub->name }}</h1>
    <div class="row mb-5">
        <div class="col-md-4 d-flex justify-content-center">
            @if ($pub->thumbnail_image)
                <img src="{{ Storage::url($pub->thumbnail_image) }}" class="img-fluid rounded shadow" alt="{{ $pub->name }}" style="width: 100%; max-width: 300px;">
            @else
                <img src="https://via.placeholder.com/300" class="img-fluid rounded shadow" alt="{{ $pub->name }}" style="width: 100%; max-width: 300px;">
            @endif
        </div>
        <div class="col-md-8">
            <div class="bg-light p-4 rounded shadow-sm">
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Description</h3>
                    <p class="text-muted">{{ $pub->description }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Année</h3>
                    <p class="text-muted">{{ $pub->year }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Type de Publicité</h3>
                    <p class="text-muted">{{ $pub->ad_type }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Démographique Ciblée</h3>
                    <p class="text-muted">{{ $pub->target_demographic }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Fréquence</h3>
                    <p class="text-muted">{{ $pub->frequency }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Marque/Magasin</h3>
                    <p>
                        @if ($pub->brandStore)
                            <a href="{{ route('brandsStores.show', $pub->brandStore->id) }}" class="text-decoration-none text-dark">{{ $pub->brandStore->name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <h3 class="h5 font-weight-bold">Durée</h3>
                    <p class="text-muted">{{ gmdate("H:i:s", strtotime($pub->duration) - strtotime('TODAY')) }}</p>
                </div>

                @if ($pub->file_path)
                    <div class="mb-4">
                        <h3 class="h5 font-weight-bold">Fichier</h3>
                        <video controls class="w-100 rounded shadow-sm mt-2">
                            <source src="{{ Storage::url($pub->file_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la balise vidéo.
                        </video>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="mb-4">
    <h3 class="h5 font-weight-bold">Tags</h3>
    <div id="tagsList">
        @foreach($pub->tags as $tag)
            <a href="{{ route('tags.show', $tag->id) }}" class="badge badge-secondary" style="margin-right: 2px;">{{ $tag->name }}</a>
        @endforeach
    </div>
    <button type="button" class="btn" id="addTagButton">+</button>

    <div id="addTagDiv" style="display: none;">
        <form id="addTagForm">
            @csrf
            <input type="text" id="tagName" class="form-control" placeholder="Entrez un tag">
            <br>
            <button type="submit" class="btn btn-secondary">Ajouter</button>
        </form>
    </div>
</div>


    <div class="d-flex justify-content-between">
        <a href="{{ route('pubs.edit', $pub->id) }}" class="btn btn-outline-dark">Modifier la publicité</a>
        <form action="{{ route('pubs.destroy', $pub->id) }}" method="POST" class="d-inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Es-tu sûr de vouloir supprimer cette publicité ?')">Supprimer la publicité</button>
        </form>
    </div>

    <a href="{{ route('pubs.index') }}" class="btn btn-outline-secondary mt-4">Retour à la liste</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addTagButton').addEventListener('click', function() {
        document.getElementById('addTagDiv').style.display = 'block';
    });

    document.getElementById('addTagForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let tagName = document.getElementById('tagName').value.trim();

        if (!tagName) {
            alert('Le champ du tag ne peut pas être vide.');
            return;
        }

        fetch('{{ route("pubs.addTag", $pub->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: tagName })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de l\'ajout du tag');
            }
            return response.json();
        })
        .then(data => {
            let tagBadge = document.createElement('span');
            tagBadge.className = 'badge badge-secondary';
            tagBadge.textContent = data.tag.name;

            document.getElementById('tagsList').appendChild(tagBadge);

            document.getElementById('addTagDiv').style.display = 'none';
            document.getElementById('addTagForm').reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la communication avec le serveur: ' + error.message);
        });
    });
});
</script>
@endsection
