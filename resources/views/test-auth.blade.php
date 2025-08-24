@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Test d'authentification</div>
                <div class="card-body">
                    @auth
                        <div class="alert alert-success">
                            <h5>✅ Vous êtes connecté !</h5>
                            <p><strong>Nom :</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p><strong>Email :</strong> {{ Auth::user()->email }}</p>
                            <p><strong>ID :</strong> {{ Auth::user()->id }}</p>
                            <p><strong>Date de connexion :</strong> {{ Auth::user()->created_at }}</p>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Test des routes :</h6>
                            <a href="{{ route('appointments.index') }}" class="btn btn-primary me-2">Test Rendez-vous</a>
                            <a href="{{ route('patient.dashboard') }}" class="btn btn-success me-2">Dashboard Patient</a>
                            <a href="{{ route('patient.medical-file') }}" class="btn btn-info me-2">Dossier Médical</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h5>❌ Vous n'êtes PAS connecté !</h5>
                            <p>Veuillez vous connecter pour accéder aux fonctionnalités.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
