<?php
require_once 'includes/db.php';

// Unset all session values
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: admin_login.php");
exit();
?>
