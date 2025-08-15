<?php
// install.php (Updated for all new features)

$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'guild_hub';

$conn = new mysqli($db_host, $db_user, $db_pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $db_name");
$conn->select_db($db_name);

$sql_queries = "
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `uid` varchar(50) NOT NULL, `email` varchar(100) NOT NULL, `password` varchar(255) NOT NULL, `role` enum('Leader','Co-Leader','Member') NOT NULL DEFAULT 'Member', `join_date` timestamp NOT NULL DEFAULT current_timestamp(), `achievements` text, PRIMARY KEY (`id`), UNIQUE KEY `uid` (`uid`), UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(50) NOT NULL, `password` varchar(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `date` datetime NOT NULL, `type` enum('Scrim','Tournament') NOT NULL, `prize` int(11) DEFAULT 0, `created_at` timestamp NOT NULL DEFAULT current_timestamp(), PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `event_id` int(11) NOT NULL, `opponent` varchar(100) NOT NULL, `map` varchar(50) NOT NULL, `mode` varchar(50) NOT NULL, `time` datetime NOT NULL, `result` enum('Win','Loss','Draw') DEFAULT NULL, `created_at` timestamp NOT NULL DEFAULT current_timestamp(), PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `uid` varchar(50) NOT NULL, `age` int(3) NOT NULL, `role_pref` varchar(50) NOT NULL, `achievements` text, `status` enum('Pending','Accepted','Rejected') NOT NULL DEFAULT 'Pending', `created_at` timestamp NOT NULL DEFAULT current_timestamp(), PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `match_id` int(11) NOT NULL, `kills` int(5) NOT NULL DEFAULT 0, `damage` int(10) NOT NULL DEFAULT 0, `position` int(3) NOT NULL DEFAULT 0 COMMENT 'Finishing position in the match', `mvp` tinyint(1) NOT NULL DEFAULT 0, `created_at` timestamp NOT NULL DEFAULT current_timestamp(), PRIMARY KEY (`id`)
) ENGINE=InnoDB;
";

if ($conn->multi_query($sql_queries)) {
    do { if ($result = $conn->store_result()) { $result->free(); } } while ($conn->next_result());
    echo "Tables created/updated successfully.<br>";
} else {
    die("Error creating tables: " . $conn->error . "<br>");
}

$default_settings = [
    'app_name' => 'Guild Hub',
    'app_logo_url' => 'https://placehold.co/40x40/000000/FFD700?text=GH',
    'guild_name' => 'DEMON SLAYERS',
    'guild_level' => '4',
    'guild_members' => '50',
    'guild_power' => '1.5M'
];
foreach ($default_settings as $key => $value) {
    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value=?");
    $stmt->bind_param("sss", $key, $value, $value);
    $stmt->execute();
}
echo "Default settings populated.<br>";

$admin_user = 'admin';
$admin_pass = password_hash('password', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?) ON DUPLICATE KEY UPDATE password=?");
$stmt->bind_param("sss", $admin_user, $admin_pass, $admin_pass);
$stmt->execute();
echo "Default admin user checked/created.<br>";

echo "<h2>Installation complete!</h2><p>Please delete this install.php file now for security reasons.</p>";
$conn->close();
?>