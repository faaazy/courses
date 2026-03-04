<?php
require_once 'connect.php';
require_once 'functions.php';

$action = $_POST["action"] ?? "";

if($action === "register") {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($firstName === "") $errors[] = 'Введите имя';
    if($lastName === "") $errors[] = "Введите фамилию";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if(!preg_match('/^\d{11}$/', $phone)) $errors[] = "Некорректный номер телефона";
    if(strlen($password) < 6) $errors[] = "Пароль - минимум 6 символов";

    if(!empty($errors)) {
        redirectErrors($errors, "/register.php", [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
        ]);
    }

    $stmt = $conn->prepare("SELECT client_id FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()) {
        redirectErrors(["Этот email уже занят"], "/register.php", [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
        ]);
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO clients(email, password, first_name, last_name, phone)
    VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$email, $hashedPassword, $firstName, $lastName, $phone]);
    $clientId = (int)$conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO roles (client_id, role_name)
    VALUES(?, 'user')");
    $stmt->execute([$clientId]);

    $_SESSION["client_id"] = $clientId;
    $_SESSION["first_name"] = $firstName;
    $_SESSION["last_name"] = $lastName;
    $_SESSION["email"] = $email;
    $_SESSION["role"] = 'user';

    redirect(base_url("/index.php"));
}

if($action === "login") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"];

    if($email === "" || $password === "") {
        redirectErrors(["Заполните все поля"], "/login.php", ['email' => $email]);
    }

    $stmt = $conn->prepare(
        "SELECT c.*, r.role_name
        FROM clients c
        LEFT JOIN roles r ON r.client_id = c.client_id
        WHERE c.email = ?
        LIMIT 1
        "
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if(!$user || !password_verify($password, $user["password"])) {
        redirectErrors(["Неверный email или пароль"], "/login.php", ['email' => $email]);
    }

    $_SESSION["client_id"] = $user["client_id"];
    $_SESSION["first_name"] = $user["first_name"];
    $_SESSION["last_name"] = $user["last_name"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    if($_SESSION["role"] === "admin") {
        redirect(base_url("/admin/index.php"));
    }

    redirect(base_url("/index.php"));    
}

redirect(base_url("/index.php"));
