<?php
// admin/admin.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$user = current_user();

// ✅ Check if logged in
if (!$user) {
    header("Location: login.php");
    exit;
}

// ✅ Check if admin
if ($user['role'] !== 'admin') {
    echo "<h2>Access Denied</h2>";
    echo "<p>You are not authorized to view this page.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            display: flex;
        }

        .sidebar {
            width: 240px;
            background: #8B0000;
            /* velvet red */
            color: #fff;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar a {
            color: #fff;
            padding: 1rem;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .main-content {
            margin-left: 240px;
            padding: 2rem;
            flex-grow: 1;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                flex-direction: row;
                justify-content: space-around;
                min-height: auto;
            }

            .sidebar h2 {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="manage_posts.php">📝 Manage Posts</a>
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="home.php">🏠 Back to Home</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h1>Welcome Admin, <?= htmlspecialchars($user['email']); ?></h1>
            <p>Here you can manage posts, users, and settings.</p>
        </div>
        <div class="card">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="manage_posts.php">📌 Add or Edit Posts</a></li>
                <li><a href="manage_users.php">🔒 Block/Unblock Users</a></li>
            </ul>
        </div>
    </div>
</body>

</html>