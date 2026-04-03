<?php
require_once __DIR__ . '/db.php';

try {
    $pdo = getDB();

    // Trainers Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS trainers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        specialty VARCHAR(100),
        bio TEXT,
        photo_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Equipment Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS equipment (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        status ENUM('Active', 'Maintenance', 'Broken') DEFAULT 'Active',
        last_maintenance DATE,
        notes TEXT
    )");

    // Announcements Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Attendance Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        check_out_time DATETIME NULL,
        status ENUM('Present', 'Absent') DEFAULT 'Present',
        date DATE DEFAULT (CURRENT_DATE),
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
    )");

} catch (PDOException $e) {
    // Silently fail or log error (for production, we might want to log this)
    // die("Setup failed: " . $e->getMessage()); 
}
?>
