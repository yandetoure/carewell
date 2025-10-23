@extends('layouts.secretary')

@section('title', 'Liste d\'Attente - Secrétariat')
@section('page-title', 'Liste d\'Attente')
@section('page-subtitle', 'Gérer la liste d\'attente des patients')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Liste d'Attente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-clock fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Liste d'attente</h5>
                        <p class="text-muted">Cette page sera développée prochainement.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
