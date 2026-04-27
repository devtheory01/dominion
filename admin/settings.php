<?php
// FILE: /admin/settings.php
// Admin interface for updating system settings (Termii Keys, SMS templates, Site Info).
// Allows for logo upload using the generic uploadImage function.
// Requires admin authentication.

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    $site_name = sanitize($conn, $_POST['site_name']);
    $pastor_name = sanitize($conn, $_POST['pastor_name']);
    $termii_key = sanitize($conn, $_POST['termii_key']);
    $termii_sender = sanitize($conn, $_POST['termii_sender']);
    $sms_welcome = sanitize($conn, $_POST['sms_welcome']);
    $sms_birthday = sanitize($conn, $_POST['sms_birthday']);
    $sms_newbaby = sanitize($conn, $_POST['sms_newbaby']);
    $sms_absent_7 = sanitize($conn, $_POST['sms_absent_7']);
    $facebook = sanitize($conn, $_POST['facebook']);
    $youtube = sanitize($conn, $_POST['youtube']);
    
    $logoUpdate = "";
    if (!empty($_FILES['logo']['name'])) {
        $logoPath = uploadImage($_FILES['logo'], 'uploads/logo');
        if($logoPath) $logoUpdate = ", logo='$logoPath'";
    }

    $stmt = $conn->prepare("UPDATE settings SET site_name=?, pastor_name=?, termii_key=?, termii_sender=?, sms_welcome=?, sms_birthday=?, sms_newbaby=?, sms_absent_7=?, facebook=?, youtube=? $logoUpdate WHERE id=1");
    $stmt->bind_param("ssssssssss", $site_name, $pastor_name, $termii_key, $termii_sender, $sms_welcome, $sms_birthday, $sms_newbaby, $sms_absent_7, $facebook, $youtube);
    $stmt->execute();
    $msg = "Settings Updated.";
}

$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Settings</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
    <div style="padding: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>System Settings</h2>
        <?php if(!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
        
        <form method="POST" enctype="multipart/form-data" style="max-width: 600px; display: grid; gap: 15px;">
            <label>Site Name: <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name']) ?>"></label>
            <label>Pastor Name: <input type="text" name="pastor_name" value="<?= htmlspecialchars($settings['pastor_name']) ?>"></label>
            <label>Logo Upload: <input type="file" name="logo" accept="image/*"></label>
            <?php if($settings['logo']) echo "<img src='/{$settings['logo']}' width='100'>"; ?>
            
            <hr>
            <h3>Termii SMS Settings</h3>
            <label>Termii API Key: <input type="text" name="termii_key" value="<?= htmlspecialchars($settings['termii_key']) ?>"></label>
            <label>Termii Sender ID: <input type="text" name="termii_sender" value="<?= htmlspecialchars($settings['termii_sender']) ?>"></label>
            <label>Welcome SMS: <textarea name="sms_welcome"><?= htmlspecialchars($settings['sms_welcome']) ?></textarea></label>
            <label>Birthday SMS: <textarea name="sms_birthday"><?= htmlspecialchars($settings['sms_birthday']) ?></textarea></label>
            <label>New Baby SMS: <textarea name="sms_newbaby"><?= htmlspecialchars($settings['sms_newbaby']) ?></textarea></label>
            <label>Absent 7+ Days SMS: <textarea name="sms_absent_7"><?= htmlspecialchars($settings['sms_absent_7']) ?></textarea></label>
            
            <hr>
            <h3>Social Links</h3>
            <label>Facebook URL: <input type="text" name="facebook" value="<?= htmlspecialchars($settings['facebook']) ?>"></label>
            <label>YouTube URL: <input type="text" name="youtube" value="<?= htmlspecialchars($settings['youtube']) ?>"></label>

            <button type="submit" name="save_settings" class="btn">Save Settings</button>
        </form>
    </div>
</body>
</html>
