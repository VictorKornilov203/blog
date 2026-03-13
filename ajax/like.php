<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    
    // Увеличиваем счетчик лайков
    $stmt = $pdo->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?");
    
    if ($stmt->execute([$comment_id])) {
        // Получаем новое количество лайков
        $stmt = $pdo->prepare("SELECT likes FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $likes = $stmt->fetchColumn();
        
        echo json_encode(['success' => true, 'likes' => $likes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка']);
    }
}
?>