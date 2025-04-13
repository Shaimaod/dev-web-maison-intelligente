@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Rechercher des profils</div>

                <div class="card-body">
                    <form action="{{ route('profiles.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Rechercher un utilisateur..." value="{{ $query ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </div>
                    </form>

                    @if(isset($users) && $users->count() > 0)
                        <div class="list-group">
                            @foreach($users as $user)
                                <a href="{{ route('profiles.show', $user) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            @if($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $user->name }}">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $user->name }}</h5>
                                                <small>{{ $user->username }}</small>
                                            </div>
                                            <p class="mb-1">{{ $user->email }}</p>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-primary">{{ ucfirst($user->level) }}</span>
                                                <span class="badge bg-success">{{ $user->points }} points</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucun utilisateur trouvé.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 