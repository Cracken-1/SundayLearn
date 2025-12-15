# XAMPP Upload Configuration Guide

## Manual PHP Configuration (Recommended)

Since the automatic PHP configuration isn't taking effect, please manually update your XAMPP PHP settings:

### Step 1: Open PHP Configuration File
1. Open `C:\xampp\php\php.ini` in a text editor (as Administrator)

### Step 2: Find and Update These Settings
Search for these lines and update them (remove the semicolon `;` if present):

```ini
; File Uploads
file_uploads = On
upload_max_filesize = 100M
max_file_uploads = 20

; Post Data
post_max_size = 100M

; Execution Time
max_execution_time = 300
max_input_time = 300

; Memory
memory_limit = 256M
```

### Step 3: Restart XAMPP
1. Stop Apache in XAMPP Control Panel
2. Start Apache again

### Step 4: Verify Settings
Visit: `http://127.0.0.1:8000/phpinfo.php` to verify the changes took effect.

## Alternative: Environment-Specific Configuration

If you can't modify the main php.ini file, you can also:

1. **Create a custom php.ini in your project root:**
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   max_execution_time = 300
   max_input_time = 300
   memory_limit = 256M
   max_file_uploads = 20
   ```

2. **Or modify XAMPP's Apache configuration** to load project-specific PHP settings.

## Current Implementation

The application has been configured with:
- Custom middleware to handle large uploads
- Separate file upload fields for videos, audio, and documents
- 100MB limit per file
- Enhanced error handling and user feedback

## Troubleshooting

If uploads still fail:
1. Check `storage/logs/laravel.log` for errors
2. Verify PHP settings at `/phpinfo.php`
3. Ensure the `storage` directory is writable
4. Check available disk space

## File Organization

Files are stored in:
- Videos: `storage/app/public/lessons/video/`
- Audio: `storage/app/public/lessons/audio/`
- Documents: `storage/app/public/lessons/document/`