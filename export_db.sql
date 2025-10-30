-- file: export_db.sql

CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE user_management;

-- tabel users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(150),
  role ENUM('admin_gudang','user') DEFAULT 'admin_gudang',
  is_active TINYINT(1) DEFAULT 0,
  activation_token VARCHAR(255),
  reset_token VARCHAR(255),
  reset_expires DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabel products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) DEFAULT 0,
  stock INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh user (tidak aktif) -> gunakan untuk testing (password: password123)
INSERT INTO users (email, password, fullname, role, is_active) VALUES
('admin@example.com', '$2y$10$uQxgWwGv9xqQ0YQ9Vf6jUuKqXb5hR0bF6H9w9m9a1b2c3d4e5f6g', 'Admin Contoh', 'admin_gudang', 1);
-- NOTE: hash di atas hanya placeholder; lebih baik register lewat form
