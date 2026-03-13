<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id) {
    
    $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    
   
    if ($post && $post['image'] && file_exists('../' . $post['image'])) {
        unlink('../' . $post['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
}
redirect('posts.php');
?>
