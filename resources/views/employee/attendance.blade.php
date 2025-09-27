@extends('employee.layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Welcome, {{ $employee->name }}!</h1>
        <p class="text-muted mb-0">{{ now()->format('l, F j, Y') }}</p>
    </div>
    <div class="text-end">
        <small class="text-muted">Employee ID: {{ $employee->employee_id }}</small><br>
        <span class="badge bg-info">{{ $employee->department ?? 'No Department' }}</span>
    </div>
</div>

<!-- Today's Status -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title mb-2">
                            <i class="fas fa-clock me-2"></i>Today's Status
                        </h5>
                        @if($todayAttendance)
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1">
                                        <strong>Check In:</strong> 
                                        @if($todayAttendance->check_in)
                                            {{ $todayAttendance->check_in->format('H:i A') }}
                                            <i class="fas fa-check-circle text-success ms-1"></i>
                                        @else
                                            <span class="text-warning">Not checked in</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1">
                                        <strong>Check Out:</strong> 
                                        @if($todayAttendance->check_out)
                                            {{ $todayAttendance->check_out->format('H:i A') }}
                                            <i class="fas fa-check-circle text-success ms-1"></i>
                                        @else
                                            <span class="text-warning">Not checked out</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($todayAttendance->hours_worked)
                                <p class="mb-0">
                                    <strong>Hours Worked Today:</strong> 
                                    <span class="badge bg-light text-dark">{{ number_format($todayAttendance->hours_worked, 2) }} hours</span>
                                </p>
                            @endif
                        @else
                            <p class="mb-0">No attendance recorded for today.</p>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-grid gap-2">
                            @if(!$todayAttendance || !$todayAttendance->check_in)
                                <form method="POST" action="{{ route('employee.checkin') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-lg" onclick="return confirm('Are you sure you want to check in?')">
                                        <i class="fas fa-sign-in-alt me-2"></i>Check In
                                    </button>
                                </form>
                            @elseif(!$todayAttendance->check_out)
                                <form method="POST" action="{{ route('employee.checkout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('Are you sure you want to check out?')">
                                        <i class="fas fa-sign-out-alt me-2"></i>Check Out
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Day Complete!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <h3>{{ number_format($totalWeeklyHours, 1) }}</h3>
                <p class="mb-0">Hours This Week</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card secondary">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                <h3>{{ $daysWorkedThisWeek }}</h3>
                <p class="mb-0">Days Worked</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card warning">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h3>{{ $daysWorkedThisWeek > 0 ? number_format($totalWeeklyHours / $daysWorkedThisWeek, 1) : '0' }}</h3>
                <p class="mb-0">Avg Hours/Day</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-history me-2"></i>Recent Attendance History</h5>
    </div>
    <div class="card-body">
        @if($recentAttendance->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttendance as $attendance)
                        <tr>
                            <td>
                                <strong>{{ $attendance->date->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                            </td>
                            <td>
                                @if($attendance->check_in)
                                    <span class="badge bg-success">{{ $attendance->check_in->format('H:i A') }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out)
                                    <span class="badge bg-warning">{{ $attendance->check_out->format('H:i A') }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->hours_worked)
                                    {{ number_format($attendance->hours_worked, 2) }}h
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_in && $attendance->check_out)
                                    <span class="badge bg-success">Complete</span>
                                @elseif($attendance->check_in)
                                    <span class="badge bg-info">In Progress</span>
                                @else
                                    <span class="badge bg-secondary">Absent</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('employee.attendance') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>View Full History
                </a>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">No attendance records found.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh page every 5 minutes to keep status updated
setInterval(function() {
    location.reload();
}, 300000);

// Current time display
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.title = 'Employee Portal - ' + timeString;
}
setInterval(updateTime, 1000);
</script>
@endpush