<?php
require_once '../connect.php';
require_once '../functions.php';

redirectAdmin();

$sql = "SELECT o.order_id, o.expires_at,
              p.amount, p.status, p.payment_date, p.payment_method,
              c.client_id, c.first_name, c.last_name, c.email,
              co.title AS course_title, co.course_id
       FROM orders o
       JOIN clients c ON c.client_id  = o.client_id
       JOIN courses co ON co.course_id = o.course_id
       JOIN payments p ON p.payment_id = o.payment_id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll();

require_once '../views/header.php';
?>

    <div class="container page-wrapper">

        <div class="admin-header">
            <h1>Заявки и заказы</h1>
            <div class="admin-nav">
                <a href="<?= base_url('/admin/index.php') ?>" class="btn btn-outline">Дашборд</a>
                <a href="<?= base_url('/admin/users.php') ?>" class="btn btn-outline">Пользователи</a>
                <a href="<?= base_url('/admin/courses.php') ?>" class="btn btn-outline">Курсы</a>
                <a href="<?= base_url('/admin/orders.php') ?>" class="btn btn-gold">Заявки</a>
            </div>
        </div>

        <div style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">
            Всего: <?= count($orders) ?> заказов
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">🛒</div>
                <h3>Заказов нет</h3>
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
                        <th>Метод</th>
                        <th>Дата</th>
                        <th>Доступ до</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order):
                        $labels = ['paid' => 'Оплачено', 'pending' => 'Ожидает', 'cancelled' => 'Отменено'];
                        $methodLabels = ['card' => 'Карта', 'manual' => 'Вручную'];
                        ?>
                        <tr>
                            <td><?= (int)$order['order_id'] ?></td>
                            <td>
                                <a href="<?= base_url('/admin/users.php?action=detail&id=' . (int)$order['client_id']) ?>"
                                   style="font-weight:600; color:var(--gold-dark);">
                                    <?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?>
                                </a>
                                <div style="font-size:12px; color:var(--text-muted);"><?= htmlspecialchars($order['email']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($order['course_title']) ?></td>
                            <td><?= $order['amount'] > 0 ? formatPrice($order['amount']) : '—' ?></td>
                            <td><?= htmlspecialchars($methodLabels[$order['payment_method']] ?? $order['payment_method']) ?></td>
                            <td><?= date('d.m.Y', strtotime($order['payment_date'])) ?></td>
                            <td><?= date('d.m.Y', strtotime($order['expires_at'])) ?></td>
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

<?php require_once '../views/footer.php'; ?>