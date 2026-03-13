<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$stmt = $pdo->prepare("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    redirect('index.php');
}


$stmt = $pdo->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ? 
    ORDER BY c.created_at DESC
");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();

include 'includes/header.php';
?>

<article class="full-post">
    <h1><?php echo escape($post['title']); ?></h1>
    
    <?php if ($post['image']): ?>
        <img src="<?php echo escape($post['image']); ?>" alt="<?php echo escape($post['title']); ?>" class="full-post-image">
    <?php endif; ?>
    
    <div class="post-content">
        <?php echo nl2br(escape($post['content'])); ?>
    </div>
    
    <div class="post-meta">
        <span>Автор: <?php echo escape($post['username']); ?></span>
        <span>Дата: <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></span>
    </div>
</article>

<section class="comments-section">
    <h2>Комментарии (<?php echo count($comments); ?>)</h2>
    
    
    <div id="comments-list">
        <?php foreach ($comments as $comment): ?>
            <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                <div class="comment-header">
                    <strong><?php echo escape($comment['username']); ?></strong>
                    <span><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></span>
                </div>
                <div class="comment-content">
                    <?php echo nl2br(escape($comment['content'])); ?>
                </div>
                <div class="comment-actions">
                    <button class="like-btn" data-comment-id="<?php echo $comment['id']; ?>">
                        ❤️ <span class="likes-count"><?php echo $comment['likes']; ?></span>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
  
    <?php if (isLoggedIn()): ?>
        <form id="comment-form" class="comment-form">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <div class="form-group">
                <label for="comment-content">Ваш комментарий:</label>
                <textarea id="comment-content" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn">Отправить</button>
        </form>
        <div id="comment-message"></div>
    <?php else: ?>
        <p class="login-to-comment">Чтобы оставить комментарий, <a href="login.php">войдите</a> в систему.</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
