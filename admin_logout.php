<?php
// Start the session
session_start();

// Destroy the session to log out the admin
session_unset();
session_destroy();

// Redirect to the index page
header("Location: /frontend/index.php");
exit();
?>
