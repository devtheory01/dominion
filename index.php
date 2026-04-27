<?php
// FILE: /index.php
// Public Homepage. 
// Features Hero section, site stats, 3 branch previews, and founder summary.

require_once __DIR__ . '/includes/header.php';

$stats = [
    'members' => $conn->query("SELECT COUNT(*) as c FROM members WHERE status='Active'")->fetch_assoc()['c'],
    'branches' => $conn->query("SELECT COUNT(*) as c FROM branches")->fetch_assoc()['c'],
    'sermons' => $conn->query("SELECT COUNT(*) as c FROM sermons")->fetch_assoc()['c']
];

$recent_branches = $conn->query("SELECT * FROM branches ORDER BY id DESC LIMIT 3");
$pastor_name = getSetting($conn, 'pastor_name');
?>

<section class="hero fade-in">
    <h1>Welcome to <?= htmlspecialchars($site_name) ?></h1>
    <p>Raising Leaders, Transforming Society.</p>
    <br>
    <a href="/about.php" class="btn">Discover More</a>
</section>

<section class="stats-counter grid-3 fade-in" style="text-align: center; background:var(--white); margin-top:-50px; position:relative; box-shadow:0 5px 15px rgba(0,0,0,0.1); border-radius:8px;">
    <div><h2 style="color:var(--red); font-size:36px;"><?= $stats['members'] ?>+</h2><p>Active Members</p></div>
    <div><h2 style="color:var(--red); font-size:36px;"><?= $stats['branches'] ?></h2><p>Branches</p></div>
    <div><h2 style="color:var(--red); font-size:36px;"><?= $stats['sermons'] ?>+</h2><p>Sermons</p></div>
</section>

<section class="section-branches fade-in" style="padding: 40px 20px; text-align:center;">
    <h2>Our Branches</h2>
    <p>Join us at a location near you</p>
    <div class="grid-3">
        <?php while($b = $recent_branches->fetch_assoc()): ?>
        <div class="card">
            <?php if($b['photo']): ?>
                <img src="/<?= $b['photo'] ?>" loading="lazy" style="width:100%; height:200px; object-fit:cover; border-radius:4px;">
            <?php endif; ?>
            <h3 style="margin-top:15px;"><?= htmlspecialchars($b['name']) ?></h3>
            <p><small><?= htmlspecialchars($b['location']) ?></small></p>
            <p><strong>Pastor:</strong> <?= htmlspecialchars($b['pastor']) ?></p>
        </div>
        <?php endwhile; ?>
    </div>
    <a href="/about.php" class="btn">View All Branches</a>
</section>

<section class="founder fade-in" style="background:#fff; padding:60px 20px;">
    <div style="max-width:800px; margin:0 auto; text-align:center;">
        <h2>About The Founder</h2>
        <h3 style="color:var(--red); margin:10px 0;"><?= htmlspecialchars($pastor_name) ?></h3>
        <p>Pastor David Ogbueli is the founder and senior pastor of Dominion City. He is dedicated to raising leaders that will transform society with the message of Christ.</p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
