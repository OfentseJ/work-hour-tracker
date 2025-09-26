@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard</h1>
    <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3>{{ $totalEmployees }}</h3>
                <p class="mb-0">Total Employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card secondary">
            <div class="card-body text-center">
                <i class="fas fa-user-check fa-2x mb-3"></i>
                <h3>{{ $activeEmployees }}</h3>
                <p class="mb-0">Active Employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                <h3>{{ $todayAttendance }}</h3>
                <p class="mb-0">Today's Attendance</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-clock"></i> Quick Check-in/out</h5>
            </div>
            <div class="card-body">
                <form id="attendanceForm">
                    @csrf
                    <div class="mb-3">
                        <label for="employee_select" class="form-label">Select Employee</label>
                        <select class="form-select" id="employee_select" required>
                            <option value="">Choose employee...</option>
                            @foreach(App\Models\Employee::where('status', 'active')->get() as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="button" class="btn btn-success" onclick="checkIn()">
                            <i class="fas fa-sign-in-alt"></i> Check In
                        </button>
                        <button type="button" class="btn btn-warning" onclick="checkOut()">
                            <i class="fas fa-sign-out-alt"></i> Check Out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add New Employee
                    </a>
                    <a href="{{ route('admin.attendance') }}" class="btn btn-info">
                        <i class="fas fa-list"></i> View All Attendance
                    </a>
                    <a href="{{ route('admin.weekly-report') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> Weekly Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-history"></i> Today's Recent Activity</h5>
    </div>
    <div class="card-body">
        @if($recentAttendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttendances as $attendance)
                        <tr>
                            <td>
                                <strong>{{ $attendance->employee->name }}</strong><br>
                                <small class="text-muted">{{ $attendance->employee->employee_id }}</small>
                            </td>
                            <td>
                                @if($attendance->check_in)
                                    <span class="badge bg-success">{{ $attendance->check_in->format('H:i A') }}</span>
                                @else
                                    <span class="badge bg-secondary">Not checked in</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out)
                                    <span class="badge bg-warning">{{ $attendance->check_out->format('H:i A') }}</span>
                                @else
                                    <span class="badge bg-secondary">Not checked out</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->hours_worked)
                                    {{ number_format($attendance->hours_worked, 2) }} hrs
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_in && !$attendance->check_out)
                                    <span class="badge bg-info">Working</span>
                                @elseif($attendance->check_in && $attendance->check_out)
                                    <span class="badge bg-success">Complete</span>
                                @else
                                    <span class="badge bg-secondary">Absent</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">No attendance records for today yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function checkIn() {
    const employeeId = document.getElementById('employee_select').value;
    if (!employeeId) {
        alert('Please select an employee first!');
        return;
    }
    
    if (confirm('Confirm check-in for this employee?')) {
        submitAttendance('{{ route("admin.checkin") }}', employeeId);
    }
}

function checkOut() {
    const employeeId = document.getElementById('employee_select').value;
    if (!employeeId) {
        alert('Please select an employee first!');
        return;
    }
    
    if (confirm('Confirm check-out for this employee?')) {
        submitAttendance('{{ route("admin.checkout") }}', employeeId);
    }
}

function submitAttendance(url, employeeId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('input[name="_token"]').value;
    
    const employeeInput = document.createElement('input');
    employeeInput.type = 'hidden';
    employeeInput.name = 'employee_id';
    employeeInput.value = employeeId;
    
    form.appendChild(csrfToken);
    form.appendChild(employeeInput);
    document.body.appendChild(form);
    form.submit();
}

// Form validation
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const select = document.getElementById('employee_select');
    if (!select.value) {
        alert('Please select an employee!');
        select.focus();
    }
});
</script>
@endpush