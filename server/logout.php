<!-- Code Developed by Gabe -->
<!-- Logs user out -->
<?php
session_start(); // Start the session
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: ../pages/index.php"); // Redirect to the landing page
exit();