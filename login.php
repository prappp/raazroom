<?php
// public/login.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '//csrf.php';

csrf_verify();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  // ✅ Fetch user with is_blocked
  $stmt = $mysqli->prepare("SELECT id, email, password_hash, is_admin, verified, is_blocked FROM users WHERE email=?");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();

  if ($user && password_verify($pass, $user['password_hash'])) {
    // ✅ Check if user is blocked
    if ($user['is_blocked'] == 1) {
      $error = '🚫 Your account has been blocked. Please contact support.';
    } else {
      // ✅ Normal login flow
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['is_admin'] = (int) $user['is_admin'];
      $_SESSION['verified'] = (int) $user['verified'];

      $next = $_GET['next'] ?? 'home.php';
      header('Location: ' . $next);
      exit;
    }
  } else {
    $error = 'Invalid credentials';
  }
}

require_once __DIR__ . '/header.php';
?>
<h2>Login</h2>
<?php if (!empty($_GET['registered'])): ?>
  <div class="notice success">Registration successful! Please verify your email.</div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="notice error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" class="form-card">
  <?php csrf_field(); ?>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button class="btn" type="submit">Log In</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>