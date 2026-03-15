<?php
// api/get_posts.php
require_once __DIR__ . '/../inc/db.php';
header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 9;
$offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

try {
    $sql = "SELECT 
                p.id,
                p.image_path,
                p.caption,
                p.created_at,
                u.username,
                u.id AS user_id
            FROM posts p
            JOIN users u ON p.user_id = u.id";
    
    // Filter by user if specified
    if ($user_id) {
        $sql .= " WHERE p.user_id = :user_id";
    }

    $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    if ($user_id) {
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll();

    $formatted_posts = array_map(function($post) {
        return [
            'id' => $post['id'],
            'image_path' => htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8'),
            'caption' => htmlspecialchars($post['caption'], ENT_QUOTES, 'UTF-8'),
            'created_at' => $post['created_at'],
            'username' => htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'),
            'user_id' => $post['user_id']
        ];
    }, $posts);

    echo json_encode($formatted_posts);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch posts']);
}
