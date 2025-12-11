# SundayLearn Deployment Guide

## Quick Deployment Steps

### 1. Clone and Setup
```bash
git clone https://github.com/Cracken-1/SundayLearn.git
cd SundayLearn
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your settings:
```env
APP_NAME="SundayLearn"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=sundaylearn
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Setup
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 4. Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Web Server Configuration

#### Apache (.htaccess included)
Point document root to `/public` directory

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/SundayLearn/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database
- [ ] Set up SSL certificate
- [ ] Configure mail settings
- [ ] Set proper file permissions
- [ ] Configure backup schedule
- [ ] Test all functionality

## Default Admin Account
After seeding, login with:
- Email: admin@sundaylearn.com
- Password: password

**Change this immediately after first login!**

For detailed deployment instructions, see [TECHNICAL_SPECS.md](TECHNICAL_SPECS.md).