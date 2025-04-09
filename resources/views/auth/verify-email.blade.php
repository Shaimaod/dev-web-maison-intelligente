@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2 class="mb-4">Vérifie ton adresse e-mail</h2>

    <p>
        Un e-mail de vérification a été envoyé à <strong>{{ Auth::user()->email }}</strong>.<br>
        Merci de vérifier ta boîte mail et de cliquer sur le lien pour activer ton compte.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mt-3">
            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
        </div>
    @endif

    <form class="mt-4" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Renvoyer l'e-mail de vérification</button>
    </form>

    <form class="mt-3" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-danger">Se déconnecter</button>
    </form>
</div>
@endsection
