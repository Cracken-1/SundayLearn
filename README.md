# SundayLearn - Sunday School Management Platform

![SundayLearn Logo](public/images/logo.png)

SundayLearn is a comprehensive web-based platform designed to help Sunday school teachers and administrators manage lessons, resources, and educational content for children's ministry programs.

## 🌟 Features

### For Teachers & Content Creators
- **Lesson Management**: Create, edit, and organize Sunday school lessons
- **Resource Library**: Upload and manage teaching materials, worksheets, and multimedia content
- **Blog System**: Share teaching tips and educational articles
- **Event Management**: Track special events, holidays, and church calendar items
- **Teaching Tips**: Access and contribute helpful teaching strategies

### For Administrators
- **User Management**: Manage admin users with role-based permissions (Super Admin, Admin, Editor)
- **Analytics Dashboard**: Track content performance, views, and downloads
- **System Settings**: Configure platform settings and integrations
- **Backup Management**: Automated backup and restore functionality
- **Telegram Integration**: Import content from Telegram channels (Admin only)

### For Visitors
- **Browse Lessons**: Search and filter lessons by age group, topic, and difficulty
- **Download Resources**: Access free teaching materials and worksheets
- **Newsletter Subscription**: Stay updated with new content and tips
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- MySQL 5.7 or higher / PostgreSQL 12+
- Composer
- Node.js & NPM (for asset compilation)
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Cracken-1/SundayLearn.git
   cd SundayLearn
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sundaylearn
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Compile assets**
   ```bash
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` to access the application.

## 👥 User Roles & Permissions

### Super Administrator
- Full system access
- User management
- System settings
- Integrations (Telegram)
- Backup management
- All content management features

### Administrator
- User management (limited)
- Content management
- Analytics access
- System monitoring
- Integration management

### Editor
- Content creation and editing
- Resource management
- Blog post creation
- Teaching tips management
- Limited analytics access

## 📱 Key Sections

### Public Website
- **Home**: Welcome page with featured content
- **Lessons**: Browse and search Sunday school lessons
- **Resources**: Download teaching materials
- **Blog**: Read teaching tips and articles
- **About**: Information about the platform

### Admin Dashboard
- **Overview**: System statistics and quick actions
- **Content Management**: Manage lessons, blogs, resources
- **User Management**: Admin user administration
- **Analytics**: Performance metrics and reports
- **Settings**: System configuration and preferences

## 🔧 Configuration

### Environment Variables
Key environment variables to configure:

```env
# Application
APP_NAME="SundayLearn"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_host
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password

# Telegram Integration (Optional)
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHANNEL_ID=your_channel_id
```

### File Storage
The application supports multiple storage drivers:
- **Local**: Default file storage in `storage/app/public`
- **S3**: Amazon S3 bucket storage
- **Other**: Any Laravel-supported filesystem

## 🛡️ Security Features

- **Role-based Access Control**: Three-tier permission system
- **CSRF Protection**: All forms protected against CSRF attacks
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **Secure File Uploads**: File type validation and secure storage
- **Password Hashing**: Bcrypt password encryption

## 📊 Analytics & Tracking

- **Privacy-Compliant**: No personal data collection
- **Page Views**: Track content popularity
- **Download Statistics**: Monitor resource usage
- **Search Analytics**: Understand user search patterns
- **Device/Browser Stats**: Optimize for user preferences

## 🔌 Integrations

### Telegram Bot (Admin Feature)
- Import content from Telegram channels
- Automatic processing of text, images, and documents
- Status tracking and management
- Configurable webhook endpoints

### Newsletter System
- Subscriber management
- Email campaign support
- Unsubscribe handling
- GDPR compliance

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database
- [ ] Set up SSL certificate
- [ ] Configure mail settings
- [ ] Set up backup schedule
- [ ] Configure file storage
- [ ] Set proper file permissions
- [ ] Configure web server
- [ ] Set up monitoring

### Recommended Hosting
- **VPS/Dedicated**: Full control and customization
- **Shared Hosting**: Basic Laravel-compatible hosting
- **Cloud Platforms**: AWS, DigitalOcean, Linode
- **Managed Laravel**: Laravel Forge, Vapor

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: [Technical Specifications](TECHNICAL_SPECS.md)
- **User Manual**: [User Guide](USER_MANUAL.md)
- **Issues**: [GitHub Issues](https://github.com/Cracken-1/SundayLearn/issues)
- **Discussions**: [GitHub Discussions](https://github.com/Cracken-1/SundayLearn/discussions)

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap CSS Framework
- Font Awesome Icons
- All contributors and testers

## 📈 Roadmap

- [ ] Mobile app development
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Video streaming integration
- [ ] Advanced search features
- [ ] API development
- [ ] Plugin system

---

**Made with ❤️ for Sunday School Teachers and Children's Ministry**