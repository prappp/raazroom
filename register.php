<?php
// public/register.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/csrf.php';
csrf_verify();

$err = '';
$devVerifyLink = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err = 'Invalid email';
  } elseif ($pass !== $confirm) {
    $err = 'Passwords do not match';
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));

    // ✅ Insert user with is_blocked = 0
    $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash, verify_token, is_blocked) VALUES (?,?,?,0)");
    if (!$stmt) {
      $err = 'DB error';
    } else {
      $stmt->bind_param('sss', $email, $hash, $token);
      try {
        $stmt->execute();

        // In production, send email with verification link using SMTP/PHPMailer
        $verifyUrl = APP_URL . 'verify.php?email=' . urlencode($email) . '&token=' . urlencode($token);
        if (DEV_SHOW_VERIFY_LINK) {
          $devVerifyLink = $verifyUrl;
        }

        header('Location: login.php?registered=1');
        exit;
      } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
          $err = 'Email already registered';
        } else {
          $err = 'Registration failed';
        }
      }
    }
  }
}
require_once __DIR__ . '/header.php';
?>
<h2>Register</h2>
<?php if ($err): ?>
  <div class="notice error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<form method="post" class="form-card">
  <?php csrf_field(); ?>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Password
    <input type="password" name="password" minlength="6" required>
  </label>
  <label>Confirm Password
    <input type="password" name="confirm" minlength="6" required>
  </label>
  <button class="btn" type="submit">Create Account</button>
</form>
<?php if ($devVerifyLink): ?>
  <div class="notice info">
    Dev mode: verification link (copy & open):<br>
    <code><?= htmlspecialchars($devVerifyLink) ?></code>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>