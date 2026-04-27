<?php
// FILE: /admin/events.php
// Admin UI to create and list Posts. 
// Uses ENUM for type: Blog (Preached word summaries) or Testimony (What God did).
// Stores banner image and full text body.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_post'])) {
    $title = sanitize($conn, $_POST['title']);
    $body = $conn->real_escape_string($_POST['body']);
    $type = $_POST['type'];
    $author = sanitize($conn, $_POST['author']);
    
    $banner = '';
    if (!empty($_FILES['banner']['name'])) {
        $banner = uploadImage($_FILES['banner'], 'uploads/blogs');
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, body, type, author, banner) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $body, $type, $author, $banner);
    $stmt->execute();
    header("Location: events.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id=$id");
    header("Location: events.php");
    exit;
}

$posts = $conn->query("SELECT * FROM posts ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Events & Testimonies</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Blogs, Events & Testimonies</h2>
        
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 600px; border:1px solid #ccc; padding:15px;">
            <h3>Add New Post</h3>
            <input type="text" name="title" placeholder="Title" required>
            <select name="type">
                <option value="Blog">Blog / Event Summary</option>
                <option value="Testimony">Testimony</option>
            </select>
            <input type="text" name="author" placeholder="Author / Testifier's Name">
            <textarea name="body" placeholder="Content text here..." rows="6" required></textarea>
            <input type="file" name="banner" accept="image/*">
            <button type="submit" name="add_post" class="btn">Publish Post</button>
        </form>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Type</th><th>Title</th><th>Author</th><th>Date</th><th>Actions</th></tr>
            <?php while($row = $posts->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['type']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
