-- ============================================
-- RESOURCES TABLE SCHEMA
-- ============================================
-- Table for managing downloadable teaching resources like worksheets, coloring pages, etc.

CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('worksheet','coloring_page','activity_guide','craft','game','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

-- Sample data for resources
INSERT INTO `resources` (`title`, `description`, `type`, `file_url`, `thumbnail`, `category`, `age_group`, `file_size`, `file_type`, `downloads_count`, `is_featured`) VALUES
('David and Goliath Coloring Page', 'A beautiful coloring page depicting the story of David and Goliath, perfect for young children to color while learning about courage and faith.', 'coloring_page', '/storage/resources/david-goliath-coloring.pdf', '/storage/thumbnails/david-goliath-thumb.jpg', 'Old Testament', '3-8 years', 245760, 'PDF', 156, 1),
('Noah''s Ark Activity Worksheet', 'Interactive worksheet with puzzles, word searches, and questions about Noah''s Ark story. Great for reinforcing the lesson.', 'worksheet', '/storage/resources/noahs-ark-worksheet.pdf', '/storage/thumbnails/noahs-ark-thumb.jpg', 'Old Testament', '6-12 years', 512000, 'PDF', 203, 1),
('Christmas Nativity Craft Guide', 'Step-by-step instructions for creating a simple nativity scene craft using common household items.', 'craft', '/storage/resources/nativity-craft-guide.pdf', '/storage/thumbnails/nativity-craft-thumb.jpg', 'Christmas', '5-12 years', 1024000, 'PDF', 89, 1),
('Bible Memory Verse Cards', 'Printable memory verse cards with popular Bible verses for children, including illustrations and simple explanations.', 'activity_guide', '/storage/resources/memory-verse-cards.pdf', '/storage/thumbnails/memory-cards-thumb.jpg', 'Memory Verses', 'All Ages', 768000, 'PDF', 312, 0),
('Easter Story Sequencing Game', 'Cut-out cards that children can arrange in order to tell the Easter story. Includes teacher instructions.', 'game', '/storage/resources/easter-sequencing-game.pdf', '/storage/thumbnails/easter-game-thumb.jpg', 'Easter', '4-10 years', 456000, 'PDF', 127, 0),
('Ten Commandments Worksheet', 'Educational worksheet helping children learn and understand the Ten Commandments with age-appropriate explanations.', 'worksheet', '/storage/resources/ten-commandments-worksheet.pdf', '/storage/thumbnails/ten-commandments-thumb.jpg', 'Old Testament', '8-14 years', 345600, 'PDF', 178, 0);