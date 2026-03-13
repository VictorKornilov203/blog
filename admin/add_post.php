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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = getCurrentUserId();
    
    if (empty($title) || empty($content)) {
        $error = 'Заполните все поля';
    } else {
        $image_path = null;
        
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = 'uploads/' . $file_name;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, user_id) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$title, $content, $image_path, $user_id])) {
            $success = 'Пост успешно добавлен';
        } else {
            $error = 'Ошибка при добавлении';
        }
    }
}

include 'header.php';
?>

<h1>Добавить новый пост</h1>

<?php if ($error): ?>
    <div class="alert error"><?php echo escape($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert success"><?php echo escape($success); ?></div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" class="admin-form">
    <div class="form-group">
        <label for="title">Заголовок</label>
        <input type="text" id="title" name="title" required>
    </div>
    
    <div class="form-group">
        <label for="content">Текст</label>
        <textarea id="content" name="content" rows="10" required></textarea>
    </div>
    
    <div class="form-group">
        <label for="image">Изображение</label>
        <input type="file" id="image" name="image" accept="image/*">
    </div>
    
    <button type="submit" class="btn">Сохранить</button>
    <a href="posts.php" class="btn">Отмена</a>
</form>

<?php include 'footer.php'; ?>
