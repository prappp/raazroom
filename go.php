<?php
// public/go.php - Ad interstitial then redirect
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT redirect_link, title FROM posts WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$post = $res->fetch_assoc();
if (!$post) {
  http_response_code(404);
  echo "Post not found";
  exit;
}
$link = $post['redirect_link'];
$title = $post['title'];
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Loading Ad… | <?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="style.css">
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let t = 5;
      const countdown = document.getElementById('count');
      const btn = document.getElementById('go-btn');
      const timer = setInterval(() => {
        countdown.textContent = t;
        if (t-- <= 0) {
          clearInterval(timer);
          window.location.href = <?= json_encode($link) ?>;
        }
      }, 1000);
      btn.addEventListener('click', () => {
        window.location.href = <?= json_encode($link) ?>;
      });
    });
  </script>
</head>

<body class="ad-body">
  <div class="ad-box">
    <div class="ad-placeholder">
      <!-- Google Ads placeholder: replace with your real ad code -->
      <div class="ad-slot">Ad Space</div>
    </div>
    <p>Redirecting in <span id="count">5</span>…</p>
    <button id="go-btn" class="btn">Skip & Go Now</button>
  </div>
</body>

</html>