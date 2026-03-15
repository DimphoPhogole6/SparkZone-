<?php
$page_title = 'Explore Events';
require_once 'inc/header.php';
require_once 'inc/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle like action
if (isset($_POST['like_post'])) {
    $post_id = (int)$_POST['post_id'];
    
    try {
        // Check if already liked
        $check = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $check->execute([$user_id, $post_id]);
        
        if ($check->rowCount() > 0) {
            // Unlike - delete the like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            if ($stmt->execute([$user_id, $post_id])) {
                $_SESSION['message'] = "Post unliked successfully!";
            }
        } else {
            // Like - insert new like
            $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            if ($stmt->execute([$user_id, $post_id])) {
                $_SESSION['message'] = "Post liked successfully!";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error processing like: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete action
if (isset($_POST['delete_post'])) {
    $post_id = (int)$_POST['post_id'];
    
    try {
        // Verify ownership
        $check = $pdo->prepare("SELECT user_id, image_path FROM posts WHERE id = ?");
        $check->execute([$post_id]);
        $post = $check->fetch();
        
        if ($post && $post['user_id'] == $user_id) {
            // Start transaction
            $pdo->beginTransaction();
            
            // Delete associated likes first
            $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ?");
            $stmt->execute([$post_id]);
            
            // Delete the post from database
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);
            
            // Commit transaction
            $pdo->commit();
            
            // Delete the image file if it exists
            if ($post['image_path'] && file_exists($post['image_path'])) {
                unlink($post['image_path']);
            }
            
            $_SESSION['message'] = "Post deleted successfully!";
        } else {
            $_SESSION['error'] = "You don't have permission to delete this post.";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error deleting post: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #ff6600, #ff9a00);
    color: white;
    text-align: center;
    padding: 80px 20px;
    margin-bottom: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.page-header h2 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
    font-weight: 700;
}

.page-header p {
    margin: 0;
    font-size: 1.1rem;
    opacity: 0.95;
}

/* Alert Messages */
.alert {
    max-width: 1200px;
    margin: 0 auto 20px;
    padding: 15px 20px;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Posts Section */
.posts-section {
    padding: 0 0 60px 0;
    background: #fafafa;
    min-height: 60vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Grid Layout */
.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

/* Post Cards */
.post-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

/* User Header */
.post-user-header {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f8f8;
    border-bottom: 1px solid #eee;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6600, #ff9a00);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    margin-right: 12px;
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-info {
    flex: 1;
}

.user-info .username {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
    margin: 0;
}

.user-info .post-date {
    font-size: 0.85rem;
    color: #999;
    margin: 2px 0 0 0;
}

.post-card img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.post-card:hover img {
    transform: scale(1.05);
}

.post-content {
    padding: 20px;
}

.post-content .caption {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.05rem;
    line-height: 1.5;
}

/* Action Buttons */
.post-actions {
    display: flex;
    gap: 10px;
    padding: 0 20px 20px;
}

.btn-action {
    flex: 1;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-like {
    background: #f0f0f0;
    color: #555;
}

.btn-like:hover {
    background: #e0e0e0;
}

.btn-like.liked {
    background: #ffe5e5;
    color: #ff6600;
}

.btn-like.liked:hover {
    background: #ffd0d0;
}

.btn-delete {
    background: #fff0f0;
    color: #dc3545;
    max-width: 120px;
}

.btn-delete:hover {
    background: #ffe0e0;
    transform: scale(1.02);
}

.like-count {
    font-weight: 600;
    display: none; /* Hide like count */
}

/* Stats Badge */
.stats-banner {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 30px;
    text-align: center;
}

.stats-banner h3 {
    margin: 0;
    color: #333;
    font-size: 1.2rem;
}

.stats-banner .count {
    color: #ff6600;
    font-weight: 700;
    font-size: 2rem;
}

/* Loading & Error States */
.loading {
    text-align: center;
    padding: 30px;
    color: #ff6600;
    font-size: 1.1rem;
    font-weight: 500;
}

.error {
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 80px 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin: 0 auto;
    max-width: 500px;
}

.error::before {
    content: "📭";
    display: block;
    font-size: 4rem;
    margin-bottom: 20px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .post-card img {
        height: 240px;
    }
    
    .page-header h2 {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .post-card img {
        height: 200px;
    }
    
    .page-header {
        padding: 60px 20px;
    }
    
    .page-header h2 {
        font-size: 1.75rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .post-actions {
        flex-direction: column;
    }
    
    .btn-delete {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .posts-grid {
        grid-template-columns: 1fr;
    }
    
    .post-card img {
        height: 250px;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.post-card {
    animation: fadeIn 0.5s ease-out;
}
</style>

<div class="page-header">
    <div class="container">
        <h2>Explore Community Events</h2>
        <p>Discover amazing events shared by our community members</p>
    </div>
</div>

<section class="posts-section">
    <div class="container">
        <?php
        // Display success/error messages
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        
        // Fetch posts with username, profile picture, and like counts
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    p.id, 
                    p.image_path, 
                    p.caption, 
                    p.created_at,
                    p.user_id,
                    u.username,
                    COALESCE(u.profile_picture, '') as profile_picture,
                    COUNT(DISTINCT l.id) as like_count,
                    MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) as user_liked
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN likes l ON p.id = l.post_id
                GROUP BY p.id, p.image_path, p.caption, p.created_at, p.user_id, u.username, u.profile_picture
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // If profile_picture column doesn't exist, fetch without it
            $stmt = $pdo->prepare("
                SELECT 
                    p.id, 
                    p.image_path, 
                    p.caption, 
                    p.created_at,
                    p.user_id,
                    u.username,
                    '' as profile_picture,
                    COUNT(DISTINCT l.id) as like_count,
                    MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) as user_liked
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN likes l ON p.id = l.post_id
                GROUP BY p.id, p.image_path, p.caption, p.created_at, p.user_id, u.username
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Display stats banner
        if (count($posts) > 0) {
            echo '<div class="stats-banner">';
            echo '<h3><span class="count">' . count($posts) . '</span> Events Available</h3>';
            echo '</div>';
        }
        ?>
        
        <div id="posts-container" class="posts-grid">
            <?php
            if (count($posts) > 0) {
                foreach ($posts as $post) {
                    $is_owner = ($post['user_id'] == $user_id);
                    $is_liked = ($post['user_liked'] == 1);
                    $like_count = (int)$post['like_count'];
                    
                    // Get first letter of username for avatar fallback
                    $initial = strtoupper(substr($post['username'], 0, 1));
                    
                    echo '<div class="post-card" data-post-id="' . $post['id'] . '">';
                    
                    // User header
                    echo '<div class="post-user-header">';
                    echo '<div class="user-avatar">';
                    if (!empty($post['profile_picture']) && file_exists($post['profile_picture'])) {
                        echo '<img src="' . htmlspecialchars($post['profile_picture']) . '" alt="' . htmlspecialchars($post['username']) . '">';
                    } else {
                        echo $initial;
                    }
                    echo '</div>';
                    echo '<div class="user-info">';
                    echo '<p class="username">' . htmlspecialchars($post['username']) . '</p>';
                    echo '<p class="post-date">' . date('F j, Y', strtotime($post['created_at'])) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Image
                    echo '<img src="' . htmlspecialchars($post['image_path']) . '" alt="' . htmlspecialchars($post['caption']) . '">';
                    
                    // Content
                    echo '<div class="post-content">';
                    echo '<p class="caption">' . htmlspecialchars($post['caption']) . '</p>';
                    echo '</div>';
                    
                    // Actions
                    echo '<div class="post-actions">';
                    
                    // Like button
                    echo '<form method="POST" style="flex: 1;">';
                    echo '<input type="hidden" name="post_id" value="' . $post['id'] . '">';
                    echo '<button type="submit" name="like_post" class="btn-action btn-like ' . ($is_liked ? 'liked' : '') . '">';
                    echo ($is_liked ? '❤️ Liked' : '🤍 Like');
                    echo '</button>';
                    echo '</form>';
                    
                    // Delete button (only for post owner)
                    if ($is_owner) {
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="post_id" value="' . $post['id'] . '">';
                        echo '<button type="submit" name="delete_post" class="btn-action btn-delete" onclick="return confirm(\'Are you sure you want to delete this post? This action cannot be undone.\')">';
                        echo '🗑️ Delete';
                        echo '</button>';
                        echo '</form>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="error">';
                echo '<p>No events posted yet.</p>';
                echo '<p>Be the first to share an event with the community!</p>';
                echo '</div>';
            }
            ?>
        </div>
        
        <div id="loading" class="loading" style="display: none;">Loading more events...</div>
    </div>
</section>

<?php require_once 'inc/footer.php'; ?>