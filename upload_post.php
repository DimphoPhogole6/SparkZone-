<?php
$page_title = 'Upload Post';
require_once 'inc/header.php';
require_once 'inc/db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = trim($_POST['caption'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select an image to upload.';
    } elseif (empty($caption)) {
        $error = 'Please add a caption for your post.';
    } else {
        $file = $_FILES['image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB limit

        if (!in_array($file['type'], $allowed_types)) {
            $error = 'Only JPG, PNG, and GIF images are allowed.';
        } elseif ($file['size'] > $max_size) {
            $error = 'File size must be less than 5MB.';
        } else {
            // --- FIXED PATH SECTION ---
            $upload_dir = __DIR__ . '/uploads/'; // Server directory path
            $public_dir = 'uploads/'; // Path to store in database (web accessible)

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('post_') . '.' . $extension;

            $server_path = $upload_dir . $filename;  // Where to store on server
            $public_path = $public_dir . $filename;  // What to store in DB

            if (move_uploaded_file($file['tmp_name'], $server_path)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO posts (user_id, image_path, caption) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $public_path, $caption]);

                    // Redirect to profile after successful upload
                    header('Location: profile.php');
                    exit;
                } catch (PDOException $e) {
                    // Remove file if DB insert fails
                    if (file_exists($server_path)) unlink($server_path);
                    $error = 'An error occurred while saving your post. Please try again.';
                }
            } else {
                $error = 'Failed to upload image. Please try again.';
            }
        }
    }
}
?>

<div class="page-header">
    <div class="container">
        <h2>Share Your Event</h2>
        <p>Upload a photo and description of your community event</p>
    </div>
</div>

<section class="upload-section">
    <div class="container">
        <div class="upload-form">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary">View Your Post</a>
                    <a href="upload_post.php" class="btn btn-secondary">Upload Another</a>
                </div>
            <?php else: ?>
                <form method="POST" action="upload_post.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Event Image</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                        <small>Accepted formats: JPG, PNG, GIF (Max size: 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="caption">Event Description</label>
                        <textarea id="caption" name="caption" rows="4" placeholder="Describe your event..." required><?php echo isset($caption) ? htmlspecialchars($caption) : ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Share Event</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'inc/footer.php'; ?>
