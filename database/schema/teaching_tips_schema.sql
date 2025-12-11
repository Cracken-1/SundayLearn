-- ============================================
-- TEACHING TIPS TABLE SCHEMA
-- ============================================
-- Table for storing helpful teaching tips and advice for Sunday school teachers

CREATE TABLE `teaching_tips` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lightbulb',
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_teaching_tips_active` (`is_active`),
  KEY `idx_teaching_tips_category` (`category`),
  KEY `idx_teaching_tips_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for teaching tips
INSERT INTO `teaching_tips` (`title`, `content`, `icon`, `category`, `is_active`, `display_order`) VALUES
('Use Visual Aids', 'Children learn better when they can see what you''re teaching. Use pictures, props, and visual demonstrations to make Bible stories come alive.', 'eye', 'Engagement', 1, 1),
('Keep It Interactive', 'Ask questions throughout your lesson to keep children engaged. Use "What do you think?" and "How would you feel?" to encourage participation.', 'comments', 'Engagement', 1, 2),
('Prepare for Different Ages', 'If you have mixed age groups, prepare activities for both younger and older children. Have simple coloring for little ones and discussion questions for older kids.', 'users', 'Planning', 1, 3),
('Use Movement and Actions', 'Children love to move! Incorporate hand motions, acting out Bible stories, or simple games to help them remember the lesson.', 'running', 'Activities', 1, 4),
('Connect to Their World', 'Help children understand Bible stories by connecting them to their everyday experiences. Use examples they can relate to from school, home, or play.', 'heart', 'Connection', 1, 5),
('Practice Your Lesson', 'Run through your lesson at least once before teaching. This helps you feel confident and identify any areas that need adjustment.', 'clock', 'Preparation', 1, 6),
('Have a Backup Plan', 'Always prepare an extra activity or story in case your lesson finishes early or if something doesn''t work as planned.', 'shield-alt', 'Preparation', 1, 7),
('Use Positive Reinforcement', 'Praise children for good behavior and participation. Stickers, high-fives, or simple "Great job!" comments go a long way.', 'star', 'Classroom Management', 1, 8);