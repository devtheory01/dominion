<?php
// FILE: /admin/followups.php
// Admin UI to log and manage follow-ups for members.
// Useful for First-Timers or absent members.
// Tracks status (Pending, Contacted, Completed) and notes.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_followup'])) {
    $member_id = (int)$_POST['member_id'];
    $followup_date = filter_var($_POST['followup_date'], FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $notes = sanitize($conn, $_POST['notes']);
    
    $stmt = $conn->prepare("INSERT INTO followups (member_id, followup_date, status, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $member_id, $followup_date, $status, $notes);
    $stmt->execute();
    header("Location: followups.php");
    exit;
}

if (isset($_GET['status_update']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $st = sanitize($conn, $_GET['status_update']);
    $conn->query("UPDATE followups SET status='$st' WHERE id=$id");
    header("Location: followups.php");
    exit;
}

$members = $conn->query("SELECT id, name FROM members WHERE status='Active'");
$memberList = [];
while($m = $members->fetch_assoc()) $memberList[] = $m;

$followups = $conn->query("SELECT f.*, m.name as mname, m.phone FROM followups f LEFT JOIN members m ON f.member_id = m.id ORDER BY f.followup_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Follow Ups</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Follow Ups</h2>
        
        <form method="POST" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 400px; border:1px solid #ccc; padding:15px;">
            <h3>Schedule Follow Up</h3>
            <select name="member_id" required>
                <option value="">Select Member</option>
                <?php foreach($memberList as $m): ?><option value="<?= $m['id'] ?>"><?= $m['name'] ?></option><?php endforeach; ?>
            </select>
            <input type="date" name="followup_date" required min="<?= date('Y-m-d') ?>">
            <select name="status"><option value="Pending">Pending</option><option value="Contacted">Contacted</option><option value="Completed">Completed</option></select>
            <textarea name="notes" placeholder="Notes (e.g. Needs prayers)" rows="3"></textarea>
            <button type="submit" name="add_followup" class="btn">Add Followup</button>
        </form>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Date</th><th>Member</th><th>Phone</th><th>Notes</th><th>Status</th><th>Actions</th></tr>
            <?php while($row = $followups->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['followup_date']) ?></td>
                <td><?= htmlspecialchars($row['mname']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="?id=<?= $row['id'] ?>&status_update=Pending">Pending</a> | 
                    <a href="?id=<?= $row['id'] ?>&status_update=Contacted">Contacted</a> | 
                    <a href="?id=<?= $row['id'] ?>&status_update=Completed">Completed</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
