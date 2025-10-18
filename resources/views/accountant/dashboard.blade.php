@extends('layouts.accountant')

@section('title', 'Accountant Dashboard - CareWell')
@section('page-title', 'Financial Dashboard')
@section('page-subtitle', 'Revenue Management and Financial Analytics')
@section('user-role', 'Accountant')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Financial Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format($totalRevenue) }} FCFA</h4>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format($monthlyRevenue) }} FCFA</h4>
                            <p class="text-muted mb-0">Monthly Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingPayments }}</h4>
                            <p class="text-muted mb-0">Pending Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $completedPayments }}</h4>
                            <p class="text-muted mb-0">Completed Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Revenue by Service
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Payment Methods
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Transactions
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($transaction->appointment_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $transaction->service->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-dollar-sign text-warning me-2"></i>
                                                    5,000 FCFA
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">Cash</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Recent Transactions</h5>
                            <p class="text-muted">No completed appointments found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('accountant.reports') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-chart-line me-2"></i>Generate Reports
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accountant.billing') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Manage Billing
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="fas fa-download me-2"></i>Export Data
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100">
                                <i class="fas fa-sync me-2"></i>Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.card-header h5 {
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Service Revenue Chart
const serviceRevenueCtx = document.getElementById('serviceRevenueChart').getContext('2d');
const serviceRevenueChart = new Chart(serviceRevenueCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($serviceStats as $service)
                '{{ $service->name }}',
            @endforeach
        ],
        datasets: [{
            label: 'Revenue (FCFA)',
            data: [
                @foreach($serviceStats as $service)
                    {{ $service->appointments_count * 5000 }},
                @endforeach
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Payment Methods Chart
const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
const paymentMethodsChart = new Chart(paymentMethodsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Cash', 'Card', 'Mobile Money'],
        datasets: [{
            data: [{{ $paymentMethods['cash'] }}, {{ $paymentMethods['card'] }}, {{ $paymentMethods['mobile_money'] }}],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 205, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 205, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush
