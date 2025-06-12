<?php
define('BASE_DIR', __DIR__);
define('DB_FILE', BASE_DIR . '/includes/database.db');

try {
  if (file_exists(DB_FILE)) {
    unlink(DB_FILE);
  }

  $db = new PDO('sqlite:' . DB_FILE);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->exec("PRAGMA foreign_keys = ON");

  $db->exec("CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE NOT NULL,
  email TEXT UNIQUE NOT NULL,
  password TEXT NOT NULL,
  bio TEXT,
  profile_pic VARCHAR(255),
  location VARCHAR(100),
  website VARCHAR(255),
  last_active DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_admin BOOLEAN DEFAULT 0
  )");

  $db->exec("CREATE TABLE categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT UNIQUE NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_by INTEGER,
  FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
  )");

  $db->exec("CREATE TABLE books (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  author TEXT NOT NULL,
  description TEXT,
  category_id INTEGER,
  file_path TEXT NOT NULL,
  cover_image TEXT,
  views INTEGER DEFAULT 0,
  downloads INTEGER DEFAULT 0,
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  uploaded_by INTEGER,
  FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL,
  FOREIGN KEY(uploaded_by) REFERENCES users(id) ON DELETE SET NULL
  )");

  $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
  $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
  $stmt->execute(['admin', 'admin@example.com', $hashedPassword, 1]);
  $adminId = $db->lastInsertId();

  $hashedPassword = password_hash('user123', PASSWORD_DEFAULT);
  $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
  $stmt->execute(['user1', 'user1@example.com', $hashedPassword, 0]);

  $categories = [
    ['Fiction',
      'Imaginary stories and novels'],
    ['Science',
      'Scientific books and publications'],
    ['History',
      'Historical accounts and analysis']
  ];

  foreach ($categories as $category) {
    $stmt = $db->prepare("INSERT INTO categories (name, description, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$category[0], $category[1], $adminId]);
  }

  echo "Database initialized successfully at: " . realpath(DB_FILE) . "<br>";
  echo "Admin: username 'admin', password 'admin123'<br>";
  echo "User: username 'user1', password 'user123'<br>";
  echo "Added categories: Fiction, Science, History";

} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
}
?>