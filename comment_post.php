<?php
// public/comment_post.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';
require_login();
csrf_verify();

$post_id = (int) ($_POST['post_id'] ?? 0);
$body = trim($_POST['body'] ?? '');
if ($post_id && $body) {
    $stmt = $mysqli->prepare("INSERT INTO comments (user_id, post_id, body) VALUES (?,?,?)");
    $uid = $_SESSION['user_id'];
    $stmt->bind_param('iis', $uid, $post_id, $body);
    $stmt->execute();
}

header('Location: home.php');
exit;