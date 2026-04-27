<?php
// FILE: /contact.php
// Public Contact Us Page and Form.
// Submits to DB via standard POST (or AJAX natively via standard submission), saves in contact_messages.

require_once __DIR__ . '/includes/header.php';

$msg_feedback = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    $name = sanitize($conn, $_POST['name']);
    $phone = sanitize($conn, $_POST['phone']);
    $email = sanitize($conn, $_POST['email']);
    $message = sanitize($conn, $_POST['message']);

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, phone, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $message);
    if($stmt->execute()) {
        $msg_feedback = "Thank you! Your message has been received.";
    }
}
?>

<div style="background:var(--red); color:white; padding:40px 20px; text-align:center;" class="fade-in">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you.</p>
</div>

<div style="max-width: 600px; margin: 40px auto; padding: 20px;" class="fade-in card">
    <?php if(!empty($msg_feedback)) echo "<p style='color:green; text-align:center; font-weight:bold;'>$msg_feedback</p>"; ?>
    
    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required>
        
        <label>Phone Number</label>
        <input type="text" name="phone" required>
        
        <label>Email Address</label>
        <input type="email" name="email">
        
        <label>Message</label>
        <textarea name="message" rows="5" required></textarea>
        
        <button type="submit" name="submit_contact" class="btn" style="width:100%;">Send Message</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
