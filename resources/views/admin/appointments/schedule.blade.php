@extends('layouts.admin')

@section('title', 'Planning des Rendez-vous - Admin')
@section('page-title', 'Planning Global')
@section('page-subtitle', 'Vue calendaire de tous les rendez-vous')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Rendez-vous à venir</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Service</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                <td>{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</td>
                                <td>{{ $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A' }}</td>
                                <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'info') }}-soft">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucun rendez-vous à venir</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
