<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой блог</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="logo">Блог</a>
                
                <!-- Гамбургер-меню для мобильных -->
                <button class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="index.php">Главная</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="profile.php"><?php echo escape(getCurrentUsername()); ?></a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin/index.php">Админка</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Выйти</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Вход</a></li>
                        <li><a href="register.php">Регистрация</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">