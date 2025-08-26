<?php
// admin/manage_users.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$user = current_user();

// ✅ Only admin
if (!$user || $user['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Handle block/unblock
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($_GET['action'] === 'block') {
        $stmt = $mysqli->prepare("UPDATE users SET is_blocked = 1 WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif ($_GET['action'] === 'unblock') {
        $stmt = $mysqli->prepare("UPDATE users SET is_blocked = 0 WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: manage_users.php");
    exit;
}

// ✅ Fetch users
$result = $mysqli->query("SELECT id, email, role, is_blocked, created_at FROM users ORDER BY created_at DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users - <?= APP_NAME ?></title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .block {
            background: #c0392b;
            color: #fff;
        }

        .unblock {
            background: #27ae60;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="admin.php">🏠 Dashboard</a>
        <a href="manage_posts.php">📝 Manage Posts</a>
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h1>Manage Users</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['role'] ?></td>
                        <td><?= $u['is_blocked'] ? '🚫 Blocked' : '✅ Active' ?></td>
                        <td><?= $u['created_at'] ?></td>
                        <td>
                            <?php if ($u['is_blocked']): ?>
                                <a href="?action=unblock&id=<?= $u['id'] ?>" class="btn unblock">Unblock</a>
                            <?php else: ?>
                                <a href="?action=block&id=<?= $u['id'] ?>" class="btn block">Block</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>

</html>