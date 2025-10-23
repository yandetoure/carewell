@extends('layouts.secretary')

@section('title', 'Rappels - Secrétariat')
@section('page-title', 'Rappels')
@section('page-subtitle', 'Gérer les rappels de rendez-vous')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Rappels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Rappels de rendez-vous</h5>
                        <p class="text-muted">Cette page sera développée prochainement.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
