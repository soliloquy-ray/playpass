-- SQL to create the admins table
-- Run this in your database if migration doesn't work

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'super_admin') NOT NULL DEFAULT 'admin',
  `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
  `avatar_url` VARCHAR(255) NULL,
  `last_login_at` DATETIME NULL,
  `last_activity_at` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

