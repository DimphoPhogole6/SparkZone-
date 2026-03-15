<?php
// inc/header.php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo isset($page_title) ? $page_title : 'Social Event Sharing'; ?></title>

    <!-- Canonical stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="<?php echo BASE_URL; ?>assets/logo2.jpg" alt="SparkZone Logo">
                    <h1><?php echo SITE_NAME; ?></h1>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>explore.php">Explore</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php">About</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="<?php echo BASE_URL; ?>upload_post.php">Upload Post</a></li>
                            <li><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                            <li><a href="<?php echo BASE_URL; ?>signup.php">Sign Up</a></li>
                        <?php endif; ?>
						<?php if (isset($_SESSION['user_id'])): ?>
							<a href="profile.php">My Profile</a>
							<?php endif; ?>

                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main class="main-content">
