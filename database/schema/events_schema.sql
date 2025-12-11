-- ============================================
-- EVENTS TABLE SCHEMA
-- ============================================
-- Table for managing church events, holidays, and special occasions

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_type` enum('holiday','special','seasonal','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#dc3545',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'calendar',
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

-- Sample data for events
INSERT INTO `events` (`title`, `description`, `event_date`, `event_type`, `color`, `icon`, `is_featured`, `display_order`) VALUES
('Christmas Day', 'Celebration of the birth of Jesus Christ', '2024-12-25', 'holiday', '#dc3545', 'gifts', 1, 1),
('Easter Sunday', 'Celebration of the resurrection of Jesus Christ', '2024-03-31', 'holiday', '#ffc107', 'cross', 1, 2),
('Good Friday', 'Commemoration of the crucifixion of Jesus Christ', '2024-03-29', 'holiday', '#6f42c1', 'cross', 1, 3),
('Pentecost', 'Celebration of the Holy Spirit descending upon the apostles', '2024-05-19', 'holiday', '#dc3545', 'dove', 0, 4),
('Advent Season Begins', 'Beginning of the Christmas season preparation', '2024-12-01', 'seasonal', '#6f42c1', 'calendar-star', 1, 5);