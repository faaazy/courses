<?php
require_once 'connect.php';
require_once 'functions.php';

if (isLoggedIn()) redirect(base_url("/index.php"));

$errors = getErrors();
$oldData = getOldData();

require_once 'views/header.php';
?>
    <div class="page-wrapper auth-page">
        <div class="container">
            <div class="auth-card">

                <div class="auth-card__icon">🎓</div>
                <h1>Создать аккаунт</h1>
                <p class="auth-card__sub">Заполните форму для регистрации</p>

                <?php if (!empty($errors)): ?>
                    <ul class="error-list">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="<?= base_url("/auth.php") ?>">
                    <input type="hidden" name="action" value="register">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Имя</label>
                            <input type="text" id="first_name" name="first_name"
                                placeholder="Иван"
                                value="<?= htmlspecialchars($oldData['first_name'] ?? '') ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Фамилия</label>
                            <input type="text" id="last_name" name="last_name"
                                placeholder="Иванов"
                                value="<?= htmlspecialchars($oldData['last_name'] ?? '') ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Номер телефона</label>
                        <input type="tel" id="phone" name="phone"
                            placeholder="89991234567"
                            pattern="^\d{11}$"
                            value="<?= htmlspecialchars($oldData['phone'] ?? '') ?>"
                            title="Введите корректный номер телефона(11 символов)"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            placeholder="your@email.ru"
                            value="<?= htmlspecialchars($oldData['email'] ?? '') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password"
                            placeholder="Минимум 6 символов"
                            required>
                    </div>

                    <button type="submit" class="btn btn-gold btn-full btn-lg">Зарегистрироваться</button>
                </form>

                <div class="auth-link">
                    Уже есть аккаунт? <a href="<?= base_url("/login.php") ?>">Войти</a>
                </div>

            </div>
        </div>
    </div>

<?php require_once 'views/footer.php'; ?>
