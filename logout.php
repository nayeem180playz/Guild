<?php
// logout.php
require_once 'common/config.php';

// Unset all of the session variables.
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page.
header("Location: login.php");
exit;
?>