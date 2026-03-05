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
                            <a href="<?= base_url("/payment.php?id=" . (int)$course['course_id']) ?>" class="btn btn-gold btn-full btn-lg">
                                Купить курс
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php require_once 'views/footer.php'; ?>
