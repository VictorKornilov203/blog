<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $content = trim($_POST['content']);
    $user_id = getCurrentUserId();
    
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Комментарий не может быть пустым']);
        exit;
    }
    
    
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$post_id, $user_id, $content])) {
        $comment_id = $pdo->lastInsertId();
        
       
        $stmt = $pdo->prepare("
            SELECT c.*, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch();
        
        echo json_encode([
            'success' => true,
            'comment' => [
                'id' => $comment['id'],
                'username' => escape($comment['username']),
                'content' => nl2br(escape($comment['content'])),
                'created_at' => date('d.m.Y H:i', strtotime($comment['created_at'])),
                'likes' => $comment['likes']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении']);
    }
}
?>
