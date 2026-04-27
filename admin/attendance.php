<?php
// FILE: /admin/attendance.php
// Admin UI for Attendance tracking.
// Allows bulk marking of attendance by selecting a branch and date.
// Shows a quick list of members who have been absent for 7+ days.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_attendance'])) {
    $service_date = sanitize($conn, $_POST['service_date']);
    $present_ids = isset($_POST['present']) && is_array($_POST['present']) ? $_POST['present'] : [];
    $branch_id = (int)$_POST['branch_id'];

    // For all active members in this branch, register attendance
    $members = $conn->query("SELECT id FROM members WHERE branch_id=$branch_id AND status='Active'");
    
    // Simplistic Logic: delete any existing attendance for this branch on this date first
    $conn->query("DELETE a FROM attendance a INNER JOIN members m ON a.member_id = m.id WHERE a.service_date='$service_date' AND m.branch_id=$branch_id");

    while($m = $members->fetch_assoc()) {
        $m_id = $m['id'];
        $is_present = in_array($m_id, $present_ids) ? 1 : 0;
        $conn->query("INSERT INTO attendance (member_id, service_date, present) VALUES ($m_id, '$service_date', $is_present)");
    }
    $msg = "Attendance saved.";
}

$branches = $conn->query("SELECT id, name FROM branches");
$branchList = [];
while($b = $branches->fetch_assoc()) $branchList[] = $b;

$selected_branch = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;
$members_to_mark = null;
if ($selected_branch > 0) {
    $members_to_mark = $conn->query("SELECT id, name FROM members WHERE branch_id=$selected_branch AND status='Active'");
}

$absent_list = $conn->query("SELECT m.name, m.phone, b.name as bname FROM members m 
    LEFT JOIN branches b ON m.branch_id = b.id 
    WHERE status='Active' AND NOT EXISTS (
        SELECT 1 FROM attendance a WHERE a.member_id = m.id AND a.present = 1 AND a.service_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    )");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Attendance</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Mark Attendance</h2>
        <?php if(!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
        
        <form method="GET" style="margin-bottom:20px;">
            <select name="branch_id" required>
                <option value="">Select Branch</option>
                <?php foreach($branchList as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= $selected_branch == $b['id'] ? 'selected' : '' ?>><?= $b['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Load Members</button>
        </form>

        <?php if($members_to_mark && $members_to_mark->num_rows > 0): ?>
        <form method="POST">
            <input type="hidden" name="branch_id" value="<?= $selected_branch ?>">
            <label>Service Date: <input type="date" name="service_date" required max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"></label>
            <table border="1" style="width:100%; border-collapse: collapse; margin-top:10px;">
                <tr><th>Present?</th><th>Name</th></tr>
                <?php while($row = $members_to_mark->fetch_assoc()): ?>
                <tr>
                    <td><input type="checkbox" name="present[]" value="<?= $row['id'] ?>" checked></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <br>
            <button type="submit" name="mark_attendance" class="btn">Save Attendance</button>
        </form>
        <?php endif; ?>

        <hr style="margin: 40px 0;">
        <h3>Absent 7+ Days</h3>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <tr><th>Name</th><th>Branch</th><th>Phone</th></tr>
            <?php while($abs = $absent_list->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($abs['name']) ?></td>
                <td><?= htmlspecialchars($abs['bname']) ?></td>
                <td><?= htmlspecialchars($abs['phone']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
