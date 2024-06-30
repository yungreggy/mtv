@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un Tag</h1>
    @include('partials.messages') <!-- Inclusion des messages -->
    <form action="{{ route('tags.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom du Tag</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
