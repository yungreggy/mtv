@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-dark">Modifier le Bloc Publicitaire: {{ $blocPub->name }}</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('blocPubs.update', $blocPub->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $blocPub->name }}" required>
        </div>
        <div class="form-group">
            <label for="program_id">Programme</label>
            <select class="form-control" id="program_id" name="program_id" required>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ $program->id == $blocPub->program_id ? 'selected' : '' }}>{{ $program->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="include_intro">Inclure Intro MTV</label>
            <input type="checkbox" id="include_intro" name="include_intro" {{ $blocPub->include_intro ? 'checked' : '' }}>
        </div>
        <div class="form-group">
            <label for="include_outro">Inclure Outro MTV</label>
            <input type="checkbox" id="include_outro" name="include_outro" {{ $blocPub->include_outro ? 'checked' : '' }}>
        </div>

        <h3 class="mt-4 text-secondary">Publicités Associées</h3>
        <ul class="list-group mb-4" id="pubs-list">
            @foreach ($blocPub->pubs as $index => $pub)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <input type="hidden" name="pubs[{{ $index }}][id]" value="{{ $pub->id }}">
                    <input type="number" name="pubs[{{ $index }}][order]" value="{{ $pub->pivot->order }}" class="form-control" style="width: 60px;">
                    <span class="pub-name">{{ $pub->name }}</span>
                    <div>
                        <span class="badge">{{ $pub->duration }}</span>
                        <button type="button" class="btn btn-link text-danger btn-remove-pub" title="Supprimer">
                            <i class="material-icons">remove_circle</i>
                        </button>
                        <button type="button" the="btn btn-link text-primary btn-replace-pub" title="Remplacer">
                            <i class="material-icons">autorenew</i>
                        </button>
                        <button type="button" class="btn btn-link text-secondary btn-move-up" title="Monter">
                            <i class="material-icons">arrow_upward</i>
                        </button>
                        <button type="button" class="btn btn-link text-secondary btn-move-down" title="Descendre">
                            <i class="material-icons">arrow_downward</i>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="form-group">
            <label for="add_pub">Ajouter une publicité</label>
            <select class="form-control" id="add_pub">
                <option value="">Sélectionner une publicité</option>
                @foreach($pubs as $pub)
                    <option value="{{ $pub->id }}">{{ $pub->name }} ({{ $pub->duration }})</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary mt-2" id="btn-add-pub">Ajouter</button>
        </div>

        <button type="submit" class="btn btn-success mt-3">Enregistrer les modifications</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pubsList = document.getElementById('pubs-list');
        const addPubSelect = document.getElementById('add_pub');
        const addPubButton = document.getElementById('btn-add-pub');

        addPubButton.addEventListener('click', function() {
            const selectedPubId = addPubSelect.value;
            if (selectedPubId) {
                const selectedPubText = addPubSelect.options[addPubSelect.selectedIndex].text;
                const index = document.querySelectorAll('#pubs-list li').length;
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <input type="hidden" name="pubs[${index}][id]" value="${selectedPubId}">
                    <input type="number" name="pubs[${index}][order]" value="${index + 1}" class="form-control" style="width: 60px;">
                    <span class="pub-name">${selectedPubText}</span>
                    <div>
                        <button type="button" class="btn btn-link text-danger btn-remove-pub" title="Supprimer">
                            <i class="material-icons">remove_circle</i>
                        </button>
                        <button type="button" class="btn btn-link text-primary btn-replace-pub" title="Remplacer">
                            <i class="material-icons">autorenew</i>
                        </button>
                        <button type="button" class="btn btn-link text-secondary btn-move-up" title="Monter">
                            <i class="material-icons">arrow_upward</i>
                        </button>
                        <button type="button" class="btn btn-link text-secondary btn-move-down" title="Descendre">
                            <i class="material-icons">arrow_downward</i>
                        </button>
                    </div>
                `;
                pubsList.appendChild(listItem);
            }
        });

        pubsList.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-pub')) {
                e.target.closest('li').remove();
            } else if (e.target.closest('.btn-replace-pub')) {
                // Replace logic
                const currentItem = e.target.closest('li');
                const replacePubSelect = document.createElement('select');
                replacePubSelect.classList.add('form-control');
                replacePubSelect.innerHTML = `<option value="">Sélectionner une publicité</option>
                    @foreach($pubs as $pub)
                        <option value="{{ $pub->id }}">{{ $pub->name }} ({{ $pub->duration }})</option>
                    @endforeach`;
                replacePubSelect.addEventListener('change', function() {
                    const newPubId = replacePubSelect.value;
                    const newPubText = replacePubSelect.options[replacePubSelect.selectedIndex].text;
                    currentItem.querySelector('input[name="pubs[]"]').value = newPubId;
                    currentItem.querySelector('.pub-name').textContent = newPubText;
                    replacePubSelect.remove();
                });
                currentItem.querySelector('.pub-name').appendChild(replacePubSelect);
            } else if (e.target.closest('.btn-move-up')) {
                const currentItem = e.target.closest('li');
                const previousItem = currentItem.previousElementSibling;
                if (previousItem) {
                    pubsList.insertBefore(currentItem, previousItem);
                }
            } else if (e.target.closest('.btn-move-down')) {
                const currentItem = e.target.closest('li');
                const nextItem = currentItem.nextElementSibling;
                if (nextItem) {
                    pubsList.insertBefore(nextItem, currentItem);
                }
            }
        });
    });
</script>
@endsection

<!-- Styles -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons');

    body {
        background-color: #f4f4f4;
        font-family: 'Poppins', sans-serif;
        color: #4a4a4a;
    }

    .container {
        max-width: 800px;
        margin: 30px auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .details p {
        font-size: 1.1rem;
        color: #555;
    }

    .details p strong {
        color: #333;
    }

    .pub-link {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
    }

    .pub-link:hover {
        color: #555;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .list-group-item:hover {
        background-color: #f1f1f1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
        border-radius: 12px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 1rem;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    .text-dark {
        color: #333 !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #333;
        cursor: pointer;
        padding: 0;
        font-size: 1.5rem;
        margin-left: 10px;
        transition: color 0.3s;
    }

    .btn-icon:hover {
        color: #555;
    }

    .btn-delete {
        color: #e3342f;
    }

    .btn-delete:hover {
        color: #cc1f1a;
    }
</style>
