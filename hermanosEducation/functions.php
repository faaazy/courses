<?php

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['client_id']);
}

function isAdmin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
}

function redirectLogin() {
    if(!isLoggedIn()) {
        redirect("/login.php");
    }
}

function redirectAdmin() {
    redirectLogin();

    if(!isAdmin()) {
        redirect("/index.php");
    }
}

function base_url($path = "") {
    $path = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $path;
}

function redirectErrors($errors, $redirectUrl, $oldData = []) {
    $_SESSION["errors"] = $errors;
    $_SESSION["old_data"] = $oldData;
    redirect($redirectUrl);
}

function getErrors() {
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $errors;
}

function getOldData() {
    $data = $_SESSION['old_data'] ?? [];
    unset($_SESSION['old_data']);
    return $data;
}

function formatPrice($price) {
    return number_format((int)$price, 0, '.', ' ') . ' р';
}