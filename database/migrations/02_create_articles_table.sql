CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL ,
    content MEDIUMTEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    views_count INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Indexes on columns that will be used in sorting queries
    INDEX idx_articles_views_count (views_count),
    INDEX idx_articles_published (published_at)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;