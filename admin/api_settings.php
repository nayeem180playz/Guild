<?php
// common/config.php
session_start();

/**
 * ===================================================
 * DATABASE CONNECTION SETTINGS
 * Based on your server app's configuration.
 * ===================================================
 */

// Your MariaDB server's IP address and port from the screenshot
define('DB_HOST', '192.168.0.101:3306');

// Your MariaDB username
define('DB_USER', 'root');

// Your MariaDB password (it's empty in your app)
define('DB_PASS', '');

// The name of the database you want to connect to
define('DB_NAME', 'guild_hub');


/**
 * ===================================================
 * ESTABLISHING THE CONNECTION
 * Do not change the code below this line.
 * ===================================================
 */

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection and show error if it fails
if ($conn->connect_error) {
    // This line shows the error message like the one you saw
    die("Connection failed: " . $conn->connect_error);
}
?>