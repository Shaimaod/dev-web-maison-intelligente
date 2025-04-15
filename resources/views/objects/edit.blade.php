@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'objet</h1>
    <form action="{{ route('object.update', $object->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $object->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ $object->description }}</textarea>
        </div>

        <!-- Ajoutez d'autres champs nécessaires ici -->

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
