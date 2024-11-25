<?php

require_once('../database/database.php');
$db = db_connect();

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];

    if (empty($input_email)||empty($input_password)){
        echo "Please provide your login credentials!";
        exit;
    }

    // SQL query to find the user by email
    $stmt = $db->prepare("SELECT loginID, password_hash, f_name, l_name FROM logins WHERE email = ?");
    $stmt->bind_param("s", $input_email);
    $stmt->execute();
    $result = $stmt->get_result();

    //Check if a user with the given email exists..
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        //Verify the password
        if(password_verify($input_password, $user['password_hash'])){
            session_start();
            $_SESSION['loginID'] = $user['loginID']; // Store loginID in session
            $_SESSION['f_name'] = $user['f_name'];  // Store first name
            $_SESSION['l_name'] = $user['l_name'];  // Store last name
            header("Location: ../index.php"); // Redirect to landing page
            exit;
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No account found with that email.";
    }
    $stmt->close();
}

