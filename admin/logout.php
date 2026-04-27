<?php
// FILE: /admin/logout.php
// Admin logout endpoint.
// Destroys the session and redirects user to the login screen.

session_start();
session_unset();
session_destroy();
header("Location: /admin/login.php");
exit;
?>
