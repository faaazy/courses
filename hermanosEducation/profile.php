<?php
require_once 'connect.php';
require_once 'functions.php';

redirectLogin();

$stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->execute([$_SESSION['client_id']]);
$client = $stmt->fetch();

$stmt = $conn->prepare(
    "SELECT c.*, r.price, r.name AS rate_name, o.expires_at, o.order_id
     FROM orders o
     JOIN courses c ON c.course_id = o.course_id
     JOIN rates r ON r.rates_id = o.rates_id
     WHERE o.client_id = ?"
);
$stmt->execute([$_SESSION['client_id']]);
$myCourses = $stmt->fetchAll();

require_once 'views/header.php';
?>
<div class="page-wrapper profile-page">
    <div class="container">
        <div class="profile-layout">

            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <?= htmlspecialchars(mb_substr($client['first_name'], 0, 1)) ?>
                    </div>
                    <div class="profile-name">
                        <?= htmlspecialchars($client['first_name']) ?> <?= htmlspecialchars($client['last_name']) ?>
                    </div>
                    <div class="profile-email"><?= htmlspecialchars($client['email']) ?></div>
                    <?php if (!empty($client['phone'])): ?>
                        <div class="profile-phone"><?= htmlspecialchars($client['phone']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-main">
                <h2>
                    Мои курсы
                    <span>
                        <?= count($myCourses) ?>
                    </span>
                </h2>

                <?php if (empty($myCourses)): ?>
                    <div class="empty-state">
                        <h3>Курсов пока нет</h3>
                        <p>Перейдите в каталог и выберите первый курс</p>
                        <a href="<?= base_url("/index.php") ?>" class="btn btn-gold">Перейти к курсам</a>
                    </div>
                <?php else: ?>
                    <div class="my-courses-list">
                        <?php foreach ($myCourses as $course):
                            $expired = strtotime($course['expires_at']) < time();
                        ?>
                            <div class="my-course-card <?= $expired ? 'my-course-card--expired' : '' ?>">
                                <img class="my-course-card__img"
                                    src="<?= htmlspecialchars($course['image_url'] ?? '') ?>"
                                    alt="<?= htmlspecialchars($course['title']) ?>">
                                <div class="my-course-card__body">
                                    <h3 class="my-course-card__title"><?= htmlspecialchars($course['title']) ?></h3>
                                    <div class="my-course-card__meta">
                                        <span>🕐 <?= htmlspecialchars($course['duration_hours']) ?> ч.</span>
                                        <span><?= htmlspecialchars($course['rate_name']) ?> — <?= formatPrice($course['price']) ?></span>
                                    </div>
                                    <div class="my-course-card__footer">
                                        <?php if ($expired): ?>
                                            <span class="badge-expired">Доступ истёк <?= date('d.m.Y', strtotime($course['expires_at'])) ?></span>
                                        <?php else: ?>
                                            <span class="badge-active">До <?= date('d.m.Y', strtotime($course['expires_at'])) ?></span>
                                        <?php endif; ?>
                                        <a href="/course.php?id=<?= (int)$course['course_id'] ?>" class="btn btn-outline" style="padding: 7px 16px; font-size: 13px;">
                                            Перейти
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
