@extends('layouts.nurse')

@section('title', 'Profile - CareWell')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage Your Personal Information')
@section('user-role', 'Nurse')

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

    <!-- Profile Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPatients }}</h4>
                            <p class="text-muted mb-0">Patients Cared For</p>
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
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayAppointments }}</h4>
                            <p class="text-muted mb-0">Today's Appointments</p>
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
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $prescriptionsGiven }}</h4>
                            <p class="text-muted mb-0">Prescriptions Given</p>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $experienceYears }}</h4>
                            <p class="text-muted mb-0">Years Experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $nurse->first_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $nurse->last_name }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $nurse->email }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $nurse->phone_number }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="day_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control" id="day_of_birth" name="day_of_birth" value="{{ $nurse->day_of_birth }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adress">Address</label>
                                    <input type="text" class="form-control" id="adress" name="adress" value="{{ $nurse->adress }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="identification_number">Identification Number</label>
                                    <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{ $nurse->identification_number }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="photo">Profile Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bio">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ $nurse->bio ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <!-- Profile Photo -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-photo mb-3">
                        @if($nurse->photo)
                            <img src="{{ asset('storage/' . $nurse->photo) }}" alt="Profile Photo" class="rounded-circle" width="150" height="150">
                        @else
                            <i class="fas fa-user-circle fa-5x text-muted"></i>
                        @endif
                    </div>
                    <h5 class="card-title">{{ $nurse->first_name }} {{ $nurse->last_name }}</h5>
                    <p class="text-muted">Registered Nurse</p>
                    <p class="text-muted">{{ $nurse->email }}</p>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Professional Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>License Number:</strong><br>
                        <span class="text-muted">{{ $nurse->license_number ?? 'Not specified' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Specialization:</strong><br>
                        <span class="text-muted">{{ $nurse->specialization ?? 'General Nursing' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Experience:</strong><br>
                        <span class="text-muted">{{ $experienceYears }} years</span>
                    </div>
                    <div class="mb-3">
                        <strong>Department:</strong><br>
                        <span class="text-muted">{{ $nurse->department ?? 'General Ward' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Patients This Month:</span>
                        <strong>{{ $monthlyPatients }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Prescriptions This Week:</span>
                        <strong>{{ $weeklyPrescriptions }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Vital Signs Today:</span>
                        <strong>{{ $todayVitalSigns }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Appointments Today:</span>
                        <strong>{{ $todayAppointments }}</strong>
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

.profile-photo img {
    object-fit: cover;
}

.card-header h5 {
    color: #495057;
}
</style>
@endpush
