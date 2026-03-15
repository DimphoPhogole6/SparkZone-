<?php
$page_title = 'Home';
require_once 'inc/header.php';
?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h2>Share Your Events with the World</h2>
            <p>Join SparkZone to discover and share amazing community events, gatherings, and social activities.</p>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="hero-buttons">
                    <a href="signup.php" class="btn btn-primary">Get Started</a>
                    <a href="explore.php" class="btn btn-secondary">Explore Events</a>
                </div>
            <?php else: ?>
                <div class="hero-buttons">
                    <a href="upload_post.php" class="btn btn-primary">Share an Event</a>
                    <a href="explore.php" class="btn btn-secondary">Discover Events</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>





<?php require_once 'inc/footer.php'; ?>
