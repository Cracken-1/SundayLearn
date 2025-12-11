<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SundayLearn</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #DAA520;
            --accent-color: #CD853F;
            --text-dark: #2C1810;
            --text-light: #6B4423;
            --background-light: #FDF6E3;
            --background-white: #FFFFFF;
            --border-color: #E6D5B7;
            --font-heading: 'Crimson Text', serif;
            --font-body: 'Open Sans', sans-serif;
            --shadow-light: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-medium: 0 4px 8px rgba(0,0,0,0.15);
            --shadow-heavy: 0 8px 16px rgba(0,0,0,0.2);
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-body);
            color: var(--text-dark);
        }
        
        .login-card {
            background: var(--background-white);
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            border: 2px solid var(--border-color);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="cross" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M10,2 L10,18 M2,10 L18,10" stroke="rgba(255,255,255,0.1)" stroke-width="1" fill="none"/></pattern></defs><rect width="100" height="100" fill="url(%23cross)"/></svg>');
            opacity: 0.3;
        }
        
        .login-header h3 {
            font-family: var(--font-heading);
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        
        .login-header p {
            position: relative;
            z-index: 1;
        }
        
        .login-body {
            padding: 2.5rem 2rem;
            background: var(--background-light);
        }
        
        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--background-white);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(218, 165, 32, 0.25);
            background: var(--background-white);
        }
        
        .btn-admin {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            padding: 14px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-medium);
            font-family: var(--font-body);
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
            color: white;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        }
        
        .btn-admin:active {
            transform: translateY(0);
        }
        
        .alert-info {
            background: rgba(218, 165, 32, 0.1);
            border: 1px solid var(--secondary-color);
            color: var(--text-dark);
            border-radius: 10px;
        }
        
        .text-muted {
            color: var(--text-light) !important;
        }
        
        .login-footer-link {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-footer-link:hover {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .admin-icon {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 1rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="admin-icon">
                <i class="fas fa-book-open fa-2x"></i>
            </div>
            <h3 class="mb-2">Admin Access</h3>
            <p class="mb-0 opacity-90">SundayLearn Administration Portal</p>
        </div>
        
        <div class="login-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Login Failed:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @php
                $isAuthenticated = auth()->guard('admin')->check();
                $hasErrors = $errors->any() || session('error');
                $showLoginForm = !$isAuthenticated || $hasErrors;
            @endphp

            @if($isAuthenticated && !$hasErrors)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Already Logged In:</strong> You are currently logged in as {{ auth()->guard('admin')->user()->name }} 
                    ({{ ucfirst(str_replace('_', ' ', auth()->guard('admin')->user()->role)) }}).
                    <div class="mt-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-tachometer-alt me-1"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </div>
                </div>
            @endif

            @if($showLoginForm)
                <div class="alert alert-info">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Admin Access:</strong> Enter your credentials to access the administration panel.
                    <div class="mt-2 small">
                        <strong>Available Roles:</strong> Super Administrator, Administrator, Editor
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-user me-2"></i>Email Address
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               placeholder="Enter your email address" 
                               value="{{ old('email') }}"
                               required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" 
                               placeholder="••••••••" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-admin">
                            <i class="fas fa-sign-in-alt me-2"></i>Access Admin Dashboard
                        </button>
                    </div>
                </form>
            @endif
            
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="login-footer-link">
                    <i class="fas fa-arrow-left me-2"></i>Back to Website
                </a>
            </div>
            

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>