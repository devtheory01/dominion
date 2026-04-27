<?php
// FILE: /includes/db.php
// Instantiates a database connection using defined parameters.
// Also ensures error reporting and handling if the DB is down.
// Include this wherever database queries are executed.

require_once __DIR__ . '/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
