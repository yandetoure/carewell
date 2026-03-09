@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h4 mb-3">Tableau de bord statistique</h2>
            </div>

            <!-- Cartes de résumé -->
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase mb-2" style="font-size: 0.8rem; opacity: 0.8;">Total
                            Rendez-vous</h6>
                        <h2 class="mb-0">{{ number_format($totalAppointments) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase mb-2" style="font-size: 0.8rem; opacity: 0.8;">Revenus Totaux
                        </h6>
                        <h2 class="mb-0">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase mb-2" style="font-size: 0.8rem; opacity: 0.8;">Patients</h6>
                        <h2 class="mb-0">{{ number_format($totalPatients) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase mb-2" style="font-size: 0.8rem; opacity: 0.8;">Médecins</h6>
                        <h2 class="mb-0">{{ number_format($totalDoctors) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Évolution de l'activité -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Évolution de l'activité (12 derniers mois)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="position: relative; height: 350px;">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par statut -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statuts des Rendez-vous</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2" style="position: relative; height: 316px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Démographie des patients -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Démographie des Patients (Âge)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar" style="position: relative; height: 300px;">
                            <canvas id="demographicsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Services -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Services</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar" style="position: relative; height: 300px;">
                            <canvas id="servicesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configuration commune
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#858796';

            // 1. Graphique d'activité (Mixte: Ligne pour revenus, Barres pour RDV)
            const ctxActivity = document.getElementById("activityChart");
            new Chart(ctxActivity, {
                type: 'bar',
                data: {
                    labels: @json($activityLabels),
                    datasets: [{
                        label: "Revenus (FCFA)",
                        type: "line",
                        lineTension: 0.3,
                        backgroundColor: "rgba(28, 200, 138, 0.05)",
                        borderColor: "rgba(28, 200, 138, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(28, 200, 138, 1)",
                        pointBorderColor: "rgba(28, 200, 138, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                        pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: @json($revenueData),
                        yAxisID: 'y1',
                    }, {
                        label: "Rendez-vous",
                        type: "bar",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                        data: @json($appointmentsData),
                        yAxisID: 'y',
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { maxTicksLimit: 12 }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function (value) { return value; }
                            },
                            grid: { color: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                callback: function (value) { return value + ' FCFA'; }
                            }
                        },
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleMarginBottom: 10,
                            titleColor: '#6e707e',
                            titleFont: { size: 14 },
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                        }
                    }
                }
            });

            // 2. Graphique Statuts (Doughnut)
            const ctxStatus = document.getElementById("statusChart");
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        data: @json($statusData),
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e02d1b', '#60616f'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        }
                    },
                    cutout: '70%',
                },
            });

            // 3. Graphique Démographie (Pie)
            const ctxDemo = document.getElementById("demographicsChart");
            new Chart(ctxDemo, {
                type: 'pie',
                data: {
                    labels: @json($demographicsLabels),
                    datasets: [{
                        data: @json($demographicsData),
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    }
                },
            });

            // 4. Top Services (Barre horizontale)
            const ctxServices = document.getElementById("servicesChart");
            new Chart(ctxServices, {
                type: 'bar',
                data: {
                    labels: @json($servicesLabels),
                    datasets: [{
                        label: "Nombre de RDV",
                        data: @json($servicesData),
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            ticks: { beginAtZero: true }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                    }
                },
            });
        });
    </script>
@endsection