-- ============================================
-- SUNDAY LEARN PLATFORM - COMPLETE DATABASE SCHEMA
-- ============================================
-- This file contains the complete database schema for the Sunday Learn platform
-- Generated on: December 11, 2025
-- ============================================

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- LESSONS TABLE
-- ============================================
CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `scripture` varchar(255) DEFAULT NULL,
  `age_group` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `materials` text DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `discussion_questions` text DEFAULT NULL,
  `memory_verse` text DEFAULT NULL,
  `prayer` text DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `thumbnail` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `audio_url` varchar(500) DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lessons_status` (`status`),
  KEY `idx_lessons_featured` (`featured`),
  KEY `idx_lessons_age_group` (`age_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOG POSTS TABLE
-- ============================================
CREATE TABLE `blog_posts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `idx_blog_posts_status` (`status`),
  KEY `idx_blog_posts_featured` (`featured`),
  KEY `idx_blog_posts_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- EVENTS TABLE
-- ============================================
CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_type` enum('holiday','special','seasonal','other') NOT NULL DEFAULT 'other',
  `color` varchar(7) NOT NULL DEFAULT '#dc3545',
  `icon` varchar(50) NOT NULL DEFAULT 'calendar',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_events_date` (`event_date`),
  KEY `idx_events_type` (`event_type`),
  KEY `idx_events_featured` (`is_featured`),
  KEY `idx_events_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TEACHING TIPS TABLE
-- ============================================
CREATE TABLE `teaching_tips` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'lightbulb',
  `category` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_teaching_tips_active` (`is_active`),
  KEY `idx_teaching_tips_category` (`category`),
  KEY `idx_teaching_tips_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- RESOURCES TABLE
-- ============================================
CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('worksheet','coloring_page','activity_guide','craft','game','other') NOT NULL DEFAULT 'other',
  `file_url` varchar(500) NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `downloads_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_resources_type` (`type`),
  KEY `idx_resources_category` (`category`),
  KEY `idx_resources_age_group` (`age_group`),
  KEY `idx_resources_featured` (`is_featured`),
  KEY `idx_resources_downloads` (`downloads_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- NEWSLETTERS TABLE
-- ============================================
CREATE TABLE `newsletters` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL UNIQUE,
  `name` varchar(255) DEFAULT NULL,
  `status` enum('subscribed','unsubscribed','bounced') NOT NULL DEFAULT 'subscribed',
  `unsubscribe_token` varchar(100) UNIQUE DEFAULT NULL,
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletters_email_unique` (`email`),
  UNIQUE KEY `newsletters_unsubscribe_token_unique` (`unsubscribe_token`),
  KEY `idx_newsletters_status` (`status`),
  KEY `idx_newsletters_subscribed_at` (`subscribed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TELEGRAM RAW IMPORTS TABLE
-- ============================================
CREATE TABLE `telegram_raw_imports` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` bigint(20) NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `message_text` longtext DEFAULT NULL,
  `message_type` varchar(50) NOT NULL DEFAULT 'text',
  `file_path` varchar(500) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `media_group_id` varchar(100) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `raw_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_telegram_message_id` (`message_id`),
  KEY `idx_telegram_processed` (`processed`),
  KEY `idx_telegram_lesson_id` (`lesson_id`),
  KEY `idx_telegram_media_group` (`media_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ADMIN ACTIVITIES TABLE
-- ============================================
CREATE TABLE `admin_activities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `model_type` varchar(100) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_admin_activities_action` (`action`),
  KEY `idx_admin_activities_model` (`model_type`, `model_id`),
  KEY `idx_admin_activities_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PERSONAL ACCESS TOKENS TABLE (Laravel Sanctum)
-- ============================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL UNIQUE,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SAMPLE DATA INSERTS
-- ============================================

-- Sample Events
INSERT INTO `events` (`title`, `description`, `event_date`, `event_type`, `color`, `icon`, `is_featured`, `display_order`) VALUES
('Christmas Day', 'Celebrate the birth of Jesus Christ', '2025-12-25', 'holiday', '#dc3545', 'gifts', 1, 1),
('Easter Sunday', 'Resurrection of Jesus Christ', '2025-04-20', 'holiday', '#ffc107', 'cross', 1, 2),
('Good Friday', 'Commemoration of the crucifixion of Jesus', '2025-04-18', 'holiday', '#6f42c1', 'cross', 0, 3),
('Pentecost', 'Coming of the Holy Spirit', '2025-06-08', 'holiday', '#dc3545', 'dove', 0, 4),
('Advent Season Begins', 'Preparation for Christmas', '2025-11-30', 'seasonal', '#6f42c1', 'calendar-star', 1, 5);

-- Sample Teaching Tips
INSERT INTO `teaching_tips` (`title`, `content`, `icon`, `category`, `is_active`, `display_order`) VALUES
('Use Visual Aids', 'Children learn better when they can see what you''re teaching. Use pictures, props, and visual demonstrations to make Bible stories come alive.', 'eye', 'Visual Learning', 1, 1),
('Keep It Interactive', 'Ask questions throughout your lesson to keep children engaged. Let them participate in the story by acting out parts or making sound effects.', 'users', 'Engagement', 1, 2),
('Repeat Key Points', 'Children need to hear important concepts multiple times. Repeat your main lesson point in different ways throughout the class.', 'repeat', 'Reinforcement', 1, 3),
('Use Age-Appropriate Language', 'Adjust your vocabulary and concepts to match your students'' developmental level. What works for teens won''t work for preschoolers.', 'child', 'Communication', 1, 4),
('Prepare for Different Learning Styles', 'Some children are visual learners, others are auditory, and some learn through movement. Include activities that appeal to all learning styles.', 'brain', 'Learning Styles', 1, 5);

-- Sample Resources
INSERT INTO `resources` (`title`, `description`, `type`, `file_url`, `category`, `age_group`, `file_type`, `downloads_count`, `is_featured`) VALUES
('David and Goliath Coloring Page', 'A beautiful coloring page depicting the biblical story of David and Goliath', 'coloring_page', '/resources/david-goliath-coloring.pdf', 'Old Testament', '3-8 years', 'pdf', 0, 1),
('Noah''s Ark Activity Sheet', 'Fun activities and puzzles based on Noah''s Ark story', 'worksheet', '/resources/noahs-ark-worksheet.pdf', 'Old Testament', '5-10 years', 'pdf', 0, 1),
('Christmas Craft Instructions', 'Step-by-step guide to create Christmas ornaments', 'craft', '/resources/christmas-craft-guide.pdf', 'Holidays', 'All Ages', 'pdf', 0, 1),
('Bible Memory Verse Cards', 'Printable cards with popular Bible verses for memorization', 'worksheet', '/resources/memory-verse-cards.pdf', 'Scripture Memory', 'All Ages', 'pdf', 0, 0),
('Easter Story Puppet Templates', 'Cut-out templates to create puppets for Easter story telling', 'craft', '/resources/easter-puppet-templates.pdf', 'Holidays', '4-12 years', 'pdf', 0, 1);

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

-- Additional indexes for better performance
CREATE INDEX idx_lessons_created_at ON lessons(created_at);
CREATE INDEX idx_blog_posts_created_at ON blog_posts(created_at);
CREATE INDEX idx_events_created_at ON events(created_at);
CREATE INDEX idx_teaching_tips_created_at ON teaching_tips(created_at);
CREATE INDEX idx_resources_created_at ON resources(created_at);
CREATE INDEX idx_newsletters_created_at ON newsletters(created_at);

-- ============================================
-- FOREIGN KEY CONSTRAINTS
-- ============================================

-- Add foreign key constraint for telegram imports to lessons
ALTER TABLE `telegram_raw_imports` 
ADD CONSTRAINT `fk_telegram_lesson` 
FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- VIEWS FOR COMMON QUERIES
-- ============================================

-- View for upcoming events
CREATE VIEW `upcoming_events` AS
SELECT * FROM `events` 
WHERE `event_date` >= CURDATE() 
ORDER BY `event_date` ASC, `display_order` ASC;

-- View for featured content
CREATE VIEW `featured_content` AS
SELECT 'lesson' as content_type, id, title, created_at FROM lessons WHERE featured = 1 AND status = 'published'
UNION ALL
SELECT 'blog' as content_type, id, title, created_at FROM blog_posts WHERE featured = 1 AND status = 'published'
UNION ALL
SELECT 'event' as content_type, id, title, created_at FROM events WHERE is_featured = 1
UNION ALL
SELECT 'resource' as content_type, id, title, created_at FROM resources WHERE is_featured = 1
ORDER BY created_at DESC;

-- View for active teaching tips
CREATE VIEW `active_teaching_tips` AS
SELECT * FROM `teaching_tips` 
WHERE `is_active` = 1 
ORDER BY `display_order` ASC, `created_at` DESC;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure to get dashboard statistics
DELIMITER //
CREATE PROCEDURE GetDashboardStats()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM lessons WHERE status = 'published') as published_lessons,
        (SELECT COUNT(*) FROM blog_posts WHERE status = 'published') as published_blogs,
        (SELECT COUNT(*) FROM events WHERE event_date >= CURDATE()) as upcoming_events,
        (SELECT COUNT(*) FROM resources) as total_resources,
        (SELECT COUNT(*) FROM newsletters WHERE status = 'subscribed') as newsletter_subscribers,
        (SELECT COUNT(*) FROM teaching_tips WHERE is_active = 1) as active_tips;
END //
DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger to update newsletter subscribed_at when status changes to subscribed
DELIMITER //
CREATE TRIGGER newsletter_subscription_trigger
BEFORE UPDATE ON newsletters
FOR EACH ROW
BEGIN
    IF NEW.status = 'subscribed' AND OLD.status != 'subscribed' THEN
        SET NEW.subscribed_at = NOW();
        SET NEW.unsubscribed_at = NULL;
    ELSEIF NEW.status = 'unsubscribed' AND OLD.status != 'unsubscribed' THEN
        SET NEW.unsubscribed_at = NOW();
    END IF;
END //
DELIMITER ;

-- ============================================
-- END OF SCHEMA
-- ============================================