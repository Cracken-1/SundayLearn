# SundayLearn Technical Specifications

## Table of Contents
1. [System Architecture](#system-architecture)
2. [Technology Stack](#technology-stack)
3. [Database Schema](#database-schema)
4. [API Documentation](#api-documentation)
5. [Security Implementation](#security-implementation)
6. [Performance Specifications](#performance-specifications)
7. [Deployment Guide](#deployment-guide)
8. [Development Setup](#development-setup)

## System Architecture

### Overview
SundayLearn is built using a modern MVC (Model-View-Controller) architecture with Laravel framework, providing a robust and scalable foundation for content management and user interaction.

### Architecture Components

#### Frontend Layer
- **Blade Templates**: Server-side rendering with Laravel's templating engine
- **Bootstrap 5**: Responsive CSS framework for mobile-first design
- **JavaScript**: Vanilla JS and jQuery for interactive elements
- **Font Awesome**: Icon library for consistent UI elements

#### Backend Layer
- **Laravel Framework**: PHP web application framework
- **Eloquent ORM**: Database abstraction and relationship management
- **Middleware**: Request filtering and authentication
- **Service Providers**: Application bootstrapping and service binding

#### Data Layer
- **MySQL/PostgreSQL**: Primary database for application data
- **File Storage**: Local or cloud-based file storage system
- **Caching**: Redis/Memcached for performance optimization
- **Session Management**: Database or file-based session storage

## Technology Stack

### Core Technologies
- **PHP**: 8.1+ (Required for Laravel 10)
- **Laravel**: 10.x LTS
- **MySQL**: 5.7+ or PostgreSQL 12+
- **Composer**: PHP dependency management
- **Node.js**: 16+ for asset compilation
- **NPM/Yarn**: JavaScript package management

### Frontend Dependencies
```json
{
  "bootstrap": "^5.3.0",
  "jquery": "^3.6.0",
  "@fortawesome/fontawesome-free": "^6.4.0",
  "chart.js": "^4.3.0",
  "datatables.net": "^1.13.0"
}
```

### Backend Dependencies
```json
{
  "laravel/framework": "^10.0",
  "laravel/sanctum": "^3.2",
  "intervention/image": "^2.7",
  "spatie/laravel-permission": "^5.10",
  "barryvdh/laravel-dompdf": "^2.0",
  "maatwebsite/excel": "^3.1"
}
```

## Database Schema

### Core Tables

#### Users & Authentication
```sql
-- admin_users table
CREATE TABLE admin_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    created_by BIGINT UNSIGNED NULL,
    password_change_required BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);
```

#### Content Management
```sql
-- lessons table
CREATE TABLE lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT,
    excerpt TEXT,
    age_group VARCHAR(50),
    duration VARCHAR(50),
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',
    scripture_reference TEXT,
    materials_needed TEXT,
    featured_image VARCHAR(255),
    video_url VARCHAR(255),
    audio_url VARCHAR(255),
    views_count INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_published (published_at),
    INDEX idx_age_group (age_group),
    INDEX idx_created_by (created_by)
);

-- blog_posts table
CREATE TABLE blog_posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT,
    excerpt TEXT,
    featured_image VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    views_count INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_published (published_at),
    INDEX idx_created_by (created_by)
);

-- resources table
CREATE TABLE resources (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size BIGINT,
    file_type VARCHAR(100),
    category VARCHAR(100),
    age_group VARCHAR(50),
    download_count INT DEFAULT 0,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_category (category),
    INDEX idx_age_group (age_group)
);
```

#### Analytics & Tracking
```sql
-- analytics table
CREATE TABLE analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    event_category VARCHAR(100),
    event_action VARCHAR(100),
    event_label VARCHAR(255),
    page_url TEXT,
    referrer_url TEXT,
    user_agent TEXT,
    ip_hash VARCHAR(64),
    session_hash VARCHAR(64),
    user_id BIGINT UNSIGNED NULL,
    device_type VARCHAR(50),
    browser VARCHAR(100),
    operating_system VARCHAR(100),
    country_code VARCHAR(2),
    search_query VARCHAR(255),
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at),
    INDEX idx_page_url (page_url(255))
);
```

#### Newsletter & Communications
```sql
-- newsletter_subscribers table
CREATE TABLE newsletter_subscribers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    verification_token VARCHAR(255),
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_active (is_active)
);
```

### Relationships
- **One-to-Many**: AdminUser → Lessons, BlogPosts, Resources
- **Many-to-Many**: Lessons ↔ Categories, Resources ↔ Tags
- **Polymorphic**: Comments → Lessons/BlogPosts

## API Documentation

### Authentication Endpoints
```php
POST /api/auth/login
POST /api/auth/logout
POST /api/auth/refresh
GET  /api/auth/user
```

### Content Endpoints
```php
// Lessons
GET    /api/lessons              // List all published lessons
GET    /api/lessons/{id}         // Get specific lesson
POST   /api/lessons              // Create lesson (auth required)
PUT    /api/lessons/{id}         // Update lesson (auth required)
DELETE /api/lessons/{id}         // Delete lesson (auth required)

// Resources
GET    /api/resources            // List all resources
GET    /api/resources/{id}       // Get specific resource
POST   /api/resources            // Upload resource (auth required)
DELETE /api/resources/{id}       // Delete resource (auth required)

// Analytics
POST   /api/analytics/track      // Track user events
GET    /api/analytics/stats      // Get analytics data (auth required)
```

### Request/Response Examples

#### Get Lessons
```http
GET /api/lessons?age_group=6-8&limit=10&page=1

Response:
{
  "data": [
    {
      "id": 1,
      "title": "David and Goliath",
      "slug": "david-and-goliath",
      "excerpt": "Learn about courage and faith...",
      "age_group": "6-8",
      "duration": "45 minutes",
      "featured_image": "/storage/images/david-goliath.jpg",
      "published_at": "2024-01-15T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 25,
    "per_page": 10
  }
}
```

## Security Implementation

### Authentication & Authorization
- **Multi-Factor Authentication**: Optional 2FA for admin accounts
- **Role-Based Access Control**: Three-tier permission system
- **Session Management**: Secure session handling with CSRF protection
- **Password Security**: Bcrypt hashing with salt

### Data Protection
- **Input Validation**: Server-side validation for all user inputs
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Output escaping and Content Security Policy
- **File Upload Security**: Type validation and secure storage

### Privacy Compliance
- **GDPR Compliance**: Data minimization and user consent
- **Analytics Privacy**: IP hashing and anonymization
- **Cookie Policy**: Transparent cookie usage disclosure
- **Data Retention**: Configurable data retention policies

### Security Headers
```php
// Implemented security headers
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
```

## Performance Specifications

### System Requirements

#### Minimum Requirements
- **CPU**: 1 vCPU (2.0 GHz)
- **RAM**: 1 GB
- **Storage**: 10 GB SSD
- **Bandwidth**: 100 Mbps
- **Concurrent Users**: 50

#### Recommended Requirements
- **CPU**: 2 vCPU (2.4 GHz)
- **RAM**: 4 GB
- **Storage**: 50 GB SSD
- **Bandwidth**: 1 Gbps
- **Concurrent Users**: 500

#### High-Traffic Requirements
- **CPU**: 4+ vCPU (3.0 GHz)
- **RAM**: 8+ GB
- **Storage**: 100+ GB SSD
- **Bandwidth**: 10 Gbps
- **Concurrent Users**: 5000+

### Performance Optimizations

#### Caching Strategy
```php
// Application caching
'cache' => [
    'default' => 'redis',
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],
    ],
],

// View caching
php artisan view:cache

// Route caching
php artisan route:cache

// Config caching
php artisan config:cache
```

#### Database Optimization
- **Indexing**: Strategic indexes on frequently queried columns
- **Query Optimization**: Eager loading and query optimization
- **Connection Pooling**: Database connection management
- **Read Replicas**: Separate read/write database instances

#### File Storage Optimization
- **CDN Integration**: Content delivery network for static assets
- **Image Optimization**: Automatic image compression and resizing
- **Lazy Loading**: Progressive image loading for better performance
- **File Caching**: Browser and server-side file caching

## Deployment Guide

### Production Environment Setup

#### Server Configuration
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-mbstring php8.1-gd

# Install Nginx
sudo apt install nginx

# Install MySQL
sudo apt install mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/sundaylearn/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

#### SSL Configuration
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com

# Auto-renewal
sudo crontab -e
0 12 * * * /usr/bin/certbot renew --quiet
```

### Docker Deployment
```dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### Environment Configuration
```env
# Production environment
APP_NAME="SundayLearn"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sundaylearn_prod
DB_USERNAME=sundaylearn_user
DB_PASSWORD=secure_password_here

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
```

## Development Setup

### Local Development Environment

#### Prerequisites Installation
```bash
# Install PHP (macOS with Homebrew)
brew install php@8.1

# Install Composer
brew install composer

# Install Node.js
brew install node

# Install MySQL
brew install mysql
```

#### Project Setup
```bash
# Clone repository
git clone https://github.com/Cracken-1/SundayLearn.git
cd SundayLearn

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Storage link
php artisan storage:link

# Compile assets
npm run dev

# Start development server
php artisan serve
```

#### Development Tools
```json
{
  "devDependencies": {
    "laravel-mix": "^6.0.49",
    "sass": "^1.62.1",
    "sass-loader": "^13.3.1",
    "postcss": "^8.4.23",
    "autoprefixer": "^10.4.14"
  }
}
```

### Testing Framework
```php
// Feature test example
class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_published_lesson()
    {
        $lesson = Lesson::factory()->published()->create();
        
        $response = $this->get("/lessons/{$lesson->slug}");
        
        $response->assertStatus(200)
                ->assertSee($lesson->title);
    }
}

// Run tests
php artisan test
```

### Code Quality Tools
```bash
# PHP CS Fixer
composer require --dev friendsofphp/php-cs-fixer

# PHPStan
composer require --dev phpstan/phpstan

# Laravel Pint
composer require --dev laravel/pint

# Run code analysis
./vendor/bin/pint
./vendor/bin/phpstan analyse
```

---

**For additional technical support, please refer to the Laravel documentation or contact the development team.**