<?php
/**
 * @file
 * PHP code for logging out a user and destroying the session.
 *
 *
 * 
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the index.php page after logout
header("Location: index.php");

// Exit the script
exit();
