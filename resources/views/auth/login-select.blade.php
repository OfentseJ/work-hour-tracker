<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Hours Tracker - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .login-option {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .login-option:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="text-primary mb-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </h2>
                            <h3 class="text-primary">WorkTracker</h3>
                            <p class="text-muted">Choose your login type</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                                    <div class="card login-option h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
                                            <h5 class="card-title">Administrator</h5>
                                            <p class="card-text text-muted">
                                                Manage employees and view reports
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('employee.login') }}" class="text-decoration-none">
                                    <div class="card login-option h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-user fa-3x text-success mb-3"></i>
                                            <h5 class="card-title">Employee</h5>
                                            <p class="card-text text-muted">
                                                Check in/out and view your attendance
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">
                                        <strong>Admin Demo:</strong><br>
                                        admin@worktracker.com<br>
                                        password
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">
                                        <strong>Employee Demo:</strong><br>
                                        john@company.com<br>
                                        password123
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>