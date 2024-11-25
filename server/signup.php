<?php

require_once('../database/database.php');
$db = db_connect();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize inputs
    $first_name = trim($_POST['first-name']);
    $last_name = trim($_POST['last-name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required!");
    }

    // Validate that passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Check if the email already exists
    $stmt = $db->prepare("SELECT email FROM logins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("An account with this email already exists!");
    }
    $stmt->close();

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $stmt = $db->prepare("INSERT INTO logins (f_name, l_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    if ($stmt->execute()) {
        // Automatically log the user in after registration
        $_SESSION['loginID'] = $stmt->insert_id;
        $_SESSION['f_name'] = $first_name;
        $_SESSION['l_name'] = $last_name;

        // Redirect to the landing page
        header("Location: ../index.php");
        exit();
    } else {
        die("Error creating account: " . $db->error);
    }

    $stmt->close();
    $db->close();
} else {
    // Redirect to signup form if the request method is not POST
    header("Location: ../index.php");
    exit();
}
