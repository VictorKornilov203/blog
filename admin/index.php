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

include 'header.php';
?>

<h1>Админ-панель</h1>

<div class="admin-menu">
    <ul>
        <li><a href="posts.php">Управление постами</a></li>
        <li><a href="comments.php">Управление комментариями</a></li>
    </ul>
</div>

<div class="admin-stats">
    <?php
    
    $postsCount = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $commentsCount = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    ?>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Постов</h3>
            <p class="stat-number"><?php echo $postsCount; ?></p>
        </div>
        <div class="stat-card">
            <h3>Комментариев</h3>
            <p class="stat-number"><?php echo $commentsCount; ?></p>
        </div>
        <div class="stat-card">
            <h3>Пользователей</h3>
            <p class="stat-number"><?php echo $usersCount; ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
