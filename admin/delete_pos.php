<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id) {
    // Сначала получаем информацию о посте, чтобы удалить картинку
    $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    
    // Удаляем картинку, если она есть
    if ($post && $post['image'] && file_exists('../' . $post['image'])) {
        unlink('../' . $post['image']);
    }
    
    // Удаляем пост (комментарии удалятся автоматически из-за FOREIGN KEY)
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
}

// Возвращаемся на страницу с постами
redirect('posts.php');
?>