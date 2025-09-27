@extends('layouts.app')

@section('title', 'Attendance Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Attendance Records</h1>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary" onclick="filterToday()">Today</button>
        <button type="button" class="btn btn-outline-primary" onclick="filterWeek()">This Week</button>
        <button type="button" class="btn btn-outline-primary" onclick="filterMonth()">This Month</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Hours Worked</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <strong>{{ $attendance->date->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                            </td>
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
                                @elseif($attendance->check_in)
                                    <button class="btn btn-sm btn-outline-warning" onclick="quickCheckOut({{ $attendance->employee->id }})">
                                        Check Out
                                    </button>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->hours_worked)
                                    <span class="badge bg-info">{{ number_format($attendance->hours_worked, 2) }} hrs</span>
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
                                    <span class="badge bg-danger">Absent</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if(!$attendance->check_in)
                                        <button class="btn btn-outline-success" onclick="quickCheckIn({{ $attendance->employee->id }})">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-primary" onclick="viewDetails({{ $attendance->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4>No Attendance Records</h4>
                <p class="text-muted">No attendance records found for the selected period.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function quickCheckIn(employeeId) {
    if (confirm('Confirm check-in for this employee?')) {
        submitAttendance('{{ route("admin.checkin") }}', employeeId);
    }
}

function quickCheckOut(employeeId) {
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
    csrfToken.value = '{{ csrf_token() }}';
    
    const employeeInput = document.createElement('input');
    employeeInput.type = 'hidden';
    employeeInput.name = 'employee_id';
    employeeInput.value = employeeId;
    
    form.appendChild(csrfToken);
    form.appendChild(employeeInput);
    document.body.appendChild(form);
    form.submit();
}

function viewDetails(attendanceId) {
    alert('View attendance details functionality would be implemented here');
}

function filterToday() {
    window.location.href = '{{ route("admin.attendance") }}?filter=today';
}

function filterWeek() {
    window.location.href = '{{ route("admin.attendance") }}?filter=week';
}

function filterMonth() {
    window.location.href = '{{ route("admin.attendance") }}?filter=month';
}
</script>
@endpush