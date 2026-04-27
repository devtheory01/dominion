<?php
// FILE: /sermons.php
// Public Sermons Grid.
// Uses YouTube modal popups. Filter by Title/Date allowed via basic GET parameter.

require_once __DIR__ . '/includes/header.php';

$where = "1=1";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = sanitize($conn, $_GET['q']);
    $where .= " AND (title LIKE '%$q%' OR preacher LIKE '%$q%')";
}

$sermons = $conn->query("SELECT * FROM sermons WHERE $where ORDER BY service_date DESC, id DESC");
?>

<div style="background:var(--red); color:white; padding:40px 20px; text-align:center;" class="fade-in">
    <h1>Sermon Archive</h1>
</div>

<div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;" class="fade-in">
    <form method="GET" style="max-width: 400px; display:flex; gap:10px; margin-bottom:30px;">
        <input type="text" name="q" placeholder="Search by title or preacher" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="margin:0;">
        <button type="submit" class="btn">Filter</button>
    </form>

    <div class="grid-3" style="padding:0;">
        <?php while($s = $sermons->fetch_assoc()): ?>
        <div class="card" style="text-align:center;">
            <?php if($s['banner']): ?>
                <img src="/<?= $s['banner'] ?>" loading="lazy" style="width:100%; height:200px; object-fit:cover; border-radius:4px;">
            <?php else: ?>
                <div style="width:100%; height:200px; background:#ddd; display:flex; align-items:center; justify-content:center;">No Image</div>
            <?php endif; ?>
            
            <h3 style="margin:15px 0 5px;"><?= htmlspecialchars($s['title']) ?></h3>
            <p style="color:#666;">By: <?= htmlspecialchars($s['preacher']) ?> | <?= date('M d, Y', strtotime($s['service_date'])) ?></p>
            
            <button class="btn" style="width:100%; margin-top:15px;" onclick="openModal('<?= htmlspecialchars($s['youtube_id']) ?>')">► Watch Now</button>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
