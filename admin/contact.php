<?php
// FILE: /admin/contact.php
// Admin UI to view messages submitted through the frontend Contact form.
// Displays messages from the contact_messages table descending.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    header("Location: contact.php");
    exit;
}

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Inbox</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Inbox Messages</h2>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Date</th><th>Sender</th><th>Email</th><th>Phone</th><th>Message</th><th>Actions</th></tr>
            <?php while($row = $messages->fetch_assoc()): ?>
            <tr>
                <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td style="max-width:300px; word-wrap:break-word;"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete message?');" style="color:red; text-decoration:none;">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
