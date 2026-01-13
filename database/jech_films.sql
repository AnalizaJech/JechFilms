-- =====================================================
-- JECH FILMS - Sistema de Streaming Multimedia Local
-- Base de datos MySQL
-- =====================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS jech_films 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE jech_films;

-- =====================================================
-- TABLA: users
-- Almacena información de usuarios del sistema
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: categories
-- Categorías para películas y series (con subcategorías)
-- =====================================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: movies
-- Catálogo de películas del sistema
-- =====================================================
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    year YEAR,
    duration INT COMMENT 'Duración en minutos',
    poster VARCHAR(255) DEFAULT NULL,
    backdrop VARCHAR(255) DEFAULT NULL,
    video_path VARCHAR(255) NOT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    is_vault BOOLEAN DEFAULT FALSE COMMENT 'Contenido de caja fuerte',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_featured (is_featured),
    INDEX idx_vault (is_vault),
    INDEX idx_year (year),
    FULLTEXT idx_search (title, description)
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: movie_categories
-- Relación muchos a muchos entre películas y categorías
-- =====================================================
CREATE TABLE movie_categories (
    movie_id INT NOT NULL,
    category_id INT NOT NULL,
    
    PRIMARY KEY (movie_id, category_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: series
-- Catálogo de series del sistema
-- =====================================================
CREATE TABLE series (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    year_start YEAR,
    year_end YEAR DEFAULT NULL COMMENT 'NULL si aún en emisión',
    poster VARCHAR(255) DEFAULT NULL,
    backdrop VARCHAR(255) DEFAULT NULL,
    total_seasons INT DEFAULT 1,
    is_featured BOOLEAN DEFAULT FALSE,
    is_vault BOOLEAN DEFAULT FALSE COMMENT 'Contenido de caja fuerte',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_featured (is_featured),
    INDEX idx_vault (is_vault),
    FULLTEXT idx_search (title, description)
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: series_categories
-- Relación muchos a muchos entre series y categorías
-- =====================================================
CREATE TABLE series_categories (
    series_id INT NOT NULL,
    category_id INT NOT NULL,
    
    PRIMARY KEY (series_id, category_id),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: episodes
-- Episodios de las series
-- =====================================================
CREATE TABLE episodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    series_id INT NOT NULL,
    season INT NOT NULL DEFAULT 1,
    episode_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT COMMENT 'Duración en minutos',
    video_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_series_season (series_id, season),
    UNIQUE KEY unique_episode (series_id, season, episode_number),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: user_lists
-- Lista personal de contenido guardado por usuarios
-- =====================================================
CREATE TABLE user_lists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    content_type ENUM('movie', 'series') NOT NULL,
    content_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_user_content (user_id, content_type, content_id),
    INDEX idx_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: reactions
-- Likes y dislikes de usuarios al contenido
-- =====================================================
CREATE TABLE reactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    content_type ENUM('movie', 'series') NOT NULL,
    content_id INT NOT NULL,
    reaction ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_user_reaction (user_id, content_type, content_id),
    INDEX idx_content (content_type, content_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: views
-- Registro de visualizaciones para estadísticas
-- =====================================================
CREATE TABLE views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL COMMENT 'NULL para visitantes anónimos',
    content_type ENUM('movie', 'episode') NOT NULL,
    content_id INT NOT NULL,
    watched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    watch_duration INT DEFAULT 0 COMMENT 'Segundos vistos',
    
    INDEX idx_user (user_id),
    INDEX idx_content (content_type, content_id),
    INDEX idx_watched_at (watched_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- TABLA: vault_settings
-- Configuración de la Caja Fuerte (contenido adulto)
-- =====================================================
CREATE TABLE vault_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    access_code VARCHAR(255) NOT NULL COMMENT 'Código hasheado',
    is_enabled BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Usuario administrador por defecto (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@jechfilms.local', '$2y$10$S4uhllSwr5i5X2y49YkwO.W9GrMSJOSzSzVy3.VZ7DfmXClaP78/a', 'admin');

-- Configuración inicial de Caja Fuerte (código: 1234)
INSERT INTO vault_settings (access_code, is_enabled) VALUES 
('$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE);

-- Categorías iniciales
INSERT INTO categories (name, slug) VALUES 
('Acción', 'accion'),
('Aventura', 'aventura'),
('Comedia', 'comedia'),
('Drama', 'drama'),
('Terror', 'terror'),
('Ciencia Ficción', 'ciencia-ficcion'),
('Romance', 'romance'),
('Documental', 'documental'),
('Animación', 'animacion'),
('Thriller', 'thriller');
