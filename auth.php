<?php
// includes/auth.php
require_once __DIR__ . '/config.php';

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function current_user()
{
    global $mysqli;

    if (!is_logged_in())
        return null;

    $stmt = $mysqli->prepare("SELECT id, email, role, verified FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_admin'] = ($user['role'] === 'admin') ? 1 : 0;
        $_SESSION['verified'] = $user['verified'];
    }

    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? 'user',
        'is_admin' => $_SESSION['is_admin'] ?? 0,
        'verified' => $_SESSION['verified'] ?? 0,
    ];
}

function require_admin()
{
    if (!is_logged_in() || empty($_SESSION['is_admin'])) {
        header('Location: login.php');
        exit;
    }
}
?>