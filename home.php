<?php
// public/home.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/header.php';

// fetch posts
$stmt = $mysqli->prepare("SELECT id, title, description, thumbnail FROM posts ORDER BY created_at DESC");
$stmt->execute();
$res = $stmt->get_result();
$posts = $res->fetch_all(MYSQLI_ASSOC);
?>
<h2 class="page-title">Latest Posts</h2>

<div class="grid">
  <?php foreach ($posts as $p): ?>
    <article class="card">
      <a href="<?= is_logged_in() ? 'go.php?id=' . $p['id'] : 'login.php?next=' . urlencode('go.php?id=' . $p['id']) ?>">
        <img src="<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
          alt="<?= htmlspecialchars($p['title']) ?>">
      </a>
      <div class="card-body">
        <h3><?= htmlspecialchars($p['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars(mb_strimwidth($p['description'], 0, 140, '…'))) ?></p>
        <a class="btn"
          href="<?= is_logged_in() ? 'go.php?id=' . $p['id'] : 'login.php?next=' . urlencode('go.php?id=' . $p['id']) ?>">View</a>
      </div>
    </article>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>