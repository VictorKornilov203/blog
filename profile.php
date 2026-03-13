<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = getCurrentUserId();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

include 'includes/header.php';
?>

<h1>Профиль пользователя</h1>

<div class="profile-info">
    <p><strong>Имя пользователя:</strong> <?php echo escape($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo escape($user['email']); ?></p>
    <p><strong>Дата регистрации:</strong> <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
    <p><strong>Роль:</strong> <?php echo escape($user['role']); ?></p>
</div>

<?php include 'includes/footer.php'; ?>