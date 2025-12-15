<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - SundayLearn')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
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
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h4 {
            margin: 0;
            font-family: 'Crimson Text', serif;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.7;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .user-info {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-weight: 600;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .management-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .management-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            background: var(--secondary-color);
            color: white;
        }

        /* Fix for tab visibility issues */
        .nav-tabs .nav-link {
            color: var(--text-dark) !important;
            background-color: transparent;
            border: 1px solid transparent;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            padding: 0.75rem 1rem;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color) !important;
            border-bottom-color: var(--secondary-color);
            background-color: rgba(218, 165, 32, 0.1);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--background-white);
            border-color: var(--border-color) var(--border-color) var(--background-white);
            border-bottom-color: var(--secondary-color) !important;
            border-bottom-width: 3px !important;
        }

        .card-header-tabs .nav-link {
            color: var(--text-dark) !important;
            background-color: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .card-header-tabs .nav-link:hover {
            color: var(--primary-color) !important;
            border-bottom-color: var(--secondary-color);
            background-color: rgba(218, 165, 32, 0.1);
        }

        .card-header-tabs .nav-link.active {
            color: var(--primary-color) !important;
            background-color: transparent;
            border-bottom-color: var(--secondary-color) !important;
            border-bottom-width: 3px !important;
            font-weight: 600;
        }

        /* Ensure tab content is visible */
        .tab-content {
            background-color: var(--background-white);
            border-radius: 0 0 0.375rem 0.375rem;
        }

        /* Fix for settings tabs */
        #settingsTabs .nav-link {
            color: var(--text-dark) !important;
            background-color: transparent;
            border: 1px solid var(--border-color);
            border-bottom: none;
            margin-right: 0.25rem;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        #settingsTabs .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(218, 165, 32, 0.1);
        }

        #settingsTabs .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--background-white);
            border-color: var(--border-color);
            border-bottom-color: var(--background-white);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-book-open fa-2x mb-2"></i>
                <h4>SundayLearn</h4>
                <div class="text-center mt-2">
                    <span class="role-badge">{{ ucfirst(str_replace('_', ' ', auth()->guard('admin')->user()->role ?? 'Admin')) }}</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Overview
                    </a>
                </div>

                <!-- Content Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Content Management</div>
                    <a href="{{ route('admin.lessons.index') }}" class="nav-link {{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        Lessons
                    </a>
                    <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                        <i class="fas fa-blog"></i>
                        Blog Posts
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        Events
                    </a>
                    <a href="{{ route('admin.teaching-tips.index') }}" class="nav-link {{ request()->routeIs('admin.teaching-tips.*') ? 'active' : '' }}">
                        <i class="fas fa-lightbulb"></i>
                        Teaching Tips
                    </a>
                    <a href="{{ route('admin.resources.index') }}" class="nav-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        Resources
                    </a>
                </div>

                <!-- Analytics & Reports -->
                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </div>

                @if(auth()->guard('admin')->user()->canManageUsers())
                <!-- User Management -->
                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Admin Users
                    </a>
                    <a href="{{ route('admin.newsletters.index') }}" class="nav-link {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                        Newsletter Subscribers
                    </a>
                </div>
                @endif

                @if(auth()->guard('admin')->user()->canAccessIntegrations())
                <!-- Integrations -->
                <div class="nav-section">
                    <div class="nav-section-title">Integrations</div>
                    <a href="{{ route('admin.telegram-imports.index') }}" class="nav-link {{ request()->routeIs('admin.telegram-imports.*') ? 'active' : '' }}">
                        <i class="fab fa-telegram"></i>
                        Telegram Imports
                    </a>
                </div>

                <!-- System -->
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.content.index') }}" class="nav-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        Content Management
                    </a>
                    <a href="{{ route('admin.backups.index') }}" class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        Backups
                    </a>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        @if(auth()->guard('admin')->user()->canAccessSystemSettings())
                            <i class="fas fa-cogs"></i>
                            Settings
                        @else
                            <i class="fas fa-edit"></i>
                            Editor Settings
                        @endif
                    </a>
                </div>
                @endif
            </nav>

            <!-- User Info -->
            <div class="user-info">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}</div>
                        <small class="opacity-75">{{ auth()->guard('admin')->user()->email ?? 'admin@sundaylearn.com' }}</small>
                        <div class="mt-1">
                            <span class="badge badge-sm" style="background: rgba(255,255,255,0.2); font-size: 0.7rem;">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem; color: #28a745;"></i>Online
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.account-settings') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-user-cog me-1"></i> Account Settings
                    </a>
                    <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
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

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>