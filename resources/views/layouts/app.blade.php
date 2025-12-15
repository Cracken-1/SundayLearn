<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SundayLearn - Sunday School Teaching Platform')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @unless(isset($isAdminArea) && $isAdminArea)
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="{{ route('home') }}">
                    <i class="fas fa-book-open"></i>
                    <span>SundayLearn</span>
                </a>
            </div>
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('lessons.index') }}" class="{{ request()->routeIs('lessons.*') ? 'active' : '' }}">Lessons</a></li>
                <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
                <li><a href="{{ route('resources.index') }}" class="{{ request()->routeIs('resources.*') ? 'active' : '' }}">Resources</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li>
                    <a href="{{ route('admin.login') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: var(--secondary-color); font-weight: 600;">
                        <i class="fas fa-cog"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    @endunless

    <main>
        @yield('content')
    </main>

    @unless(isset($isAdminArea) && $isAdminArea)
    <section class="newsletter-section">
        <div class="container" style="text-align: center;">
            <h3 style="color: white; margin-bottom: 1rem; font-size: 2rem;">Stay Updated</h3>
            <p style="margin-bottom: 2rem; opacity: 0.95; font-size: 1.1rem;">
                Get new lessons and teaching tips delivered to your inbox every week
            </p>
            <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing! (Demo mode)');">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i> Subscribe
                </button>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About SundayLearn</h4>
                    <p>Empowering Sunday school teachers with quality biblical education resources.</p>
                    <div style="margin-top: 1rem;">
                        <a href="#" style="color: var(--secondary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: var(--secondary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: var(--secondary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: var(--secondary-color); font-size: 1.5rem;"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('lessons.index') }}">Browse Lessons</a></li>
                        <li><a href="{{ route('blog.index') }}">Teaching Blog</a></li>
                        <li><a href="{{ route('resources.index') }}">Resources</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="{{ route('lessons.index') }}?age=3-5">Ages 3-5</a></li>
                        <li><a href="{{ route('lessons.index') }}?age=6-8">Ages 6-8</a></li>
                        <li><a href="{{ route('lessons.index') }}?age=9-12">Ages 9-12</a></li>
                        <li><a href="{{ route('lessons.index') }}?age=teen">Teen</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> hello@sundaylearn.com</p>
                    <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> Serving churches nationwide</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SundayLearn. All rights reserved. | <a href="#" style="color: var(--secondary-color);">Privacy Policy</a> | <a href="#" style="color: var(--secondary-color);">Terms of Service</a></p>
            </div>
        </div>
    </footer>
    @endunless

    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Mobile Navigation Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navToggle');
            const navMenu = document.getElementById('navMenu');
            
            if (navToggle && navMenu) {
                navToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                        navMenu.classList.remove('active');
                    }
                });
                
                // Close menu when clicking on a link
                navMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        navMenu.classList.remove('active');
                    });
                });
            }
        });
        
        // Back to top button visibility
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>