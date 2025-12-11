<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #DAA520 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
        }
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 3rem;
            text-align: center;
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .debug-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: left;
            font-family: monospace;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="h2 mb-3">403 - Unauthorized Access</h1>
        <p class="text-muted mb-4">You don't have permission to access this resource.</p>
        
        @if(isset($exception) && $exception->getMessage())
            <div class="alert alert-warning">
                <strong>Error Details:</strong> {{ $exception->getMessage() }}
            </div>
        @endif
        
        @if(auth()->guard('admin')->check())
            <div class="alert alert-info">
                <div class="d-flex align-items-center mb-2">
                    <div class="me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B4513, #DAA520); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ auth()->guard('admin')->user()->name }}</strong>
                        <div class="small text-muted">{{ ucfirst(str_replace('_', ' ', auth()->guard('admin')->user()->role)) }}</div>
                    </div>
                </div>
                <p class="mb-0 small">
                    <i class="fas fa-info-circle me-1"></i>
                    You're logged in but don't have permission to access this resource. 
                    Contact your administrator if you believe this is an error.
                </p>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You are not currently logged in. Please log in to access admin features.
            </div>
        @endif
        
        <div class="mt-4">
            @if(auth()->guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                    <i class="fas fa-tachometer-alt"></i> Back to Dashboard
                </a>
                <a href="{{ route('admin.login', ['session_issue' => 1]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-sign-in-alt"></i> Re-login
                </a>
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            @endif
        </div>
        
        <div class="mt-3">
            <a href="{{ route('home') }}" class="text-muted">
                <i class="fas fa-home"></i> Back to Website
            </a>
        </div>
    </div>
</body>
</html>