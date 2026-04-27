<?php
// FILE: /includes/auth.php
// Middleware for Admin Authentication.
// Starts session and checks if the admin is logged in.
// Redirects to login.php if session variable is not set.

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
    header("Location: /admin/login.php");
    exit();
}
?>
