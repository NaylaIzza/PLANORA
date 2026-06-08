-- ============================================
-- DATABASE: db_planora
-- Import file ini di phpMyAdmin > tab SQL
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `db_planora`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `db_planora`;

-- --------------------------------------------------------
-- Tabel: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `nama`        varchar(100) NOT NULL,
  `email`       varchar(100) NOT NULL,
  `password`    varchar(255) NOT NULL,
  `telegram_id` varchar(50)  DEFAULT NULL,
  `created_at`  timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: tasks
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
  `id`        int(11)      NOT NULL AUTO_INCREMENT,
  `user_id`   int(11)      DEFAULT NULL,
  `judul`     varchar(255) NOT NULL,
  `matkul`    varchar(100) NOT NULL,
  `deadline`  varchar(50)  NOT NULL,
  `kesulitan` varchar(50)  NOT NULL,
  `prioritas` varchar(50)  NOT NULL,
  `warna`     varchar(20)  NOT NULL,
  `bg`        varchar(20)  NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tasks_ibfk_1`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: user_stats
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_stats` (
  `user_id`       int(11) NOT NULL,
  `total_selesai` int(11) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_stats_ibfk_1`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
