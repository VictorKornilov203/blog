<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

// Пагинация
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Получаем общее количество постов
$totalStmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $totalStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Получаем посты для текущей страницы - ИСПРАВЛЕННЫЙ КОД
$stmt = $pdo->prepare("
    SELECT p.*, u.username,
    LEFT(p.content, 200) as preview
    FROM posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

include 'includes/header.php';
?>
<main class="container">
<h1>Последние посты</h1>

<div class="posts">
    <?php foreach ($posts as $post): ?>
        <article class="post-card">
            <h2><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo escape($post['title']); ?></a></h2>
            
            <?php if ($post['image']): ?>
                <img src="<?php echo escape($post['image']); ?>" alt="<?php echo escape($post['title']); ?>" class="post-image">
            <?php endif; ?>
            
            <p class="post-preview"><?php echo escape($post['preview']); ?>...</p>
            
            <div class="post-meta">
                <span>Автор: <?php echo escape($post['username']); ?></span>
                <span>Дата: <?php echo date('d.m.Y', strtotime($post['created_at'])); ?></span>
            </div>
            
            <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Читать далее</a>
        </article>
    <?php endforeach; ?>
</div>

<!-- Пагинация -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="page-link">Предыдущая</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="page-link">Следующая</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>
