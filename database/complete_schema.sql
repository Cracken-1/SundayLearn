-- ============================================
-- Sunday School Platform - Complete Database Schema
-- For InfinityFree MySQL Database
-- ============================================

-- Set character set
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login_at` TIMESTAMP NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_admin_users_email` (`email`),
  INDEX `idx_admin_users_active` (`is_active`)
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
  `thumbnail` VARCHAR(500) NULL COMMENT 'Thumbnail image path',
  `image_url` VARCHAR(500) NULL COMMENT 'Featured image path',
  `overview` TEXT NULL COMMENT 'Lesson overview',
  `objectives` JSON NULL COMMENT 'Learning objectives array',
  `content` LONGTEXT NOT NULL COMMENT 'Main lesson content',
  `discussion_questions` JSON NULL COMMENT 'Discussion questions array',
  `video_url` VARCHAR(500) NULL COMMENT 'YouTube/Vimeo URL',
  `audio_url` VARCHAR(500) NULL COMMENT 'Audio file URL',
  `downloads` JSON NULL COMMENT 'Downloadable resources',
  `attachments` JSON NULL COMMENT 'File attachments (PDF, DOC, etc)',
  `category` VARCHAR(100) NULL COMMENT 'Lesson category',
  `difficulty` VARCHAR(50) NULL COMMENT 'beginner, intermediate, advanced',
  `order` INT DEFAULT 0 COMMENT 'Display order',
  `tags` JSON NULL COMMENT 'Tags array',
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
  FULLTEXT INDEX `idx_lessons_search` (`title`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sunday School lessons';

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
  `image_url` VARCHAR(500) NULL COMMENT 'Featured image path',
  `category` VARCHAR(100) NULL COMMENT 'Blog category',
  `tags` JSON NULL COMMENT 'Tags array',
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
  `file_id` VARCHAR(500) NULL COMMENT 'Telegram file ID',
  `file_unique_id` VARCHAR(500) NULL COMMENT 'Telegram unique file ID',
  `file_path` VARCHAR(500) NULL COMMENT 'Downloaded file path',
  `file_size` BIGINT NULL COMMENT 'File size in bytes',
  `mime_type` VARCHAR(100) NULL COMMENT 'File MIME type',
  `media_group_id` VARCHAR(255) NULL COMMENT 'Media group ID for albums',
  `raw_data` JSON NULL COMMENT 'Complete Telegram update JSON',
  `processing_status` VARCHAR(50) DEFAULT 'pending' COMMENT 'pending, processing, completed, failed',
  `processed_at` TIMESTAMP NULL COMMENT 'Processing completion time',
  `error_message` TEXT NULL COMMENT 'Error message if failed',
  `created_lesson_id` BIGINT NULL COMMENT 'Created lesson ID',
  `created_blog_id` BIGINT NULL COMMENT 'Created blog post ID',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_telegram_imports_status` (`processing_status`),
  INDEX `idx_telegram_imports_message_id` (`telegram_message_id`),
  INDEX `idx_telegram_imports_media_group` (`media_group_id`),
  INDEX `idx_telegram_imports_created_at` (`created_at`),
  INDEX `idx_telegram_imports_user_id` (`user_id`)
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
  `properties` JSON NULL COMMENT 'Additional data',
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
-- Table: resources
-- Purpose: Store downloadable teaching resources like worksheets, coloring pages, etc.
-- ============================================
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `type` ENUM('worksheet','coloring_page','activity_guide','craft','game','other') NOT NULL DEFAULT 'other',
  `file_url` VARCHAR(500) NOT NULL,
  `thumbnail` VARCHAR(500) NULL,
  `category` VARCHAR(100) NULL,
  `age_group` VARCHAR(50) NULL,
  `file_size` INT NULL,
  `file_type` VARCHAR(50) NULL,
  `downloads_count` INT NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_resources_type` (`type`),
  INDEX `idx_resources_category` (`category`),
  INDEX `idx_resources_age_group` (`age_group`),
  INDEX `idx_resources_featured` (`is_featured`),
  INDEX `idx_resources_downloads` (`downloads_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Downloadable teaching resources';

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
  `page_url` VARCHAR(500) NULL COMMENT 'current page URL',
  `referrer_url` VARCHAR(500) NULL COMMENT 'referring page URL',
  `user_agent` TEXT NULL COMMENT 'browser user agent',
  `ip_hash` VARCHAR(64) NULL COMMENT 'hashed IP for privacy (SHA-256)',
  `session_hash` VARCHAR(64) NULL COMMENT 'hashed session ID for privacy',
  `user_id` BIGINT UNSIGNED NULL COMMENT 'authenticated user ID (optional)',
  `device_type` VARCHAR(50) NULL COMMENT 'desktop, mobile, tablet',
  `browser` VARCHAR(100) NULL COMMENT 'browser name (general)',
  `operating_system` VARCHAR(100) NULL COMMENT 'OS name (general)',
  `country_code` VARCHAR(2) NULL COMMENT 'ISO country code only',
  `search_query` VARCHAR(255) NULL COMMENT 'search terms if applicable',
  `metadata` JSON NULL COMMENT 'additional event data',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_analytics_event_type` (`event_type`),
  INDEX `idx_analytics_category` (`event_category`),
  INDEX `idx_analytics_action` (`event_action`),
  INDEX `idx_analytics_created_at` (`created_at`),
  INDEX `idx_analytics_session` (`session_hash`),
  INDEX `idx_analytics_user` (`user_id`),
  INDEX `idx_analytics_ip` (`ip_hash`)
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
-- Insert Initial Data
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

-- Insert sample lesson (optional - remove if not needed)
INSERT INTO `lessons` (
  `title`, 
  `slug`, 
  `excerpt`, 
  `scripture`, 
  `theme`, 
  `age_group`, 
  `duration`, 
  `content`, 
  `category`, 
  `difficulty`, 
  `status`, 
  `published_at`,
  `created_at`,
  `updated_at`
) VALUES (
  'Welcome to Sunday School',
  'welcome-to-sunday-school',
  'An introductory lesson about God\'s love and the importance of learning together.',
  'John 3:16',
  'God\'s Love',
  'Children (5-8)',
  30,
  '<h2>Introduction</h2><p>Welcome to our Sunday School! Today we will learn about God\'s amazing love for us.</p><h2>Main Teaching</h2><p>God loves each and every one of us so much that He sent His son Jesus to save us.</p><h2>Activity</h2><p>Draw a picture of something that shows God\'s love.</p>',
  'Introductory',
  'beginner',
  'published',
  NOW(),
  NOW(),
  NOW()
);

-- Insert sample blog post (optional - remove if not needed)
INSERT INTO `blog_posts` (
  `title`,
  `slug`,
  `excerpt`,
  `content`,
  `author`,
  `category`,
  `status`,
  `published_at`,
  `created_at`,
  `updated_at`
) VALUES (
  'Welcome to Our Sunday School Blog',
  'welcome-to-our-blog',
  'Learn about our mission to provide quality Sunday School resources for teachers and students.',
  '<h2>Our Mission</h2><p>We are dedicated to providing high-quality Sunday School resources that help teachers effectively share God\'s word with students of all ages.</p><h2>What You\'ll Find Here</h2><ul><li>Lesson plans and teaching resources</li><li>Activity ideas and printables</li><li>Tips for effective teaching</li><li>Inspirational stories</li></ul>',
  'Admin Team',
  'Announcements',
  'published',
  NOW(),
  NOW(),
  NOW()
);

-- Insert sample events
INSERT INTO `events` (`title`, `description`, `event_date`, `event_type`, `color`, `icon`, `is_featured`, `display_order`) VALUES
('Christmas Day', 'Celebration of the birth of Jesus Christ', '2024-12-25', 'holiday', '#dc3545', 'gifts', 1, 1),
('Easter Sunday', 'Celebration of the resurrection of Jesus Christ', '2024-03-31', 'holiday', '#ffc107', 'cross', 1, 2),
('Good Friday', 'Commemoration of the crucifixion of Jesus Christ', '2024-03-29', 'holiday', '#6f42c1', 'cross', 1, 3),
('Pentecost', 'Celebration of the Holy Spirit descending upon the apostles', '2024-05-19', 'holiday', '#dc3545', 'dove', 0, 4),
('Advent Season Begins', 'Beginning of the Christmas season preparation', '2024-12-01', 'seasonal', '#6f42c1', 'calendar-star', 1, 5);

-- Insert sample teaching tips
INSERT INTO `teaching_tips` (`title`, `content`, `icon`, `category`, `is_active`, `display_order`) VALUES
('Use Visual Aids', 'Children learn better when they can see what you''re teaching. Use pictures, props, and visual demonstrations to make Bible stories come alive.', 'eye', 'Engagement', 1, 1),
('Keep It Interactive', 'Ask questions throughout your lesson to keep children engaged. Use "What do you think?" and "How would you feel?" to encourage participation.', 'comments', 'Engagement', 1, 2),
('Prepare for Different Ages', 'If you have mixed age groups, prepare activities for both younger and older children. Have simple coloring for little ones and discussion questions for older kids.', 'users', 'Planning', 1, 3),
('Use Movement and Actions', 'Children love to move! Incorporate hand motions, acting out Bible stories, or simple games to help them remember the lesson.', 'running', 'Activities', 1, 4),
('Connect to Their World', 'Help children understand Bible stories by connecting them to their everyday experiences. Use examples they can relate to from school, home, or play.', 'heart', 'Connection', 1, 5),
('Practice Your Lesson', 'Run through your lesson at least once before teaching. This helps you feel confident and identify any areas that need adjustment.', 'clock', 'Preparation', 1, 6),
('Have a Backup Plan', 'Always prepare an extra activity or story in case your lesson finishes early or if something doesn''t work as planned.', 'shield-alt', 'Preparation', 1, 7),
('Use Positive Reinforcement', 'Praise children for good behavior and participation. Stickers, high-fives, or simple "Great job!" comments go a long way.', 'star', 'Classroom Management', 1, 8);

-- Insert sample resources
INSERT INTO `resources` (`title`, `description`, `type`, `file_url`, `thumbnail`, `category`, `age_group`, `file_size`, `file_type`, `downloads_count`, `is_featured`) VALUES
('David and Goliath Coloring Page', 'A beautiful coloring page depicting the story of David and Goliath, perfect for young children to color while learning about courage and faith.', 'coloring_page', '/storage/resources/david-goliath-coloring.pdf', '/storage/thumbnails/david-goliath-thumb.jpg', 'Old Testament', '3-8 years', 245760, 'PDF', 156, 1),
('Noah''s Ark Activity Worksheet', 'Interactive worksheet with puzzles, word searches, and questions about Noah''s Ark story. Great for reinforcing the lesson.', 'worksheet', '/storage/resources/noahs-ark-worksheet.pdf', '/storage/thumbnails/noahs-ark-thumb.jpg', 'Old Testament', '6-12 years', 512000, 'PDF', 203, 1),
('Christmas Nativity Craft Guide', 'Step-by-step instructions for creating a simple nativity scene craft using common household items.', 'craft', '/storage/resources/nativity-craft-guide.pdf', '/storage/thumbnails/nativity-craft-thumb.jpg', 'Christmas', '5-12 years', 1024000, 'PDF', 89, 1),
('Bible Memory Verse Cards', 'Printable memory verse cards with popular Bible verses for children, including illustrations and simple explanations.', 'activity_guide', '/storage/resources/memory-verse-cards.pdf', '/storage/thumbnails/memory-cards-thumb.jpg', 'Memory Verses', 'All Ages', 768000, 'PDF', 312, 0),
('Easter Story Sequencing Game', 'Cut-out cards that children can arrange in order to tell the Easter story. Includes teacher instructions.', 'game', '/storage/resources/easter-sequencing-game.pdf', '/storage/thumbnails/easter-game-thumb.jpg', 'Easter', '4-10 years', 456000, 'PDF', 127, 0),
('Ten Commandments Worksheet', 'Educational worksheet helping children learn and understand the Ten Commandments with age-appropriate explanations.', 'worksheet', '/storage/resources/ten-commandments-worksheet.pdf', '/storage/thumbnails/ten-commandments-thumb.jpg', 'Old Testament', '8-14 years', 345600, 'PDF', 178, 0);

-- Insert sample analytics data (privacy-compliant with hashed IPs and country codes only)
INSERT INTO `analytics` (`event_type`, `event_category`, `event_action`, `event_label`, `page_url`, `device_type`, `browser`, `operating_system`, `country_code`, `search_query`, `ip_hash`, `session_hash`, `created_at`) VALUES
('page_view', 'lessons', 'view', 'homepage', '/', 'desktop', 'Chrome', 'Windows', 'US', 'christmas lessons', 'a1b2c3d4e5f6...', 'x1y2z3a4b5c6...', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('lesson_view', 'lessons', 'view', 'david-and-goliath', '/lessons/1', 'mobile', 'Safari', 'iOS', 'CA', NULL, 'b2c3d4e5f6a1...', 'y2z3a4b5c6x1...', DATE_SUB(NOW(), INTERVAL 2 HOURS)),
('resource_download', 'resources', 'download', 'coloring-page-1', '/resources/1/download', 'desktop', 'Firefox', 'Windows', 'GB', NULL, 'c3d4e5f6a1b2...', 'z3a4b5c6x1y2...', DATE_SUB(NOW(), INTERVAL 30 MINUTES)),
('search', 'lessons', 'search', 'christmas lessons', '/lessons', 'tablet', 'Chrome', 'Android', 'AU', 'christmas lessons', 'd4e5f6a1b2c3...', 'a4b5c6x1y2z3...', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('page_view', 'blog', 'view', 'teaching-tips', '/blog', 'desktop', 'Chrome', 'macOS', 'US', NULL, 'e5f6a1b2c3d4...', 'b5c6x1y2z3a4...', DATE_SUB(NOW(), INTERVAL 3 HOURS)),
('lesson_view', 'lessons', 'view', 'noahs-ark', '/lessons/2', 'mobile', 'Chrome', 'Android', 'DE', NULL, 'f6a1b2c3d4e5...', 'c6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 45 MINUTES)),
('page_view', 'resources', 'view', 'resources-index', '/resources', 'desktop', 'Edge', 'Windows', 'FR', NULL, 'a1b2c3d4e5f6...', 'd6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 2 HOURS)),
('resource_download', 'resources', 'download', 'worksheet-2', '/resources/2/download', 'desktop', 'Chrome', 'Windows', 'US', NULL, 'b1c2d3e4f5a6...', 'e6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 15 MINUTES));

-- Insert migration records
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2024_12_06_000001_create_telegram_raw_imports_table', 1),
('2024_12_08_000001_add_attachments_to_lessons_table', 1),
('2024_12_08_000002_create_admin_activities_table', 1),
('2025_12_09_160551_create_events_table', 4),
('2025_12_09_160829_create_teaching_tips_table', 4),
('2025_12_09_161024_create_resources_table', 4),
('2025_12_09_161230_create_newsletters_table', 4),
('2025_12_11_000001_create_analytics_table', 5);

-- ============================================
-- Enable foreign key checks
-- ============================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- Verification Queries
-- ============================================

-- Show all tables
-- SHOW TABLES;

-- Count records in each table
-- SELECT 'admin_users' as table_name, COUNT(*) as count FROM admin_users
-- UNION ALL
-- SELECT 'lessons', COUNT(*) FROM lessons
-- UNION ALL
-- SELECT 'blog_posts', COUNT(*) FROM blog_posts
-- UNION ALL
-- SELECT 'events', COUNT(*) FROM events
-- UNION ALL
-- SELECT 'teaching_tips', COUNT(*) FROM teaching_tips
-- UNION ALL
-- SELECT 'resources', COUNT(*) FROM resources
-- UNION ALL
-- SELECT 'newsletters', COUNT(*) FROM newsletters
-- UNION ALL
-- SELECT 'analytics', COUNT(*) FROM analytics
-- UNION ALL
-- SELECT 'telegram_raw_imports', COUNT(*) FROM telegram_raw_imports
-- UNION ALL
-- SELECT 'admin_activities', COUNT(*) FROM admin_activities;

-- ============================================
-- IMPORTANT NOTES
-- ============================================
-- 1. Default admin credentials:
--    Email: admin@sundaylearn.com
--    Password: password
--    CHANGE THIS IMMEDIATELY AFTER FIRST LOGIN!
--
-- 2. Password hash for "password":
--    $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
--
-- 3. To create new admin users, use this query:
--    INSERT INTO admin_users (name, email, password, role, is_active)
--    VALUES ('Name', 'email@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
--
-- 4. To change a password, use this query:
--    UPDATE admin_users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@sundaylearn.com';
--
-- 5. Database size: ~5-10MB with sample data
--    InfinityFree limit: 400MB (plenty of space!)
-- ============================================
