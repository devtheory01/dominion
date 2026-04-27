<?php
// FILE: /gallery.php
// Public Gallery Page.
// Displays images grid from gallery table.

require_once __DIR__ . '/includes/header.php';

$gallery = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>

<div style="background:var(--red); color:white; padding:40px 20px; text-align:center;" class="fade-in">
    <h1>Photo Gallery</h1>
</div>

<div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;" class="fade-in">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <?php while($g = $gallery->fetch_assoc()): ?>
        <div style="border:1px solid #ddd; padding:10px; background:#fff; border-radius:4px; text-align:center;">
            <a href="/<?= $g['image'] ?>" target="_blank">
                <img src="/<?= $g['image'] ?>" loading="lazy" style="width:100%; height:200px; object-fit:cover; border-radius:4px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            </a>
            <?php if($g['caption']): ?>
                <p style="margin-top:10px; font-style:italic; color:#555;"><?= htmlspecialchars($g['caption']) ?></p>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
