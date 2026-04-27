<?php
// FILE: /includes/header.php
// Frontend header component with Logo, Navigation, and Mobile Hamburger.
// Also includes the "WE ARE LIVE" pulsing banner logic if a sermon is live.
// Includes CSS stylesheet linking.

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$site_name = getSetting($conn, 'site_name');
$logo = getSetting($conn, 'logo');
$logo_path = !empty($logo) && file_exists(__DIR__ . '/../' . $logo) ? '/' . $logo : '';

// Check Live Sermon
$live_check = $conn->query("SELECT id FROM sermons WHERE is_live=1 LIMIT 1");
$is_live = $live_check && $live_check->num_rows > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php if($is_live): ?>
    <div class="live-banner pulse">
        <a href="/stream.php">🔴 WE ARE LIVE - Watch Now</a>
    </div>
    <?php endif; ?>
    
    <header class="header slide-up fade-in">
        <div class="logo">
            <a href="/index.php">
                <?php if($logo_path): ?>
                    <img src="<?= $logo_path ?>" alt="<?= htmlspecialchars($site_name) ?>">
                <?php else: ?>
                    <?= htmlspecialchars($site_name) ?>
                <?php endif; ?>
            </a>
        </div>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
        <nav class="nav-menu" id="nav-menu">
            <a href="/index.php">Home</a>
            <a href="/about.php">About</a>
            <a href="/sermons.php">Sermons</a>
            <a href="/events.php">Events & Blog</a>
            <a href="/gallery.php">Gallery</a>
            <a href="/contact.php">Contact</a>
        </nav>
    </header>
    <main>
