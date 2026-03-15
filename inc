inc/config.php
<?php
// config.php - project root

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sparkzone');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'SparkZone');
define('BASE_URL', '/SparkZone/'); // adjust if hosted under different path

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

inc/db.php
<?php
// inc/db.php
// Use __DIR__ to reliably require config from project root
require_once __DIR__ . '/../config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // For production you might log this instead of echoing
    die("Connection failed: " . $e->getMessage());
}
inc/header.php
<?php
// inc/db.php
// Use __DIR__ to reliably require config from project root
require_once __DIR__ . '/../config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // For production you might log this instead of echoing
    die("Connection failed: " . $e->getMessage());
}
inc/footer.php
   </main>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Canonical JS -->
    <script src="<?php echo BASE_URL; ?>assets/main.js"></script>
</body>
</html>
