<?php
// FILE: /post.php
// Public Single Post View.
// Displays the full body of a testimony or blog, alongside share buttons logic.

require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: events.php");
    exit;
}
$id = (int)$_GET['id'];
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();

if (!$post) {
    echo "<div style='text-align:center; padding:50px;'><h2>Post not found!</h2></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$page_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<div style="max-width: 800px; margin: 40px auto; padding: 0 20px; background:var(--white); border-radius:8px; overflow:hidden;" class="fade-in card">
    <?php if($post['banner']): ?>
        <img src="/<?= $post['banner'] ?>" style="width:100%; max-height:400px; object-fit:cover;">
    <?php endif; ?>
    
    <div style="padding: 30px;">
        <span style="color:var(--red); font-weight:bold;"><?= htmlspecialchars($post['type']) ?></span>
        <h1 style="margin: 10px 0;"><?= htmlspecialchars($post['title']) ?></h1>
        <p style="color:#666; margin-bottom:20px;">By <?= htmlspecialchars($post['author']) ?> | <?= date('F d, Y', strtotime($post['created_at'])) ?></p>
        
        <div style="line-height:1.8; font-size:16px;">
            <?= nl2br(htmlspecialchars($post['body'])) ?>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top:1px solid #eee;">
            <strong>Share this:</strong> 
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($page_url) ?>" target="_blank" class="btn" style="display:inline-block; margin-left:10px;">Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($page_url) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" class="btn" style="display:inline-block; margin-left:10px; background:#1DA1F2;">Twitter</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
