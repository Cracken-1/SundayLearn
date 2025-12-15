# URGENT: Fix Upload Size Issue

## The Problem
Your XAMPP PHP configuration has these limits:
- `upload_max_filesize = 2M`
- `post_max_size = 8M`

These cannot be changed via PHP code - they must be changed in the php.ini file.

## SOLUTION: Update XAMPP PHP Configuration

### Step 1: Open PHP Configuration
1. **Stop XAMPP Apache** (important!)
2. Open file: `C:\xampp\php\php.ini` in Notepad (Run as Administrator)

### Step 2: Find and Change These Lines
Search for these settings and change them:

**Find this line:**
```ini
upload_max_filesize = 2M
```
**Change to:**
```ini
upload_max_filesize = 100M
```

**Find this line:**
```ini
post_max_size = 8M
```
**Change to:**
```ini
post_max_size = 100M
```

**Find this line:**
```ini
max_execution_time = 30
```
**Change to:**
```ini
max_execution_time = 300
```

**Find this line:**
```ini
max_input_time = 60
```
**Change to:**
```ini
max_input_time = 300
```

**Find this line:**
```ini
memory_limit = 128M
```
**Change to:**
```ini
memory_limit = 256M
```

### Step 3: Save and Restart
1. **Save** the php.ini file
2. **Start XAMPP Apache** again
3. **Restart the Laravel server**: `php artisan serve`

### Step 4: Verify
Visit: `http://127.0.0.1:8000/phpinfo.php` and check that:
- `upload_max_filesize` shows `100M`
- `post_max_size` shows `100M`

## Alternative Quick Fix
If you can't edit the main php.ini file, create a new file:
`C:\xampp\php\conf.d\uploads.ini`

With this content:
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

Then restart Apache.

## After Making Changes
Your 24MB file upload should work without the "POST Content Too Large" error.