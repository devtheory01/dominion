<?php
// FILE: /admin/sms.php
// Admin UI to broadcast Bulk SMS to a specific branch or all members.
// Uses the Termii API setup in includes/functions.php.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$msg_feedback = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_bulk'])) {
    $branch_id = $_POST['branch_id']; // 'All' or INT
    $message = sanitize($conn, $_POST['message']);
    
    $where = "WHERE status='Active'";
    if ($branch_id !== 'All') {
        $bid = (int)$branch_id;
        $where .= " AND branch_id=$bid";
    }

    $members = $conn->query("SELECT phone FROM members $where");
    $sentCount = 0;
    while($row = $members->fetch_assoc()) {
        if(!empty($row['phone'])) {
            sendSMS($row['phone'], $message);
            $sentCount++;
        }
    }
    $msg_feedback = "Broadcast sent to $sentCount members.";
}

$branches = $conn->query("SELECT id, name FROM branches");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Bulk SMS</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Send Bulk SMS</h2>
        <?php if(!empty($msg_feedback)) echo "<p style='color:green;'>$msg_feedback</p>"; ?>
        
        <form method="POST" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 500px; border:1px solid #ccc; padding:15px;">
            <select name="branch_id" required>
                <option value="All">All Active Members (Globally)</option>
                <?php while($b = $branches->fetch_assoc()): ?>
                    <option value="<?= $b['id'] ?>">Only <?= $b['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <textarea name="message" rows="5" required placeholder="Type your SMS broadcast here... (Keep it short, max 160 chars per block)"></textarea>
            <button type="submit" name="send_bulk" class="btn" onclick="return confirm('Broadcast to all selected members now?');">Send Bulk SMS</button>
        </form>
    </div>
</body>
</html>
