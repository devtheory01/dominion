<?php
// FILE: /about.php
// Public About Page.
// Displays the founder's story, mission, and the list of branches.

require_once __DIR__ . '/includes/header.php';

$pastor_name = getSetting($conn, 'pastor_name');
$branches = $conn->query("SELECT * FROM branches ORDER BY name ASC");
?>

<div style="background:var(--red); color:white; padding:40px 20px; text-align:center;" class="fade-in">
    <h1>About Us</h1>
    <p>Mission, Vision, and Our Growing Family</p>
</div>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;" class="fade-in">
    <h2>Our Founder: <?= htmlspecialchars($pastor_name) ?></h2>
    <p>Pastor David Ogbueli’s vision birthed Dominion City with the mandate to raise leaders that will transform society. Under his dynamic leadership, the ministry has grown rapidly, bringing the gospel of Christ to the uttermost parts of the earth.</p>
    
    <hr style="margin:40px 0;">

    <h2>Our Branches</h2>
    <div class="grid-3" style="padding: 20px 0;">
        <?php while($b = $branches->fetch_assoc()): ?>
        <div class="card">
            <?php if($b['photo']): ?>
                <img src="/<?= $b['photo'] ?>" loading="lazy" style="width:100%; height:200px; object-fit:cover; border-radius:4px;">
            <?php endif; ?>
            <h3 style="margin-top:15px; color:var(--red);"><?= htmlspecialchars($b['name']) ?></h3>
            <p><small><?= htmlspecialchars($b['location']) ?></small></p>
            <p style="margin-top:10px;"><strong>Pastor:</strong> <?= htmlspecialchars($b['pastor']) ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
