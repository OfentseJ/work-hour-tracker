@extends('employee.layouts.app')

@section('title', 'My Attendance History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>My Attendance History</h1>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-success" onclick="filterPeriod('week')">This Week</button>
        <button type="button" class="btn btn-outline-success" onclick="filterPeriod('month')">This Month</button>
        <button type="button" class="btn btn-outline-success" onclick="filterPeriod('all')">All Time</button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                <h3>{{ $attendances->where('check_in')->count() }}</h3>
                <p class="mb-0">Total Days Present</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card secondary">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <h3>{{ number_format($attendances->where('hours_worked', '>', 0)->sum('hours_worked'), 1) }}</h3>
                <p class="mb-0">Total Hours</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h3>{{ $attendances->where('hours_worked', '>', 0)->count() > 0 ? number_format($attendances->where('hours_worked', '>', 0)->avg('hours_worked'), 1) : '0' }}</h3>
                <p class="mb-0">Avg Hours/Day</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
            <div class="card-body text-center">
                <i class="fas fa-percentage fa-2x mb-3"></i>
                <h3>{{ $attendances->count() > 0 ? round(($attendances->where('check_in')->count() / $attendances->count()) * 100) : 0 }}%</h3>
                <p class="mb-0">Attendance Rate</p>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-table me-2"></i>Detailed Attendance Records</h5>
    </div>
    <div class="card-body">
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Break Time</th>
                            <th>Hours Worked</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <strong>{{ $attendance->date->format('M d, Y') }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $attendance->date->isWeekend() ? 'bg-warning' : 'bg-info' }}">
                                    {{ $attendance->date->format('l') }}
                                </span>
                            </td>
                            <td>
                                @if($attendance->check_in)
                                    <span class="badge bg-success">
                                        {{ $attendance->check_in->format('H:i A') }}
                                    </span>
                                    @if($attendance->check_in->format('H:i') > '09:00')
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle" title="Late arrival"></i>
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Not checked in</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out)
                                    <span class="badge bg-warning text-dark">
                                        {{ $attendance->check_out->format('H:i A') }}
                                    </span>
                                @elseif($attendance->check_in)
                                    <span class="badge bg-info">Still working</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_in && $attendance->check_out && $attendance->hours_worked)
                                    @php
                                        $totalMinutes = $attendance->check_in->diffInMinutes($attendance->check_out);
                                        $workedMinutes = $attendance->hours_worked * 60;
                                        $breakMinutes = $totalMinutes - $workedMinutes;
                                    @endphp
                                    @if($breakMinutes > 0)
                                        <span class="text-muted">{{ round($breakMinutes) }}min</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->hours_worked)
                                    <span class="badge bg-primary">
                                        {{ number_format($attendance->hours_worked, 2) }}h
                                    </span>
                                    @if($attendance->hours_worked >= 8)
                                        <small class="text-success">
                                            <i class="fas fa-check-circle" title="Full day"></i>
                                        </small>
                                    @elseif($attendance->hours_worked < 4)
                                        <small class="text-warning">
                                            <i class="fas fa-clock" title="Half day"></i>
                                        </small>
                                    @endif
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
                                @if($attendance->notes)
                                    <span class="text-muted" title="{{ $attendance->notes }}">
                                        <i class="fas fa-sticky-note"></i>
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                <p class="text-muted">Your attendance history will appear here once you start checking in.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterPeriod(period) {
    // Implementation for filtering by period
    const url = new URL(window.location.href);
    url.searchParams.set('period', period);
    window.location.href = url.toString();
}

// Tooltip initialization
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush