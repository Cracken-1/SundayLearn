-- ============================================
-- ANALYTICS TABLE SCHEMA
-- ============================================
-- Table for tracking website analytics and user interactions

CREATE TABLE `analytics` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'page_view, lesson_view, resource_download, search, etc.',
  `event_category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'lessons, resources, blog, etc.',
  `event_action` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'view, download, search, click, etc.',
  `event_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'specific item identifier',
  `page_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'current page URL',
  `referrer_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'referring page URL',
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'browser user agent',
  `ip_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hashed IP for privacy (SHA-256)',
  `session_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hashed session ID for privacy',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'authenticated user ID (optional)',
  `device_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'desktop, mobile, tablet',
  `browser` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'browser name (general)',
  `operating_system` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'OS name (general)',
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ISO country code only',
  `search_query` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'search terms if applicable',
  `metadata` json DEFAULT NULL COMMENT 'additional event data',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_analytics_event_type` (`event_type`),
  KEY `idx_analytics_category` (`event_category`),
  KEY `idx_analytics_action` (`event_action`),
  KEY `idx_analytics_created_at` (`created_at`),
  KEY `idx_analytics_session` (`session_hash`),
  KEY `idx_analytics_user` (`user_id`),
  KEY `idx_analytics_ip` (`ip_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample analytics data (privacy-compliant with hashed IPs and country codes only)
INSERT INTO `analytics` (`event_type`, `event_category`, `event_action`, `event_label`, `page_url`, `device_type`, `browser`, `operating_system`, `country_code`, `search_query`, `ip_hash`, `session_hash`, `created_at`) VALUES
('page_view', 'lessons', 'view', 'homepage', '/', 'desktop', 'Chrome', 'Windows', 'US', 'christmas lessons', 'a1b2c3d4e5f6...', 'x1y2z3a4b5c6...', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('lesson_view', 'lessons', 'view', 'david-and-goliath', '/lessons/1', 'mobile', 'Safari', 'iOS', 'CA', NULL, 'b2c3d4e5f6a1...', 'y2z3a4b5c6x1...', DATE_SUB(NOW(), INTERVAL 2 HOURS)),
('resource_download', 'resources', 'download', 'coloring-page-1', '/resources/1/download', 'desktop', 'Firefox', 'Windows', 'GB', NULL, 'c3d4e5f6a1b2...', 'z3a4b5c6x1y2...', DATE_SUB(NOW(), INTERVAL 30 MINUTES)),
('search', 'lessons', 'search', 'christmas lessons', '/lessons', 'tablet', 'Chrome', 'Android', 'AU', 'christmas lessons', 'd4e5f6a1b2c3...', 'a4b5c6x1y2z3...', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('page_view', 'blog', 'view', 'teaching-tips', '/blog', 'desktop', 'Chrome', 'macOS', 'US', NULL, 'e5f6a1b2c3d4...', 'b5c6x1y2z3a4...', DATE_SUB(NOW(), INTERVAL 3 HOURS)),
('lesson_view', 'lessons', 'view', 'noahs-ark', '/lessons/2', 'mobile', 'Chrome', 'Android', 'DE', NULL, 'f6a1b2c3d4e5...', 'c6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 45 MINUTES)),
('page_view', 'resources', 'view', 'resources-index', '/resources', 'desktop', 'Edge', 'Windows', 'FR', NULL, 'a1b2c3d4e5f6...', 'd6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 2 HOURS)),
('resource_download', 'resources', 'download', 'worksheet-2', '/resources/2/download', 'desktop', 'Chrome', 'Windows', 'US', NULL, 'b1c2d3e4f5a6...', 'e6x1y2z3a4b5...', DATE_SUB(NOW(), INTERVAL 15 MINUTES));