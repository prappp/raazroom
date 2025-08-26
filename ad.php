<?php
require_once "/config.php";

$id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();
if (!$post)
    die("Invalid post");
?>

<h2>Advertisement</h2>
<p>Watch the ad to continue...</p>

<!-- Example: show Google AdSense or dummy ad -->
<div style="border:1px solid #ccc; padding:20px; width:300px; height:250px;">
    <p>[Google Ad Placeholder]</p>
</div>

<script>
    // Auto redirect after 10 seconds
    setTimeout(function () {
        window.location.href = "<?= $post['link'] ?>";
    }, 10000);
</script>