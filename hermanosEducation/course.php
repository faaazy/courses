<?php
require_once 'connect.php';
require_once 'functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) redirect(base_url('/index.php'));

$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) redirect(base_url('/index.php'));

$stmt = $conn->prepare("SELECT * FROM rates WHERE course_id = ? LIMIT 1");
$stmt->execute([$id]);
$rate = $stmt->fetch();

if (isLoggedIn() && $rate) {
    $stmt = $conn->prepare(
        "SELECT order_id FROM orders
         WHERE client_id = ? AND course_id = ? AND rates_id = ? AND expires_at >= CURDATE()
         LIMIT 1"
    );
    $stmt->execute([$_SESSION['client_id'], $id, $rate['rates_id']]);
}

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    if (!isLoggedIn()) redirect(base_url('/login.php'));

    if (!$rate) {
        $error = 'Тариф не найден';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO payments (client_id, rates_id, amount, payment_method, status)
             VALUES (?, ?, ?, 'card', 'paid')"
        );
        $stmt->execute([$_SESSION['client_id'], $rate['rates_id'], $rate['price']]);
        $paymentId = (int)$conn->lastInsertId();

        $expiresAt = date('Y-m-d', strtotime('+' . $rate['access_duration_days'] . ' days'));
        $stmt = $conn->prepare(
            "INSERT INTO orders (client_id, course_id, rates_id, payment_id, expires_at)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$_SESSION['client_id'], $id, $rate['rates_id'], $paymentId, $expiresAt]);
        $orderId = (int)$conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO order_payments (order_id, payment_id) VALUES (?, ?)");
        $stmt->execute([$orderId, $paymentId]);

        $success = true;
    }
}

require_once 'views/header.php';
?>
<div class="page-wrapper course-page">
    <div class="container">

        <a href="<?= base_url("/index.php") ?>" class="back-link">Все курсы</a>

        <div class="course-detail">
            <div class="course-detail__main">
                <div class="course-card__tags" style="margin-bottom: 14px;">
                    <span class="tag tag-level"><?= htmlspecialchars($course['level']) ?></span>
                </div>

                <h1 class="course-detail__title"><?= htmlspecialchars($course['title']) ?></h1>

                <div class="course-detail__meta">
                    <span>🕐 <?= htmlspecialchars($course['duration_hours']) ?> часов</span>
                </div>

                <?php if (!empty($course['description'])): ?>
                    <div class="course-detail__desc">
                        <h2>О курсе</h2>
                        <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="course-detail__sidebar">
                <div class="purchase-card">

                    <img src="<?= htmlspecialchars($course['image_url'] ?? '') ?>"
                        alt="<?= htmlspecialchars($course['title']) ?>"
                        class="purchase-card__img">

                    <div class="purchase-card__body">

                        <?php if ($success): ?>
                            <div class="alert alert-success">✓ Курс успешно куплен!</div>
                        <?php endif; ?>

                        <?php if ($error !== ''): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if ($rate): ?>
                            <div class="price-block" style="margin-bottom: 20px;">
                                <span class="price-current"><?= formatPrice($rate['price']) ?></span>
                            </div>
                            <p class="purchase-card__access">
                                Доступ на <?= htmlspecialchars($rate['access_duration_days']) ?> дней
                            </p>
                        <?php endif; ?>

                        <?php if (!isLoggedIn()): ?>
                            <a href="<?= base_url("/login.php") ?>" class="btn btn-gold btn-full btn-lg">
                                Войдите чтобы купить
                            </a>
                        <?php else: ?>
                            <form method="POST">
                                <button type="submit" name="buy" class="btn btn-gold btn-full btn-lg">
                                    Купить курс
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php require_once 'views/footer.php'; ?>

