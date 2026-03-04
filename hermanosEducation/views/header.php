<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hermanos Education</title>
    <link rel="stylesheet" href="<?= base_url("styles/style.css") ?>">
</head>
<body>

<header class="navbar">
    <div class="container">
        <div class="navbar__inner">

            <a href="<?= base_url("index.php") ?>" class="navbar__logo">
                <div class="navbar__logo-icon">🎓</div>
                Hermanos Education
            </a>

            <nav class="navbar__nav">
                <a href="<?= base_url("index.php") ?>">Курсы</a>
            </nav>

            <div class="navbar__actions">
                <?php if (isLoggedIn()): ?>

                    <a href="<?= base_url("profile.php") ?>" class="navbar__user">
                        <div class="navbar__avatar">
                            <?= htmlspecialchars(mb_substr($_SESSION['first_name'], 0, 1)) ?>
                        </div>
                        <span class="navbar__user-name"><?= htmlspecialchars($_SESSION['first_name']) ?></span>
                    </a>

                    <?php if (isAdmin()): ?>
                        <a href="<?= base_url("/admin/index.php") ?>" class="btn btn-outline">Админ-панель</a>
                    <?php endif; ?>

                    <a href="<?= base_url("logout.php") ?>" class="btn btn-outline">Выйти</a>

                <?php else: ?>

                    <a href="<?= base_url("login.php") ?>" class="btn btn-outline">Вход</a>
                    <a href="<?= base_url("register.php") ?>" class="btn btn-gold">Регистрация</a>

                <?php endif; ?>
            </div>

        </div>
    </div>
</header>