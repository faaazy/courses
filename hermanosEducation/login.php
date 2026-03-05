<?php
require_once 'connect.php';
require_once 'functions.php';

if (isLoggedIn()) redirect(base_url("/index.php"));

$errors  = getErrors();
$oldData = getOldData();

require_once 'views/header.php';
?>

    <div class="page-wrapper auth-page">
        <div class="container">
            <div class="auth-card">
                <div class="auth-card__icon">🎓</div>
                <h1>Добро пожаловать</h1>
                <p class="auth-card__sub">Войдите в свой аккаунт</p>

                <?php if (!empty($errors)): ?>
                    <ul class="error-list">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="<?= base_url("/auth.php") ?>">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               placeholder="your@email.ru"
                               value="<?= htmlspecialchars($oldData['email'] ?? '') ?>"
                               autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password"
                               placeholder="Введите пароль"
                               autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn btn-gold btn-full btn-lg">Войти</button>
                </form>

                <div class="auth-link">
                    Нет аккаунта? <a href="/register.php">Зарегистрироваться</a>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'views/footer.php'; ?>