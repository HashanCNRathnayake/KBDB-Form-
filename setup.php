<?php
require __DIR__ . '/db.php';

// Create tables

// Create users table
$conn->query("
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  password VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
  role ENUM('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
");

// Seed users if empty
$res = $conn->query("SELECT COUNT(*) AS c FROM users");
$row = $res->fetch_assoc();
if ((int)$row['c'] === 0) {
  $seed = [
    ['Admin', '$2y$10$IbygD/njdhffGN6Ja/iCNemvprLo3mgcGOi8k2H2rcRz3Mr9xbIZq', 'admin'],
    ['AdminUser', '$2y$10$IbygD/njdhffGN6Ja/iCNemvprLo3mgcGOi8k2H2rcRz3Mr9xbIZq', 'user'],
  ];
  // Admin - admin@A2%0a
  // AdminUser - admin@A2%0a
  $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
  foreach ($seed as $s) {
    $stmt->bind_param('sss', $s[0], $s[1], $s[2]);
    $stmt->execute();
  }
  $stmt->close();
}

$conn->query("
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(255) NOT NULL UNIQUE,
  color VARCHAR(50)  NOT NULL DEFAULT 'blue',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$conn->query("
CREATE TABLE IF NOT EXISTS faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  question TEXT NOT NULL,
  answer   MEDIUMTEXT NOT NULL,
  category_id INT NOT NULL,
  keywords TEXT NULL,
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  active ENUM('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
  person_or_group VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_faq_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_faq_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Seed categories if empty
$res = $conn->query("SELECT COUNT(*) AS c FROM categories");
$row = $res->fetch_assoc();
if ((int)$row['c'] === 0) {
  $seed = [
    ['Attendance and Progress', 'blue'],
    ['Lithan Platform', 'green'],
    ['Training Allowance', 'yellow'],
    ['QR code Singpass e-attendance', 'pink'],
  ];
  $stmt = $conn->prepare("INSERT INTO categories (name, color) VALUES (?, ?)");
  foreach ($seed as $s) {
    $stmt->bind_param('ss', $s[0], $s[1]);
    $stmt->execute();
  }
  $stmt->close();
}

echo "✅ Setup complete. Go to <a href='index.php'>Create FAQ</a> or <a href='admin/dashboard.php'>View FAQs</a>.";
