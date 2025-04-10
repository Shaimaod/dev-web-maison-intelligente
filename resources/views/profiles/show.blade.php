@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profil de {{ $user->name }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary mb-3 mx-auto d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->username }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            @if($user->bio)
                                <p><strong>Bio:</strong> {{ $user->bio }}</p>
                            @endif
                            <p class="text-muted">
                                Membre depuis {{ $user->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    @if(auth()->id() === $user->id)
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                Modifier mon profil
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 