# Contributing to SundayLearn

Thank you for your interest in contributing to SundayLearn! This document provides guidelines and information for contributors.

## Table of Contents
- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Process](#development-process)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)

## Code of Conduct

### Our Pledge
We are committed to making participation in this project a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity and expression, level of experience, nationality, personal appearance, race, religion, or sexual identity and orientation.

### Our Standards
Examples of behavior that contributes to creating a positive environment include:
- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

## Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16+ and NPM
- MySQL 5.7+ or PostgreSQL 12+
- Git

### Development Setup
1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/SundayLearn.git
   cd SundayLearn
   ```
3. Install dependencies:
   ```bash
   composer install
   npm install
   ```
4. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure your database in `.env`
6. Run migrations:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
7. Start development server:
   ```bash
   php artisan serve
   npm run dev
   ```

## Development Process

### Branching Strategy
- `main`: Production-ready code
- `develop`: Integration branch for features
- `feature/feature-name`: New features
- `bugfix/bug-description`: Bug fixes
- `hotfix/critical-fix`: Critical production fixes

### Workflow
1. Create a new branch from `develop`:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/your-feature-name
   ```
2. Make your changes
3. Test your changes thoroughly
4. Commit your changes with clear messages
5. Push to your fork
6. Create a Pull Request

## Coding Standards

### PHP Standards
We follow PSR-12 coding standards with some additional rules:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $items = Model::query()
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->view('items.index', compact('items'));
    }
}
```

### Laravel Best Practices
- Use Eloquent ORM instead of raw queries
- Follow Laravel naming conventions
- Use form requests for validation
- Implement proper error handling
- Use Laravel's built-in features (caching, queues, etc.)

### Frontend Standards
- Use Bootstrap 5 classes consistently
- Write semantic HTML
- Follow BEM methodology for custom CSS
- Use vanilla JavaScript or jQuery sparingly
- Ensure mobile responsiveness

### Database Standards
- Use descriptive table and column names
- Add proper indexes for performance
- Use foreign key constraints
- Include timestamps on all tables
- Write clear migration files

## Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/LessonTest.php

# Run with coverage
php artisan test --coverage
```

### Writing Tests
- Write feature tests for user interactions
- Write unit tests for complex business logic
- Use factories for test data
- Mock external services
- Aim for good test coverage

Example test:
```php
<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_lesson(): void
    {
        $admin = AdminUser::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/lessons', [
                'title' => 'Test Lesson',
                'content' => 'Test content',
                'age_group' => '6-8',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lessons', [
            'title' => 'Test Lesson',
        ]);
    }
}
```

## Submitting Changes

### Pull Request Process
1. Ensure your code follows our coding standards
2. Update documentation if needed
3. Add or update tests for your changes
4. Ensure all tests pass
5. Update the CHANGELOG.md if applicable
6. Create a detailed pull request description

### Pull Request Template
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added for new functionality
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings introduced
```

### Commit Message Format
Use clear, descriptive commit messages:
```
type(scope): description

feat(lessons): add video upload functionality
fix(auth): resolve login redirect issue
docs(readme): update installation instructions
style(css): improve mobile responsiveness
test(lessons): add unit tests for lesson creation
```

## Issue Reporting

### Bug Reports
When reporting bugs, please include:
- Clear description of the issue
- Steps to reproduce
- Expected vs actual behavior
- Environment details (PHP version, browser, etc.)
- Screenshots if applicable

### Feature Requests
For feature requests, please provide:
- Clear description of the feature
- Use case and benefits
- Possible implementation approach
- Any relevant mockups or examples

## Documentation

### Code Documentation
- Use PHPDoc for all classes and methods
- Include parameter and return type information
- Provide clear descriptions and examples
- Document any complex business logic

### User Documentation
- Update user manual for new features
- Include screenshots for UI changes
- Provide step-by-step instructions
- Consider different user skill levels

## Community

### Getting Help
- Check existing documentation first
- Search existing issues and discussions
- Ask questions in GitHub Discussions
- Be respectful and patient

### Communication Channels
- GitHub Issues: Bug reports and feature requests
- GitHub Discussions: General questions and ideas
- Pull Requests: Code review and collaboration

## Recognition

Contributors will be recognized in:
- CONTRIBUTORS.md file
- Release notes for significant contributions
- GitHub contributor statistics

Thank you for contributing to SundayLearn and helping make Sunday school education better for everyone!