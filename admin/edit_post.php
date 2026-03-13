<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    redirect('posts.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = 'Заполните все поля';
    } else {
        $image_path = $post['image']; 
        
      
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                
                if ($post['image'] && file_exists('../' . $post['image'])) {
                    unlink('../' . $post['image']);
                }
                $image_path = 'uploads/' . $file_name;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
        
        if ($stmt->execute([$title, $content, $image_path, $post_id])) {
            $success = 'Пост успешно обновлен';
           
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);
            $post = $stmt->fetch();
        } else {
            $error = 'Ошибка при обновлении';
        }
    }
}

include 'header.php';
?>

<h1>Редактировать пост</h1>

<?php if ($error): ?>
    <div class="alert error"><?php echo escape($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert success"><?php echo escape($success); ?></div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" class="admin-form">
    <div class="form-group">
        <label for="title">Заголовок</label>
        <input type="text" id="title" name="title" value="<?php echo escape($post['title']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="content">Текст</label>
        <textarea id="content" name="content" rows="10" required><?php echo escape($post['content']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="image">Изображение</label>
        <?php if ($post['image']): ?>
            <div class="current-image">
                <p>Текущее изображение:</p>
                <img src="/blog/<?php echo $post['image']; ?>" alt="Current image" style="max-width: 200px; margin-bottom: 10px;">
            </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/*">
        <small>Оставьте пустым, чтобы не менять изображение</small>
    </div>
    
    <button type="submit" class="btn">Сохранить изменения</button>
    <a href="posts.php" class="btn">Отмена</a>
</form>

<?php include 'footer.php'; ?>
