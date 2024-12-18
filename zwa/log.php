<?php
/**
 * @file
 * PHP code for user authentication.
 *
 *
 * 
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Database connection 
require "connection.php";



// Get user credentials from the POST request
$email = $_POST["email"];
$password = $_POST["password"];

// Prepared statement to retrieve user information based on the email
$query = "SELECT id, name, surname, email, role, password, avatar FROM users WHERE email=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the result is valid and there is at least one row
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify the password using password_verify function
    if (password_verify($password, $user["password"])) {
        // Store user information in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user']['name'] = $user['name'];
        $_SESSION['user']['surname'] = $user['surname'];
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['user']['role'] = $user['role'];
        $_SESSION['user']['avatarName'] = $user['avatar']; 

        // Redirect to the account.php page
        header("Location: account.php");
        exit(); 
    } else {
        // Display an error message for incorrect email or password
        echo "Wrong email or password.";
    }
} else {
    // Display an error message for user not found
    echo "User not found.";
}

// Close the database connection
$conn->close();
