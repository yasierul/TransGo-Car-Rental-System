<?php
// includes/auth_session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// function to require login
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /TransGo/login.php');
        exit;
    }
}

// function to require admin role
function require_admin() {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        // Not allowed
        header('Location: /TransGo/login.php');
        exit;
    }
}
?>
