
-- Supabase Compatible Schema

-- Table: users
CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'viewer',
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_users_role ON users (role);
COMMENT ON COLUMN users.role IS 'viewer, subscriber, contributor';

-- Table: personal_access_tokens
CREATE TABLE personal_access_tokens (
  id BIGSERIAL PRIMARY KEY,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  abilities TEXT NULL,
  last_used_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_personal_access_tokens_tokenable ON personal_access_tokens (tokenable_type, tokenable_id);

-- Table: admin_users
CREATE TABLE admin_users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  created_by BIGINT NULL,
  password_change_required BOOLEAN DEFAULT false,
  is_active BOOLEAN DEFAULT true,
  last_login_at TIMESTAMP NULL,
  last_login_ip VARCHAR(45) NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_admin_users_email ON admin_users (email);
CREATE INDEX idx_admin_users_active ON admin_users (is_active);
CREATE INDEX idx_admin_users_role ON admin_users (role);
ALTER TABLE admin_users ADD CONSTRAINT fk_admin_users_created_by FOREIGN KEY (created_by) REFERENCES admin_users (id) ON DELETE SET NULL;
COMMENT ON COLUMN admin_users.role IS 'super_admin, admin, editor';
COMMENT ON COLUMN admin_users.created_by IS 'ID of admin who created this user';
COMMENT ON COLUMN admin_users.password_change_required IS 'Force password change on next login';
COMMENT ON COLUMN admin_users.last_login_ip IS 'Last login IP address';

-- Table: lessons
CREATE TABLE lessons (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt TEXT NULL,
  scripture VARCHAR(255) NULL,
  theme VARCHAR(255) NULL,
  age_group VARCHAR(255) NULL,
  duration INT NULL,
  thumbnail TEXT NULL,
  image_url TEXT NULL,
  overview TEXT NULL,
  objectives TEXT NULL,
  content TEXT NOT NULL,
  discussion_questions TEXT NULL,
  video_url TEXT NULL,
  audio_url TEXT NULL,
  downloads TEXT NULL,
  attachments TEXT NULL,
  category VARCHAR(100) NULL,
  difficulty VARCHAR(50) NULL,
  "order" INT DEFAULT 0,
  tags TEXT NULL,
  meta_title VARCHAR(255) NULL,
  meta_description TEXT NULL,
  is_featured BOOLEAN DEFAULT false,
  views_count INT DEFAULT 0,
  last_viewed_at TIMESTAMP NULL,
  status VARCHAR(50) DEFAULT 'draft',
  published_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_lessons_slug ON lessons (slug);
CREATE INDEX idx_lessons_status ON lessons (status);
CREATE INDEX idx_lessons_category ON lessons (category);
CREATE INDEX idx_lessons_difficulty ON lessons (difficulty);
CREATE INDEX idx_lessons_published_at ON lessons (published_at);
CREATE INDEX idx_lessons_is_featured ON lessons (is_featured);
CREATE INDEX idx_lessons_age_group ON lessons (age_group);
CREATE INDEX idx_lessons_status_published ON lessons (status, published_at);
CREATE INDEX idx_lessons_featured_status ON lessons (is_featured, status);
CREATE INDEX idx_lessons_category_status ON lessons (category, status);
COMMENT ON COLUMN lessons.excerpt IS 'Short description';
COMMENT ON COLUMN lessons.scripture IS 'Bible reference';
COMMENT ON COLUMN lessons.theme IS 'Lesson theme';
COMMENT ON COLUMN lessons.age_group IS 'Target age group';
COMMENT ON COLUMN lessons.duration IS 'Duration in minutes';
COMMENT ON COLUMN lessons.thumbnail IS 'Thumbnail image path';
COMMENT ON COLUMN lessons.image_url IS 'Featured image path';
COMMENT ON COLUMN lessons.overview IS 'Lesson overview';
COMMENT ON COLUMN lessons.objectives IS 'Learning objectives (JSON format)';
COMMENT ON COLUMN lessons.content IS 'Main lesson content';
COMMENT ON COLUMN lessons.discussion_questions IS 'Discussion questions (JSON format)';
COMMENT ON COLUMN lessons.video_url IS 'YouTube/Vimeo URL';
COMMENT ON COLUMN lessons.audio_url IS 'Audio file URL';
COMMENT ON COLUMN lessons.downloads IS 'Downloadable resources (JSON format)';
COMMENT ON COLUMN lessons.attachments IS 'File attachments including video/audio (JSON format)';
COMMENT ON COLUMN lessons.category IS 'Lesson category';
COMMENT ON COLUMN lessons.difficulty IS 'beginner, intermediate, advanced';
COMMENT ON COLUMN lessons.order IS 'Display order';
COMMENT ON COLUMN lessons.tags IS 'Tags (JSON format)';
COMMENT ON COLUMN lessons.meta_title IS 'SEO meta title';
COMMENT ON COLUMN lessons.meta_description IS 'SEO meta description';
COMMENT ON COLUMN lessons.is_featured IS 'Featured lesson flag';
COMMENT ON COLUMN lessons.views_count IS 'Number of views';
COMMENT ON COLUMN lessons.last_viewed_at IS 'Last view timestamp';
COMMENT ON COLUMN lessons.status IS 'draft, published';
COMMENT ON COLUMN lessons.published_at IS 'Publication date';

-- Table: blog_posts
CREATE TABLE blog_posts (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt TEXT NULL,
  content TEXT NOT NULL,
  author VARCHAR(255) NULL,
  image_url TEXT NULL,
  category VARCHAR(100) NULL,
  tags TEXT NULL,
  meta_title VARCHAR(255) NULL,
  meta_description TEXT NULL,
  is_featured BOOLEAN DEFAULT false,
  views_count INT DEFAULT 0,
  status VARCHAR(50) DEFAULT 'draft',
  published_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_blog_posts_slug ON blog_posts (slug);
CREATE INDEX idx_blog_posts_status ON blog_posts (status);
CREATE INDEX idx_blog_posts_category ON blog_posts (category);
CREATE INDEX idx_blog_posts_published_at ON blog_posts (published_at);
CREATE INDEX idx_blog_posts_is_featured ON blog_posts (is_featured);
CREATE INDEX idx_blog_posts_status_published ON blog_posts (status, published_at);
CREATE INDEX idx_blog_posts_featured_status ON blog_posts (is_featured, status);
COMMENT ON COLUMN blog_posts.excerpt IS 'Short description';
COMMENT ON COLUMN blog_posts.content IS 'Blog post content';
COMMENT ON COLUMN blog_posts.author IS 'Author name';
COMMENT ON COLUMN blog_posts.image_url IS 'Featured image path';
COMMENT ON COLUMN blog_posts.category IS 'Blog category';
COMMENT ON COLUMN blog_posts.tags IS 'Tags (JSON format)';
COMMENT ON COLUMN blog_posts.meta_title IS 'SEO meta title';
COMMENT ON COLUMN blog_posts.meta_description IS 'SEO meta description';
COMMENT ON COLUMN blog_posts.is_featured IS 'Featured post flag';
COMMENT ON COLUMN blog_posts.views_count IS 'Number of views';
COMMENT ON COLUMN blog_posts.status IS 'draft, published';
COMMENT ON COLUMN blog_posts.published_at IS 'Publication date';

-- Table: telegram_raw_imports
CREATE TABLE telegram_raw_imports (
  id BIGSERIAL PRIMARY KEY,
  telegram_message_id BIGINT NULL,
  chat_id BIGINT NULL,
  user_id BIGINT NULL,
  username VARCHAR(255) NULL,
  message_type VARCHAR(50) NULL,
  text_content TEXT NULL,
  caption TEXT NULL,
  media_type VARCHAR(50) NULL,
  file_id TEXT NULL,
  file_unique_id TEXT NULL,
  file_path TEXT NULL,
  file_size BIGINT NULL,
  mime_type VARCHAR(100) NULL,
  media_group_id VARCHAR(255) NULL,
  raw_data TEXT NULL,
  processing_status VARCHAR(50) DEFAULT 'pending',
  processed_at TIMESTAMP NULL,
  error_message TEXT NULL,
  lesson_id BIGINT NULL,
  created_lesson_id BIGINT NULL,
  created_blog_id BIGINT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_telegram_imports_status ON telegram_raw_imports (processing_status);
CREATE INDEX idx_telegram_imports_message_id ON telegram_raw_imports (telegram_message_id);
CREATE INDEX idx_telegram_imports_media_group ON telegram_raw_imports (media_group_id);
CREATE INDEX idx_telegram_imports_created_at ON telegram_raw_imports (created_at);
CREATE INDEX idx_telegram_imports_user_id ON telegram_raw_imports (user_id);
CREATE INDEX idx_telegram_imports_lesson_id ON telegram_raw_imports (lesson_id);
ALTER TABLE telegram_raw_imports ADD CONSTRAINT fk_telegram_imports_lesson_id FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE SET NULL;
COMMENT ON COLUMN telegram_raw_imports.telegram_message_id IS 'Telegram message ID';
COMMENT ON COLUMN telegram_raw_imports.chat_id IS 'Telegram chat ID';
COMMENT ON COLUMN telegram_raw_imports.user_id IS 'Telegram user ID';
COMMENT ON COLUMN telegram_raw_imports.username IS 'Telegram username';
COMMENT ON COLUMN telegram_raw_imports.message_type IS 'text, photo, video, document';
COMMENT ON COLUMN telegram_raw_imports.text_content IS 'Message text';
COMMENT ON COLUMN telegram_raw_imports.caption IS 'Media caption';
COMMENT ON COLUMN telegram_raw_imports.media_type IS 'photo, video, audio, document';
COMMENT ON COLUMN telegram_raw_imports.file_id IS 'Telegram file ID';
COMMENT ON COLUMN telegram_raw_imports.file_unique_id IS 'Telegram unique file ID';
COMMENT ON COLUMN telegram_raw_imports.file_path IS 'Downloaded file path';
COMMENT ON COLUMN telegram_raw_imports.file_size IS 'File size in bytes';
COMMENT ON COLUMN telegram_raw_imports.mime_type IS 'File MIME type';
COMMENT ON COLUMN telegram_raw_imports.media_group_id IS 'Media group ID for albums';
COMMENT ON COLUMN telegram_raw_imports.raw_data IS 'Complete Telegram update (JSON format)';
COMMENT ON COLUMN telegram_raw_imports.processing_status IS 'pending, processing, completed, failed';
COMMENT ON COLUMN telegram_raw_imports.processed_at IS 'Processing completion time';
COMMENT ON COLUMN telegram_raw_imports.error_message IS 'Error message if failed';
COMMENT ON COLUMN telegram_raw_imports.lesson_id IS 'Associated lesson ID';
COMMENT ON COLUMN telegram_raw_imports.created_lesson_id IS 'Created lesson ID';
COMMENT ON COLUMN telegram_raw_imports.created_blog_id IS 'Created blog post ID';

-- Table: admin_activities
CREATE TABLE admin_activities (
  id BIGSERIAL PRIMARY KEY,
  action VARCHAR(100) NOT NULL,
  model_type VARCHAR(255) NULL,
  model_id BIGINT NULL,
  description TEXT NOT NULL,
  properties TEXT NULL,
  ip_address VARCHAR(45) NULL,
  user_agent TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_admin_activities_model ON admin_activities (model_type, model_id);
CREATE INDEX idx_admin_activities_action ON admin_activities (action);
CREATE INDEX idx_admin_activities_created_at ON admin_activities (created_at);
COMMENT ON COLUMN admin_activities.action IS 'created, updated, deleted, published';
COMMENT ON COLUMN admin_activities.model_type IS 'Model class name';
COMMENT ON COLUMN admin_activities.model_id IS 'Model record ID';
COMMENT ON COLUMN admin_activities.description IS 'Activity description';
COMMENT ON COLUMN admin_activities.properties IS 'Additional data (JSON format)';
COMMENT ON COLUMN admin_activities.ip_address IS 'User IP address';
COMMENT ON COLUMN admin_activities.user_agent IS 'User agent string';

-- Table: events
CREATE TABLE events (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  event_date DATE NOT NULL,
  event_type VARCHAR(255) NOT NULL DEFAULT 'other',
  color VARCHAR(7) NOT NULL DEFAULT '#dc3545',
  icon VARCHAR(50) NOT NULL DEFAULT 'calendar',
  is_featured BOOLEAN NOT NULL DEFAULT false,
  display_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_events_date ON events (event_date);
CREATE INDEX idx_events_type ON events (event_type);
CREATE INDEX idx_events_featured ON events (is_featured);
CREATE INDEX idx_events_display_order ON events (display_order);

-- Table: teaching_tips
CREATE TABLE teaching_tips (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  icon VARCHAR(50) NOT NULL DEFAULT 'lightbulb',
  category VARCHAR(100) NULL,
  is_active BOOLEAN NOT NULL DEFAULT true,
  display_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_teaching_tips_active ON teaching_tips (is_active);
CREATE INDEX idx_teaching_tips_category ON teaching_tips (category);
CREATE INDEX idx_teaching_tips_display_order ON teaching_tips (display_order);

-- Table: resources
CREATE TABLE resources (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  type VARCHAR(255) NOT NULL DEFAULT 'other',
  file_url TEXT NOT NULL,
  thumbnail TEXT NULL,
  category VARCHAR(100) NULL,
  age_group VARCHAR(50) NULL,
  file_size INT NULL,
  file_type VARCHAR(50) NULL,
  downloads_count INT NOT NULL DEFAULT 0,
  is_featured BOOLEAN NOT NULL DEFAULT false,
  lesson_id BIGINT NULL,
  source VARCHAR(50) DEFAULT 'uploaded',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_resources_type ON resources (type);
CREATE INDEX idx_resources_category ON resources (category);
CREATE INDEX idx_resources_age_group ON resources (age_group);
CREATE INDEX idx_resources_featured ON resources (is_featured);
CREATE INDEX idx_resources_downloads ON resources (downloads_count);
CREATE INDEX idx_resources_type_featured ON resources (type, is_featured);
CREATE INDEX idx_resources_age_type ON resources (age_group, type);
CREATE INDEX idx_resources_lesson_id ON resources (lesson_id);
ALTER TABLE resources ADD CONSTRAINT fk_resources_lesson_id FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE SET NULL;
COMMENT ON COLUMN resources.lesson_id IS 'Linked lesson ID';
COMMENT ON COLUMN resources.source IS 'uploaded, lesson_attachment';

-- Table: newsletters
CREATE TABLE newsletters (
  id BIGSERIAL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(255) NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'subscribed',
  unsubscribe_token VARCHAR(100) UNIQUE NULL,
  subscribed_at TIMESTAMP NULL,
  unsubscribed_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_newsletters_email ON newsletters (email);
CREATE INDEX idx_newsletters_status ON newsletters (status);
CREATE INDEX idx_newsletters_token ON newsletters (unsubscribe_token);

-- Table: analytics
CREATE TABLE analytics (
  id BIGSERIAL PRIMARY KEY,
  event_type VARCHAR(50) NOT NULL,
  event_category VARCHAR(100) NULL,
  event_action VARCHAR(100) NULL,
  event_label VARCHAR(255) NULL,
  page_url TEXT NULL,
  referrer_url TEXT NULL,
  user_agent TEXT NULL,
  ip_hash VARCHAR(64) NULL,
  session_hash VARCHAR(64) NULL,
  user_id BIGINT NULL,
  device_type VARCHAR(50) NULL,
  browser VARCHAR(100) NULL,
  operating_system VARCHAR(100) NULL,
  country_code VARCHAR(2) NULL,
  search_query VARCHAR(255) NULL,
  metadata TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_analytics_event_type ON analytics (event_type);
CREATE INDEX idx_analytics_category ON analytics (event_category);
CREATE INDEX idx_analytics_action ON analytics (event_action);
CREATE INDEX idx_analytics_created_at ON analytics (created_at);
CREATE INDEX idx_analytics_session ON analytics (session_hash);
CREATE INDEX idx_analytics_user ON analytics (user_id);
CREATE INDEX idx_analytics_ip ON analytics (ip_hash);
CREATE INDEX idx_analytics_type_date ON analytics (event_type, created_at);
CREATE INDEX idx_analytics_category_date ON analytics (event_category, created_at);
COMMENT ON COLUMN analytics.event_type IS 'page_view, lesson_view, resource_download, search, etc.';
COMMENT ON COLUMN analytics.event_category IS 'lessons, resources, blog, etc.';
COMMENT ON COLUMN analytics.event_action IS 'view, download, search, click, etc.';
COMMENT ON COLUMN analytics.event_label IS 'specific item identifier';
COMMENT ON COLUMN analytics.page_url IS 'current page URL';
COMMENT ON COLUMN analytics.referrer_url IS 'referring page URL';
COMMENT ON COLUMN analytics.user_agent IS 'browser user agent';
COMMENT ON COLUMN analytics.ip_hash IS 'hashed IP for privacy (SHA-256)';
COMMENT ON COLUMN analytics.session_hash IS 'hashed session ID for privacy';
COMMENT ON COLUMN analytics.user_id IS 'authenticated user ID (optional)';
COMMENT ON COLUMN analytics.device_type IS 'desktop, mobile, tablet';
COMMENT ON COLUMN analytics.browser IS 'browser name (general)';
COMMENT ON COLUMN analytics.operating_system IS 'OS name (general)';
COMMENT ON COLUMN analytics.country_code IS 'ISO country code only';
COMMENT ON COLUMN analytics.search_query IS 'search terms if applicable';
COMMENT ON COLUMN analytics.metadata IS 'additional event data (JSON format)';

-- Table: migrations
CREATE TABLE migrations (
  id SERIAL PRIMARY KEY,
  migration VARCHAR(255) NOT NULL,
  batch INT NOT NULL
);
