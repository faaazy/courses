<?php
require_once 'connect.php';
require_once 'functions.php';

$sql ="SELECT c.*, r.price
       FROM courses c
       LEFT JOIN rates r ON r.course_id = c.course_id";

$courses = $conn->query($sql)->fetchAll();

require_once 'views/header.php';
?>
    <section class="hero">
        <div class="container">
            <h1>Учитесь без ограничений</h1>
            <p>Начните, смените или продвиньте свою карьеру с курсами<br>от практикующих экспертов.</p>
        </div>
    </section>

    <div class="page-wrapper">
        <div class="container">

            <?php if (empty($courses)): ?>
                <div class="empty-state">
                    <div class="empty-state__icon">🔍</div>
                    <h3>Курсы не найдены</h3>
                </div>
            <?php else: ?>
                <div class="courses-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <img class="course-card__img"
                                 src="<?= htmlspecialchars($course['image_url'] ?? '') ?>"
                                 alt="<?= htmlspecialchars($course['title']) ?>"
                                 loading="lazy">
                            <div class="course-card__body">
                                <div class="course-card__tags">
                                    <span class="tag tag-level"><?= htmlspecialchars($course['level']) ?></span>
                                </div>
                                <h3 class="course-card__title"><?= htmlspecialchars($course['title']) ?></h3>
                                <div class="course-card__meta">
                                    <span>🕐 <?= htmlspecialchars($course['duration_hours']) ?> ч.</span>
                                </div>
                                <div class="course-card__footer">
                                    <div class="price-block">
                                        <?php if ($course['price'] !== null): ?>
                                            <span class="price-current"><?= formatPrice($course['price']) ?></span>
                                        <?php else: ?>
                                            <span class="price-current">Бесплатно</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= base_url('/course.php?id=' . (int)$course['course_id']) ?>" class="btn btn-gold">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once "views/footer.php"; ?>