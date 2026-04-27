<?php
// FILE: /admin/gallery.php
// Admin UI to upload and delete photos in the gallery.
// Allows singular photo uploads with a caption. Saves path to DB.
// Requires admin authentication.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_images'])) {
    $caption = sanitize($conn, $_POST['caption']);
    
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage($_FILES['image'], 'uploads/gallery');
        if ($imagePath) {
            $stmt = $conn->prepare("INSERT INTO gallery (image, caption) VALUES (?, ?)");
            $stmt->bind_param("ss", $imagePath, $caption);
            $stmt->execute();
        }
    }
    header("Location: gallery.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM gallery WHERE id=$id");
    header("Location: gallery.php");
    exit;
}

$gallery = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Gallery</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Gallery</h2>
        
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 400px; border:1px solid #ccc; padding:15px;">
            <h3>Upload Image</h3>
            <input type="file" name="image" accept="image/*" required>
            <input type="text" name="caption" placeholder="Optional Caption">
            <button type="submit" name="upload_images" class="btn">Upload</button>
        </form>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
            <?php while($row = $gallery->fetch_assoc()): ?>
            <div style="border: 1px solid #ddd; padding: 10px; text-align:center;">
                <img src="/<?= $row['image'] ?>" style="width:100%; height:150px; object-fit:cover; margin-bottom:10px;">
                <small><?= htmlspecialchars($row['caption']) ?></small><br>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete image?');" style="color:red; text-decoration:none;">Delete</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
