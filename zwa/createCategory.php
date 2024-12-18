<?php
/**
 * @file
 * PHP code for handling the creation of categories in a database.
 *
 *
 * 
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Database connection 
require "connection.php";


// Check if the form for creating a category has been submitted
if (isset($_POST['createCategory'])) {
    // Get the category name from the form
    $categoryName = $_POST['name'];

    // Check if the category name is empty
    if (empty($categoryName)) {
        echo "Category Name is required.";
    } else {
        // Prepare and execute a query to insert the category into the database using a parameterized statement
        $createQuery = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($createQuery);
        $stmt->bind_param("s", $categoryName);

        // Execute the prepared statement
        $stmt->execute();
        
        // Close the prepared statement
        $stmt->close();

        
        // $createQuery = "INSERT INTO categories (name) VALUES ('$categoryName')";
        // $createResult = $conn->query($createQuery);

        // Redirect to the index page after category creation
        header("Location: index.php");
        exit();
    }
}

// Close the database connection
$conn->close();
