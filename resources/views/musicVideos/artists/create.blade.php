@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter un nouvel artiste</h1>
    @include('partials.messages')
    <form action="{{ route('artists.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom de l'artiste</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image Miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg, image/png, image/gif">
        </div>

        <div class="mb-3">
            <label for="biography" class="form-label">Biographie</label>
            <textarea class="form-control" id="biography" name="biography" rows="3" maxlength="1000" placeholder="Entrez la biographie ici (facultatif)"></textarea>
        </div>
        <div class="mb-3">
            <label for="main_genre" class="form-label">Genre principal</label>
            <select class="form-control" id="main_genre" name="main_genre" required>
                <option value="">--Sélectionnez un genre--</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->name }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="website" class="form-label">Site Web</label>
            <input type="url" class="form-control" id="website" name="website">
        </div>

        <div class="mb-3">
            <label for="career_start_year" class="form-label">Active depuis</label>
            <input type="number" class="form-control" id="career_start_year" name="career_start_year">
        </div>

        <div class="mb-3">
            <label for="country_of_origin" class="form-label">Pays d'origine</label>
            <select class="form-control" id="country_of_origin" name="country_of_origin" required>
                <option value="N/A" selected>N/A</option>
                <!-- options -->
                <option value="USA">USA</option>
                <option value="Argentina">Argentina</option>
                <option value="Australia">Australia</option>
                <option value="Belgium">Belgium</option>
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="China">China</option>
                <option value="Denmark">Denmark</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="Greece">Greece</option>
                <option value="India">India</option>
                <option value="Ireland">Ireland</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Japan">Japan</option>
                <option value="Mexico">Mexico</option>
                <option value="Netherlands">Netherlands</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Norway">Norway</option>
                <option value="Portugal">Portugal</option>
                <option value="Russia">Russia</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Korea">South Korea</option>
                <option value="Spain">Spain</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Turkey">Turkey</option>
                <option value="UK">UK</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
    </form>
</div>
@endsection

<!-- Ajouter les liens vers les icônes FontAwesome dans le head -->
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

<!-- Ajouter les styles personnalisés -->
@section('styles')
<style>
    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 0.25rem;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        border-color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded {
        border-radius: 0.25rem !important;
    }

    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.075) !important;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }

    .container {
        max-width: 800px;
    }

    .mb-3 label {
        margin-bottom: .5rem;
    }

    .mb-3 input, .mb-3 select, .mb-3 textarea {
        margin-bottom: 1rem;
    }
</style>
@endsection
