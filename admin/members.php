<?php
// FILE: /admin/members.php
// Admin UI for Members CRUD, SMS Welcome triggers, Congrats logic on Life Events.
// Includes form for new members and displays members list.
// Requires admin authentication.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = sanitize($conn, $_POST['name']);
    $phone = sanitize($conn, $_POST['phone']);
    $email = sanitize($conn, $_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $branch_id = (int)$_POST['branch_id'];
    $life_event = $_POST['life_event'];
    $status = $_POST['status'];
    $send_welcome = isset($_POST['send_welcome']) ? 1 : 0;
    
    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = uploadImage($_FILES['photo'], 'uploads/members');
    }

    $stmt = $conn->prepare("INSERT INTO members (name, phone, email, dob, gender, branch_id, photo, life_event, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $name, $phone, $email, $dob, $gender, $branch_id, $photo, $life_event, $status);
    $stmt->execute();
    
    if ($send_welcome) {
        $welcome_msg = getSetting($conn, 'sms_welcome');
        if (!empty($welcome_msg)) sendSMS($phone, str_replace('{name}', $name, $welcome_msg));
    }
    header("Location: members.php");
    exit;
}

if (isset($_GET['congrats'])) {
    $id = (int)$_GET['congrats'];
    $member = $conn->query("SELECT name, phone, life_event FROM members WHERE id=$id")->fetch_assoc();
    if ($member && $member['life_event'] == 'NewBaby') {
        $msg = getSetting($conn, 'sms_newbaby');
        if($msg) sendSMS($member['phone'], str_replace('{name}', $member['name'], $msg));
    }
    header("Location: members.php");
    exit;
}

$branches_query = $conn->query("SELECT id, name FROM branches");
$branches = [];
while($b = $branches_query->fetch_assoc()) $branches[] = $b;

$members = $conn->query("SELECT m.*, b.name as branch_name FROM members m LEFT JOIN branches b ON m.branch_id = b.id ORDER BY m.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Members</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>Manage Members</h2>
        
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 20px; display: grid; gap: 10px; max-width: 500px; border:1px solid #ccc; padding:15px;">
            <h3>Add New Member</h3>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="email" name="email" placeholder="Email">
            <input type="date" name="dob" title="Date of Birth" required>
            <select name="gender" required><option value="Female">Female</option><option value="Male">Male</option></select>
            <select name="branch_id" required>
                <option value="">Select Branch</option>
                <?php foreach($branches as $b): ?><option value="<?= $b['id'] ?>"><?= $b['name'] ?></option><?php endforeach; ?>
            </select>
            <select name="life_event">
                <option value="None">Life Event: None</option>
                <option value="NewBaby">New Baby</option>
                <option value="Wedding">Wedding</option>
                <option value="Graduation">Graduation</option>
            </select>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <input type="file" name="photo" accept="image/*">
            <label><input type="checkbox" name="send_welcome" value="1" checked> Send Welcome SMS</label>
            <button type="submit" name="add_member" class="btn">Add Member</button>
        </form>

        <table style="width:100%; border-collapse: collapse; text-align: left;" border="1">
            <tr><th>Name</th><th>Branch</th><th>Phone</th><th>Event</th><th>Status</th><th>Actions</th></tr>
            <?php while($row = $members->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['branch_name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['life_event']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if($row['life_event'] == 'NewBaby'): ?>
                        <a href="?congrats=<?= $row['id'] ?>"><button>Send Congrats SMS</button></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
