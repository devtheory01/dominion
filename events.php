<?php
// FILE: /events.php
// Public Feed for Blogs and Testimonies.
// Displays summaries.

require_once __DIR__ . '/includes/header.php';

$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<div style="background:var(--red); color:white; padding:40px 20px; text-align:center;" class="fade-in">
    <h1>Blog & Testimonies</h1>
    <p>Words of faith and stories of transformation.</p>
</div>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;" class="fade-in">
    <div class="grid-3" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); padding:0;">
        <?php while($p = $posts->fetch_assoc()): ?>
        <div class="card">
            <?php if($p['banner']): ?>
                <img src="/<?= $p['banner'] ?>" loading="lazy" style="width:100%; height:180px; object-fit:cover; border-radius:4px;">
            <?php endif; ?>
            <small style="background:var(--red); color:white; padding:2px 8px; border-radius:10px; display:inline-block; margin:10px 0;"><?= htmlspecialchars($p['type']) ?></small>
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <p style="color:#777; font-size:14px; margin-bottom:10px;">By <?= htmlspecialchars($p['author']) ?> on <?= date('M d, Y', strtotime($p['created_at'])) ?></p>
            <p><?= substr(strip_tags($p['body']), 0, 100) ?>...</p>
            <a href="post.php?id=<?= $p['id'] ?>" style="display:inline-block; margin-top:10px; font-weight:bold;">Read More →</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
