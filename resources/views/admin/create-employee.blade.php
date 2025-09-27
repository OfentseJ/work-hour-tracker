
@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-plus"></i> Add New Employee</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.employees.store') }}" id="employeeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee ID *</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required>
                                <div class="form-text">Unique identifier for the employee</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <option value="HR" {{ old('department') === 'HR' ? 'selected' : '' }}>Human Resources</option>
                                    <option value="IT" {{ old('department') === 'IT' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Finance" {{ old('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Marketing" {{ old('department') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="Operations" {{ old('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form validation
document.getElementById('employeeForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long!');
        return false;
    }
    
    // Check for empty required fields
    const requiredFields = ['name', 'employee_id', 'email', 'password'];
    for (let field of requiredFields) {
        if (!document.getElementById(field).value.trim()) {
            e.preventDefault();
            alert('Please fill in all required fields!');
            document.getElementById(field).focus();
            return false;
        }
    }
});

// Generate employee ID suggestion
document.getElementById('name').addEventListener('input', function() {
    const name = this.value.trim();
    if (name && !document.getElementById('employee_id').value) {
        const initials = name.split(' ')
            .map(word => word.charAt(0).toUpperCase())
            .join('');
        const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        document.getElementById('employee_id').value = initials + randomNum;
    }
});
</script>
@endpush