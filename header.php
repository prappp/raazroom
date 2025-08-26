<?php
require_once __DIR__ . '/auth.php';   // ensures current_user() is available
$user = current_user();               // now it will work
?>
<?php
// includes/header.php
require_once __DIR__ . '/config.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="main.js"></script>
</head>

<body>
    <header class="site-header">
        <div class="brand"><a href="home.php"><?= APP_NAME ?></a></div>
        <nav class="nav">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="terms.php">Terms</a>
            <a href="contact.php">Support</a>
            <?php if ($user): ?>
                <span class="user-email">Hi,
                    <?= htmlspecialchars($user['email']) ?>     <?php if (!$user['verified'])
                               echo ' (unverified)'; ?></span>
                <?php if (!empty($user['is_admin'])): ?><a href="admin.php">Admin</a><?php endif; ?>
                <a href="logout.php" class="btn-out">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn-in">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">