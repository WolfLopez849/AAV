<?php
session_start();

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getUserInfo() {
    if (isUserLoggedIn()) {
        return [
            'name' => $_SESSION['user_name'] ?? 'Usuario',
            'role' => $_SESSION['user_role'] ?? 'Administrador'
        ];
    }
    return [
        'name' => 'Usuario',
        'role' => 'Administrador'
    ];
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}

$userInfo = getUserInfo();
