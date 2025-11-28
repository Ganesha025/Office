<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page (or wherever you want)
header("Location: login.php");
exit;
?>
