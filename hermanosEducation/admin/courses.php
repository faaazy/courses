<?php
require_once '../connect.php';
require_once '../functions.php';

redirectAdmin();

$action = $_GET['action']   ?? 'list';
$courseId = (int)($_GET['id'] ?? 0);
$errors = getErrors();
$oldData = getOldData();

if ($action === 'delete' && $courseId > 0) {
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->execute([$courseId]);
    redirect(base_url('/admin/courses.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $level = $_POST['level'] ?? 'Начальный';
    $hours = (int)($_POST['duration_hours']?? 0);
    $imageUrl = trim($_POST['image_url'] ?? '');
    $price = (int)($_POST['price'] ?? 0);

    $errs = [];
    if ($title === '') $errs[] = 'Введите название курса';
    if ($price <= 0) $errs[] = 'Введите цену';

    if (!empty($errs)) {
        redirectErrors($errs, base_url('/admin/courses.php?action=') . $action . ($courseId ? '&id=' . $courseId : ''), $_POST);
    }

    if ($action === 'create') {
        $stmt = $conn->prepare(
            "INSERT INTO courses (title, description, level, duration_hours, image_url)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$title, $desc, $level, $hours, $imageUrl]);
        $newId = (int)$conn->lastInsertId();

        $stmt = $conn->prepare(
            "INSERT INTO rates (course_id, name, price, access_duration_days) VALUES (?, 'Стандарт', ?, 365)"
        );
        $stmt->execute([$newId, $price]);

    } elseif ($action === 'edit' && $courseId > 0) {
        $stmt = $conn->prepare(
            "UPDATE courses SET title = ?, description = ?, level = ?, duration_hours = ?, image_url = ?
             WHERE course_id = ?"
        );
        $stmt->execute([$title, $desc, $level, $hours, $imageUrl, $courseId]);

        $stmt = $conn->prepare("UPDATE rates SET price = ? WHERE course_id = ?");
        $stmt->execute([$price, $courseId]);
    }

    redirect(base_url('/admin/courses.php'));
}

if ($action === 'edit' && $courseId > 0) {
    $stmt = $conn->prepare(
        "SELECT c.*, r.price FROM courses c LEFT JOIN rates r ON r.course_id = c.course_id WHERE c.course_id = ? LIMIT 1"
    );
    $stmt->execute([$courseId]);
    $course = $stmt->fetch();
    if (!$course) redirect(base_url('/admin/courses.php'));
}

$courses = $conn->query(
    "SELECT c.*, r.price FROM courses c LEFT JOIN rates r ON r.course_id = c.course_id ORDER BY c.course_id DESC"
)->fetchAll();

$levels = ['Начальный', 'Средний', 'Продвинутый'];

require_once '../views/header.php';
?>

    <div class="container page-wrapper">

        <div class="admin-header">
            <h1><?= $action === 'create' ? 'Новый курс' : ($action === 'edit' ? 'Редактировать курс' : 'Управление курсами') ?></h1>
            <div class="admin-nav">
                <a href="<?= base_url('/admin/index.php') ?>" class="btn btn-outline">Дашборд</a>
                <a href="<?= base_url('/admin/users.php') ?>" class="btn btn-outline">Пользователи</a>
                <a href="<?= base_url('/admin/courses.php') ?>" class="btn btn-gold">Курсы</a>
                <a href="<?= base_url('/admin/orders.php') ?>" class="btn btn-outline">Заявки</a>
            </div>
        </div>

        <?php if ($action === 'create' || $action === 'edit'): ?>

            <?php $fill = !empty($oldData) ? $oldData : ($course ?? []); ?>

            <?php if (!empty($errors)): ?>
                <ul class="error-list" style="max-width:680px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="admin-form-wrap">
                <form method="POST" action="<?= base_url('/admin/courses.php?action=') . htmlspecialchars($action) ?><?= $courseId ? '&id=' . $courseId : '' ?>">

                    <div class="form-group">
                        <label>Название курса</label>
                        <input type="text" name="title" placeholder="Название курса"
                               value="<?= htmlspecialchars($fill['title'] ?? '') ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Уровень</label>
                            <select name="level">
                                <?php foreach ($levels as $lvl): ?>
                                    <option value="<?= htmlspecialchars($lvl) ?>"
                                        <?= ($fill['level'] ?? '') === $lvl ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lvl) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Длительность (часов)</label>
                            <input type="number" name="duration_hours" min="0"
                                   value="<?= htmlspecialchars($fill['duration_hours'] ?? 0) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Цена (р)</label>
                        <input type="number" name="price" min="0" placeholder="5490"
                               value="<?= htmlspecialchars($fill['price'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Ссылка на изображение</label>
                        <input type="text" name="image_url" placeholder="http://localhost/hermanosEducation/images/..."
                               value="<?= htmlspecialchars($fill['image_url'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Описание</label>
                        <textarea name="description" rows="4" placeholder="Описание курса..."><?= htmlspecialchars($fill['description'] ?? '') ?></textarea>
                    </div>

                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-gold btn-lg">
                            <?= $action === 'create' ? 'Создать курс' : 'Сохранить' ?>
                        </button>
                        <a href="<?= base_url('/admin/courses.php') ?>" class="btn btn-outline btn-lg">Отмена</a>
                    </div>

                </form>
            </div>

        <?php else: ?>

            <div style="margin-bottom:24px;">
                <a href="<?= base_url('/admin/courses.php?action=create') ?>" class="btn btn-gold">+ Добавить курс</a>
            </div>

            <?php if (empty($courses)): ?>
                <div class="empty-state">
                    <div class="empty-state__icon">📚</div>
                    <h3>Курсов пока нет</h3>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Уровень</th>
                            <th>Часов</th>
                            <th>Цена</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= (int)$course['course_id'] ?></td>
                                <td style="font-weight:600;"><?= htmlspecialchars($course['title']) ?></td>
                                <td><?= htmlspecialchars($course['level']) ?></td>
                                <td><?= htmlspecialchars($course['duration_hours']) ?></td>
                                <td><?= $course['price'] ? formatPrice($course['price']) : '—' ?></td>
                                <td>
                                    <div style="display:flex; gap:8px;">
                                        <a href="<?= base_url('/admin/courses.php?action=edit&id=' . (int)$course['course_id']) ?>"
                                           class="btn btn-outline" style="padding:6px 14px; font-size:13px;">
                                            Изменить
                                        </a>
                                        <a href="<?= base_url('/admin/courses.php?action=delete&id=' . (int)$course['course_id']) ?>"
                                           class="btn btn-danger" style="padding:6px 14px; font-size:13px;"
                                           onclick="return confirm('Удалить курс «<?= htmlspecialchars(addslashes($course['title'])) ?>»?')">
                                            Удалить
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>

<?php require_once '../views/footer.php'; ?>