<?php
require_once '../connect.php';
require_once '../functions.php';

redirectAdmin();

$totalClients = $conn->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$totalCourses = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$totalOrders  = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $conn->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'paid'")->fetchColumn();

$recentOrders = $conn->query(
    "SELECT o.order_id, o.expires_at,
            p.amount, p.status, p.payment_date,
            c.first_name, c.last_name, c.email,
            co.title AS course_title
     FROM orders o
     JOIN clients c ON c.client_id  = o.client_id
     JOIN courses co ON co.course_id = o.course_id
     JOIN payments p ON p.payment_id = o.payment_id
     ORDER BY o.order_id DESC
     LIMIT 5"
)->fetchAll();

require_once '../views/header.php';
?>

    <div class="container page-wrapper" >

        <div class="admin-header">
            <h1>Панель управления</h1>
            <div class="admin-nav">
                <a href="<?= base_url('/admin/index.php') ?>" class="btn btn-gold">Дашборд</a>
                <a href="<?= base_url('/admin/users.php') ?>" class="btn btn-outline">Пользователи</a>
                <a href="<?= base_url('/admin/courses.php') ?>" class="btn btn-outline">Курсы</a>
                <a href="<?= base_url('/admin/orders.php') ?>" class="btn btn-outline">Заявки</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon">👥</div>
                <div class="stat-card__value"><?= number_format((int)$totalClients) ?></div>
                <div class="stat-card__label">Пользователей</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon">📚</div>
                <div class="stat-card__value"><?= number_format((int)$totalCourses) ?></div>
                <div class="stat-card__label">Курсов</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon">🛒</div>
                <div class="stat-card__value"><?= number_format((int)$totalOrders) ?></div>
                <div class="stat-card__label">Заказов</div>
            </div>
            <div class="stat-card stat-card--gold">
                <div class="stat-card__icon">💰</div>
                <div class="stat-card__value"><?= formatPrice((int)$totalRevenue) ?></div>
                <div class="stat-card__label">Выручка</div>
            </div>
        </div>

        <div class="admin-section">
            <div class="admin-section__header">
                <h2>Последние заказы</h2>
                <a href="<?= base_url('/admin/orders.php') ?>" class="btn btn-outline" style="font-size:13px; padding:7px 14px;">Все заказы</a>
            </div>

            <?php if (empty($recentOrders)): ?>
                <div class="empty-state" style="padding:40px 0;">
                    <div class="empty-state__icon">🛒</div>
                    <h3>Заказов пока нет</h3>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Клиент</th>
                            <th>Курс</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentOrders as $order):
                            $labels = ['paid' => 'Оплачено', 'pending' => 'Ожидает', 'cancelled' => 'Отменено'];
                            ?>
                            <tr>
                                <td><?= (int)$order['order_id'] ?></td>
                                <td>
                                    <div><?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><?= htmlspecialchars($order['email']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($order['course_title']) ?></td>
                                <td><?= formatPrice($order['amount']) ?></td>
                                <td><?= date('d.m.Y', strtotime($order['payment_date'])) ?></td>
                                <td>
                                    <span class="badge-status badge-status--<?= htmlspecialchars($order['status']) ?>">
                                        <?= htmlspecialchars($labels[$order['status']] ?? $order['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

<?php require_once '../views/footer.php'; ?>