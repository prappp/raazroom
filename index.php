<?php
// public/index.php - Curtain intro entry
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome | Raaz Room</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="curtain.js"></script>
</head>

<body class="curtain-body">
  <audio id="curtain-audio" src="curtain.mp3" preload="auto"></audio>
  <div class="curtain">
    <div class="curtain-panel left"></div>
    <div class="curtain-message">
      <h1>Welcome to Raaz Room</h1>
      <p>Tap anywhere to enter</p>
    </div>
    <div class="curtain-panel right"></div>
  </div>
</body>

</html>