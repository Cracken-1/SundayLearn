<?php

// Set PHP configuration for large file uploads
// This runs before Laravel's middleware stack

// Set upload limits
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');
ini_set('memory_limit', '256M');
ini_set('max_file_uploads', '20');

// Log the settings for debugging - commented out to prevent output buffering issues
// error_log("Upload config set - upload_max_filesize: " . ini_get('upload_max_filesize'));
// error_log("Upload config set - post_max_size: " . ini_get('post_max_size'));