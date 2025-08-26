<?php
// includes/config.php
// Update these DB creds for your XAMPP MySQL
$DB_HOST = 'sql100.infinityfree.com';
$DB_NAME = 'if0_39768246_raazroom_db';
$DB_USER = 'if0_39768246';
$DB_PASS = '7llbnmKGZtaJ';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB Connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

session_start();

// Basic app settings
define('APP_NAME', 'Raaz Room');
define('APP_URL', 'http://localhost/raaz_room_site'); // adjust if needed
define('DEV_SHOW_VERIFY_LINK', true); // shows verify link on screen for local dev
?>