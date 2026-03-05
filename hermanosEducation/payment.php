<?php
require_once 'connect.php';
require_once 'functions.php';

redirectLogin();

$courseId = (int)($_GET['id'] ?? 0);
if ($courseId === 0) redirect(base_url('/index.php'));

$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();
if (!$course) redirect(base_url('/index.php'));

$stmt = $conn->prepare("SELECT * FROM rates WHERE course_id = ? LIMIT 1");
$stmt->execute([$courseId]);
$rate = $stmt->fetch();
if (!$rate) redirect(base_url('/course.php?id=') . $courseId);

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $cardName = trim($_POST['card_name'] ?? '');
    $cardExpiry = trim($_POST['card_expiry'] ?? '');
    $cardCvv = trim($_POST['card_cvv'] ?? '');

    $errors = [];
    if (strlen($cardNumber) !== 16) $errors[] = 'Номер карты должен содержать 16 цифр';
    if ($cardName === '') $errors[] = 'Введите имя владельца карты';
    if (!preg_match('/^\d{2}\/\d{2}$/', $cardExpiry)) $errors[] = 'Введите срок в формате MM/YY';
    if (!preg_match('/^\d{3}$/', $cardCvv)) $errors[] = 'CVV должен содержать 3 цифры';

    if (empty($errors)) {
        $lastDigit = (int)substr($cardNumber, -1);

        $stmt = $conn->prepare(
            "INSERT INTO payments (client_id, rates_id, amount, payment_method, status)
             VALUES (?, ?, ?, 'card', 'pending')"
        );
        $stmt->execute([$_SESSION['client_id'], $rate['rates_id'], $rate['price']]);
        $paymentId = (int)$conn->lastInsertId();

        if ($lastDigit === 1) {
            $stmt = $conn->prepare("UPDATE payments SET status = 'paid' WHERE payment_id = ?");
            $stmt->execute([$paymentId]);

            $expiresAt = date('Y-m-d', strtotime('+' . $rate['access_duration_days'] . ' days'));
            $stmt = $conn->prepare(
                "INSERT INTO orders (client_id, course_id, rates_id, payment_id, expires_at)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$_SESSION['client_id'], $courseId, $rate['rates_id'], $paymentId, $expiresAt]);
            $orderId = (int)$conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, payment_id) VALUES (?, ?)");
            $stmt->execute([$orderId, $paymentId]);

            $success = true;

        } else {
            $stmt = $conn->prepare("UPDATE payments SET status = 'cancelled' WHERE payment_id = ?");
            $stmt->execute([$paymentId]);

            if ($lastDigit === 2) {
                $error = 'Оплата отклонена банком. Проверьте данные карты или обратитесь в банк.';
            } else {
                $error = 'Оплата отклонена. Для тестовой оплаты используйте карту оканчивающуюся на <strong>1</strong>.';
            }
        }

    } else {
        $error = implode('<br>', array_map('htmlspecialchars', $errors));
    }
}

require_once 'views/header.php';
?>

    <div class="container" style="padding-top: 48px; padding-bottom: 72px;">

        <a href="<?= base_url("/course.php?id=") . $courseId ?>" class="back-link">← Вернуться к курсу</a>

        <div class="payment-layout">

            <!-- Левая колонка — форма -->
            <div class="payment-form-wrap">
                <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 28px;">Оплата курса</h1>

                <?php if ($success): ?>

                    <div class="alert alert-success" style="padding: 24px; font-size: 15px;">
                        <div style="font-size: 32px; margin-bottom: 12px;">✓</div>
                        <strong>Оплата прошла успешно!</strong><br><br>
                        Курс добавлен в ваш профиль. Доступ открыт на <?= htmlspecialchars($rate['access_duration_days']) ?> дней.
                    </div>
                    <a href="<?= base_url("/profile.php") ?>" class="btn btn-gold btn-lg" style="margin-top: 16px;">
                        Перейти в профиль
                    </a>

                <?php else: ?>

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-error"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" class="payment-form">

                        <div class="form-group">
                            <label for="card_number">Номер карты</label>
                            <input type="text" id="card_number" name="card_number"
                                   placeholder="0000 0000 0000 0001"
                                   maxlength="19"
                                   autocomplete="cc-number"
                                   required>
                            <div class="form-hint">
                                Оканчивается на <strong>1</strong> — успешная оплата,
                                на <strong>2</strong> — отклонена
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="card_name">Имя владельца</label>
                            <input type="text" id="card_name" name="card_name"
                                   placeholder="IVAN IVANOV"
                                   style="text-transform: uppercase;"
                                   autocomplete="cc-name"
                                   required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry">Срок действия</label>
                                <input type="text" id="card_expiry" name="card_expiry"
                                       placeholder="MM/YY"
                                       maxlength="5"
                                       autocomplete="cc-exp"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="card_cvv">CVV</label>
                                <input type="password" id="card_cvv" name="card_cvv"
                                       placeholder="•••"
                                       maxlength="3"
                                       autocomplete="cc-csc"
                                       required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold btn-full btn-lg" style="margin-top: 8px;">
                            Оплатить <?= formatPrice($rate['price']) ?>
                        </button>

                    </form>

                <?php endif; ?>
            </div>

            <div class="order-summary">
                <h2 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Ваш заказ</h2>

                <img src="<?= htmlspecialchars($course['image_url'] ?? '') ?>"
                     alt="<?= htmlspecialchars($course['title']) ?>"
                     style="width:100%; height:160px; object-fit:cover; border-radius:10px; margin-bottom:16px;" />

                <div style="font-weight:700; font-size:15px; margin-bottom:8px; line-height:1.4;">
                    <?= htmlspecialchars($course['title']) ?>
                </div>
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">
                    Тариф: <?= htmlspecialchars($rate['name']) ?>
                </div>
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">
                    Доступ: <?= htmlspecialchars($rate['access_duration_days']) ?> дней
                </div>
                <div style="border-top:1px solid var(--border); padding-top:16px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:14px; color:var(--text-muted);">Итого</span>
                    <span style="font-size:24px; font-weight:800; color:var(--gold-dark);">
                    <?= formatPrice($rate['price']) ?>
                </span>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('card_number').addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '').substring(0, 16);
            this.value = val.match(/.{1,4}/g)?.join(' ') ?? val;
        });

        document.getElementById('card_expiry').addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 3) val = val.substring(0, 2) + '/' + val.substring(2);
            this.value = val;
        });
    </script>

<?php require_once 'views/footer.php'; ?>