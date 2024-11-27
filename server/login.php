<!-- Code Developed by Gabe -->
<?php

// Include the database connection file to establish a connection to the database.
require_once('../database/database.php');
$db = db_connect(); // Call the function to connect to the database.

// Check if the form has been submitted via POST method.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve the email and password entered by the user in the login form.
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];

    // Check if both email and password are provided. If not, display an error message.
    if (empty($input_email) || empty($input_password)) {
        echo "Please provide your login credentials!";
        exit; // Exit the script if credentials are missing.
    }

    // Prepare an SQL query to find the user by email in the database.
    $stmt = $db->prepare("SELECT loginID, password_hash, f_name, l_name FROM logins WHERE email = ?");
    
    // Bind the user input (email) to the SQL query to prevent SQL injection.
    $stmt->bind_param("s", $input_email);
    
    // Execute the prepared statement.
    $stmt->execute();
    // Retrieve the result of the query.
    $result = $stmt->get_result();

    // Check if a user with the provided email exists in the database.
    if ($result->num_rows === 1) {
        // Fetch the user data from the result set.
        $user = $result->fetch_assoc();

        // Verify if the entered password matches the hashed password stored in the database.
        if(password_verify($input_password, $user['password_hash'])) {
            // Start a new session to store user login details.
            session_start();
            $_SESSION['loginID'] = $user['loginID']; // Store the user's login ID in session.
            $_SESSION['f_name'] = $user['f_name'];  // Store the user's first name in session.
            $_SESSION['l_name'] = $user['l_name'];  // Store the user's last name in session.

            // Go back to the previous page after successful login (using JavaScript to go back in history).
            echo "<script>history.go(-1);</script>";
            exit; // Exit the script after login.

        } else {
            // If the password is incorrect, display an error message.
            echo "Invalid password";
        }
    } else {
        // If no user with the given email is found, display an error message.
        echo "No account found with that email.";
    }
}
    // Close the prepared statement to release resources.
    $stmt->close();
?>