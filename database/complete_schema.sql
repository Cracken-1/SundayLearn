-- ============================================
-- Sunday School Platform - Complete Database Schema
-- For InfinityFree MySQL Database (Updated with Video Support)
-- ============================================

-- Set character set
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- Table: users
-- Purpose: Store regular user accounts (Laravel default)
-- ============================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) DEFAULT 'viewer' COMMENT 'viewer, subscriber, contributor',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Regular user accounts';

-- ============================================
-- Table: personal_access_tokens
-- Purpose: Laravel Sanctum API tokens
-- ============================================
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL UNIQUE,
  `abilities` TEXT NULL,
  `last_used_at` TIMESTAMP NULL,
  `expires_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_personal_access_tokens_tokenable` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='API access tokens';

-- ============================================
-- Table: admin_users
-- Purpose: Store admin user accounts
-- ============================================
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) DEFAULT 'admin' COMMENT 'super_admin, admin, editor',
  `created_by` BIGINT UNSIGNED NULL COMMENT 'ID of admin who created this user',
  `password_change_required` TINYINT(1) DEFAULT 0 COMMENT 'Force password change on next login',
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login_at` TIMESTAMP NULL,
  `last_login_ip` VARCHAR(45) NULL COMMENT 'Last login IP address',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_admin_users_email` (`email`),
  INDEX `idx_admin_users_active` (`is_active`),
  INDEX `idx_admin_users_role` (`role`),
  FOREIGN KEY `fk_admin_users_created_by` (`created_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin user accounts';

-- ============================================
-- Table: lessons
-- Purpose: Store Sunday School lessons
-- ============================================
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT NULL COMMENT 'Short description',
  `scripture` VARCHAR(255) NULL COMMENT 'Bible reference',
  `theme` VARCHAR(255) NULL COMMENT 'Lesson theme',
  `age_group` VARCHAR(255) NULL COMMENT 'Target age group',
  `duration` INT NULL COMMENT 'Duration in minutes',
  `thumbnail` TEXT NULL COMMENT 'Thumbnail image path',
  `image_url` TEXT NULL COMMENT 'Featured image path',
  `overview` TEXT NULL COMMENT 'Lesson overview',
  `objectives` TEXT NULL COMMENT 'Learning objectives (JSON format)',
  `content` LONGTEXT NOT NULL COMMENT 'Main lesson content',
  `discussion_questions` TEXT NULL COMMENT 'Discussion questions (JSON format)',
  `video_url` TEXT NULL COMMENT 'YouTube/Vimeo URL',
  `audio_url` TEXT NULL COMMENT 'Audio file URL',
  `downloads` TEXT NULL COMMENT 'Downloadable resources (JSON format)',
  `attachments` TEXT NULL COMMENT 'File attachments including video/audio (JSON format)',
  `category` VARCHAR(100) NULL COMMENT 'Lesson category',
  `difficulty` VARCHAR(50) NULL COMMENT 'beginner, intermediate, advanced',
  `order` INT DEFAULT 0 COMMENT 'Display order',
  `tags` TEXT NULL COMMENT 'Tags (JSON format)',
  `meta_title` VARCHAR(255) NULL COMMENT 'SEO meta title',
  `meta_description` TEXT NULL COMMENT 'SEO meta description',
  `is_featured` TINYINT(1) DEFAULT 0 COMMENT 'Featured lesson flag',
  `views_count` INT DEFAULT 0 COMMENT 'Number of views',
  `last_viewed_at` TIMESTAMP NULL COMMENT 'Last view timestamp',
  `status` VARCHAR(50) DEFAULT 'draft' COMMENT 'draft, published',
  `published_at` TIMESTAMP NULL COMMENT 'Publication date',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_lessons_slug` (`slug`),
  INDEX `idx_lessons_status` (`status`),
  INDEX `idx_lessons_category` (`category`),
  INDEX `idx_lessons_difficulty` (`difficulty`),
  INDEX `idx_lessons_published_at` (`published_at`),
  INDEX `idx_lessons_is_featured` (`is_featured`),
  INDEX `idx_lessons_age_group` (`age_group`),
  INDEX `idx_lessons_status_published` (`status`, `published_at`),
  INDEX `idx_lessons_featured_status` (`is_featured`, `status`),
  INDEX `idx_lessons_category_status` (`category`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sunday School lessons with video support';

-- ============================================
-- Table: blog_posts
-- Purpose: Store blog articles
-- ============================================
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT NULL COMMENT 'Short description',
  `content` LONGTEXT NOT NULL COMMENT 'Blog post content',
  `author` VARCHAR(255) NULL COMMENT 'Author name',
  `image_url` TEXT NULL COMMENT 'Featured image path',
  `category` VARCHAR(100) NULL COMMENT 'Blog category',
  `tags` TEXT NULL COMMENT 'Tags (JSON format)',
  `meta_title` VARCHAR(255) NULL COMMENT 'SEO meta title',
  `meta_description` TEXT NULL COMMENT 'SEO meta description',
  `is_featured` TINYINT(1) DEFAULT 0 COMMENT 'Featured post flag',
  `views_count` INT DEFAULT 0 COMMENT 'Number of views',
  `status` VARCHAR(50) DEFAULT 'draft' COMMENT 'draft, published',
  `published_at` TIMESTAMP NULL COMMENT 'Publication date',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_blog_posts_slug` (`slug`),
  INDEX `idx_blog_posts_status` (`status`),
  INDEX `idx_blog_posts_category` (`category`),
  INDEX `idx_blog_posts_published_at` (`published_at`),
  INDEX `idx_blog_posts_is_featured` (`is_featured`),
  INDEX `idx_blog_posts_status_published` (`status`, `published_at`),
  INDEX `idx_blog_posts_featured_status` (`is_featured`, `status`),
  FULLTEXT INDEX `idx_blog_posts_search` (`title`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Blog posts';

-- ============================================
-- Table: telegram_raw_imports
-- Purpose: Store raw data from Telegram bot
-- ============================================
DROP TABLE IF EXISTS `telegram_raw_imports`;
CREATE TABLE `telegram_raw_imports` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `telegram_message_id` BIGINT NULL COMMENT 'Telegram message ID',
  `chat_id` BIGINT NULL COMMENT 'Telegram chat ID',
  `user_id` BIGINT NULL COMMENT 'Telegram user ID',
  `username` VARCHAR(255) NULL COMMENT 'Telegram username',
  `message_type` VARCHAR(50) NULL COMMENT 'text, photo, video, document',
  `text_content` TEXT NULL COMMENT 'Message text',
  `caption` TEXT NULL COMMENT 'Media caption',
  `media_type` VARCHAR(50) NULL COMMENT 'photo, video, audio, document',
  `file_id` TEXT NULL COMMENT 'Telegram file ID',
  `file_unique_id` TEXT NULL COMMENT 'Telegram unique file ID',
  `file_path` TEXT NULL COMMENT 'Downloaded file path',
  `file_size` BIGINT NULL COMMENT 'File size in bytes',
  `mime_type` VARCHAR(100) NULL COMMENT 'File MIME type',
  `media_group_id` VARCHAR(255) NULL COMMENT 'Media group ID for albums',
  `raw_data` LONGTEXT NULL COMMENT 'Complete Telegram update (JSON format)',
  `processing_status` VARCHAR(50) DEFAULT 'pending' COMMENT 'pending, processing, completed, failed',
  `processed_at` TIMESTAMP NULL COMMENT 'Processing completion time',
  `error_message` TEXT NULL COMMENT 'Error message if failed',
  `lesson_id` BIGINT UNSIGNED NULL COMMENT 'Associated lesson ID',
  `created_lesson_id` BIGINT NULL COMMENT 'Created lesson ID',
  `created_blog_id` BIGINT NULL COMMENT 'Created blog post ID',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_telegram_imports_status` (`processing_status`),
  INDEX `idx_telegram_imports_message_id` (`telegram_message_id`),
  INDEX `idx_telegram_imports_media_group` (`media_group_id`),
  INDEX `idx_telegram_imports_created_at` (`created_at`),
  INDEX `idx_telegram_imports_user_id` (`user_id`),
  INDEX `idx_telegram_imports_lesson_id` (`lesson_id`),
  FOREIGN KEY `fk_telegram_imports_lesson_id` (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Telegram bot imports';

-- ============================================
-- Table: admin_activities
-- Purpose: Track admin user activities
-- ============================================
DROP TABLE IF EXISTS `admin_activities`;
CREATE TABLE `admin_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `action` VARCHAR(100) NOT NULL COMMENT 'created, updated, deleted, published',
  `model_type` VARCHAR(255) NULL COMMENT 'Model class name',
  `model_id` BIGINT NULL COMMENT 'Model record ID',
  `description` TEXT NOT NULL COMMENT 'Activity description',
  `properties` TEXT NULL COMMENT 'Additional data (JSON format)',
  `ip_address` VARCHAR(45) NULL COMMENT 'User IP address',
  `user_agent` TEXT NULL COMMENT 'User agent string',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_admin_activities_model` (`model_type`, `model_id`),
  INDEX `idx_admin_activities_action` (`action`),
  INDEX `idx_admin_activities_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin activity log';

-- ============================================
-- Table: events
-- Purpose: Store church events, holidays, and special occasions
-- ============================================
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `event_date` DATE NOT NULL,
  `event_type` ENUM('holiday','special','seasonal','other') NOT NULL DEFAULT 'other',
  `color` VARCHAR(7) NOT NULL DEFAULT '#dc3545',
  `icon` VARCHAR(50) NOT NULL DEFAULT 'calendar',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_events_date` (`event_date`),
  INDEX `idx_events_type` (`event_type`),
  INDEX `idx_events_featured` (`is_featured`),
  INDEX `idx_events_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Church events and holidays';

-- ============================================
-- Table: teaching_tips
-- Purpose: Store helpful teaching tips and advice for Sunday school teachers
-- ============================================
DROP TABLE IF EXISTS `teaching_tips`;
CREATE TABLE `teaching_tips` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'lightbulb',
  `category` VARCHAR(100) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_teaching_tips_active` (`is_active`),
  INDEX `idx_teaching_tips_category` (`category`),
  INDEX `idx_teaching_tips_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Teaching tips and advice';

-- ============================================
-- Table: resources (UPDATED WITH VIDEO SUPPORT)
-- Purpose: Store downloadable teaching resources including video content
-- ============================================
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `type` ENUM('worksheet','coloring_page','activity_guide','craft','game','video','other') NOT NULL DEFAULT 'other',
  `file_url` TEXT NOT NULL,
  `thumbnail` TEXT NULL,
  `category` VARCHAR(100) NULL,
  `age_group` VARCHAR(50) NULL,
  `file_size` INT NULL,
  `file_type` VARCHAR(50) NULL,
  `downloads_count` INT NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `lesson_id` BIGINT UNSIGNED NULL COMMENT 'Linked lesson ID',
  `source` VARCHAR(50) DEFAULT 'uploaded' COMMENT 'uploaded, lesson_attachment',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_resources_type` (`type`),
  INDEX `idx_resources_category` (`category`),
  INDEX `idx_resources_age_group` (`age_group`),
  INDEX `idx_resources_featured` (`is_featured`),
  INDEX `idx_resources_downloads` (`downloads_count`),
  INDEX `idx_resources_type_featured` (`type`, `is_featured`),
  INDEX `idx_resources_age_type` (`age_group`, `type`),
  INDEX `idx_resources_lesson_id` (`lesson_id`),
  FOREIGN KEY `fk_resources_lesson_id` (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Downloadable teaching resources with video support';

-- ============================================
-- Table: newsletters
-- Purpose: Store newsletter subscriber information
-- ============================================
DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE `newsletters` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `name` VARCHAR(255) NULL,
  `status` ENUM('subscribed','unsubscribed','bounced') NOT NULL DEFAULT 'subscribed',
  `unsubscribe_token` VARCHAR(100) UNIQUE NULL,
  `subscribed_at` TIMESTAMP NULL,
  `unsubscribed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_newsletters_email` (`email`),
  INDEX `idx_newsletters_status` (`status`),
  INDEX `idx_newsletters_token` (`unsubscribe_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Newsletter subscribers';

-- ============================================
-- Table: analytics
-- Purpose: Track website analytics and user interactions
-- ============================================
DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `event_type` VARCHAR(50) NOT NULL COMMENT 'page_view, lesson_view, resource_download, search, etc.',
  `event_category` VARCHAR(100) NULL COMMENT 'lessons, resources, blog, etc.',
  `event_action` VARCHAR(100) NULL COMMENT 'view, download, search, click, etc.',
  `event_label` VARCHAR(255) NULL COMMENT 'specific item identifier',
  `page_url` TEXT NULL COMMENT 'current page URL',
  `referrer_url` TEXT NULL COMMENT 'referring page URL',
  `user_agent` TEXT NULL COMMENT 'browser user agent',
  `ip_hash` VARCHAR(64) NULL COMMENT 'hashed IP for privacy (SHA-256)',
  `session_hash` VARCHAR(64) NULL COMMENT 'hashed session ID for privacy',
  `user_id` BIGINT UNSIGNED NULL COMMENT 'authenticated user ID (optional)',
  `device_type` VARCHAR(50) NULL COMMENT 'desktop, mobile, tablet',
  `browser` VARCHAR(100) NULL COMMENT 'browser name (general)',
  `operating_system` VARCHAR(100) NULL COMMENT 'OS name (general)',
  `country_code` VARCHAR(2) NULL COMMENT 'ISO country code only',
  `search_query` VARCHAR(255) NULL COMMENT 'search terms if applicable',
  `metadata` TEXT NULL COMMENT 'additional event data (JSON format)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_analytics_event_type` (`event_type`),
  INDEX `idx_analytics_category` (`event_category`),
  INDEX `idx_analytics_action` (`event_action`),
  INDEX `idx_analytics_created_at` (`created_at`),
  INDEX `idx_analytics_session` (`session_hash`),
  INDEX `idx_analytics_user` (`user_id`),
  INDEX `idx_analytics_ip` (`ip_hash`),
  INDEX `idx_analytics_type_date` (`event_type`, `created_at`),
  INDEX `idx_analytics_category_date` (`event_category`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Website analytics and user interactions';

-- ============================================
-- Table: migrations
-- Purpose: Laravel migration tracking
-- ============================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laravel migrations';

-- ============================================
-- Insert ONLY Essential Data (Admin User Only)
-- ============================================

-- Insert default admin user
-- Email: admin@sundaylearn.com
-- Password: password (CHANGE THIS IMMEDIATELY!)
INSERT INTO `admin_users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) 
VALUES (
  'Admin User',
  'admin@sundaylearn.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'super_admin',
  1,
  NOW(),
  NOW()
);

-- Insert migration records
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2024_01_01_000001_create_users_table', 2),
('2024_12_06_000001_create_telegram_raw_imports_table', 1),
('2024_12_08_000001_add_attachments_to_lessons_table', 1),
('2024_12_08_000002_create_admin_activities_table', 1),
('2025_12_09_131322_add_lesson_id_to_telegram_raw_imports_table', 3),
('2025_12_09_160551_create_events_table', 4),
('2025_12_09_160829_create_teaching_tips_table', 4),
('2025_12_09_161024_create_resources_table', 4),
('2025_12_09_161230_create_newsletters_table', 4),
('2025_12_11_123349_create_admin_users_table', 5),
('2025_12_11_125914_update_admin_users_table_add_role_fields', 5),
('2025_12_11_144310_add_last_login_ip_to_admin_users_table', 6),
('2025_12_11_000001_create_analytics_table', 7),
('2025_12_15_072903_add_lesson_id_to_resources_table', 8);

-- ============================================
-- Enable foreign key checks
-- ============================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- IMPORTANT NOTES
-- ============================================
-- 1. Default admin credentials:
--    Email: admin@sundaylearn.com
--    Password: password
--    CHANGE THIS IMMEDIATELY AFTER FIRST LOGIN!
--
-- 2. UPDATED FEATURES:
--    - Video support in resources (type: 'video')
--    - Enhanced lesson attachments support video/audio files
--    - Performance indexes added for better query speed
--    - File size limits increased to 50MB for media files
--
-- 3. SUPPORTED FILE TYPES:
--    - Lessons: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF, ZIP, MP4, AVI, MOV, WMV, WebM, MP3, WAV, OGG, M4A
--    - Resources: Same as lessons + video type classification
--
-- 4. This schema contains NO sample data
-- 5. All tables are empty except admin_users and migrations
-- 6. Ready for production use with video support
-- 7. Database size: ~1MB (minimal)
-- ============================================