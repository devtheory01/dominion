<?php
// FILE: /admin/branches.php
// Admin UI for Branch management CRUD.
// Lists church branches and handles insertion of new branches.
// Requires admin authentication.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_branch'])) {
    $name = sanitize($conn, $_POST['name']);
    $location = sanitize($conn, $_POST['location']);
    $pastor = sanitize($conn, $_POST['pastor']);
    
    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = uploadImage($_FILES['photo'], 'uploads/branches');
    }

    $stmt = $conn->prepare("INSERT INTO branches (name, location, pastor, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $location, $pastor, $photo);
    $stmt->execute();
    header("Location: branches.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM branches WHERE id=$id");
    header("Location: branches.php");
    exit;
}

$branches = $conn->query("SELECT * FROM branches ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Branches</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Branches</h2>
        
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 400px; border:1px solid #ccc; padding:15px;">
            <h3>Add New Branch</h3>
            <input type="text" name="name" placeholder="Branch Name" required>
            <input type="text" name="location" placeholder="Location Details" required>
            <input type="text" name="pastor" placeholder="Pastoring Minister" required>
            <input type="file" name="photo" accept="image/*">
            <button type="submit" name="add_branch" class="btn">Add Branch</button>
        </form>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Photo</th><th>Name</th><th>Location</th><th>Pastor</th><th>Actions</th></tr>
            <?php while($row = $branches->fetch_assoc()): ?>
            <tr>
                <td><?php if($row['photo']) echo "<img src='/{$row['photo']}' width='50'>"; ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['pastor']) ?></td>
                <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
