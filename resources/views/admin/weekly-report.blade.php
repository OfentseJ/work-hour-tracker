@extends('layouts.app')

@section('title', 'Weekly Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Weekly Report</h1>
        <p class="text-muted mb-0">{{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }}</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary" onclick="exportReport()">
            <i class="fas fa-download"></i> Export
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="printReport()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <h3>{{ number_format($weeklyData->sum('total_hours'), 1) }}</h3>
                <p class="mb-0">Total Hours</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card secondary">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3>{{ $weeklyData->count() }}</h3>
                <p class="mb-0">Active Employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                <h3>{{ number_format($weeklyData->avg('days_worked'), 1) }}</h3>
                <p class="mb-0">Avg Days Worked</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h3>{{ number_format($weeklyData->avg('avg_hours'), 1) }}</h3>
                <p class="mb-0">Avg Hours/Day</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Section -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Hours Worked This Week</h5>
            </div>
            <div class="card-body">
                <canvas id="hoursChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-star"></i> Top Performers</h5>
            </div>
            <div class="card-body">
                @php
                $topPerformers = $weeklyData->sortByDesc('total_hours')->take(5);
                @endphp

                @foreach($topPerformers as $employee => $data)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $employee }}</strong><br>
                        <small class="text-muted">{{ $data['days_worked'] }} days</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">{{ number_format($data['total_hours'], 1) }}h</span>
                    </div>
                </div>
                @endforeach

                @if($topPerformers->isEmpty())
                <div class="text-center py-3">
                    <i class="fas fa-info-circle text-muted"></i>
                    <p class="text-muted mb-0">No data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-table"></i> Detailed Weekly Summary</h5>
    </div>
    <div class="card-body">
        @if($weeklyData->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Days Worked</th>
                        <th>Total Hours</th>
                        <th>Average Hours/Day</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeklyData->sortByDesc('total_hours') as $employee => $data)
                    <tr>
                        <td><strong>{{ $employee }}</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $data['days_worked'] }}/7</span>
                        </td>
                        <td>{{ number_format($data['total_hours'], 2) }} hours</td>
                        <td>{{ number_format($data['avg_hours'], 2) }} hours</td>
                        <td>
                            @if($data['total_hours'] >= 40)
                            <span class="badge bg-success">Excellent</span>
                            @elseif($data['total_hours'] >= 32)
                            <span class="badge bg-primary">Good</span>
                            @elseif($data['total_hours'] >= 24)
                            <span class="badge bg-warning">Fair</span>
                            @else
                            <span class="badge bg-danger">Needs Improvement</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <h4>No Data Available</h4>
            <p class="text-muted">No attendance data found for this week.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart.js implementation
    const ctx = document.getElementById('hoursChart').getContext('2d');
    const chartData = @json($chartData);

    const hoursChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Hours Worked',
                data: chartData.hours,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' hours';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Hours'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Employees'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });

    function exportReport() {
        alert('Export functionality would be implemented here (PDF/Excel export)');
    }

    function printReport() {
        window.print();
    }

    // Auto-refresh every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
</script>
@endpush