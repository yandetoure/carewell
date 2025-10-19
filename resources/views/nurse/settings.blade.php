@extends('layouts.nurse')

@section('title', 'Paramètres - CareWell')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Gérer Vos Préférences et Paramètres')
@section('user-role', 'Infirmière')

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

    <div class="row">
        <!-- Notification Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Notification Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ $settings['email_notifications'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_notifications">
                                Email Notifications
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" value="1" {{ $settings['sms_notifications'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms_notifications">
                                SMS Notifications
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="appointment_reminders" name="appointment_reminders" value="1" {{ $settings['appointment_reminders'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="appointment_reminders">
                                Appointment Reminders
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="urgent_notifications" name="urgent_notifications" value="1" {{ $settings['urgent_notifications'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="urgent_notifications">
                                Urgent Notifications
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="medication_alerts" name="medication_alerts" value="1" {{ $settings['medication_alerts'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="medication_alerts">
                                Medication Alerts
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Notification Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Display Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-palette me-2"></i>Display Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="theme">Theme</label>
                            <select class="form-control" id="theme" name="theme">
                                <option value="light" {{ $settings['theme'] == 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ $settings['theme'] == 'dark' ? 'selected' : '' }}>Dark</option>
                                <option value="auto" {{ $settings['theme'] == 'auto' ? 'selected' : '' }}>Auto</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="language">Language</label>
                            <select class="form-control" id="language" name="language">
                                <option value="en" {{ $settings['language'] == 'en' ? 'selected' : '' }}>English</option>
                                <option value="fr" {{ $settings['language'] == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="ar" {{ $settings['language'] == 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="timezone">Timezone</label>
                            <select class="form-control" id="timezone" name="timezone">
                                <option value="Africa/Dakar" {{ $settings['timezone'] == 'Africa/Dakar' ? 'selected' : '' }}>Africa/Dakar</option>
                                <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Europe/Paris" {{ $settings['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date_format">Date Format</label>
                            <select class="form-control" id="date_format" name="date_format">
                                <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Display Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Security Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Security Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" value="1" {{ $settings['two_factor_auth'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="two_factor_auth">
                                Two-Factor Authentication
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="session_timeout" name="session_timeout" value="1" {{ $settings['session_timeout'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="session_timeout">
                                Auto Session Timeout
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="session_duration">Session Duration (minutes)</label>
                            <input type="number" class="form-control" id="session_duration" name="session_duration" value="{{ $settings['session_duration'] }}" min="15" max="480">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="login_notifications" name="login_notifications" value="1" {{ $settings['login_notifications'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="login_notifications">
                                Login Notifications
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Security Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Work Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>Work Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nurse.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="working_hours_start">Working Hours Start</label>
                            <input type="time" class="form-control" id="working_hours_start" name="working_hours_start" value="{{ $settings['working_hours_start'] }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="working_hours_end">Working Hours End</label>
                            <input type="time" class="form-control" id="working_hours_end" name="working_hours_end" value="{{ $settings['working_hours_end'] }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="appointment_duration">Default Appointment Duration (minutes)</label>
                            <input type="number" class="form-control" id="appointment_duration" name="appointment_duration" value="{{ $settings['appointment_duration'] }}" min="15" max="120">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="weekend_appointments" name="weekend_appointments" value="1" {{ $settings['weekend_appointments'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="weekend_appointments">
                                Allow Weekend Appointments
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="emergency_notifications" name="emergency_notifications" value="1" {{ $settings['emergency_notifications'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="emergency_notifications">
                                Emergency Notifications
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Work Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Management -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>Data Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                <h6>Export Data</h6>
                                <p class="text-muted">Download your data</p>
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i>Export
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                                <h6>Delete Account</h6>
                                <p class="text-muted">Permanently delete your account</p>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    <i class="fas fa-trash me-2"></i>Delete Account
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-sync fa-3x text-warning mb-3"></i>
                                <h6>Reset Settings</h6>
                                <p class="text-muted">Reset to default settings</p>
                                <button type="button" class="btn btn-outline-warning">
                                    <i class="fas fa-sync me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> All your data will be permanently deleted.</p>
                <div class="form-group">
                    <label for="confirmDelete">Type "DELETE" to confirm:</label>
                    <input type="text" class="form-control" id="confirmDelete" placeholder="DELETE">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete Account</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header h5 {
    color: #495057;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush

@push('scripts')
<script>
// Enable delete button only when "DELETE" is typed
document.getElementById('confirmDelete').addEventListener('input', function() {
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (this.value === 'DELETE') {
        confirmBtn.disabled = false;
    } else {
        confirmBtn.disabled = true;
    }
});

// Handle delete account
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
        // Here you would typically make an AJAX request to delete the account
        alert('Account deletion functionality would be implemented here.');
    }
});
</script>
@endpush
