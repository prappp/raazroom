<?php
// admin/manage_posts.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$user = current_user();

// ✅ Only admin
if (!$user || $user['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Handle Add / Update Post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $link = trim($_POST['redirect_link']);
    $thumb = null;

    // Thumbnail upload
    if (!empty($_FILES['thumbnail']['name'])) {
        $targetDir = __DIR__ . "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES['thumbnail']['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
            $thumb = "uploads/" . $filename;
        }
    }

    if (!empty($_POST['post_id'])) {
        // 🔹 Update existing post
        $postId = intval($_POST['post_id']);
        if ($thumb) {
            $stmt = $mysqli->prepare("UPDATE posts SET title=?, description=?, redirect_link=?, thumbnail=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $desc, $link, $thumb, $postId);
        } else {
            $stmt = $mysqli->prepare("UPDATE posts SET title=?, description=?, redirect_link=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $desc, $link, $postId);
        }
        $stmt->execute();
    } else {
        // 🔹 Insert new post
        $stmt = $mysqli->prepare("INSERT INTO posts (title, description, thumbnail, redirect_link, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $title, $desc, $thumb, $link);
        $stmt->execute();
    }

    header("Location: manage_posts.php");
    exit;
}

// ✅ Handle delete
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    header("Location: manage_posts.php");
    exit;
}

// ✅ Handle edit request - fetch post for editing
$editPost = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $mysqli->prepare("SELECT * FROM posts WHERE id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editPost = $result->fetch_assoc();
    $stmt->close();
}

// ✅ Fetch posts
$result = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Posts - <?= APP_NAME ?></title>
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
            text-decoration: none;
        }

        .delete {
            background: #c0392b;
            color: #fff;
        }

        .edit {
            background: #2980b9;
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
            <h1><?= $editPost ? "Edit Post" : "Add New Post" ?></h1>
            <form method="post" enctype="multipart/form-data">
                <?php if ($editPost): ?>
                    <input type="hidden" name="post_id" value="<?= $editPost['id'] ?>">
                <?php endif; ?>
                <p><input type="text" name="title" placeholder="Post Title" required style="width:100%;padding:10px;"
                        value="<?= $editPost ? htmlspecialchars($editPost['title']) : '' ?>"></p>
                <p><textarea name="description" placeholder="Description" required
                        style="width:100%;padding:10px;"><?= $editPost ? htmlspecialchars($editPost['description']) : '' ?></textarea>
                </p>
                <p><input type="text" name="redirect_link" placeholder="Redirect Link (after ad)" required
                        style="width:100%;padding:10px;"
                        value="<?= $editPost ? htmlspecialchars($editPost['redirect_link']) : '' ?>"></p>
                <p><input type="file" name="thumbnail" accept="image/*" <?= $editPost ? '' : 'required' ?>></p>
                <?php if ($editPost && $editPost['thumbnail']): ?>
                    <p><img src="../<?= $editPost['thumbnail'] ?>" width="100"></p>
                <?php endif; ?>
                <p><button type="submit" class="btn"
                        style="background:#27ae60;color:#fff;"><?= $editPost ? "Update Post" : "Add Post" ?></button>
                </p>
            </form>
        </div>

        <div class="card">
            <h2>Existing Posts</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Redirect Link</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?php if ($p['thumbnail']): ?><img src="../<?= $p['thumbnail'] ?>" width="60"><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td><a href="<?= htmlspecialchars($p['redirect_link']) ?>" target="_blank">🔗 Link</a></td>
                        <td><?= $p['created_at'] ?></td>
                        <td>
                            <a href="manage_posts.php?edit=<?= $p['id'] ?>" class="btn edit">✏️ Edit</a>
                            <a href="manage_posts.php?delete=<?= $p['id'] ?>" class="btn delete"
                                onclick="return confirm('Are you sure you want to delete this post?');">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>

</html>