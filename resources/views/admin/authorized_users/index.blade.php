@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Emails autorisés à s'inscrire</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulaire d'ajout -->
    <form method="POST" action="{{ route('authorized-users.store') }}" class="row g-3 mb-4">
        @csrf
        <div class="col-md-8">
            <input type="email" name="email" class="form-control" placeholder="ex: parent@example.com" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </div>
    </form>

    <!-- Tableau des emails -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Email</th>
                <th>Ajouté le</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authorizedUsers as $user)
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('authorized-users.destroy', $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet email ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">Aucun email autorisé.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $authorizedUsers->links() }}
</div>
@endsection
