@extends('employee.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-circle me-2"></i>My Profile</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-user-circle fa-5x text-success mb-3"></i>
                            <h5>{{ $employee->name }}</h5>
                            <p class="text-muted mb-0">{{ $employee->employee_id }}</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Personal Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Full Name:</strong></td>
                                <td>{{ $employee->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Employee ID:</strong></td>
                                <td><span class="badge bg-primary">{{ $employee->employee_id }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Email Address:</strong></td>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td>
                                    @if($employee->department)
                                        <span class="badge bg-info">{{ $employee->department }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Member Since:</strong></td>
                                <td>{{ $employee->created_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Quick Stats</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                        <h6>{{ $employee->attendances()->count() }}</h6>
                                        <small class="text-muted">Total Records</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                        <h6>{{ number_format($employee->attendances()->whereNotNull('hours_worked')->sum('hours_worked'), 1) }}</h6>
                                        <small class="text-muted">Total Hours</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                                        <h6>{{ $employee->attendances()->whereNotNull('hours_worked')->count() > 0 ? number_format($employee->attendances()->whereNotNull('hours_worked')->avg('hours_worked'), 1) : '0' }}</h6>
                                        <small class="text-muted">Avg Hours/Day</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-star fa-2x text-info mb-2"></i>
                                        <h6>
                                            @php
                                                $totalDays = $employee->attendances()->count();
                                                $presentDays = $employee->attendances()->whereNotNull('check_in')->count();
                                                $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;
                                            @endphp
                                            {{ $attendanceRate }}%
                                        </h6>
                                        <small class="text-muted">Attendance Rate</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Account Security</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Password Management:</strong> To change your password or update personal information, please contact your system administrator.
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-shield-alt me-2"></i>
                            <strong>Security Notice:</strong> Always log out when using shared computers and never share your login credentials.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection