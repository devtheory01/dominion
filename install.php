<?php
// FILE: /install.php
// Connects to InfinityFree MySQL DB and creates 11 tables required for the church management system.
// It also inserts default admin credentials and site settings.
// Delete this file after successful execution to prevent unauthorized resets.

require_once 'includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->select_db(DB_NAME);

$tables = [
    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        username VARCHAR(50), 
        password VARCHAR(255), 
        role VARCHAR(20) DEFAULT 'Admin'
    )",
    "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        site_name VARCHAR(255) DEFAULT 'Dominion City', 
        pastor_name VARCHAR(255), 
        logo VARCHAR(255), 
        termii_key VARCHAR(255), 
        termii_sender VARCHAR(50) DEFAULT 'DCITY', 
        sms_welcome TEXT, 
        sms_birthday TEXT, 
        sms_newbaby TEXT, 
        sms_absent_7 TEXT, 
        facebook VARCHAR(255), 
        youtube VARCHAR(255)
    )",
    "CREATE TABLE IF NOT EXISTS branches (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255), 
        location TEXT, 
        pastor VARCHAR(255), 
        photo VARCHAR(255)
    )",
    "CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255), 
        phone VARCHAR(20), 
        email VARCHAR(255), 
        dob DATE, 
        gender ENUM('Male','Female'), 
        branch_id INT, 
        photo VARCHAR(255), 
        life_event ENUM('None','NewBaby','Wedding','Graduation') DEFAULT 'None', 
        join_date DATE DEFAULT CURRENT_DATE, 
        status ENUM('Active','Inactive') DEFAULT 'Active'
    )",
    "CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        member_id INT, 
        service_date DATE, 
        present TINYINT DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS sermons (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(255), 
        preacher VARCHAR(255), 
        youtube_id VARCHAR(50), 
        banner VARCHAR(255), 
        service_date DATE, 
        is_live TINYINT DEFAULT 0
    )",
    "CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(255), 
        body TEXT, 
        type ENUM('Blog','Testimony'), 
        author VARCHAR(255), 
        banner VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        image VARCHAR(255), 
        caption VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS followups (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        member_id INT, 
        followup_date DATE, 
        status ENUM('Pending','Contacted','Completed'), 
        notes TEXT
    )",
    "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255), 
        phone VARCHAR(20), 
        email VARCHAR(255), 
        message TEXT, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
}

// Insert defaults
$check_admin = $conn->query("SELECT * FROM admins");
if ($check_admin->num_rows == 0) {
    $conn->query("INSERT INTO admins (username,password) VALUES ('pastor','" . password_hash('church123', PASSWORD_DEFAULT) . "')");
}

$check_settings = $conn->query("SELECT * FROM settings");
if ($check_settings->num_rows == 0) {
    $conn->query("INSERT INTO settings (site_name,pastor_name) VALUES ('Dominion City','Pastor David Ogbueli')");
}

$dirs = ['uploads/logo', 'uploads/founder', 'uploads/branches', 'uploads/members', 'uploads/gallery', 'uploads/blogs'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

echo "Installation Complete. Database tables created. Default Admin: pastor / church123. <br><b>Please delete install.php now.</b>";
?>
