<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/csrf.php';
csrf_verify();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // In production, send mail via SMTP; here we just acknowledge
  $email = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if ($email && $message) {
    $msg = 'Thanks! We received your message and will reply soon.';
  } else {
    $msg = 'Please fill all fields.';
  }
}
require_once __DIR__ . 'header.php';
?>
<h2>Contact Support</h2>
<?php if ($msg): ?>
  <div class="notice info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post" class="form-card">
  <?php csrf_field(); ?>
  <label>Your Email
    <input type="email" name="email" required>
  </label>
  <label>Message
    <textarea name="message" rows="5" required></textarea>
  </label>
  <button class="btn" type="submit">Send</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>