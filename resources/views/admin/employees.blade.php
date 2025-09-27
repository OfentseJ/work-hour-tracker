@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Employee Management</h1>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add New Employee
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Total Attendance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->employee_id }}</strong></td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>
                                @if($employee->department)
                                    <span class="badge bg-info">{{ $employee->department }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>{{ $employee->attendances_count }}</td>
                            <td>
                                @if($employee->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewEmployee({{ $employee->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="editEmployee({{ $employee->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $employees->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4>No Employees Found</h4>
                <p class="text-muted">Start by adding your first employee to the system.</p>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Add Employee
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewEmployee(id) {
    // Implementation for viewing employee details
    alert('View employee functionality would be implemented here');
}

function editEmployee(id) {
    // Implementation for editing employee
    alert('Edit employee functionality would be implemented here');
}
</script>
@endpush
