<?php
// FILE: /admin/sermons.php
// Admin UI for Sermons.
// Controls insertion of YouTube IDs, preacher details, and setting the active "is_live" stream banner.
// Only ONE sermon can be marked is_live=1 at a time.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sermon'])) {
    $title = sanitize($conn, $_POST['title']);
    $preacher = sanitize($conn, $_POST['preacher']);
    $youtube_id = sanitize($conn, $_POST['youtube_id']);
    $service_date = $_POST['service_date'];
    $is_live = isset($_POST['is_live']) ? 1 : 0;
    
    // Only 1 sermon can be live
    if ($is_live) $conn->query("UPDATE sermons SET is_live=0");
    
    $banner = '';
    if (!empty($_FILES['banner']['name'])) {
        $banner = uploadImage($_FILES['banner'], 'uploads/blogs');
    }

    $stmt = $conn->prepare("INSERT INTO sermons (title, preacher, youtube_id, banner, service_date, is_live) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $preacher, $youtube_id, $banner, $service_date, $is_live);
    $stmt->execute();
    header("Location: sermons.php");
    exit;
}

if (isset($_GET['toggle_live'])) {
    $id = (int)$_GET['toggle_live'];
    $conn->query("UPDATE sermons SET is_live=0"); // Reset all
    $conn->query("UPDATE sermons SET is_live=1 WHERE id=$id");
    header("Location: sermons.php");
    exit;
}

$sermons = $conn->query("SELECT * FROM sermons ORDER BY service_date DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Sermons</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Sermons</h2>
        
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 500px; border:1px solid #ccc; padding:15px;">
            <h3>Add New Sermon</h3>
            <input type="text" name="title" placeholder="Sermon Title" required>
            <input type="text" name="preacher" placeholder="Preacher Name" required>
            <input type="text" name="youtube_id" placeholder="YouTube Video ID (e.g. dQw4w9WgXcQ)" required>
            <input type="date" name="service_date" required>
            <input type="file" name="banner" accept="image/*">
            <label><input type="checkbox" name="is_live" value="1"> Make Live Stream (Activates Banner)</label>
            <button type="submit" name="add_sermon" class="btn">Add Sermon</button>
        </form>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Title</th><th>Preacher</th><th>Date</th><th>Youtube ID</th><th>Live Status</th><th>Actions</th></tr>
            <?php while($row = $sermons->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['preacher']) ?></td>
                <td><?= htmlspecialchars($row['service_date']) ?></td>
                <td><?= htmlspecialchars($row['youtube_id']) ?></td>
                <td>
                    <?php if($row['is_live']): ?>
                        <strong style='color:red;'>LIVE</strong>
                    <?php else: ?>
                        Not Live
                    <?php endif; ?>
                </td>
                <td><a href="?toggle_live=<?= $row['id'] ?>"><button>Set Live</button></a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
