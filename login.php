<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            redirect('index.php');
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-form">
    <h1>Вход в систему</h1>
    
    <?php if ($error): ?>
        <div class="alert error"><?php echo escape($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Войти</button>
    </form>
    
    <p class="auth-link">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
</div>

<?php include 'includes/footer.php'; ?>