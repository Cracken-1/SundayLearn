# Friends of Children Ministries

A comprehensive Sunday School learning platform designed to support children's ministry with engaging biblical lessons, multimedia resources, and administrative tools.

## Features

- **Age-Appropriate Lessons**: Structured content for different age groups (preschool to teens)
- **Multimedia Integration**: Support for video, audio, and interactive content
- **Resource Management**: Downloadable materials and teaching aids
- **Administrative Dashboard**: Complete content management system
- **Telegram Integration**: Import content from Telegram channels
- **Newsletter System**: Email subscription management
- **Responsive Design**: Mobile-friendly interface

## Age Groups

- **Class A**: Preschool (ages 3-5)
- **Class B**: Lower Class (ages 6-9)
- **Class C**: Preteens (ages 10-12)
- **Class D**: Teens (ages 13+)

## Technology Stack

- **Backend**: Laravel 10.x with PHP 8.1+
- **Frontend**: Vite, Bootstrap, Font Awesome
- **Database**: PostgreSQL (Supabase)
- **Storage**: Supabase Storage
- **Deployment**: Vercel-ready

## Installation

1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Configure environment: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Build assets: `npm run build`

## Production Updates

Use the included update scripts for production maintenance:

```bash
# Update packages and version
npm run update

# Windows PowerShell
.\update-production.ps1
```

## Configuration

Set up your environment variables in `.env`:

- Database connection (Supabase)
- Supabase storage credentials
- Telegram bot configuration (optional)
- Mail settings for newsletters

## License

MIT License - see LICENSE file for details.

## Support

For support and questions, please contact the development team.
