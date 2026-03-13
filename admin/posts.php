<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// Добавь это временное решение для стилей
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

// Получаем все посты
$stmt = $pdo->query("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();

include 'header.php';
?>

<h1>Управление постами</h1>

<a href="add_post.php" class="btn btn-primary">Добавить новый пост</a>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Заголовок</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post['id']; ?></td>
                <td><?php echo escape($post['title']); ?></td>
                <td><?php echo escape($post['username']); ?></td>
                <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                <td>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn-small">Редактировать</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                       class="btn-small btn-danger" 
                       onclick="return confirm('Удалить пост? Все комментарии также будут удалены.')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>