<?php
// FILE: /admin/dashboard.php
// Main Admin Dashboard displaying core statistics and quick actions.
// Calculates Total Members, Absent Members (7+ days), Birthdays Today, and Sermon Count.
// Requires admin authentication.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Stats queries
$total_members = $conn->query("SELECT COUNT(*) as c FROM members WHERE status='Active'")->fetch_assoc()['c'];

$sermons_count = $conn->query("SELECT COUNT(*) as c FROM sermons")->fetch_assoc()['c'];

$today_md = date('m-d');
$birthdays = $conn->query("SELECT COUNT(*) as c FROM members WHERE DATE_FORMAT(dob, '%m-%d') = '$today_md' AND status='Active'")->fetch_assoc()['c'];

$absent_7 = $conn->query("SELECT COUNT(*) as c FROM members m 
    WHERE status='Active' AND NOT EXISTS (
        SELECT 1 FROM attendance a WHERE a.member_id = m.id AND a.present = 1 AND a.service_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    )")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Admin Dashboard</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div class="admin-wrapper" style="padding: 20px;">
        <h2>Dashboard</h2>
        <a href="logout.php" style="float: right;">Logout</a>
        <nav style="margin-bottom: 20px;">
            <a href="dashboard.php">Dashboard</a> | 
            <a href="settings.php">Settings</a> | 
            <a href="branches.php">Branches</a> | 
            <a href="members.php">Members</a> | 
            <a href="attendance.php">Attendance</a> | 
            <a href="sermons.php">Sermons</a> | 
            <a href="events.php">Events</a> | 
            <a href="gallery.php">Gallery</a> | 
            <a href="followups.php">Followups</a> | 
            <a href="contact.php">Inbox</a> | 
            <a href="sms.php">Bulk SMS</a>
        </nav>
        
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
            <div class="card" style="padding: 20px; border: 1px solid #ccc;"><h3>Total Members</h3><p><?= $total_members ?></p></div>
            <div class="card" style="padding: 20px; border: 1px solid #ccc;"><h3>Absent 7+ Days</h3><p><?= $absent_7 ?></p></div>
            <div class="card" style="padding: 20px; border: 1px solid #ccc;"><h3>Birthdays Today</h3><p><?= $birthdays ?></p></div>
            <div class="card" style="padding: 20px; border: 1px solid #ccc;"><h3>Total Sermons</h3><p><?= $sermons_count ?></p></div>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <a href="members.php" class="btn">Add Member</a>
            <a href="attendance.php" class="btn">Mark Attendance</a>
        </div>
    </div>
</body>
</html>
