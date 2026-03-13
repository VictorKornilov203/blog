<?php
require_once '../config/db.php';
require_once '../includes/functions.php';


echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
    .admin-menu { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
    .admin-menu ul { list-style: none; padding: 0; display: flex; gap: 20px; }
    .admin-menu a { text-decoration: none; color: #333; padding: 10px 15px; background: #f0f0f0; border-radius: 4px; }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
    .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .stat-number { font-size: 2rem; color: #007bff; }
</style>';

if (!isAdmin()) {
    redirect('../login.php');
}
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../login.php');
}


if (isset($_GET['delete'])) {
    $comment_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    redirect('comments.php');
}


$stmt = $pdo->query("
    SELECT c.*, u.username, p.title as post_title 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    JOIN posts p ON c.post_id = p.id 
    ORDER BY c.created_at DESC
");
$comments = $stmt->fetchAll();

include 'header.php';
?>

<h1>Управление комментариями</h1>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Пост</th>
            <th>Автор</th>
            <th>Комментарий</th>
            <th>Дата</th>
            <th>Лайки</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?php echo $comment['id']; ?></td>
                <td>
                    <a href="../post.php?id=<?php echo $comment['post_id']; ?>" target="_blank">
                        <?php echo escape(substr($comment['post_title'], 0, 30)) . '...'; ?>
                    </a>
                </td>
                <td><?php echo escape($comment['username']); ?></td>
                <td><?php echo escape(substr($comment['content'], 0, 50)) . '...'; ?></td>
                <td><?php echo date('d.m.Y', strtotime($comment['created_at'])); ?></td>
                <td><?php echo $comment['likes']; ?></td>
                <td>
                    <a href="?delete=<?php echo $comment['id']; ?>" 
                       class="btn-small btn-danger"
                       onclick="return confirm('Удалить этот комментарий?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
