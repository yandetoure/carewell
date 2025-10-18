@extends('layouts.dashboard')

@section('title', 'Accountant Dashboard - CareWell')
@section('page-title', 'Accountant Dashboard')
@section('page-subtitle', 'Financial Management and Billing')
@section('user-role', 'Accountant')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Dashboard</div>
        <div class="nav-item">
            <a href="{{ route('accountant.dashboard') }}" class="nav-link {{ request()->routeIs('accountant.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Overview</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('accountant.reports') }}" class="nav-link {{ request()->routeIs('accountant.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Financial Reports</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Financial Management</div>
        <div class="nav-item">
            <a href="{{ route('accountant.billing') }}" class="nav-link {{ request()->routeIs('accountant.billing*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Billing Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-credit-card"></i>
                <span>Payment Processing</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-receipt"></i>
                <span>Invoices</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Analytics</div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-chart-pie"></i>
                <span>Revenue Analytics</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-chart-bar"></i>
                <span>Service Performance</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-calendar-alt"></i>
                <span>Monthly Reports</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Settings</div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-user-edit"></i>
                <span>Profile</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>
@endsection
