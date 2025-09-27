<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Hours Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .cta-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            margin: 50px 0;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 mb-4">
                <i class="fas fa-clock fa-lg me-3"></i>
                Work Hours Tracker
            </h1>
            <p class="lead mb-5">Streamline your workforce management with our comprehensive time tracking solution</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="cta-section text-center">
                        <h3 class="mb-4">Get Started Today</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.login') }}" class="btn btn-light btn-lg w-100">
                                    <i class="fas fa-user-shield me-2"></i>
                                    Admin Login
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('employee.login') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-user me-2"></i>
                                    Employee Login
                                </a>
                            </div>
                        </div>
                        <p class="text-white-50 mt-3">
                            New to the system? Contact your administrator for account setup.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container pb-5">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Employee Management</h5>
                        <p class="card-text">Secure registration and comprehensive employee profile management</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Time Tracking</h5>
                        <p class="card-text">Real-time check-in/check-out with automatic hour calculations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Detailed Reports</h5>
                        <p class="card-text">Comprehensive analytics and interactive weekly summaries</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Secure Access</h5>
                        <p class="card-text">Multi-level authentication ensuring data security and privacy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>