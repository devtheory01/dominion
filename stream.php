<?php
// FILE: /stream.php
// Dedicated Full-Width Player for Live Services.
// Pulls the sermon marked as is_live=1. 

require_once __DIR__ . '/includes/header.php';

$live_sermon = $conn->query("SELECT * FROM sermons WHERE is_live=1 LIMIT 1")->fetch_assoc();
?>

<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px; text-align:center;" class="fade-in">
    <?php if($live_sermon): ?>
        <h1 style="color:var(--red); margin-bottom:10px;">🔴 LIVE STREAM</h1>
        <h2><?= htmlspecialchars($live_sermon['title']) ?></h2>
        <p style="margin-bottom: 20px;">Ministering: <?= htmlspecialchars($live_sermon['preacher']) ?></p>
        
        <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; background:#000;">
            <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($live_sermon['youtube_id']) ?>?autoplay=1" style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;" allowfullscreen></iframe>
        </div>
    <?php else: ?>
        <div style="padding: 100px 20px; border:2px dashed #ccc; border-radius:8px;">
            <h1>No Live Service Actually Broadcasting</h1>
            <p>Please check back during our regular service times or visit our <a href="/sermons.php">Sermon Archive</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
