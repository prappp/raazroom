<?php
// public/verify.php
require_once __DIR__ . '/config.php';

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';
$ok = false;

if ($email && $token) {
  $stmt = $mysqli->prepare("UPDATE users SET verified=1, verify_token=NULL WHERE email=? AND verify_token=?");
  $stmt->bind_param('ss', $email, $token);
  $stmt->execute();
  if ($stmt->affected_rows > 0)
    $ok = true;
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Email Verification</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="verify-body">
  <div class="verify-box">
    <?php if ($ok): ?>
      <h2>Email verified!</h2>
      <p>You can now <a href="login.php">log in</a>.</p>
    <?php else: ?>
      <h2>Verification failed</h2>
      <p>Link invalid or already used.</p>
    <?php endif; ?>
  </div>
</body>

</html>