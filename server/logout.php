<!-- Logs user out -->
<?php
session_start(); // Start the session
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to the homepage or login page
exit();