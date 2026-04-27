<?php
// FILE: /includes/footer.php
// Frontend footer component with Navigation Links and Social Icons.
// Contains Javascript links, IntersectionObserver trigger, and back-to-top button.
// Closes the open main, body, and html tags from header.php.

$site_name = getSetting($conn, 'site_name') ?: 'Dominion City';
$facebook = getSetting($conn, 'facebook');
$youtube = getSetting($conn, 'youtube');
?>
    </main>
    <footer class="footer fade-in">
        <div class="footer-grid">
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="/index.php">Home</a>
                <a href="/about.php">About</a>
                <a href="/sermons.php">Sermons</a>
                <a href="/contact.php">Contact</a>
            </div>
            <div class="footer-social">
                <h4>Connect With Us</h4>
                <?php if($facebook) echo "<a href='".htmlspecialchars($facebook)."' target='_blank'>Facebook</a><br>"; ?>
                <?php if($youtube) echo "<a href='".htmlspecialchars($youtube)."' target='_blank'>YouTube</a><br>"; ?>
            </div>
        </div>
        <p class="copyright">&copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.</p>
        <button id="back-to-top" onclick="window.scrollTo({top:0, behavior:'smooth'})">↑</button>
    </footer>

    <!-- Video Modal -->
    <div id="videoModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal(event)">&times;</span>
            <div id="videoContainer"></div>
        </div>
    </div>

    <script src="/assets/script.js"></script>
</body>
</html>
