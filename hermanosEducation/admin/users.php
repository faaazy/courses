<?php
require_once '../connect.php';
require_once '../functions.php';

redirectAdmin();

$action = $_GET['action'] ?? 'list';
$clientId = (int)($_GET['id'] ?? 0);
$errors = getErrors();

if ($action === 'grant' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetClientId = (int)($_POST['client_id'] ?? 0);
    $courseId = (int)($_POST['course_id'] ?? 0);

    if ($targetClientId > 0 && $courseId > 0) {
        $stmt = $conn->prepare("SELECT * FROM rates WHERE course_id = ? LIMIT 1");
        $stmt->execute([$courseId]);
        $rate = $stmt->fetch();

        if ($rate) {
            $stmt = $conn->prepare(
                "INSERT INTO payments (client_id, rates_id, amount, payment_method, status)
                 VALUES (?, ?, 0, 'manual', 'paid')"
            );
            $stmt->execute([$targetClientId, $rate['rates_id']]);
            $paymentId = (int)$conn->lastInsertId();

            $expiresAt = date('Y-m-d', strtotime('+' . $rate['access_duration_days'] . ' days'));
            $stmt = $conn->prepare(
                "INSERT INTO orders (client_id, course_id, rates_id, payment_id, expires_at)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$targetClientId, $courseId, $rate['rates_id'], $paymentId, $expiresAt]);
            $orderId = (int)$conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, payment_id) VALUES (?, ?)");
            $stmt->execute([$orderId, $paymentId]);
        }
    }

    redirect(base_url("/admin/users.php?action=detail&id=" . $targetClientId));
}

if ($action === 'revoke' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int)($_POST['order_id'] ?? 0);
    $targetClientId = (int)($_POST['client_id'] ?? 0);

    if ($orderId > 0) {
        $stmt = $conn->prepare("UPDATE orders SET expires_at = CURDATE() - INTERVAL 1 DAY WHERE order_id = ?");
        $stmt->execute([$orderId]);
    }

    redirect(base_url('/admin/users.php?action=detail&id=' . $targetClientId));
}

if ($action === 'detail' && $clientId > 0) {
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->execute([$clientId]);
    $client = $stmt->fetch();
    if (!$client) redirect(base_url('/admin/users.php'));

    $stmt = $conn->prepare("SELECT role_name FROM roles WHERE client_id = ?");
    $stmt->execute([$clientId]);
    $role = $stmt->fetchColumn();

    $stmt = $conn->prepare(
        "SELECT o.order_id, o.expires_at,
                co.title AS course_title, co.course_id,
                r.name AS rate_name, r.price,
                p.status, p.payment_method, p.payment_date
         FROM orders o
         JOIN courses co ON co.course_id = o.course_id
         JOIN rates r ON r.rates_id = o.rates_id
         JOIN payments p ON p.payment_id = o.payment_id
         WHERE o.client_id = ?"
    );
    $stmt->execute([$clientId]);
    $userOrders = $stmt->fetchAll();

    $courses = $conn->query("SELECT course_id, title FROM courses ORDER BY title")->fetchAll();
}

$sql    = "SELECT c.*, r.role_name
           FROM clients c
           LEFT JOIN roles r ON r.client_id = c.client_id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$clients = $stmt->fetchAll();

require_once '../views/header.php';
?>

    <div class="container" style="padding-top: 36px; padding-bottom: 72px;">

        <div class="admin-header">
            <h1><?= $action === 'detail' ? 'Профиль пользователя' : 'Пользователи' ?></h1>
            <div class="admin-nav">
                <a href="<?= base_url('/admin/index.php') ?>"   class="btn btn-outline">Дашборд</a>
                <a href="<?= base_url('/admin/users.php') ?>"   class="btn btn-gold">Пользователи</a>
                <a href="<?= base_url('/admin/courses.php') ?>" class="btn btn-outline">Курсы</a>
                <a href="<?= base_url('/admin/orders.php') ?>"  class="btn btn-outline">Заявки</a>
            </div>
        </div>

        <?php if ($action === 'detail' && $clientId > 0): ?>

            <a href="<?= base_url('/admin/users.php') ?>" class="back-link"><- Все пользователи</a>

            <div class="profile-layout" style="margin-top: 24px;">

                <div class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($client['first_name'], 0, 1))) ?>
                        </div>
                        <div class="profile-name">
                            <?= htmlspecialchars($client['first_name']) ?> <?= htmlspecialchars($client['last_name']) ?>
                        </div>
                        <div class="profile-email"><?= htmlspecialchars($client['email']) ?></div>
                        <?php if (!empty($client['phone'])): ?>
                            <div class="profile-phone"><?= htmlspecialchars($client['phone']) ?></div>
                        <?php endif; ?>
                        <div style="margin-top: 12px;">
                        <span class="badge-status badge-status--<?= $role === 'admin' ? 'paid' : 'pending' ?>">
                            <?= htmlspecialchars($role ?? 'user') ?>
                        </span>
                        </div>
                    </div>

                    <div class="profile-card" style="margin-top: 16px; text-align: left;">
                        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 16px;">Выдать доступ к курсу</h3>
                        <form method="POST" action="<?= base_url('/admin/users.php?action=grant') ?>">
                            <input type="hidden" name="client_id" value="<?= $clientId ?>">
                            <div class="form-group">
                                <label>Курс</label>
                                <select name="course_id" required>
                                    <option value="">— выберите курс —</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= (int)$course['course_id'] ?>">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-gold btn-full">Выдать доступ</button>
                        </form>
                    </div>
                </div>

                <div class="profile-main">
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">
                        Доступы к курсам
                        <span style="font-size:14px; font-weight:500; color:var(--text-muted); margin-left:8px;">
                        <?= count($userOrders) ?>
                    </span>
                    </h2>

                    <?php if (empty($userOrders)): ?>
                        <div class="empty-state" style="padding: 40px 0;">
                            <div class="empty-state__icon">📚</div>
                            <h3>Доступов пока нет</h3>
                        </div>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="admin-table">
                                <thead>
                                <tr>
                                    <th>Курс</th>
                                    <th>Тариф</th>
                                    <th>Метод</th>
                                    <th>Дата</th>
                                    <th>Доступ до</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($userOrders as $order):
                                    $expired = strtotime($order['expires_at']) < time();
                                    $labels  = ['paid' => 'Оплачено', 'pending' => 'Ожидает', 'cancelled' => 'Отменено'];
                                    $methodLabels = ['card' => 'Карта', 'manual' => 'Вручную'];
                                    ?>
                                    <tr>
                                        <td style="font-weight:600;"><?= htmlspecialchars($order['course_title']) ?></td>
                                        <td><?= htmlspecialchars($order['rate_name']) ?></td>
                                        <td><?= htmlspecialchars($methodLabels[$order['payment_method']] ?? $order['payment_method']) ?></td>
                                        <td><?= date('d.m.Y', strtotime($order['payment_date'])) ?></td>
                                        <td>
                                            <?php if ($expired): ?>
                                                <span style="color:var(--red);"><?= date('d.m.Y', strtotime($order['expires_at'])) ?></span>
                                            <?php else: ?>
                                                <?= date('d.m.Y', strtotime($order['expires_at'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge-status badge-status--<?= htmlspecialchars($order['status']) ?>">
                                                <?= htmlspecialchars($labels[$order['status']] ?? $order['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!$expired): ?>
                                                <form method="POST" action="<?= base_url('/admin/users.php?action=revoke') ?>"
                                                      onsubmit="return confirm('Отозвать доступ к курсу?')">
                                                    <input type="hidden" name="order_id"   value="<?= (int)$order['order_id'] ?>">
                                                    <input type="hidden" name="client_id"  value="<?= $clientId ?>">
                                                    <button type="submit" class="btn btn-danger" style="padding:5px 12px; font-size:12px;">
                                                        Отозвать
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        <?php else: ?>

            <?php if (empty($clients)): ?>
                <div class="empty-state">
                    <div class="empty-state__icon">👥</div>
                    <h3>Пользователи не найдены</h3>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Роль</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= (int)$client['client_id'] ?></td>
                                <td style="font-weight:600;">
                                    <?= htmlspecialchars($client['first_name']) ?> <?= htmlspecialchars($client['last_name']) ?>
                                </td>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['phone'] ?? '—') ?></td>
                                <td>
                                    <span class="badge-status badge-status--<?= $client['role_name'] === 'admin' ? 'paid' : 'pending' ?>">
                                        <?= htmlspecialchars($client['role_name'] ?? 'user') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin/users.php?action=detail&id=') . (int)$client['client_id'] ?>"
                                       class="btn btn-outline" style="padding:6px 14px; font-size:13px;">
                                        Управление
                                    </a>
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